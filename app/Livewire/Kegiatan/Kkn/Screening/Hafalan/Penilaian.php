<?php

namespace App\Livewire\Kegiatan\Kkn\Screening\Hafalan;

use App\Models\IndikatorHafalan;
use App\Models\KelompokUser;
use App\Models\PenilaianHafalan;
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Penilaian extends Component
{
    use WithPagination;

    public $periode_id;
    public $kegiatan_id;
    public $search = '';
    public $role;
    public $mahasiswaId;
    public $showModal = false;
    public $selectedMahasiswa;
    public $indikators = [];
    public $penilaian = [];
    public $totalNilai = 0;
    public $maxNilai = 0;
    public $progressPersen = 0;
    public $sudahDinilai = 0;
    public $totalIndikator = 0;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId('KKN');
        $this->role = request()->route('role') ?? auth()->user()->role;
    }

    public function getMahasiswasProperty()
    {
        $userRole = auth()->user()->role;
        $userProdiId = auth()->user()->id_prodi ?? null;

        $kelompokUsers = KelompokUser::with(['user', 'kelompok'])
            ->where('role', 'mahasiswa')
            ->whereHas('kelompok', function ($query) {
                $query
                    ->where('periode_id', $this->periode_id)
                    ->where('kegiatan_id', $this->kegiatan_id);
            })
            ->when($userRole === 'prodi', function ($query) use ($userProdiId) {
                $query->whereHas('user', function ($q) use ($userProdiId) {
                    $q->where('id_prodi', $userProdiId);
                });
            })
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('data_mahasiswa', 'like', '%' . $this->search . '%');
                });
            })
  
            ->paginate(10);

        $mappedData = $kelompokUsers->getCollection()->map(function ($kelompokUser) {
            $user = $kelompokUser->user;
            $dm = is_array($user->data_mahasiswa)
                ? $user->data_mahasiswa
                : json_decode($user->data_mahasiswa ?? '{}', true);

            $jenisKelamin = $dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L');

            // Hitung total indikator berdasarkan jenis kelamin
            $totalIndikator = IndikatorHafalan::forGender($jenisKelamin)
                ->ordered()
                ->count();

            $sudahDinilai = PenilaianHafalan::where('user_id', $user->id)
                ->where('periode_id', $this->periode_id)
                ->where('kegiatan_id', $this->kegiatan_id)
                ->count();

            $progressPersen = $totalIndikator > 0 ? round(($sudahDinilai / $totalIndikator) * 100) : 0;

            $totalNilai = PenilaianHafalan::where('user_id', $user->id)
                ->where('periode_id', $this->periode_id)
                ->where('kegiatan_id', $this->kegiatan_id)
                ->get()
                ->sum(function ($p) {
                    return $p->nilai == 'sangat_lancar' ? 100 : ($p->nilai == 'cukup_lancar' ? 70 : 0);
                });

            $maxNilai = $totalIndikator * 100;

            return (object) [
                'id' => $user->id,
                'nim' => $dm['nim'] ?? ($user->nim ?? '-'),
                'nama' => $dm['nama_mahasiswa'] ?? ($user->name ?? '-'),
                'prodi' => $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'),
                'jenis_kelamin' => $jenisKelamin,
                'kelompok' => $kelompokUser->kelompok->nama_kelompok ?? '-',
                'no_hp' => $dm['no_hp'] ?? ($user->no_hp ?? '-'),
                'foto' => $dm['foto'] ?? null,
                'progress' => $progressPersen,
                'sudah_dinilai' => $sudahDinilai,
                'total_indikator' => $totalIndikator,
                'total_nilai' => $totalNilai,
                'max_nilai' => $maxNilai,
            ];
        });

        $kelompokUsers->setCollection($mappedData);
        return $kelompokUsers;
    }

    public function openPenilaianModal($mahasiswaId)
    {
        $this->mahasiswaId = $mahasiswaId;
        $this->loadMahasiswaData();
        $this->loadIndikators();
        $this->loadExistingPenilaian();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['mahasiswaId', 'penilaian', 'totalNilai', 'sudahDinilai', 'progressPersen', 'indikators', 'selectedMahasiswa']);
    }

    public function loadMahasiswaData()
    {
        $kelompokUser = KelompokUser::with(['user', 'kelompok'])
            ->where('user_id', $this->mahasiswaId)
            ->where('role', 'mahasiswa')
            ->first();

        if ($kelompokUser) {
            $user = $kelompokUser->user;
            $dm = is_array($user->data_mahasiswa)
                ? $user->data_mahasiswa
                : json_decode($user->data_mahasiswa ?? '{}', true);

            $this->selectedMahasiswa = (object) [
                'id' => $user->id,
                'nim' => $dm['nim'] ?? ($user->nim ?? '-'),
                'nama' => $dm['nama_mahasiswa'] ?? ($user->name ?? '-'),
                'prodi' => $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'),
                'jenis_kelamin' => $dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L'),
                'kelompok' => $kelompokUser->kelompok->nama_kelompok ?? '-',
            ];
        }
    }

    public function loadIndikators()
    {
        $jenisKelamin = $this->selectedMahasiswa->jenis_kelamin ?? 'L';

        // Ambil indikator berdasarkan jenis kelamin
        $this->indikators = IndikatorHafalan::forGender($jenisKelamin)
            ->ordered()
            ->get();

        $this->totalIndikator = $this->indikators->count();
        $this->maxNilai = $this->totalIndikator * 100;
    }

    public function loadExistingPenilaian()
    {
        $existingPenilaian = PenilaianHafalan::where('user_id', $this->mahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get()
            ->keyBy('indikator_id');

        $total = 0;
        $this->sudahDinilai = 0;

        foreach ($this->indikators as $indikator) {
            if ($existingPenilaian->has($indikator->id)) {
                $nilai = $existingPenilaian[$indikator->id]->nilai;
                $this->penilaian[$indikator->id] = $nilai;
                $total += ($nilai == 'sangat_lancar' ? 100 : 70);
                $this->sudahDinilai++;
            } else {
                $this->penilaian[$indikator->id] = null;
            }
        }

        $this->totalNilai = $total;
        $this->calculateProgress();
    }

    public function calculateProgress()
    {
        if ($this->totalIndikator > 0) {
            $this->progressPersen = round(($this->sudahDinilai / $this->totalIndikator) * 100);
        } else {
            $this->progressPersen = 0;
        }
    }

    public function updatedPenilaian($value, $key)
    {
        $indikatorId = $key;

        PenilaianHafalan::updateOrCreate(
            [
                'user_id' => $this->mahasiswaId,
                'indikator_id' => $indikatorId,
                'periode_id' => $this->periode_id,
                'kegiatan_id' => $this->kegiatan_id,
            ],
            [
                'nilai' => $value,
                'penilai_id' => auth()->id(),
                'tanggal_penilaian' => now(),
            ]
        );

        // Recalculate
        $this->loadExistingPenilaian();

        $this->dispatch('progress-updated', [
            'progress' => $this->progressPersen,
            'sudahDinilai' => $this->sudahDinilai,
            'totalIndikator' => $this->totalIndikator,
            'totalNilai' => $this->totalNilai,
            'maxNilai' => $this->maxNilai,
        ]);
    }

    public function saveAllPenilaian()
    {
        if ($this->sudahDinilai < $this->totalIndikator) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Perhatian!',
                'text' => 'Masih ada ' . ($this->totalIndikator - $this->sudahDinilai) . ' indikator yang belum dinilai.'
            ]);
            return;
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Semua penilaian hafalan berhasil disimpan.',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);

        $this->closeModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.kegiatan.kkn.screening.hafalan.penilaian', [
            'mahasiswas' => $this->mahasiswas,
        ]);
    }
}
