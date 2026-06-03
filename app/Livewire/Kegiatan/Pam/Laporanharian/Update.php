<?php

namespace App\Livewire\Kegiatan\Pam\Laporanharian;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Update extends Component
{
    use WithFileUploads;

    // 🔥 DATA LAPORAN
    public $kelompokId;
    public $laporanId = null;
    public $isEditing = false;

    // 🔥 FORM PROPERTIES
    public $tanggal;
    public $jam;
    public $narasi;
    public $catatan;
    public $foto;
    public $oldFoto;

    public function mount($lapharID = null)
    {
        // 🔥 Set default tanggal & jam
        $this->tanggal = now()->toDateString();
        $this->jam = now()->format('H:i');

        // 🔥 Ambil periode aktif & kegiatan
        $periodeAktif = Periode::where('status', 'AKTIF')->first();
        $kegiatan = Kegiatan::where('nama_kegiatan', 'PAM')->first();

        if (!$periodeAktif || !$kegiatan) {
            abort(404, 'Periode atau kegiatan tidak ditemukan');
        }

        // 🔥 Ambil kelompok user
        $kelompokUser = KelompokUser::with('kelompok')
            ->where('user_id', Auth::id())
            ->whereHas('kelompok', function ($q) use ($periodeAktif, $kegiatan) {
                $q->where('periode_id', $periodeAktif->id)
                  ->where('kegiatan_id', $kegiatan->id);
            })
            ->first();

        if (!$kelompokUser) {
            abort(404, 'Kelompok tidak ditemukan');
        }

        $this->kelompokId = $kelompokUser->kelompok->id;

        // 🔥 MODE EDIT (jika ada parameter id)
        if ($lapharID) {
            $laporan = LaporanHarian::where('id', $lapharID)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $this->isEditing = true;
            $this->laporanId = $laporan->id;
            $this->tanggal = Carbon::parse($laporan->tanggal)->toDateString();
            $this->jam = Carbon::parse($laporan->tanggal)->format('H:i');
            $this->narasi = $laporan->aktivitas;
            $this->catatan = $laporan->catatan;
            $this->oldFoto = $laporan->foto;
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
            'role' => Auth::user()->role,
        ]);
    }

    public function saveLaporan()
    {
        // 🔥 VALIDASI
        $rules = [
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'narasi' => 'required|min:20',
        ];

        if (!$this->isEditing) {
            $rules['foto'] = 'required|image|max:2048'; // 2MB
        } elseif ($this->foto && !is_string($this->foto)) {
            $rules['foto'] = 'image|max:2048';
        }

        $this->validate($rules);

        // 🔥 GABUNGKAN TANGGAL & JAM
        $datetime = Carbon::parse($this->tanggal . ' ' . $this->jam);

        // 🔥 CEK PRESENSI (WAJIB HADIR)
        $presensi = Kehadiran::where('user_id', Auth::id())
            ->where('kelompok_id', $this->kelompokId)
            ->whereDate('tanggal', $this->tanggal)
            ->first();

        if (!$presensi || $presensi->status !== 'hadir') {
            session()->flash('error', 'Harus presensi hadir terlebih dahulu!');
            return;
        }

        // 🔥 UPLOAD FOTO
        $pathFoto = null;

        if ($this->foto && !is_string($this->foto)) {
            // Hapus foto lama jika ada
            if ($this->isEditing && $this->oldFoto) {
                Storage::disk('public')->delete($this->oldFoto);
            }
            $pathFoto = $this->foto->store('laporan', 'public');
        } elseif ($this->isEditing && is_string($this->foto)) {
            $pathFoto = $this->oldFoto;
        }

        // 🔥 SIMPAN KE DATABASE
        if ($this->isEditing) {
            $laporan = LaporanHarian::findOrFail($this->laporanId);
            
            $laporan->update([
                // 'tanggal' => $datetime,
                'aktivitas' => $this->narasi,
                'catatan' => $this->catatan,
                'foto' => $pathFoto,
                'status' => 'submitted',
            ]);

            session()->flash('success', 'Laporan berhasil diupdate');
        } else {
            LaporanHarian::create([
                'user_id' => Auth::id(),
                'kelompok_id' => $this->kelompokId,
                'tanggal' => $datetime,
                'aktivitas' => $this->narasi,
                'catatan' => $this->catatan,
                'foto' => $pathFoto,
                'status' => 'submitted',
            ]);

            session()->flash('success', 'Laporan berhasil disimpan');
        }

        return redirect()->route('kegiatan.pam.laporanharian.index', [
            'role' => Auth::user()->role,
        ]);
    }

    public function render()
    {
        return view('livewire.kegiatan.pam.laporanharian.update');
    }
}