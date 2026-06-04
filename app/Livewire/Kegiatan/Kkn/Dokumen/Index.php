<?php

namespace App\Livewire\Kegiatan\Kkn\Dokumen;

use App\Models\Kegiatan;
use App\Models\Periode;
use App\Models\ScreeningFile;
use App\Services\KKNService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public $role;
    public $periode_id;
    public $kegiatan_id;
    public $screeningFile;

    // File properties (Surat)
    public $file_surat;
    public $existing_surat;

    // File properties (KTP)
    public $file_ktp;
    public $existing_ktp;

    // File properties (SPP/Pembayaran)
    public $file_spp;
    public $existing_spp;

    public function mount()
    {
        $this->role = Auth::user()->role;
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId('KKN');
        
        $this->loadScreeningFile();
    }

    public function loadScreeningFile()
    {
        $this->screeningFile = ScreeningFile::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        if ($this->screeningFile) {
            $this->existing_surat = $this->screeningFile->file_surat;
            $this->existing_ktp = $this->screeningFile->file_ktp;
            $this->existing_spp = $this->screeningFile->file_spp;
        }
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

    public function back()
    {
        return redirect()->route('kegiatan.kkn.index', ['role' => $this->role]);
    }

    public function render()
    {
        return view('livewire.kegiatan.kkn.dokumen.index');
    }
}