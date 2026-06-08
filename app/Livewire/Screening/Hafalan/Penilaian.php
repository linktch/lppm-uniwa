<?php

namespace App\Livewire\Screening\Hafalan;

use App\Models\IndikatorHafalan;
use App\Models\KelompokUser;
use App\Models\PenilaianHafalan;
use App\Models\Sertifikat;
use App\Models\PejabatSignatur;
use App\Models\ProdiFakultas;
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class Penilaian extends Component
{
    use WithPagination;

    public $periode_id;
    public $kegiatan_id;
    public $search = '';
    public $backupSearch = '';
    public $role;
    public $jenisKegiatan;
    
    // Properti untuk modal password
    public $showPasswordModal = false;
    public $password = '';
    public $selectedMahasiswaId = null;
    public $selectedMahasiswaName = '';

    protected $paginationTheme = 'tailwind';

    public function mount($role, $jenisKegiatan = 'KKN')
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        
        $this->kegiatan_id = $this->getKegiatanId($jenisKegiatan);
        $this->periode_id = $this->getPeriodeId($jenisKegiatan);
    }
    
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
                'id_prodi' => $dm['id_prodi'] ?? null,
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
        $this->backupSearch = $this->search;
        $this->selectedMahasiswaId = $mahasiswaId;
        $this->selectedMahasiswaName = $mahasiswaName;
        $this->showPasswordModal = true;
        $this->password = '';
    }

    public function closePasswordModal()
    {
        $this->search = $this->backupSearch;
        $this->showPasswordModal = false;
        $this->password = '';
        $this->selectedMahasiswaId = null;
        $this->selectedMahasiswaName = null;
    }

    /**
     * Generate Sertifikat untuk mahasiswa
     */
    public function generateSertifikat()
    {
        // Cek apakah role adalah prodi
        if (auth()->user()->role != 'prodi') {
            $this->addError('password', 'Hanya role Prodi yang dapat generate sertifikat');
            return;
        }

        $this->validate([
            'password' => 'required|min:3'
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter'
        ]);

        // Cek apakah sudah ada sertifikat sebelumnya
        $existingSertifikat = Sertifikat::where('user_id', $this->selectedMahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        if ($existingSertifikat) {
            $this->addError('password', 'Sertifikat sudah pernah digenerate untuk mahasiswa ini');
            return;
        }

        // Ambil data mahasiswa
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
        
        // Ambil semua indikator dan penilaian
        $indikators = IndikatorHafalan::forGender($jenisKelamin)->ordered()->get();
        $penilaianRecords = PenilaianHafalan::where('user_id', $this->selectedMahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get()
            ->keyBy('indikator_id');

        // Hitung capaian dan nilai
        $capaianHafalan = [];
        $totalNilai = 0;
        $totalIndikator = $indikators->count();

        foreach ($indikators as $indikator) {
            $nilaiRecord = $penilaianRecords->get($indikator->id);
            $nilai = $nilaiRecord ? $nilaiRecord->nilai : null;
            $status = $nilai == 'sangat_lancar' ? 'Sangat Lancar' : ($nilai == 'cukup_lancar' ? 'Cukup Lancar' : 'Belum Dinilai');

            $capaianHafalan[] = [
                'materi' => $indikator->nama_indikator,
                'status' => $status,
            ];

            if ($nilai == 'sangat_lancar') {
                $totalNilai += 100;
            } elseif ($nilai == 'cukup_lancar') {
                $totalNilai += 70;
            }
        }

        $sudahDinilai = $penilaianRecords->count();
        $totalIndikator = $indikators->count();

        // Validasi semua indikator sudah dinilai
        if ($sudahDinilai < $totalIndikator) {
            $this->addError('password', "Masih ada " . ($totalIndikator - $sudahDinilai) . " indikator yang belum dinilai.");
            return;
        }

        $maxNilai = $totalIndikator * 100;
        $persentase = $maxNilai > 0 ? round(($totalNilai / $maxNilai) * 100) : 0;
        
        // Tentukan predikat
        $predikat = $this->getPredikat($persentase);

        // Generate nomor sertifikat
        $nomorSertifikat = $this->generateNomorSertifikat();

        // Ambil data kaprodi
        $prodiFakultasId = $dm['id_prodi'] ?? null;
        $kaprodi = $this->getKaprodi($prodiFakultasId);

        // Siapkan data untuk disimpan
        $dataSertifikat = [
            'user_id' => $this->selectedMahasiswaId,
            'periode_id' => $this->periode_id,
            'kegiatan_id' => $this->kegiatan_id,
            'nomor_sertifikat' => $nomorSertifikat,
            'nama_mahasiswa' => strtoupper($dm['nama_mahasiswa'] ?? ($user->name ?? '-')),
            'nim' => $dm['nim'] ?? ($user->nim ?? '-'),
            'prodi' => $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'),
            'kelompok' => $kelompokUser->kelompok->nama_kelompok ?? '-',
            'kabupaten' => $kelompokUser->kelompok->kabupaten ?? 'Madiun',
            'provinsi' => $kelompokUser->kelompok->provinsi ?? 'Jawa Timur',
            'predikat' => $predikat,
            'total_nilai' => $totalNilai,
            'persentase' => $persentase,
            'capaian_hafalan' => json_encode($capaianHafalan),
            'data_penanda_tangan' => json_encode([
                'kaprodi' => $kaprodi
            ]),
            'tanggal_terbit' => now(),
        ];

        // Simpan ke database
        try {
            Sertifikat::create($dataSertifikat);
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Sertifikat berhasil digenerate untuk ' . $this->selectedMahasiswaName
            ]);
            
            $this->closePasswordModal();
        } catch (\Exception $e) {
            $this->addError('password', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate nomor sertifikat otomatis
     */
    private function generateNomorSertifikat()
    {
        $lastSertifikat = Sertifikat::where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->orderBy('id', 'desc')
            ->first();

        $urutan = 1;
        if ($lastSertifikat) {
            $lastNomor = explode('/', $lastSertifikat->nomor_sertifikat)[0];
            $urutan = (int)$lastNomor + 1;
        }

        $bulanRomawi = $this->getRomanMonth(date('m'));
        $tahun = date('Y');
        
        return "{$urutan}/Uniwa_{$this->jenisKegiatan}/Sert/Hafalan/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Get predikat berdasarkan persentase
     */
    private function getPredikat($persentase)
    {
        if ($persentase >= 90) return 'Sangat Baik (A)';
        if ($persentase >= 80) return 'Baik (B)';
        if ($persentase >= 70) return 'Cukup (C)';
        return 'Kurang (D)';
    }

    /**
     * Get roman month
     */
    private function getRomanMonth($month)
    {
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romans[(int) $month];
    }

    /**
     * Get data Kaprodi berdasarkan prodi
     */
    private function getKaprodi($prodiFakultasId)
    {
        $kaprodi = null;
        
        if ($prodiFakultasId) {
            $idProdi = ProdiFakultas::where('id_prodi', $prodiFakultasId)->value('id');
            $kaprodi = PejabatSignatur::where('prodi_fakultas_id', $idProdi)
                ->where('jabatan', 'LIKE', '%Kaprodi%')
                ->first();
        }

        if (!$kaprodi) {
            $kaprodi = PejabatSignatur::where(function ($query) {
                $query->whereNull('prodi_fakultas_id')->orWhere('prodi_fakultas_id', 0);
            })
            ->where(function ($query) {
                $query->where('jabatan', 'LIKE', '%Kaprodi%')->orWhere('jabatan', 'LIKE', '%Kepala Program Studi%');
            })
            ->first();
        }

        if (!$kaprodi) {
            $kaprodi = (object) [
                'nama' => 'Dr. Hj. Fatimah Azzahra, M.Pd.',
                'jabatan' => 'Kepala Program Studi',
                'signatur_path' => null,
            ];
        }

        return [
            'nama' => $kaprodi->nama ?? 'Dr. Hj. Fatimah Azzahra, M.Pd.',
            'jabatan' => 'Kepala Program Studi',
            'signatur_path' => $kaprodi->signatur_path ?? null,
        ];
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