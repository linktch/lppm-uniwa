<?php

namespace App\Livewire\Kegiatan\Kkn\Screening\Hafalan;

use App\Models\KelompokUser;
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
    
    // protected $paginationTheme = 'bootstrap';

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
            $query->where('periode_id', $this->periode_id)
                  ->where('kegiatan_id', $this->kegiatan_id);
        })
        // Filter berdasarkan role user yang login
        ->when($userRole === 'prodi', function ($query) use ($userProdiId) {
            // Prodi hanya melihat mahasiswa dari prodi mereka
            $query->whereHas('user', function ($q) use ($userProdiId) {
                $q->where('id_prodi', $userProdiId);
            });
        })
        ->when($userRole === 'kemahasiswaan', function ($query) {
            // Kemahasiswaan melihat semua mahasiswa (tidak perlu filter tambahan)
            return $query;
        })
        ->when($userRole === 'superadmin', function ($query) {
            // Superadmin melihat semua mahasiswa
            return $query;
        })
        ->when($this->search, function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where(function ($subQuery) {
                    $subQuery->where('data_mahasiswa', 'like', '%' . $this->search . '%')
                             ->orWhere('name', 'like', '%' . $this->search . '%')
                             ->orWhere('nim', 'like', '%' . $this->search . '%');
                });
            });
        })
        ->paginate(10);

    // Map data setelah paginate
    $mappedData = $kelompokUsers->getCollection()->map(function ($kelompokUser) {
        $user = $kelompokUser->user;
        $dm = is_array($user->data_mahasiswa) 
            ? $user->data_mahasiswa 
            : json_decode($user->data_mahasiswa ?? '{}', true);
        
        return (object) [
            'id' => $user->id,
            'nim' => $dm['nim'] ?? ($user->nim ?? '-'),
            'nama' => $dm['nama_mahasiswa'] ?? ($user->name ?? '-'),
            'prodi' => $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'),
            'jenis_kelamin' => $dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L'),
            'kelompok' => $kelompokUser->kelompok->nama_kelompok ?? '-',
            'no_hp' => $dm['no_hp'] ?? ($user->no_hp ?? '-'),
            'foto' => $dm['foto'] ?? null,
        ];
    });

    $kelompokUsers->setCollection($mappedData);
    return $kelompokUsers;
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