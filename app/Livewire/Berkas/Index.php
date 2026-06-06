<?php

namespace App\Livewire\Berkas;

use App\Models\DokumenKelompok;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\Periode;
use App\Services\KKNService;
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
    // Dokumen properties
    public $existingProgramKerja;
    public $existingLaporanAkhir;
    public $existingArtikel;
    // File upload properties
    public $file_program_kerja;
    public $file_laporan_akhir;
    public $file_artikel;
    // Form properties
    public $judul_program_kerja;
    public $judul_laporan_akhir;
    public $judul_artikel;
    public $konten_artikel;

    public function mount($role, $jenisKegiatan)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId($jenisKegiatan);

        // Ambil kelompok milik user yang login
        $kelompok = Kelompok::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->first();

        $this->kelompok_id = $kelompok->id ?? null;

        $this->loadDokumen();
    }

    public function loadDokumen()
    {
        // Load Program Kerja
        $this->existingProgramKerja = DokumenKelompok::where('kelompok_id', $this->kelompok_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'program_kerja')
            ->first();

        // Load Laporan Akhir
        $this->existingLaporanAkhir = DokumenKelompok::where('kelompok_id', $this->kelompok_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'laporan_akhir')
            ->first();

        // Load Artikel
        $this->existingArtikel = DokumenKelompok::where('kelompok_id', $this->kelompok_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'artikel')
            ->first();

        // Set nilai ke form
        if ($this->existingProgramKerja) {
            $this->judul_program_kerja = $this->existingProgramKerja->judul;
        }

        if ($this->existingLaporanAkhir) {
            $this->judul_laporan_akhir = $this->existingLaporanAkhir->judul;
        }

        if ($this->existingArtikel) {
            $this->judul_artikel = $this->existingArtikel->judul;
            $this->konten_artikel = $this->existingArtikel->konten;
        }
    }

    public function uploadProgramKerja()
    {
        // Hanya admin yang bisa upload
        if (Auth::user()->role == 'mahasiswa') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak!',
                'text' => 'Mahasiswa tidak diizinkan mengupload dokumen'
            ]);
            return;
        }

        $this->validate([
            'file_program_kerja' => 'required|file|mimes:pdf|max:5120',
            'judul_program_kerja' => 'required|string|max:255',
        ]);

        try {
            if ($this->existingProgramKerja && $this->existingProgramKerja->file_path) {
                Storage::disk('public')->delete($this->existingProgramKerja->file_path);
            }

            $path = $this->file_program_kerja->store('dokumen/program_kerja', 'public');

            DokumenKelompok::updateOrCreate(
                [
                    'kelompok_id' => $this->kelompok_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'jenis' => 'program_kerja',
                ],
                [
                    'user_id' => Auth::id(),
                    'file_path' => $path,
                    'file_name' => $this->file_program_kerja->getClientOriginalName(),
                    'file_size' => $this->file_program_kerja->getSize(),
                    'file_type' => $this->file_program_kerja->getMimeType(),
                    'judul' => $this->judul_program_kerja,
                    'status' => 'published',
                ]
            );

            $this->file_program_kerja = null;
            $this->loadDokumen();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Program Kerja berhasil diupload'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function uploadLaporanAkhir()
    {
        // Hanya admin yang bisa upload
        if (Auth::user()->role == 'mahasiswa') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak!',
                'text' => 'Mahasiswa tidak diizinkan mengupload dokumen'
            ]);
            return;
        }

        $this->validate([
            'file_laporan_akhir' => 'required|file|mimes:pdf|max:5120',
            'judul_laporan_akhir' => 'required|string|max:255',
        ]);

        try {
            if ($this->existingLaporanAkhir && $this->existingLaporanAkhir->file_path) {
                Storage::disk('public')->delete($this->existingLaporanAkhir->file_path);
            }

            $path = $this->file_laporan_akhir->store('dokumen/laporan_akhir', 'public');

            DokumenKelompok::updateOrCreate(
                [
                    'kelompok_id' => $this->kelompok_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'jenis' => 'laporan_akhir',
                ],
                [
                    'user_id' => Auth::id(),
                    'file_path' => $path,
                    'file_name' => $this->file_laporan_akhir->getClientOriginalName(),
                    'file_size' => $this->file_laporan_akhir->getSize(),
                    'file_type' => $this->file_laporan_akhir->getMimeType(),
                    'judul' => $this->judul_laporan_akhir,
                    'status' => 'published',
                ]
            );

            $this->file_laporan_akhir = null;
            $this->loadDokumen();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Laporan Akhir berhasil diupload'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function uploadArtikel()
    {
        // Hanya admin yang bisa upload
        if (Auth::user()->role == 'mahasiswa') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak!',
                'text' => 'Mahasiswa tidak diizinkan mengupload dokumen'
            ]);
            return;
        }

        $this->validate([
            'judul_artikel' => 'required|string|max:255',
            'konten_artikel' => 'required|string|min:10',
            'file_artikel' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        try {
            $path = null;
            if ($this->file_artikel) {
                if ($this->existingArtikel && $this->existingArtikel->file_path) {
                    Storage::disk('public')->delete($this->existingArtikel->file_path);
                }
                $path = $this->file_artikel->store('dokumen/artikel', 'public');
            }

            DokumenKelompok::updateOrCreate(
                [
                    'kelompok_id' => $this->kelompok_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'jenis' => 'artikel',
                ],
                [
                    'user_id' => Auth::id(),
                    'file_path' => $path,
                    'file_name' => $this->file_artikel ? $this->file_artikel->getClientOriginalName() : null,
                    'file_size' => $this->file_artikel ? $this->file_artikel->getSize() : null,
                    'file_type' => $this->file_artikel ? $this->file_artikel->getMimeType() : null,
                    'judul' => $this->judul_artikel,
                    'konten' => $this->konten_artikel,
                    'status' => 'published',
                ]
            );

            $this->file_artikel = null;
            $this->loadDokumen();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Artikel berhasil dipublikasikan'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function viewFile($dokumenId)
    {
        $dokumen = DokumenKelompok::find($dokumenId);

        if ($dokumen && $dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            // Buka di tab baru dengan JavaScript
            $url = Storage::disk('public')->url($dokumen->file_path);
            $this->dispatch('open-new-tab', $url);
            return;
        }

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'File tidak ditemukan'
        ]);
    }

    public function downloadFile($dokumenId, $jenis)
    {
        $dokumen = DokumenKelompok::find($dokumenId);

        if ($dokumen && $dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            $fileName = $jenis . '_' . ($dokumen->judul ?? 'dokumen') . '.pdf';
            return Storage::disk('public')->download($dokumen->file_path, $fileName);
        }

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'File tidak ditemukan'
        ]);
    }

    public function deleteDokumen($jenis)
    {
        // Hanya admin yang bisa hapus
        if (Auth::user()->role == 'mahasiswa') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak!',
                'text' => 'Mahasiswa tidak diizinkan menghapus dokumen'
            ]);
            return;
        }

        $dokumen = null;

        switch ($jenis) {
            case 'program_kerja':
                $dokumen = $this->existingProgramKerja;
                break;
            case 'laporan_akhir':
                $dokumen = $this->existingLaporanAkhir;
                break;
            case 'artikel':
                $dokumen = $this->existingArtikel;
                break;
        }

        if ($dokumen && $dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        if ($dokumen) {
            $dokumen->delete();
        }

        $this->loadDokumen();

        // Reset form
        if ($jenis == 'program_kerja') {
            $this->judul_program_kerja = null;
        } elseif ($jenis == 'laporan_akhir') {
            $this->judul_laporan_akhir = null;
        } elseif ($jenis == 'artikel') {
            $this->judul_artikel = null;
            $this->konten_artikel = null;
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Dokumen berhasil dihapus'
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
        return view('livewire.berkas.index');
    }
}
