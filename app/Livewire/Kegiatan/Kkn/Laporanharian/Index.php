<?php

namespace App\Livewire\Kegiatan\Kkn\Laporanharian;

use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\ProdiFakultas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $periodeFilter = '';
    public $filterProdi = '';
    public $perPage = 10;
    public $role;
    public $canCreateLaporan = false;
    public $sisaHari = 0;
    public $timelineMessage = '';
    public $periodeAktif;
    public $deleteId = null;

    public function mount()
    {
        $this->role = Auth::user()->role;
        $this->checkPeriodeAktif();
    }

    public function checkPeriodeAktif()
    {
        $this->periodeAktif = Periode::where('status', 'AKTIF')->first();

        if ($this->periodeAktif) {
            $today = Carbon::now();
            $tglMulai = Carbon::parse($this->periodeAktif->tanggal_mulai);
            $tglSelesai = Carbon::parse($this->periodeAktif->tanggal_selesai);

            if ($today->between($tglMulai, $tglSelesai)) {
                $this->canCreateLaporan = true;
                $this->sisaHari = $today->diffInDays($tglSelesai);
                $this->timelineMessage = 'Periode pelaksanaan KKN sedang berlangsung. Anda dapat membuat laporan harian.';
            } elseif ($today->lt($tglMulai)) {
                $this->canCreateLaporan = false;
                $this->timelineMessage = 'Periode pelaksanaan KKN belum dimulai. Mulai tanggal ' . $tglMulai->format('d/m/Y');
            } else {
                $this->canCreateLaporan = false;
                $this->timelineMessage = 'Periode pelaksanaan KKN telah berakhir.';
            }
        } else {
            $this->canCreateLaporan = false;
            $this->timelineMessage = 'Belum ada periode aktif untuk pelaksanaan KKN.';
        }
    }

    public function create()
    {
        return redirect()->route('kegiatan.kkn.laporanharian.create', [
            'role' => $this->role
        ]);
    }

    public function view($id)
    {
        return redirect()->route('kegiatan.kkn.laporanharian.view', [
            'role' => $this->role,
            'id' => $id
        ]);
    }

    public function edit($id)
    {
        $laporan = LaporanHarian::find($id);

        if (!$laporan) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Laporan tidak ditemukan'
            ]);
            return;
        }

        if ($laporan->status != 'revisi') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Hanya laporan dengan status REVISI yang dapat diedit'
            ]);
            return;
        }

        if ($laporan->user_id != auth()->id()) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki akses'
            ]);
            return;
        }

        return redirect()->route('kegiatan.kkn.laporanharian.update', [
            'role' => $this->role,
            'id' => $id
        ]);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-confirm');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed()
    {
        logger('deleteConfirmed dipanggil');
        logger('Delete ID: ' . $this->deleteId);

        $laporan = LaporanHarian::find($this->deleteId);

        logger('Laporan ditemukan: ' . ($laporan ? 'YA' : 'TIDAK'));

        if ($laporan && $laporan->user_id == Auth::id()) {
            logger('User valid');

            if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
                Storage::disk('public')->delete($laporan->foto);
            }

            $laporan->delete();

            logger('Laporan berhasil dihapus');

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Laporan berhasil dihapus'
            ]);

            $this->dispatch('redirect-after-delete');
        } else {
            logger('Gagal hapus laporan');

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki akses untuk menghapus laporan ini'
            ]);
        }
    }

    public function render()
    {
        $query = LaporanHarian::with(['user', 'kelompok']);

        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }

        if ($this->periodeFilter) {
            $query->where('periode_id', $this->periodeFilter);
        }

        if ($this->filterProdi) {
            $query->whereHas('user', function ($q) {
                $q->where('prodi_id', $this->filterProdi);
            });
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $laporans = $query->orderBy('tanggal', 'desc')->paginate($this->perPage);
        $periodes = Periode::all();
        $prodis = ProdiFakultas::all();

        return view('livewire.kegiatan.kkn.laporanharian.index', [
            'laporans' => $laporans,
            'periodes' => $periodes,
            'prodis' => $prodis,
        ]);
    }
}
