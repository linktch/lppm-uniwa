<?php

namespace App\Livewire\Mitra\Kegiatan\Pam;

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
class Presensi extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $jenis;
    public $kelompok = null;
    public $search = '';

    public function mount($jenis)
    {
        $this->jenis = $jenis;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $kegiatanId = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');

        $periodeAktifId = Periode::where('status', 'AKTIF')->value('id');

        $kelompokIds = Kelompok::where('periode_id', $periodeAktifId)
            ->where('kegiatan_id', $kegiatanId)
            ->pluck('id');

        $kelompokMitraIds = KelompokUser::where('user_id', auth()->id())
            ->where('role', 'mitra')
            ->whereIn('kelompok_id', $kelompokIds)
            ->pluck('kelompok_id');

        $this->kelompok = Kelompok::with('periode')
            ->whereIn('id', $kelompokMitraIds)
            ->first();

        $today = Carbon::today()->toDateString();

        $kehadiranToday = Kehadiran::whereIn('kelompok_id', $kelompokMitraIds)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy(fn ($item) => $item->kelompok_id . '-' . $item->user_id);

        // 🔥 QUERY + SEARCH + PAGINATION
        $users = KelompokUser::with('user')
            ->whereIn('kelompok_id', $kelompokMitraIds)
            ->where('role', 'mahasiswa')

            // 🔥 SEARCH FIX
            ->when($this->search, function ($query) {
                $search = $this->search;

                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })

            ->paginate(10)
            ->through(function ($item) use ($kehadiranToday) {

                $user = $item->user;

                $data = is_string($user?->data_mahasiswa)
                    ? json_decode($user->data_mahasiswa, true)
                    : ($user?->data_mahasiswa ?? []);

                $key = $item->kelompok_id . '-' . $item->user_id;

                $status = strtolower($kehadiranToday[$key]->status ?? 'belum');

                return [
                    'id' => $item->user_id,
                    'nim' => $data['nim'] ?? '-',
                    'name' => $data['nama_mahasiswa'] ?? ($user->name ?? '-'),
                    'prodi' => $data['nama_program_studi'] ?? '-',
                    'status' => $status,
                ];
            });

        return view('livewire.mitra.kegiatan.pam.presensi', [
            'users' => $users,
            'mahasiswas' => $users,
            'kelompok' => $this->kelompok,
        ]);
    }

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