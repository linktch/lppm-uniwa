<?php

namespace App\Livewire\Kegiatan\Pam\Kelompok;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\User;
use App\Models\Periode;
use App\Models\Kegiatan;
use App\Models\ProdiFakultas;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $role;

    public $periodeFilter = '';
    public $search = '';
    public $jenis = 'PAM';

    public $kelompok_id;
    public $nama_kelompok;
    public $periode_id;
    public $kegiatan_id;

    public $kegiatanID;

    public $showModal = false;
    public $selectedMahasiswa;

    public $mitra = '';

    public $prodis = [];

    /**
     * =========================
     * MOUNT
     * =========================
     */
    public function mount($role)
    {
        if (!in_array($role, ['superadmin', 'prodi', 'mitra', 'kemahasiswaan'])) {
            abort(403);
        }

        $this->role = $role;

        $this->kegiatanID = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');
    }

    /**
     * =========================
     * OPEN MODAL
     * =========================
     */
    public function openModal($mahasiswaID)
    {
        $user = User::find($mahasiswaID);

        if (!$user) return;

        $data = json_decode($user->data_mahasiswa, true) ?? [];

        $this->selectedMahasiswa = [
            'id' => $user->id,
            'nim' => $data['nim'] ?? '-',
            'nama' => $data['nama_mahasiswa'] ?? $user->name,
        ];

        $this->showModal = true;
    }

    /**
     * =========================
     * SAVE PLOTTING
     * =========================
     */
    public function savePlotting()
    {
        if (!$this->selectedMahasiswa || !$this->mitra) {
            session()->flash('error', 'Data belum lengkap');
            return;
        }

        KelompokUser::updateOrCreate(
            [
                'user_id' => $this->selectedMahasiswa['id'],
            ],
            [
                'kelompok_id' => $this->mitra,
                'role' => 'mahasiswa',
            ]
        );

        $this->reset(['showModal', 'selectedMahasiswa', 'mitra']);

        session()->flash('success', 'Plotting berhasil disimpan');
    }

    /**
     * =========================
     * RENDER
     * =========================
     */
    public function render()
    {
        $user = auth()->user();

        // =========================
        // PERIODE & DATA MASTER
        // =========================
        $periodes = Periode::latest()->get();
        $kegiatan = Kegiatan::all();
        $prodis = ProdiFakultas::all();

        // =========================
        // QUERY USER
        // =========================
        $query = User::query();

        if ($this->role === 'prodi') {
            $query->where('data_mahasiswa', 'like', '%' . $user->id_prodi . '%');
        }

        if ($this->role === 'mitra') {
            $query->where('id', $user->id);
        }

        $query->where('role', 'mahasiswa');

        $dataMahasiswa = $query->paginate(10);

        // =========================
        // AMBIL KELOMPOK (ANTI N+1)
        // =========================
        $kelompokUsers = KelompokUser::with('kelompok.kegiatan')
            ->whereIn('user_id', $dataMahasiswa->getCollection()->pluck('id'))
            ->get()
            ->keyBy('user_id');

        // =========================
        // TRANSFORM DATA
        // =========================
        $dataMahasiswa->setCollection(
            $dataMahasiswa->getCollection()->map(function ($user) use ($kelompokUsers) {

                $data = json_decode($user->data_mahasiswa, true) ?? [];

                $kelompokUser = $kelompokUsers[$user->id] ?? null;

                return [
                    'id' => $user->id,
                    'nim' => $data['nim'] ?? '-',
                    'nama' => $data['nama_mahasiswa'] ?? $user->name ?? '-',
                    'prodi' => $data['nama_program_studi'] ?? '-',

                    'mitra' => $kelompokUser?->kelompok?->nama_kelompok ?? 'Belum ada kelompok',

                    'status_plotting' => $kelompokUser
                        ? 'Sudah Plotting'
                        : 'Belum ada kelompok',

                    'kegiatan' => $kelompokUser?->kelompok?->kegiatan?->nama_kegiatan ?? '-',
                ];
            })
        );

        // =========================
        // KELOMPOK LIST
        // =========================
        $kelompoks = Kelompok::with(['periode', 'kegiatan'])
            ->when($this->kegiatanID, fn($q) =>
                $q->where('kegiatan_id', $this->kegiatanID)
            )
            ->when($this->search, fn($q) =>
                $q->where('nama_kelompok', 'like', "%{$this->search}%")
            )
            ->when($this->periodeFilter, fn($q) =>
                $q->where('periode_id', $this->periodeFilter)
            )
            ->latest()
            ->paginate(10);

        // =========================
        // RETURN VIEW
        // =========================
        return view('livewire.kegiatan.pam.kelompok.index', [
            'kelompoks' => $kelompoks,
            'periodes' => $periodes,
            'kegiatan' => $kegiatan,
            'jenis' => $this->jenis,
            'dataMahasiswa' => $dataMahasiswa,
            'prodis' => $prodis,
        ]);
    }
}