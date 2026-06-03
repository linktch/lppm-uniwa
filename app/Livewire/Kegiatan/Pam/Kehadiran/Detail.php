<?php

namespace App\Livewire\Kegiatan\Pam\Kehadiran;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\KelompokUser;
use App\Models\Kegiatan;
use App\Models\Periode;
use App\Models\Kelompok;
use App\Models\Kehadiran;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // =====================
    // GLOBAL JENIS (FIX)
    // =====================
    public string $jenis = 'PAM';

    public $kelompok = null;
    public $kelompokID;
    public $search = '';

    // =====================
    // MOUNT (FIXED)
    // =====================
    public function mount($kelompokID)
    {
        $this->kelompokID = $kelompokID;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // =====================
    // RENDER
    // =====================
    public function render()
    {
        $userLogin = auth()->user();
        $today = \Carbon\Carbon::today()->toDateString();

        // 🔹 Ambil kegiatan & periode aktif
        $kegiatanId = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');

        $periodeAktifId = Periode::where('status', 'AKTIF')->value('id');

        // 🔹 Ambil semua kelompok sesuai kegiatan & periode
        $kelompokIds = Kelompok::where('periode_id', $periodeAktifId)
            ->where('kegiatan_id', $kegiatanId)
            ->pluck('id');

        // 🔹 Ambil kelompok milik MITRA (user login)
        $kelompokMitraIds = KelompokUser::where('user_id', $userLogin->id)
        
            ->where('role', 'mitra')
            ->orwhere('role', 'prodi')
            ->where('user_id', Auth()->User()->id)
            ->whereIn('kelompok_id', $kelompokIds)
            ->pluck('kelompok_id');
        // dd($kelompokMitraIds);
        // 🔹 Ambil detail kelompok (1 saja)
        $this->kelompok = Kelompok::with('periode')
            ->whereIn('id', $kelompokMitraIds)
            ->first();
        // dd($this->kelompok);

        // 🔹 Kehadiran hari ini (biar cepat pakai keyBy)
        $kehadiranToday = Kehadiran::whereIn('kelompok_id', $kelompokMitraIds)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy(fn ($item) => $item->kelompok_id . '-' . $item->user_id);
        // dd($kehadiranToday);
        // 🔹 Query mahasiswa
        $query = KelompokUser::with('user')
            ->whereIn('kelompok_id', $kelompokMitraIds)
            ->where('role', 'mahasiswa');

        // 🔥 Filter khusus PRODI
        if ($userLogin->role == 'prodi') {
            $query->whereHas('user', function ($q) use ($userLogin) {
                $q->where('id_prodi', $userLogin->id_prodi);
            });
        }

        // 🔍 Search
        if ($this->search) {
            $search = $this->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 🔹 Eksekusi + transform
        $users = $query->paginate(10)
            ->through(function ($item) use ($kehadiranToday) {

                $user = $item->user;

                $data = is_string($user?->data_mahasiswa)
                    ? json_decode($user->data_mahasiswa, true)
                    : ($user?->data_mahasiswa ?? []);

                $key = $item->kelompok_id . '-' . $item->user_id;

                $status = strtolower($kehadiranToday[$key]->status ?? 'belum');

                return [
                    'id'     => $item->user_id,
                    'nim'    => $data['nim'] ?? '-',
                    'name'   => $data['nama_mahasiswa'] ?? ($user->name ?? '-'),
                    'prodi'  => $data['nama_program_studi'] ?? '-',
                    'status' => $status,
                ];
            });
            // dd($users);
        return view('livewire.kegiatan.pam.kehadiran.detail', [
            'users' => $users,
            'mahasiswas' => $users, // kalau ini sama, sebenarnya bisa dihapus salah satu
            'kelompok' => $this->kelompok,
            
        ]);
    }

    // =====================
    // PRESENSI
    // =====================
    public function presensiHadir($userId)
    {
        $this->savePresensi($userId, 'hadir');
    }

    public function presensiIzin($userId)
    {
        $this->savePresensi($userId, 'izin');
    }

    public function presensiAlpha($userId)
    {
        $this->savePresensi($userId, 'alpha');
    }

    private function savePresensi($userId, $status)
    {
        // dd($userId);
        $today = Carbon::today()->toDateString();

        $kelompokId = $this->kelompok->id ?? null;
        if (!$kelompokId) return;

        Kehadiran::updateOrCreate(
            [
                'kelompok_id' => $kelompokId,
                'user_id' => $userId,
                'tanggal' => $today,
            ],
            [
                'status' => $status,
            ]
        );
    }
}