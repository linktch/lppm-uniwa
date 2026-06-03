<?php

namespace App\Livewire\Kegiatan\Pam\CapaianPembelajaran;

use App\Models\IndikatorPencapaian;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\PenilaianCp;
use App\Models\Periode;
use App\Models\ProdiFakultas;
use App\Models\User;
use App\Services\PAMService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Penilaian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // =========================
    // PROPERTIES
    // =========================
    public $role;
    public $search = '';
    public $periodeFilter = '';
    public $filterProdi = '';
    public $jenis = 'PAM';

    public $kegiatanID;
    public $kegiatan;

    // =========================
    // MOUNT
    // =========================
    public function mount($role)
    {
        abort_if(!in_array($role, ['superadmin', 'prodi', 'mitra', 'mahasiswa', 'kemahasiswaan']), 403);

        $this->role = $role;

        // ✅ Ambil dari service (cache)
        $this->kegiatan   = PAMService::kegiatan($this->jenis);
        $this->kegiatanID = $this->kegiatan?->id;

        $this->periodeFilter = PAMService::periodeId();
    }

    // =========================
    // RENDER
    // =========================
    public function render()
    {
        $authUser = auth()->user();

        $periodes = Periode::latest()->get();
        $prodis   = ProdiFakultas::all();

        // =========================
        // KELOMPOK AKTIF
        // =========================
        $kelompok = Kelompok::where('kegiatan_id', $this->kegiatanID)
            ->where('periode_id', $this->periodeFilter)
            ->first();

        if (!$kelompok) {
            return view('livewire.kegiatan.pam.capaian-pembelajaran.penilaian', [
                'mahasiswas' => collect(),
                'periodes' => $periodes,
                'prodis' => $prodis,
                'jenis' => $this->jenis,
            ]);
        }

        // =========================
        // AMBIL USER SESUAI ROLE
        // =========================
        if (in_array($this->role, ['mitra', 'mahasiswa'])) {

            $users = KelompokUser::with('user')
                ->where('kelompok_id', $kelompok->id)
                ->where('role', 'mahasiswa')
                ->when($this->role === 'mahasiswa', fn($q) => $q->where('user_id', $authUser->id))
                ->when($this->search, function ($q) {
                    $q->whereHas('user', function ($u) {
                        $u->where('data_mahasiswa', 'like', "%{$this->search}%");
                    });
                })
                ->get()
                ->pluck('user');

        } else {

            $users = User::where('role', 'mahasiswa')
                ->when($this->role === 'prodi', function ($q) use ($authUser) {
                    $q->where('id_prodi', $authUser->id_prodi);
                })
                ->when($this->filterProdi, function ($q) {
                    $q->where('id_prodi', $this->filterProdi);
                })
                ->when($this->search, function ($q) {
                    $q->where('data_mahasiswa', 'like', "%{$this->search}%");
                })
                ->get();
        }

        // =========================
        // PREFETCH DATA (ANTI N+1)
        // =========================
        $userIds = $users->pluck('id');

        $kelompokUsers = KelompokUser::with('kelompok')
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $indikatorCP = IndikatorPencapaian::where('kelompok_id', $kelompok->id)
            ->where('periode_id', $this->periodeFilter)
            ->where('kegiatan_id', $this->kegiatanID)
            ->get();

        $penilaianCP = PenilaianCp::whereIn('user_id', $userIds)
            ->where('kelompok_id', $kelompok->id)
            ->where('progres', 'tercapai')
            ->get()
            ->groupBy('user_id');

        $indikatorCount = $indikatorCP->count();

        // =========================
        // MAPPING DATA
        // =========================
        $dataMahasiswa = $users->map(function ($mhs) use (
            $kelompokUsers,
            $penilaianCP,
            $indikatorCP,
            $indikatorCount
        ) {

            $data = is_string($mhs->data_mahasiswa)
                ? json_decode($mhs->data_mahasiswa, true)
                : ($mhs->data_mahasiswa ?? []);

            $kelompokUser = $kelompokUsers[$mhs->id] ?? null;

            $penilaian = $penilaianCP[$mhs->id] ?? collect();
            $penilaianCount = $penilaian->count();

            $progress = $indikatorCount > 0
                ? round(($penilaianCount / $indikatorCount) * 100)
                : 0;

            return [
                'id' => $mhs->id,
                'nim' => $data['nim'] ?? '-',
                'nama' => $data['nama_mahasiswa'] ?? $mhs->name,
                'prodi' => $data['nama_program_studi'] ?? '-',
                'kelompok' => $kelompokUser?->kelompok?->nama_kelompok ?? '-',
                'kelompokID' => $kelompokUser?->kelompok?->id ?? null,
                'indikator' => $indikatorCP,
                'penilaian_cp' => $penilaian,
                'progress' => $progress,
            ];
        });

        // =========================
        // RETURN VIEW
        // =========================
        return view('livewire.kegiatan.pam.capaian-pembelajaran.penilaian', [
            'mahasiswas' => $dataMahasiswa,
            'periodes' => $periodes,
            'prodis' => $prodis,
            'jenis' => $this->jenis,
            'kegiatan' => $this->kegiatan,
        ]);
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
}