<?php

namespace App\Livewire\Kegiatan\Pam\Laporanharian;

use App\Models\Kehadiran;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\ProdiFakultas;
use App\Services\PAMService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // =========================
    // PROPERTIES
    // =========================
    public $search = '';
    public $periodeFilter = '';
    public $filterProdi = '';
    public $role;

    public $periodeAktif;
    public $kegiatan;
    public $kelompokUser;
    public $mahasiswa = [];

    // =========================
    // ALERT HELPER
    // =========================
    protected function alert($icon, $title, $text)
    {
        $this->dispatch('swal', compact('icon', 'title', 'text'));
    }

    // =========================
    // MOUNT (INIT DATA)
    // =========================
    public function mount($role)
    {
        $this->role = $role;

        // ✅ Ambil dari service (sudah pakai cache)
        $this->periodeAktif = PAMService::periode();
        $this->kegiatan = PAMService::kegiatan('PAM');

        // Validasi
        if (!$this->kegiatan) {
            abort(500, 'Kegiatan PAM tidak ditemukan');
        }

        if (!$this->periodeAktif) {
            abort(500, 'Periode aktif tidak ditemukan');
        }

        // Default filter
        $this->periodeFilter = $this->periodeAktif->id;

        // Ambil kelompok user login
        $this->kelompokUser = KelompokUser::with(['user', 'kelompok'])
            ->where('user_id', auth()->id())
            ->whereHas('kelompok', function ($q) {
                $q->where('periode_id', $this->periodeAktif->id)
                  ->where('kegiatan_id', $this->kegiatan->id);
            })
            ->first();

        // Decode data mahasiswa
        $user = $this->kelompokUser->user ?? null;

        $this->mahasiswa = is_string($user?->data_mahasiswa)
            ? json_decode($user->data_mahasiswa, true)
            : ($user?->data_mahasiswa ?? []);
    }

    // =========================
    // RESET PAGINATION
    // =========================
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter()
    {
        $this->resetPage();
    }

    // =========================
    // RENDER (QUERY UTAMA)
    // =========================
    public function render()
    {
        $user = auth()->user();

        $periode = $this->periodeFilter ?? $this->periodeAktif->id;

        // Query dasar
        $query = LaporanHarian::with(['user', 'kelompok', 'periode', 'kegiatan'])
            ->where('kegiatan_id', $this->kegiatan->id)
            ->where('periode_id', $periode);

        // =========================
        // FILTER ROLE
        // =========================
        if ($user->role === 'mitra') {
            $query->where('kelompok_id', $this->kelompokUser->kelompok->id ?? null);
        }

        elseif ($user->role === 'prodi') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            });
        }

        else {
            if ($this->filterProdi) {
                $query->whereHas('user', function ($q) {
                    $q->where('id_prodi', $this->filterProdi);
                });
            }
        }

        // =========================
        // SEARCH
        // =========================
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('data_mahasiswa', 'like', "%{$this->search}%");
            });
        }

        // =========================
        // FINAL DATA
        // =========================
        $laporans = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('livewire.kegiatan.pam.laporanharian.index', [
            'periode' => $this->periodeAktif,
            'kegiatan' => $this->kegiatan,
            'kelompok' => $this->kelompokUser->kelompok ?? null,
            'kelompok_user' => $this->kelompokUser,
            'mahasiswa' => $this->mahasiswa,
            'laporans' => $laporans,
            'prodis' => ProdiFakultas::all(),
            'periodes' => Periode::all(),
        ]);
    }

    // =========================
    // TAMBAH LAPORAN
    // =========================
    public function tambahLaporanPage()
    {
        $today = now()->toDateString();

        $adaKehadiran = Kehadiran::where('user_id', auth()->id())
            ->where('kelompok_id', $this->kelompokUser->kelompok->id ?? null)
            ->where('status', 'hadir')
            ->whereDate('tanggal', $today)
            ->exists();

        if (!$adaKehadiran) {
            $this->alert('error', 'Gagal', 'Anda belum melakukan Presensi hari ini!');
            return;
        }

        return redirect()->route('kegiatan.pam.laporanharian.create', [
            'role' => $this->role,
        ]);
    }
}