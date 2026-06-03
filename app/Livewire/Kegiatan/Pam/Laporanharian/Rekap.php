<?php

namespace App\Livewire\Kegiatan\Pam\Laporanharian;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\ProdiFakultas;
use App\Models\User;
use App\Services\PAMService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Rekap extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // FILTER & STATE
    public $role;
    public $periodeFilter = '';
    public $filterProdi = '';
    public $search = '';
    public $jenis = 'PAM';
    public $periodeAktif;
    public $kegiatanID;
    // WEEK CONTROL
    public $week;
    public $startDate;
    public $endDate;
    public $baseStartDate;
    public $baseEndDate;
    // UI
    public $showModal = false;
    public $selectedMahasiswa;
    public $mitra = '';

    public function mount($role)
    {
        abort_if(!in_array($role, ['superadmin', 'prodi', 'mitra', 'kemahasiswaan']), 403);

        $this->role = $role;

        // Kegiatan
        $this->periodeAktif = PAMService::periode();
        $this->kegiatanID = PAMService::kegiatan('PAM');
        // Kegiatan

        // Periode aktif

        $this->periodeFilter = $this->periodeAktif?->id;
        $this->baseStartDate = $this->periodeAktif?->tanggal_mulai;
        $this->baseEndDate = $this->periodeAktif?->tanggal_selesai;

        // =========================
        // WEEK AKTIF OTOMATIS
        // =========================
        if ($this->baseStartDate) {
            $start = Carbon::parse($this->baseStartDate)
                ->startOfWeek(Carbon::MONDAY);

            $this->week = max(1, floor($start->diffInDays(now()) / 7) + 1);
        } else {
            $this->week = 1;
        }

        $this->setWeekRange();
    }

    public function updatedWeek()
    {
        $this->setWeekRange();
    }

    /**
     * Set range tanggal berdasarkan minggu
     */
    public function setWeekRange()
    {
        if (!$this->baseStartDate)
            return;

        $start = Carbon::parse($this->baseStartDate)
            ->startOfWeek(Carbon::MONDAY);

        $monday = $start->copy()->addWeeks($this->week - 1);
        $thursday = $monday->copy()->addDays(3);

        $this->startDate = $monday->toDateString();
        $this->endDate = $thursday->toDateString();
    }

    public function render()
    {
        $user = auth()->user();

        // MASTER DATA
        $periodes = Periode::latest()->get();
        $kegiatan = Kegiatan::all();
        $prodis = ProdiFakultas::all();

        // QUERY MAHASISWA
        $query = User::query()->where('role', 'mahasiswa');

        if ($this->role === 'prodi') {
            $query->where('data_mahasiswa', 'like', "%{$user->id_prodi}%");
        }

        if ($this->role === 'mitra') {
            $kelompokMitra = KelompokUser::with(['kelompok.periode', 'kelompok.kegiatan'])
                ->where('user_id', auth()->id())
                ->whereHas('kelompok', function ($q) {
                    $q
                        ->where('periode_id', $this->periodeAktif->id)
                        ->where('kegiatan_id', $this->kegiatanID->id);
                })
                ->first();

            if (!$kelompokMitra) {
                $query->whereRaw('0 = 1');  // tidak ada data
                return;
            }

            $anggotaMitraUserID = KelompokUser::where('kelompok_id', $kelompokMitra->kelompok_id)
                ->where('role', 'mahasiswa')
                ->pluck('user_id');  // 🔥 ini sudah array ID

            $query->whereIn('id', $anggotaMitraUserID);
        }

        if ($this->filterProdi) {
            $query->where('data_mahasiswa', 'like', "%{$this->filterProdi}%");
        }

        if ($this->search) {
            $query->where('data_mahasiswa', 'like', "%{$this->search}%");
        }

        $dataMahasiswa = $query->paginate(10);

        // RELASI KELOMPOK
        $userIds = $dataMahasiswa->pluck('id');

        $kelompokUsers = KelompokUser::with('kelompok.kegiatan')
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');
        // dd($kelompokUsers);
        // KEHADIRAN
        $kehadiran = LaporanHarian::whereIn('user_id', $userIds)
            ->where('periode_id', $this->periodeFilter)
            ->where('kegiatan_id', $this->kegiatanID->id)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderByRaw("FIELD(status, 'revisi', 'submitted', 'approved')")
            ->get()
            ->groupBy('user_id');
        // dd($kehadiran);
        // TRANSFORM DATA
        $dataMahasiswa->setCollection(
            $dataMahasiswa->getCollection()->map(function ($user) use ($kelompokUsers, $kehadiran) {
                $data = json_decode($user->data_mahasiswa, true) ?? [];
                $kelompokUser = $kelompokUsers[$user->id] ?? null;
                $absen = $kehadiran[$user->id] ?? collect();

                $hadir = [
                    'senin' => false,
                    'selasa' => false,
                    'rabu' => false,
                    'kamis' => false,
                ];

                foreach ($absen as $item) {
                    $hari = strtolower(date('l', strtotime($item->tanggal)));

                    match ($hari) {
                        'monday' => $hadir['senin'] =  $item->status,
                        'tuesday' => $hadir['selasa'] =  $item->status,
                        'wednesday' => $hadir['rabu'] =  $item->status,
                        'thursday' => $hadir['kamis'] =  $item->status,
                        default => null,
                    };
                }

                return [
                    'id' => $user->id,
                    'nim' => $data['nim'] ?? '-',
                    'nama' => $data['nama_mahasiswa'] ?? $user->name ?? '-',
                    'prodi' => $data['nama_program_studi'] ?? '-',
                    'mitra' => $kelompokUser?->kelompok?->nama_kelompok ?? 'Belum ada kelompok',
                    'laporan' => $hadir,
                    'total_laporan' => collect($hadir)->filter()->count(),
                ];
            })
        );
        // dd($kehadiran);

        // KELOMPOK
        $kelompoks = Kelompok::with(['periode', 'kegiatan'])
            ->when($this->kegiatanID, fn($q) => $q->where('kegiatan_id', $this->kegiatanID))
            ->when($this->periodeFilter, fn($q) => $q->where('periode_id', $this->periodeFilter))
            ->when($this->search, fn($q) => $q->where('nama_kelompok', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.kegiatan.pam.laporanharian.rekap', compact(
            'kelompoks',
            'periodes',
            'kegiatan',
            'prodis',
            'dataMahasiswa'
        ))->with([
            'weekAktif' => $this->week,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
