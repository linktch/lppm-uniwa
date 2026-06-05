<?php

namespace App\Livewire\Screening\Hafalan;

use App\Models\IndikatorHafalan;
use App\Models\KelompokUser;
use App\Models\PenilaianHafalan;
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class Penilaian extends Component
{
    use WithPagination;

    public $periode_id;
    public $kegiatan_id;
    public $search = '';
    public $role;
    public $jenisKegiatan; // TAMBAHKAN PROPERTY JENIS KEGIATAN
    
    // Properti untuk modal password
    public $showPasswordModal = false;
    public $password = '';
    public $selectedMahasiswaId = null;
    public $selectedMahasiswaName = '';

    protected $paginationTheme = 'tailwind';

    public function mount($role, $jenisKegiatan = 'KKN') // TAMBAHKAN PARAMETER
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        
        // Tentukan kegiatan ID berdasarkan jenis kegiatan
        $this->kegiatan_id = $this->getKegiatanId($jenisKegiatan);
        $this->periode_id = $this->getPeriodeId($jenisKegiatan);
    }
    
    // TAMBAHKAN METHOD untuk mendapatkan kegiatan ID
    private function getKegiatanId($jenisKegiatan)
    {
        switch ($jenisKegiatan) {
            case 'KKN':
                return KKNService::kegiatanId('KKN');
            case 'PKL':
                return KKNService::kegiatanId('PKL');
            case 'PMM':
                return KKNService::kegiatanId('PMM');
            default:
                return KKNService::kegiatanId('KKN');
        }
    }
    
    // TAMBAHKAN METHOD untuk mendapatkan periode ID
    private function getPeriodeId($jenisKegiatan)
    {
        switch ($jenisKegiatan) {
            case 'KKN':
                return KKNService::periodeId();
            case 'PKL':
                return KKNService::periodeIdPkl();
            default:
                return KKNService::periodeId();
        }
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
                'desa' => $kelompokUser->kelompok->desa ?? '-',
                'kecamatan' => $kelompokUser->kelompok->kecamatan ?? '-',
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

    public function openPasswordModal($mahasiswaId, $mahasiswaName)
    {
        $this->selectedMahasiswaId = $mahasiswaId;
        $this->selectedMahasiswaName = $mahasiswaName;
        $this->showPasswordModal = true;
        $this->password = '';
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->password = '';
        $this->selectedMahasiswaId = null;
        $this->selectedMahasiswaName = '';
    }

    public function generateSertifikat()
    {
        $this->validate([
            'password' => 'required|min:3'
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter'
        ]);

        $kelompokUser = KelompokUser::with(['user', 'kelompok'])
            ->where('user_id', $this->selectedMahasiswaId)
            ->where('role', 'mahasiswa')
            ->first();
        
        if (!$kelompokUser) {
            $this->addError('password', 'Data mahasiswa tidak ditemukan');
            return;
        }

        $user = $kelompokUser->user;
        $dm = is_array($user->data_mahasiswa) 
            ? $user->data_mahasiswa 
            : json_decode($user->data_mahasiswa ?? '{}', true);

        $jenisKelamin = $dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L');
        
        $indikators = IndikatorHafalan::forGender($jenisKelamin)->ordered()->get();
        $penilaianRecords = PenilaianHafalan::where('user_id', $this->selectedMahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get();

        $sudahDinilai = $penilaianRecords->count();
        $totalIndikator = $indikators->count();

        if ($sudahDinilai < $totalIndikator) {
            $this->addError('password', "Masih ada " . ($totalIndikator - $sudahDinilai) . " indikator yang belum dinilai.");
            return;
        }

        $this->closePasswordModal();
        
        // PERBAIKI URL: gunakan $this->jenisKegiatan
        $url = url("/{$this->role}/kegiatan/{$this->jenisKegiatan}/screening/hafalan/sertifikat-preview/{$kelompokUser->user_id}");
        
        $this->dispatch('openPdfPreview', $url);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.screening.hafalan.penilaian', [
            'mahasiswas' => $this->mahasiswas,
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
        ]);
    }
}