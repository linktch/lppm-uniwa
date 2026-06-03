<?php

namespace App\Livewire\Kegiatan\Pam\Laporanharian;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\PAMService;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    // 🔥 SIMPAN YANG PENTING SAJA (ID)
    public $kelompokId;
    public $kell;

    // form
    public $tanggal;
    public $jam;
    public $narasi;
    public $catatan;
    public $foto;

    public $isEditing = false;
    public $laporanId = null;
    
    public $periodeAktif;
    public $kegiatan;

    public function mount($id = null)
    {
        // default tanggal & jam
        $this->tanggal = now()->toDateString();
        $this->jam = now()->format('H:i');

        // ✅ Ambil dari service (sudah pakai cache)
        $this->periodeAktif = PAMService::periode();
        $this->kegiatan = PAMService::kegiatan('PAM');
        // dd($this->periodeAktif);
        if (!$this->periodeAktif || !$this->kegiatan) {
            abort(404, 'Periode atau kegiatan tidak ditemukan');
        }

        // ambil kelompok user (hanya untuk ambil ID)
        $kelompokUser = KelompokUser::with('kelompok')
            ->where('user_id', auth()->id())
            ->whereHas('kelompok', fn($q) =>
                $q->where('periode_id', $this->periodeAktif->id)
                  ->where('kegiatan_id', $this->kegiatan->id)
            )
            ->first();
        // dd($kelompokUser);
        $this->kelompokId = $kelompokUser?->kelompok?->id;

        if (!$this->kelompokId) {
            abort(404, 'Kelompok tidak ditemukan');
        }

        // 🔥 MODE EDIT
        if ($id) {
            $laporan = LaporanHarian::findOrFail($id);

            $this->isEditing = true;
            $this->laporanId = $id;

            $this->tanggal = Carbon::parse($laporan->tanggal)->toDateString();
            $this->jam = Carbon::parse($laporan->tanggal)->format('H:i');
            $this->narasi = $laporan->aktivitas;
            $this->catatan = $laporan->catatan;
            $this->foto = $laporan->foto;
        }
    }

    public function removeFoto()
    {
        $this->foto = null;
    }

    public function back()
    {
        return redirect()->route('kegiatan.pam.laporanharian.index', [
            'role' => auth()->user()->role,
        ]);
    }

    public function saveLaporan()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'narasi' => 'required|min:20',
            'foto' => $this->isEditing ? 'nullable' : 'required',
        ]);

        // gabungkan tanggal + jam
        $datetime = Carbon::parse($this->tanggal . ' ' . $this->jam);

        // 🔥 cek presensi
        $presensi = Kehadiran::where('user_id', auth()->id())
            ->where('kelompok_id', $this->kelompokId)
            ->whereDate('tanggal', $this->tanggal)
            ->first();
        // dd($presensi);
        if (!$presensi || $presensi->status !== 'hadir') {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Harus presensi hadir dulu!'
            ]);
            return;
        }

        // 🔥 upload foto
        $pathFoto = null;

        if ($this->foto && !is_string($this->foto)) {
            $pathFoto = $this->foto->store('laporan', 'public');
        }

        // 🔥 update / create
        if ($this->isEditing) {
            $laporan = LaporanHarian::findOrFail($this->laporanId);

            if ($pathFoto && $laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }

            $laporan->update([
                'tanggal' => $datetime,
                'aktivitas' => $this->narasi,
                'catatan' => $this->catatan,
                'foto' => $pathFoto ?? $laporan->foto,
                'status' => 'submitted',
                'periode_id' => $this->periodeAktif->id,
                'kegiatan_id' => $this->kegiatan->id,
            ]);
        } else {
            LaporanHarian::create([
                'user_id' => auth()->id(),
                'kelompok_id' => $this->kelompokId, // ✅ FIX
                'tanggal' => $datetime,
                'aktivitas' => $this->narasi,
                'catatan' => $this->catatan,
                'foto' => $pathFoto,
                'status' => 'submitted',
                'periode_id' => $this->periodeAktif->id,
                'kegiatan_id' => $this->kegiatan->id,
            ]);
        }

        return redirect()->route('kegiatan.pam.laporanharian.index', [
            'role' => auth()->user()->role,
        ]);
    }

    public function render()
    {
        return view('livewire.kegiatan.pam.laporanharian.create');
    }
}