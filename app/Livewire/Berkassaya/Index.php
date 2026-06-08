<?php

namespace App\Livewire\Berkassaya;

use App\Models\DokumenPendaftaran;
use App\Models\ScreeningFile;
use App\Models\Sertifikat;
use App\Services\KegiatanService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    public $role;
    public $jenisKegiatan;
    public $periode_id;
    public $kegiatan_id;
    
    // Semua dokumen
    public $dokumen = [];
    
    // Sertifikat
    public $sertifikat;

    public function mount($role, $jenisKegiatan)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->periode_id = KegiatanService::getPeriodeId();
        $this->kegiatan_id = KegiatanService::getKegiatanId($this->jenisKegiatan);
        
        $this->loadDokumen();
        $this->loadSertifikat();
    }

    public function loadDokumen()
    {
        // 1. Cari dari DokumenPendaftaran terlebih dahulu
        $dokumenPendaftaran = DokumenPendaftaran::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get();
        
        // 2. Jika ada data di DokumenPendaftaran, gunakan itu
        if ($dokumenPendaftaran->isNotEmpty()) {
            $this->dokumen = $dokumenPendaftaran;
            return;
        }
        
        // 3. Jika tidak ada, cek dari ScreeningFile
        $screeningFile = ScreeningFile::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();
        
        // 4. Jika ada data di ScreeningFile, konversi ke format seperti DokumenPendaftaran
        if ($screeningFile) {
            $this->dokumen = $this->convertScreeningFileToDokumen($screeningFile);
        }
    }
    
    public function loadSertifikat()
    {
        // Load sertifikat yang sudah ada dari database
        $this->sertifikat = Sertifikat::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();
    }
    
    public function convertScreeningFileToDokumen($screeningFile)
    {
        $dokumenList = [];
        
        // Mapping field dari ScreeningFile
        $mapping = [
            ['field' => 'file_surat', 'status_field' => 'status_surat', 'keterangan_field' => 'keterangan_surat', 'jenis' => 'surat', 'label' => 'Surat Pengantar'],
            ['field' => 'file_ktp', 'status_field' => 'status_ktp', 'keterangan_field' => 'keterangan_ktp', 'jenis' => 'ktp', 'label' => 'KTP'],
            ['field' => 'file_spp', 'status_field' => 'status_spp', 'keterangan_field' => 'keterangan_spp', 'jenis' => 'spp', 'label' => 'SPP/Pembayaran'],
        ];
        
        foreach ($mapping as $map) {
            $filePath = $screeningFile->{$map['field']};
            
            if ($filePath) {
                $status = $screeningFile->{$map['status_field']} ?? 'pending';
                $keterangan = $screeningFile->{$map['keterangan_field']} ?? null;
                
                $dokumenList[] = (object) [
                    'id' => null,
                    'user_id' => $screeningFile->user_id,
                    'periode_id' => $screeningFile->periode_id,
                    'kegiatan_id' => $screeningFile->kegiatan_id,
                    'jenis_dokumen' => $map['jenis'],
                    'label' => $map['label'],
                    'file_path' => $filePath,
                    'file_name' => basename($filePath),
                    'file_size' => null,
                    'file_type' => 'application/pdf',
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'verified_by' => null,
                    'verified_at' => null,
                    'is_required' => true,
                    'dari_screening' => true,
                ];
            }
        }
        
        return collect($dokumenList);
    }

    public function downloadFile($filePath, $fileName)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'File tidak ditemukan'
        ]);
    }

    public function previewFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $url = Storage::disk('public')->url($filePath);
            $this->dispatch('openPreview', $url);
            return;
        }

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'File tidak ditemukan'
        ]);
    }

    public function back()
    {
        return redirect()->to("/{$this->role}/kegiatan/{$this->jenisKegiatan}/pendaftaran");
    }

    public function render()
    {
        return view('livewire.berkassaya.index');
    }
}