<?php

namespace App\Livewire\Kegiatan\Kkn\Screening\Hafalan;

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
    
    // Properti untuk modal password
    public $showPasswordModal = false;
    public $password = '';
    public $selectedMahasiswaId = null;
    public $selectedMahasiswaName = '';

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

    // ========== METHOD UNTUK MODAL PASSWORD ==========
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
    // Validasi password
    $this->validate([
        'password' => 'required|min:3'
    ], [
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 3 karakter'
    ]);

    // Ambil data mahasiswa untuk cek kelayakan
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

    // Cek kelayakan
    if ($sudahDinilai < $totalIndikator) {
        $this->addError('password', "Masih ada " . ($totalIndikator - $sudahDinilai) . " indikator yang belum dinilai.");
        return;
    }

    // Tutup modal
    $this->closePasswordModal();
    
    // Buat URL preview
    $url = url("/{$this->role}/kegiatan/KKN/screening/hafalan/sertifikat-preview/{$kelompokUser->user_id}");
    
    // DD URL untuk debugging
    // dd([
    //     'url' => $url,
    //     'role' => $this->role,
    //     'mahasiswa_id' => $kelompokUser->user_id,
    //     'full_url' => $url
    // ]);
    
    // Dispatch event untuk buka tab baru
    $this->dispatch('openPdfPreview', $url);
}
    private function getPredikat($persentase)
    {
        if ($persentase >= 90) return 'Sangat Baik (A)';
        if ($persentase >= 80) return 'Baik (B)';
        if ($persentase >= 70) return 'Cukup (C)';
        return 'Kurang (D)';
    }

    private function getRomanMonth($month)
    {
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 
                   7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romans[(int)$month];
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