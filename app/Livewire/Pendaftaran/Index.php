<?php

namespace App\Livewire\Pendaftaran;

use App\Models\DokumenPendaftaran;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\Kegiatan;
use App\Models\Periode;
use App\Models\User;
use App\Services\KegiatanService;
use App\Services\PersyaratanService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public $role;
    public $jenisKegiatan;
    public $periode_id;
    public $kegiatan_id;
    public $kelompok_id;
    public $persyaratan = [];
    
    // Data mahasiswa
    public $mahasiswa;
    public $data_mahasiswa;
    public $kelompokSaya;
    public $isPlotted = false;
    public $isRegistered = false;
    
    // Dynamic file properties
    public $files = [];
    public $existingDocuments = [];

    public function mount($role, $jenisKegiatan)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        
        // 1. Dapatkan periode aktif (berdiri sendiri)
        $this->periode_id = KegiatanService::getPeriodeId();
        
        if (!$this->periode_id) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Tidak ada periode aktif. Silakan hubungi admin.',
            ]);
            return;
        }
        
        // 2. Dapatkan kegiatan ID (berdiri sendiri)
        $this->kegiatan_id = KegiatanService::getKegiatanId($jenisKegiatan);
        
        if (!$this->kegiatan_id) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => "Kegiatan {$jenisKegiatan} tidak ditemukan.",
            ]);
            return;
        }
        
        // Get persyaratan berdasarkan jenis kegiatan
        $this->persyaratan = PersyaratanService::getPersyaratan($jenisKegiatan);
        
        // Get current user
        $this->mahasiswa = Auth::user();
        $this->data_mahasiswa = json_decode($this->mahasiswa->data_mahasiswa ?? '{}', true);
        
        // Cek plotting mahasiswa (apakah sudah masuk kelompok)
        $this->checkPlotting();
        
        // Cek status pendaftaran (upload dokumen)
        $this->checkRegistrationStatus();
        
        // Load existing documents
        $this->loadExistingDocuments();
    }
    
    /**
     * Cek apakah mahasiswa sudah diplotting ke kelompok
     * Melalui tabel kelompok_user -> kelompok -> periode_id & kegiatan_id
     */
    public function checkPlotting()
    {
        // Cari di tabel kelompok_user
        $kelompokUser = KelompokUser::where('user_id', $this->mahasiswa->id)
            ->where('role', 'mahasiswa')
            ->first();
        
        if (!$kelompokUser) {
            $this->isPlotted = false;
            return;
        }
        
        // Cari kelompok dengan periode dan kegiatan yang sesuai
        $kelompok = Kelompok::where('id', $kelompokUser->kelompok_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();
        
        if ($kelompok) {
            $this->isPlotted = true;
            $this->kelompokSaya = $kelompok;
            $this->kelompok_id = $kelompok->id;
        } else {
            $this->isPlotted = false;
        }
    }
    
    /**
     * Cek status pendaftaran (sudah upload dokumen)
     */
    public function checkRegistrationStatus()
    {
        $documents = DokumenPendaftaran::where('user_id', $this->mahasiswa->id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->count();
        
        $this->isRegistered = $documents > 0;
    }
    
    public function loadExistingDocuments()
    {
        $documents = DokumenPendaftaran::where('user_id', $this->mahasiswa->id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get();
        
        foreach ($documents as $doc) {
            $this->existingDocuments[$doc->jenis_dokumen] = $doc;
        }
    }
    
    public function uploadFile($jenisDokumen)
    {
        // Cek plotting dulu
        if (!$this->isPlotted) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Belum Diplotting',
                'text' => 'Anda belum diplotting ke kelompok. Silakan hubungi admin.',
            ]);
            return;
        }
        
        $rules = [
            "files.{$jenisDokumen}" => "required|file|mimes:pdf,jpg,jpeg,png|max:2048",
        ];
        
        $messages = [
            "files.{$jenisDokumen}.required" => "File {$this->persyaratan[$jenisDokumen]['label']} wajib diisi",
            "files.{$jenisDokumen}.file" => "File harus berupa file",
            "files.{$jenisDokumen}.mimes" => "File harus berformat PDF, JPG, JPEG, atau PNG",
            "files.{$jenisDokumen}.max" => "Ukuran file maksimal 2MB",
        ];
        
        $this->validate($rules, $messages);
        
        try {
            $file = $this->files[$jenisDokumen];
            $path = $file->store("pendaftaran/{$this->jenisKegiatan}/{$jenisDokumen}", 'public');
            
            DokumenPendaftaran::updateOrCreate(
                [
                    'user_id' => $this->mahasiswa->id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'jenis_dokumen' => $jenisDokumen,
                ],
                [
                    'label' => $this->persyaratan[$jenisDokumen]['label'],
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                    'is_required' => $this->persyaratan[$jenisDokumen]['required'],
                    'status' => 'pending',
                ]
            );
            
            $this->existingDocuments[$jenisDokumen] = DokumenPendaftaran::where('user_id', $this->mahasiswa->id)
                ->where('periode_id', $this->periode_id)
                ->where('kegiatan_id', $this->kegiatan_id)
                ->where('jenis_dokumen', $jenisDokumen)
                ->first();
                
            $this->files[$jenisDokumen] = null;
            $this->isRegistered = true;
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => $this->persyaratan[$jenisDokumen]['label'] . ' berhasil diupload',
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
    
    public function downloadFile($jenisDokumen)
    {
        $dokumen = $this->existingDocuments[$jenisDokumen] ?? null;
        
        if ($dokumen && $dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            $fileName = $this->jenisKegiatan . '_' . $jenisDokumen . '_' . date('Ymd') . '.pdf';
            return Storage::disk('public')->download($dokumen->file_path, $fileName);
        }
        
        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'File tidak ditemukan',
        ]);
    }
    
    public function deleteFile($jenisDokumen)
    {
        $dokumen = $this->existingDocuments[$jenisDokumen] ?? null;
        
        if ($dokumen && $dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        
        if ($dokumen) {
            $dokumen->delete();
        }
        
        unset($this->existingDocuments[$jenisDokumen]);
        
        $remainingDocs = DokumenPendaftaran::where('user_id', $this->mahasiswa->id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->count();
        
        $this->isRegistered = $remainingDocs > 0;
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => $this->persyaratan[$jenisDokumen]['label'] . ' berhasil dihapus',
        ]);
    }
    
    public function back()
    {
        return redirect()->route('kegiatan.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan
        ]);
    }
    
    public function render()
    {
        return view('livewire.pendaftaran.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'persyaratan' => $this->persyaratan,
        ]);
    }
}