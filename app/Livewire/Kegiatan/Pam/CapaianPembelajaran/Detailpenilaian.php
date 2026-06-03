<?php

namespace App\Livewire\Kegiatan\Pam\CapaianPembelajaran;

use App\Models\IndikatorPencapaian;
use App\Models\KelompokUser;
use App\Models\PenilaianCp;
use App\Models\User;
use App\Services\PAMService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detailpenilaian extends Component
{
    public $role;
    public $mahasiswaID;

    public $jenis = 'PAM';

    public $kegiatanID;
    public $periodeFilter;

    public $mahasiswa;
    public $kelompokUser;

    /**
     * MOUNT
     */
    public function mount($role, $mahasiswaID)
    {
        $this->role = $role;
        $this->mahasiswaID = $mahasiswaID;

        // ✅ mahasiswa
        $this->mahasiswa = User::findOrFail($mahasiswaID);

        // ✅ pakai service
        $this->kegiatanID    = PAMService::kegiatanId($this->jenis);
        $this->periodeFilter = PAMService::periodeId();

        // ✅ kelompok user
        $this->kelompokUser = KelompokUser::with('kelompok')
            ->where('user_id', $this->mahasiswaID)
            ->whereHas('kelompok', function ($q) {
                $q->where('periode_id', $this->periodeFilter)
                  ->where('kegiatan_id', $this->kegiatanID);
            })
            ->first();
    }

    /**
     * UPDATE NILAI
     */
    public function updateNilai($indikatorId, $value)
    {
        if (!$this->kelompokUser) return;

        PenilaianCp::updateOrCreate(
            [
                'indikator_pencapaian_id' => $indikatorId,
                'user_id' => $this->mahasiswaID,
                'kelompok_id' => $this->kelompokUser->kelompok_id, // ✅ FIX
            ],
            [
                'progres' => $value,
            ]
        );
    }

    /**
     * RENDER
     */
    public function render()
    {
        $mahasiswa = $this->mahasiswa;
        $kelompokUser = $this->kelompokUser;

        $dataMahasiswa = is_string($mahasiswa?->data_mahasiswa)
            ? json_decode($mahasiswa->data_mahasiswa, true)
            : ($mahasiswa->data_mahasiswa ?? []);

        // =========================
        // INDIKATOR PRODI
        // =========================
        $indikatorProdi = IndikatorPencapaian::where('role', 'prodi')
            ->where('periode_id', $this->periodeFilter)
            ->where('kegiatan_id', $this->kegiatanID)
            ->where('id_prodi', $mahasiswa->id_prodi ?? null)
            ->get();

        // =========================
        // INDIKATOR MITRA
        // =========================
        $indikatorMitra = IndikatorPencapaian::where('role', 'mitra')
            ->where('kelompok_id', $kelompokUser?->kelompok_id)
            ->get();

        // =========================
        // PENILAIAN
        // =========================
        $penilaian = PenilaianCp::where('user_id', $mahasiswa->id)
            ->where('kelompok_id', $kelompokUser?->kelompok_id)
            ->get()
            ->keyBy('indikator_pencapaian_id');

        // =========================
        // MAPPING
        // =========================
        $mapIndikator = function ($items) use ($penilaian) {
            return $items->map(function ($indikator) use ($penilaian) {
                $nilai = $penilaian->get($indikator->id);

                return [
                    'id' => $indikator->id,
                    'indikator' => $indikator->indikator,
                    'sudah_dinilai' => (bool) $nilai,
                    'progres' => $nilai?->progres,
                ];
            });
        };

        return view('livewire.kegiatan.pam.capaian-pembelajaran.detailpenilaian', [
            'dataMahasiswa' => $dataMahasiswa,
            'dataKelompok' => $kelompokUser,
            'indikatorProdi' => $mapIndikator($indikatorProdi),
            'indikatorMitra' => $mapIndikator($indikatorMitra),
            'jenis' => $this->jenis,
        ]);
    }
}