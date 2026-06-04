<?php

namespace App\Livewire\Kegiatan\Kkn\Laporanharian;

use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\ProdiFakultas;
use App\Models\TimelineKegiatan;
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
    public $timelinePelaksanaan;
    public $deleteId = null;

    public function mount()
    {
        $this->role = Auth::user()->role;
        $this->checkPeriodeAktif();
    }

    /**
     * Cek periode aktif berdasarkan timeline pelaksanaan
     */
    public function checkPeriodeAktif()
    {
        // Ambil timeline dengan status AKTIF dan jenis = 'Pelaksanaan'
        $this->timelinePelaksanaan = TimelineKegiatan::where('status', 'AKTIF')
            ->where('jenis', 'Pelaksanaan')
            ->first();

        if ($this->timelinePelaksanaan) {
            $today = Carbon::now();
            $tglMulai = Carbon::parse($this->timelinePelaksanaan->tanggal_mulai);
            $tglSelesai = Carbon::parse($this->timelinePelaksanaan->tanggal_selesai);

            if ($today->between($tglMulai, $tglSelesai)) {
                $this->canCreateLaporan = true;
                $this->sisaHari = $today->diffInDays($tglSelesai);
                $this->timelineMessage = sprintf(
                    '✅ Periode pelaksanaan KKN sedang berlangsung (%s - %s). Sisa waktu: %s hari.',
                    $tglMulai->format('d/m/Y'),
                    $tglSelesai->format('d/m/Y'),
                    $this->sisaHari
                );
                $this->periodeAktif = $this->timelinePelaksanaan->periode;
            } elseif ($today->lt($tglMulai)) {
                $this->canCreateLaporan = false;
                $this->timelineMessage = sprintf(
                    '⏳ Periode pelaksanaan KKN belum dimulai. Akan dimulai pada %s.',
                    $tglMulai->format('d/m/Y')
                );
            } else {
                $this->canCreateLaporan = false;
                $this->timelineMessage = sprintf(
                    '❌ Periode pelaksanaan KKN telah berakhir (selesai pada %s).',
                    $tglSelesai->format('d/m/Y')
                );
            }
        } else {
            // Tidak ada timeline pelaksanaan
            $this->canCreateLaporan = false;
            $this->timelineMessage = 'Tanggal periode pelaksanaan belum ditentukan';
        }
    }

    /**
     * Redirect ke halaman create laporan
     */
    public function create()
    {
        if (!$this->canCreateLaporan) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Tidak Dapat Membuat Laporan',
                'text' => $this->timelineMessage
            ]);
            return;
        }

        return redirect()->route('kegiatan.kkn.laporanharian.create', [
            'role' => $this->role
        ]);
    }

    /**
     * Redirect ke halaman view laporan
     */
    public function view($id)
    {
        return redirect()->route('kegiatan.kkn.laporanharian.view', [
            'role' => $this->role,
            'id' => $id
        ]);
    }

    /**
     * Redirect ke halaman edit laporan (khusus status revisi)
     */
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

    /**
     * Konfirmasi hapus laporan
     */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-confirm');
    }

    /**
     * Eksekusi hapus laporan setelah konfirmasi
     */
    #[On('deleteConfirmed')]
    public function deleteConfirmed()
    {
        $laporan = LaporanHarian::find($this->deleteId);

        if ($laporan && $laporan->user_id == Auth::id()) {
            if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $laporan->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Laporan berhasil dihapus',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki akses untuk menghapus laporan ini'
            ]);
        }

        $this->deleteId = null;
    }

    /**
     * Reset pagination saat search berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat filter periode berubah
     */
    public function updatingPeriodeFilter()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat filter prodi berubah
     */
    public function updatingFilterProdi()
    {
        $this->resetPage();
    }

    /**
     * Render view
     */
    public function render()
    {
        $query = LaporanHarian::with(['user', 'kelompok']);

        // Filter berdasarkan role
        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }

        // Filter periode
        if ($this->periodeFilter) {
            $query->where('periode_id', $this->periodeFilter);
        }

        // Filter prodi
        if ($this->filterProdi) {
            $query->whereHas('user', function ($q) {
                $q->where('prodi_id', $this->filterProdi);
            });
        }

        // Filter search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where(function ($subQuery) {
                    $subQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('data_mahasiswa', 'like', '%' . $this->search . '%');
                });
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
