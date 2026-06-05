<?php

namespace App\Livewire\Screening\Hafalan;

use App\Models\IndikatorHafalan;
use App\Models\KelompokUser;
use App\Models\PenilaianHafalan;
use App\Models\TimelineKegiatan;
use App\Services\KKNService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public $periode_id;
    public $kegiatan_id;
    public $role; // TAMBAHKAN PROPERTY ROLE
    public $jenisKegiatan; // TAMBAHKAN PROPERTY JENIS KEGIATAN
    public $mahasiswaId;
    public $mahasiswa;
    public $indikators = [];
    public $penilaian = [];
    public $totalNilai = 0;
    public $maxNilai = 0;
    public $timeline;

    public function mount($role, $jenisKegiatan, $mahasiswaId) // TAMBAHKAN PARAMETER
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->mahasiswaId = $mahasiswaId;
        
        // Tentukan kegiatan ID berdasarkan jenis kegiatan
        $this->kegiatan_id = $this->getKegiatanId($jenisKegiatan);
        $this->periode_id = $this->getPeriodeId($jenisKegiatan);
        
        $this->loadMahasiswa();
        $this->loadIndikators();
        $this->loadPenilaian();
        $this->loadTimeline();
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

    public function loadMahasiswa()
    {
        $kelompokUser = KelompokUser::with(['user', 'kelompok'])
            ->where('role', 'mahasiswa')
            ->whereHas('kelompok', function ($query) {
                $query
                    ->where('periode_id', $this->periode_id)
                    ->where('kegiatan_id', $this->kegiatan_id);
            })
            ->whereHas('user', function ($query) {
                $query->where('id', $this->mahasiswaId);
            })
            ->first();

        if (!$kelompokUser) {
            abort(404, 'Mahasiswa tidak ditemukan');
        }

        $user = $kelompokUser->user;
        $dm = is_array($user->data_mahasiswa)
            ? $user->data_mahasiswa
            : json_decode($user->data_mahasiswa ?? '{}', true);

        $this->mahasiswa = (object) [
            'id' => $user->id,
            'nim' => $dm['nim'] ?? ($user->nim ?? '-'),
            'nama' => $dm['nama_mahasiswa'] ?? ($user->name ?? '-'),
            'prodi' => $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'),
            'jenis_kelamin' => $dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L'),
            'kelompok' => $kelompokUser->kelompok->nama_kelompok ?? '-',
            'no_hp' => $dm['no_hp'] ?? ($user->no_hp ?? '-'),
            'foto' => $dm['foto'] ?? null,
        ];
    }

    public function loadIndikators()
    {
        $this->indikators = IndikatorHafalan::orderBy('urutan', 'asc')->get();

        $wajibCount = 0;
        foreach ($this->indikators as $indikator) {
            $isWajib = ($this->mahasiswa->jenis_kelamin == 'L' || $this->mahasiswa->jenis_kelamin == 'Laki-laki')
                ? $indikator->laki_laki
                : $indikator->perempuan;

            if ($isWajib) {
                $wajibCount++;
            }
        }
        $this->maxNilai = $wajibCount * 100;
    }

    public function loadPenilaian()
    {
        $existingPenilaian = PenilaianHafalan::where('user_id', $this->mahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get()
            ->keyBy('indikator_id');

        $total = 0;
        foreach ($this->indikators as $indikator) {
            $isWajib = ($this->mahasiswa->jenis_kelamin == 'L' || $this->mahasiswa->jenis_kelamin == 'Laki-laki')
                ? $indikator->laki_laki
                : $indikator->perempuan;

            if ($existingPenilaian->has($indikator->id)) {
                $nilai = $existingPenilaian[$indikator->id]->nilai;
                $this->penilaian[$indikator->id] = $nilai;

                if ($nilai === 'sangat_lancar') {
                    $total += 100;
                } elseif ($nilai === 'cukup_lancar') {
                    $total += 70;
                }
            } else {
                $this->penilaian[$indikator->id] = $isWajib ? null : 'tidak_wajib';
            }
        }
        $this->totalNilai = $total;
    }

    public function loadTimeline()
    {
        $this->timeline = TimelineKegiatan::where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'pembekalan')
            ->first();
    }

    public function savePenilaian()
    {
        $this->validate([
            'penilaian.*' => 'nullable|in:sangat_lancar,cukup_lancar,tidak_wajib',
        ]);

        try {
            foreach ($this->indikators as $indikator) {
                $nilai = $this->penilaian[$indikator->id] ?? null;

                if ($nilai === 'tidak_wajib' || $nilai === null) {
                    continue;
                }

                PenilaianHafalan::updateOrCreate(
                    [
                        'user_id' => $this->mahasiswaId,
                        'indikator_id' => $indikator->id,
                        'periode_id' => $this->periode_id,
                        'kegiatan_id' => $this->kegiatan_id,
                    ],
                    [
                        'nilai' => $nilai,
                        'penilai_id' => auth()->id(),
                        'tanggal_penilaian' => now(),
                    ]
                );
            }

            $this->loadPenilaian();

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Penilaian hafalan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function exportToPDF()
    {
        $penilaianData = PenilaianHafalan::where('user_id', $this->mahasiswaId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get()
            ->keyBy('indikator_id');

        $data = [
            'mahasiswa' => $this->mahasiswa,
            'indikators' => $this->indikators,
            'penilaian' => $penilaianData,
            'totalNilai' => $this->totalNilai,
            'maxNilai' => $this->maxNilai,
            'timeline' => $this->timeline,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'penilai' => auth()->user()->first_name,
        ];

        $pdf = Pdf::loadView('pdf.penilaian-hafalan', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'times',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $filename = 'Penilaian_Hafalan_' . $this->mahasiswa->nim . '_' . date('Y-m-d_His') . '.pdf';

        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.screening.hafalan.detail', [
            'mahasiswa' => $this->mahasiswa,
            'indikators' => $this->indikators,
            'penilaian' => $this->penilaian,
            'totalNilai' => $this->totalNilai,
            'maxNilai' => $this->maxNilai,
            'timeline' => $this->timeline,
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
        ]);
    }
}