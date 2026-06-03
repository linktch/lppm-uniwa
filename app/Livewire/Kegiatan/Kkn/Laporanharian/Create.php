<?php

namespace App\Livewire\Kegiatan\Kkn\Laporanharian;

use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\TimelineKegiatan;
use App\Services\KKNService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

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
    // ✅ Timeline properties
    public $timelinePelaksanaan;
    public $canCreateLaporan = false;
    public $timelineMessage = '';
    public $sisaHari = 0;

    public function mount($id = null)
    {
        // default tanggal & jam
        $this->tanggal = now()->toDateString();
        $this->jam = now()->format('H:i');

        // ✅ Ambil dari service KKN
        $this->periodeAktif = KKNService::periode();
        $this->kegiatan = KKNService::kegiatan('KKN');

        if (!$this->periodeAktif || !$this->kegiatan) {
            abort(404, 'Periode atau kegiatan KKN tidak ditemukan');
        }

        // ✅ Load timeline untuk validasi
        $this->loadTimeline();

        // ✅ Validasi timeline sebelum melanjutkan
        if (!$this->canCreateLaporan && !$id) {
            session()->flash('error', $this->timelineMessage);
            return redirect()->route('kegiatan.kkn.laporanharian.index', [
                'role' => auth()->user()->role,
            ]);
        }

        // ambil kelompok user (hanya untuk ambil ID)
        $kelompokUser = KelompokUser::with('kelompok')
            ->where('user_id', auth()->id())
            ->whereHas('kelompok', fn($q) =>
                $q
                    ->where('periode_id', $this->periodeAktif->id)
                    ->where('kegiatan_id', $this->kegiatan->id))
            ->first();

        $this->kelompokId = $kelompokUser?->kelompok?->id;

        if (!$this->kelompokId) {
            abort(404, 'Kelompok KKN tidak ditemukan');
        }

        // 🔥 MODE EDIT
        if ($id) {
            $laporan = LaporanHarian::findOrFail($id);

            // ✅ Validasi kepemilikan laporan
            if ($laporan->user_id != auth()->id()) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
            }

            // ✅ Validasi timeline untuk edit
            if (!$this->canCreateLaporan) {
                session()->flash('error', 'Tidak dapat mengedit laporan karena periode pelaksanaan sudah berakhir.');
                return redirect()->route('kegiatan.kkn.laporanharian.index', [
                    'role' => auth()->user()->role,
                ]);
            }

            $this->isEditing = true;
            $this->laporanId = $id;

            $this->tanggal = Carbon::parse($laporan->tanggal)->toDateString();
            $this->jam = Carbon::parse($laporan->tanggal)->format('H:i');
            $this->narasi = $laporan->aktivitas;
            $this->catatan = $laporan->catatan;
            $this->foto = $laporan->foto;
        }
    }

    // =========================
    // LOAD TIMELINE PELAKSANAAN
    // =========================
    protected function loadTimeline()
    {
        // Ambil timeline dengan jenis 'pelaksanaan' untuk periode dan kegiatan ini
        $this->timelinePelaksanaan = TimelineKegiatan::where('periode_id', $this->periodeAktif->id)
            ->where('kegiatan_id', $this->kegiatan->id)
            ->where('jenis', 'pelaksanaan')
            ->first();

        if ($this->timelinePelaksanaan) {
            $today = Carbon::today();
            $tanggalMulai = Carbon::parse($this->timelinePelaksanaan->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($this->timelinePelaksanaan->tanggal_selesai);

            if ($today->between($tanggalMulai, $tanggalSelesai)) {
                $this->canCreateLaporan = true;
                $this->timelineMessage = 'Periode pelaksanaan KKN: '
                    . $tanggalMulai->format('d/m/Y') . ' - '
                    . $tanggalSelesai->format('d/m/Y');

                // Hitung sisa hari
                $this->sisaHari = $today->diffInDays($tanggalSelesai, false);
                if ($this->sisaHari > 0) {
                    $this->timelineMessage .= " | Sisa {$this->sisaHari} hari";
                }
            } elseif ($today->lt($tanggalMulai)) {
                $this->canCreateLaporan = false;
                $this->timelineMessage = 'Pelaksanaan KKN akan dimulai pada: '
                    . $tanggalMulai->format('d/m/Y');
            } else {
                $this->canCreateLaporan = false;
                $this->timelineMessage = 'Periode pelaksanaan KKN telah berakhir pada: '
                    . $tanggalSelesai->format('d/m/Y');
            }
        } else {
            $this->canCreateLaporan = false;
            $this->timelineMessage = 'Timeline pelaksanaan KKN belum ditentukan. Silahkan hubungi administrator.';
        }
    }

    public function removeFoto()
    {
        $this->foto = null;
    }

    public function back()
    {
        return redirect()->route('kegiatan.kkn.laporanharian.index', [
            'role' => auth()->user()->role,
        ]);
    }

    public function saveLaporan()
    {
        // ✅ Validasi timeline lagi sebelum save
        if (!$this->canCreateLaporan && !$this->isEditing) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => $this->timelineMessage
            ]);
            return;
        }

        $this->validate([
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'narasi' => 'required|min:500',
            'foto' => $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        // gabungkan tanggal + jam
        $datetime = Carbon::parse($this->tanggal . ' ' . $this->jam);

        // ✅ Validasi tanggal tidak boleh melebihi tanggal selesai timeline
        if ($this->timelinePelaksanaan && $datetime->gt(Carbon::parse($this->timelinePelaksanaan->tanggal_selesai))) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Tanggal laporan melebihi periode pelaksanaan KKN.'
            ]);
            return;
        }

        // ✅ Validasi tanggal tidak boleh kurang dari tanggal mulai timeline
        if ($this->timelinePelaksanaan && $datetime->lt(Carbon::parse($this->timelinePelaksanaan->tanggal_mulai))) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Tanggal laporan belum memasuki periode pelaksanaan KKN.'
            ]);
            return;
        }

        // 🔥 upload foto
        $pathFoto = null;

        if ($this->foto && !is_string($this->foto)) {
            $pathFoto = $this->foto->store('laporan/kkn', 'public');
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

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Laporan harian berhasil diperbarui'
            ]);
        } else {
            LaporanHarian::create([
                'user_id' => auth()->id(),
                'kelompok_id' => $this->kelompokId,
                'tanggal' => $datetime,
                'aktivitas' => $this->narasi,
                'catatan' => $this->catatan,
                'foto' => $pathFoto,
                'status' => 'submitted',
                'periode_id' => $this->periodeAktif->id,
                'kegiatan_id' => $this->kegiatan->id,
            ]);
            $this->reset(['tanggal', 'jam', 'narasi', 'foto']);

            $this->dispatch('swal-and-redirect', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Laporan harian berhasil disimpan',
                'url' => route('kegiatan.kkn.laporanharian.index', [
                    'role' => auth()->user()->role,
                ])
            ]);

            return;
        }

        // Reset form setelah sukses

        // Kembali ke halaman index
        return redirect()->route('kegiatan.kkn.laporanharian.index', [
            'role' => auth()->user()->role,
        ]);
    }

    public function render()
    {
        return view('livewire.kegiatan.kkn.laporanharian.create', [
            'timelinePelaksanaan' => $this->timelinePelaksanaan,
            'canCreateLaporan' => $this->canCreateLaporan,
            'timelineMessage' => $this->timelineMessage,
            'sisaHari' => $this->sisaHari,
        ]);
    }
}
