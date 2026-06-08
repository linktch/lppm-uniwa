<?php

namespace App\Livewire\Laporanharian;

use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Sertifikat;
use App\Models\TimelineKegiatan;
use App\Services\KegiatanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public $role;
    public $jenisKegiatan;
    public $periode_id;
    public $kegiatan_id;
    public $kelompok_id;
    public $tanggal;
    public $jam;
    public $narasi;
    public $foto;
    public $oldFoto;
    public $isEditing = false;
    public $laporanId;

    // Timeline & Sertifikat
    public $timelineAktif = null;
    public $canCreateLaporan = false;
    public $timelineMessage = '';
    public $sisaHari = 0;
    public $hasSertifikat = false;
    public $sertifikatMessage = '';

    // Auto-save key
    protected $autoSaveKey = 'laporan_harian_draft';

    public function mount($role, $jenisKegiatan, $id = null)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->periode_id = KegiatanService::getPeriodeId();
        $this->kegiatan_id = KegiatanService::getKegiatanId($jenisKegiatan);

        // Cek timeline dan sertifikat
        $this->checkTimeline();
        $this->checkSertifikat();

        // Jika tidak bisa membuat laporan, redirect
        if (!$this->canCreateLaporan && !$id) {
            session()->flash('error', $this->timelineMessage);
            return redirect()->route('kegiatan.laporanharian.index', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan
            ]);
        }

        // Ambil kelompok user
        $kelompokUser = KelompokUser::where('user_id', Auth::id())
            ->where('role', 'mahasiswa')
            ->first();
        $this->kelompok_id = $kelompokUser?->kelompok_id;

        // Set tanggal default = hari ini
        $this->tanggal = date('Y-m-d');
        $this->jam = date('H:i');

        // Jika ada ID, berarti mode edit
        if ($id) {
            $this->isEditing = true;
            $this->laporanId = $id;
            $this->loadLaporan();
        } else {
            // Cek auto-save di localStorage (via JavaScript)
            $this->dispatch('checkAutoSave', key: $this->autoSaveKey);
        }
    }

    /**
     * Cek timeline pelaksanaan
     */
    public function checkTimeline()
    {
        // Ambil timeline dengan status AKTIF dan jenis = 'Pelaksanaan' untuk kegiatan ini
        $this->timelineAktif = TimelineKegiatan::where('status', 'AKTIF')
            ->where('jenis', 'Pelaksanaan')
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        if ($this->timelineAktif) {
            $today = Carbon::now();
            $tglMulai = Carbon::parse($this->timelineAktif->tanggal_mulai);
            $tglSelesai = Carbon::parse($this->timelineAktif->tanggal_selesai);

            if ($today->between($tglMulai, $tglSelesai)) {
                $this->canCreateLaporan = true;
                $this->sisaHari = $today->diffInDays($tglSelesai);
                $this->timelineMessage = sprintf(
                    '✅ Periode pelaksanaan %s sedang berlangsung (%s - %s)',
                    $this->jenisKegiatan,
                    $tglMulai->format('d/m/Y'),
                    $tglSelesai->format('d/m/Y')
                );
            } elseif ($today->lt($tglMulai)) {
                $this->canCreateLaporan = false;
                $this->timelineMessage = sprintf(
                    '⏳ Periode pelaksanaan %s belum dimulai. Akan dimulai pada %s.',
                    $this->jenisKegiatan,
                    $tglMulai->format('d/m/Y')
                );
            } else {
                $this->canCreateLaporan = false;
                $this->timelineMessage = sprintf(
                    '❌ Periode pelaksanaan %s telah berakhir (selesai pada %s).',
                    $this->jenisKegiatan,
                    $tglSelesai->format('d/m/Y')
                );
            }
        } else {
            $this->canCreateLaporan = false;
            $this->timelineMessage = "Tanggal periode pelaksanaan {$this->jenisKegiatan} belum ditentukan";
        }
    }

    /**
     * Cek apakah user sudah memiliki sertifikat
     */
    public function checkSertifikat()
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        if ($sertifikat) {
            $this->hasSertifikat = true;
            $this->sertifikatMessage = '✅ Sertifikat sudah tersedia.';
        } else {
            $this->hasSertifikat = false;
            $this->sertifikatMessage = '⚠️ Sertifikat belum tersedia. Pastikan semua penilaian sudah selesai.';
        }
    }

    public function loadLaporan()
    {
        $laporan = LaporanHarian::where('id', $this->laporanId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->tanggal = $laporan->tanggal ? Carbon::parse($laporan->tanggal)->format('Y-m-d') : date('Y-m-d');
        $this->jam = $laporan->jam ?? date('H:i');
        $this->narasi = $laporan->aktivitas;
        $this->oldFoto = $laporan->foto;
    }

    public function saveLaporan()
    {
        // Validasi timeline dan sertifikat sebelum save
        $this->checkTimeline();
        $this->checkSertifikat();

        if (!$this->canCreateLaporan) {
            session()->flash('error', $this->timelineMessage);
            return redirect()->route('kegiatan.laporanharian.index', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan
            ]);
        }

        $this->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'narasi' => 'required|min:500',
            'foto' => $this->isEditing ? 'nullable|image|mimes:jpg,jpeg,png|max:1024' : 'required|image|mimes:jpg,jpeg,png|max:1024',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'jam.required' => 'Jam wajib diisi',
            'narasi.required' => 'Narasi kegiatan wajib diisi',
            'narasi.min' => 'Narasi kegiatan minimal 500 karakter',
            'foto.required' => 'Foto dokumentasi wajib diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran gambar maksimal 1MB',
        ]);

        try {
            $fotoPath = $this->oldFoto;

            if ($this->foto && !is_string($this->foto)) {
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $this->foto->store('laporan-harian', 'public');
            }

            $dateTime = Carbon::parse($this->tanggal . ' ' . $this->jam);

            if ($this->isEditing) {
                LaporanHarian::where('id', $this->laporanId)->update([
                    'tanggal' => $dateTime,
                    'jam' => $this->jam,
                    'aktivitas' => $this->narasi,
                    'foto' => $fotoPath,
                ]);
                $message = '✅ Laporan berhasil diupdate';
            } else {
                LaporanHarian::create([
                    'user_id' => Auth::id(),
                    'kelompok_id' => $this->kelompok_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'tanggal' => $dateTime,
                    'jam' => $this->jam,
                    'aktivitas' => $this->narasi,
                    'foto' => $fotoPath,
                    'status' => 'submitted',
                ]);
                $message = '✅ Laporan berhasil disimpan';
            }

            // Hapus auto-save setelah berhasil
            $this->dispatch('clearAutoSave', key: $this->autoSaveKey);

            session()->flash('success', $message);
            return redirect()->route('kegiatan.laporanharian.index', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function removeFoto()
    {
        if ($this->oldFoto && Storage::disk('public')->exists($this->oldFoto)) {
            Storage::disk('public')->delete($this->oldFoto);
        }
        $this->oldFoto = null;
        $this->foto = null;
    }

    public function back()
    {
        return redirect()->route('kegiatan.laporanharian.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan
        ]);
    }

    public function render()
    {
        return view('livewire.laporanharian.create', [
            'canCreateLaporan' => $this->canCreateLaporan,
            'timelineMessage' => $this->timelineMessage,
            'sisaHari' => $this->sisaHari,
            'hasSertifikat' => $this->hasSertifikat,
            'sertifikatMessage' => $this->sertifikatMessage,
        ]);
    }
}