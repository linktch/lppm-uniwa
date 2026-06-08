<?php

namespace App\Http\Controllers;

use App\Models\IndikatorHafalan;
use App\Models\KelompokUser;
use App\Models\PejabatSignatur;
use App\Models\PenilaianHafalan;
use App\Models\ProdiFakultas;
use App\Models\Sertifikat;
use App\Services\KKNService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    // PERBAIKI: tambah parameter $jenisKegiatan
    public function preview(Request $request, $role, $jenisKegiatan, $mahasiswaId)
    {
        $periode_id = KKNService::periodeId();
        $kegiatan_id = KKNService::kegiatanId($jenisKegiatan ?? 'KKN'); // Gunakan $jenisKegiatan

        // ========== CEK APAKAH USER SUDAH PUNYA SERTIFIKAT ==========
        $existingSertifikat = Sertifikat::where('user_id', $mahasiswaId)
            ->where('periode_id', $periode_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->first();

        if ($existingSertifikat) {
            $nomorSertifikat = $existingSertifikat->nomor_sertifikat;
            $isNewRecord = false;
        } else {
            $lastSertifikat = Sertifikat::where('periode_id', $periode_id)
                ->where('kegiatan_id', $kegiatan_id)
                ->orderBy('id', 'desc')
                ->first();

            $urutan = 1;
            if ($lastSertifikat) {
                $lastNomor = explode('/', $lastSertifikat->nomor_sertifikat)[0];
                $urutan = (int)$lastNomor + 1;
            }

            $bulanRomawi = $this->getRomanMonth(date('m'));
            $tahun = date('Y');
            $nomorSertifikat = "{$urutan}/Uniwa_KKN/Sert/Hafalan/{$bulanRomawi}/{$tahun}";
            $isNewRecord = true;
        }

        $kelompokUser = KelompokUser::with(['user', 'kelompok'])
            ->where('user_id', $mahasiswaId)
            ->where('role', 'mahasiswa')
            ->first();

        if (!$kelompokUser) {
            abort(404, 'Data mahasiswa tidak ditemukan');
        }

        $user = $kelompokUser->user;
        $dm = is_array($user->data_mahasiswa)
            ? $user->data_mahasiswa
            : json_decode($user->data_mahasiswa ?? '{}', true);

        $cleanString = function ($str) {
            if ($str === null) return '-';
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            $str = htmlspecialchars_decode($str, ENT_QUOTES);
            return trim($str);
        };

        $namaMahasiswa = $cleanString($dm['nama_mahasiswa'] ?? ($user->name ?? '-'));
        $nim = $cleanString($dm['nim'] ?? ($user->nim ?? '-'));
        $prodi = $cleanString($dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-'));
        $prodiFakultasId = $dm['id_prodi'] ?? null;
        $jenisKelamin = $cleanString($dm['jenis_kelamin'] ?? ($dm['gender'] ?? 'L'));
        $kelompok = $cleanString($kelompokUser->kelompok->nama_kelompok ?? '-');
        $kabupaten = 'Madiun';
        $provinsi = 'Jawa Timur';
        $tahun = date('Y');

        $indikators = IndikatorHafalan::forGender($jenisKelamin)->ordered()->get();
        $penilaianRecords = PenilaianHafalan::where('user_id', $mahasiswaId)
            ->where('periode_id', $periode_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->get()
            ->keyBy('indikator_id');

        $capaianHafalan = [];
        $totalNilai = 0;
        $totalIndikator = $indikators->count();

        foreach ($indikators as $indikator) {
            $nilaiRecord = $penilaianRecords->get($indikator->id);
            $nilai = $nilaiRecord ? $nilaiRecord->nilai : null;
            $status = $nilai == 'sangat_lancar' ? 'Sangat Lancar' : ($nilai == 'cukup_lancar' ? 'Cukup Lancar' : 'Belum Dinilai');

            $capaianHafalan[] = [
                'materi' => $cleanString($indikator->nama_indikator),
                'status' => $status,
            ];

            if ($nilai == 'sangat_lancar') {
                $totalNilai += 100;
            } elseif ($nilai == 'cukup_lancar') {
                $totalNilai += 70;
            }
        }

        $maxNilai = $totalIndikator * 100;
        $persentase = $maxNilai > 0 ? round(($totalNilai / $maxNilai) * 100) : 0;
        $sudahDinilai = $penilaianRecords->count();

        if ($sudahDinilai < $totalIndikator) {
            abort(400, 'Masih ada ' . ($totalIndikator - $sudahDinilai) . ' indikator yang belum dinilai.');
        }

        $predikat = $this->getPredikat($persentase);

        $ketuaPanitia = PejabatSignatur::where('jabatan', 'LIKE', '%Ketua Panitia%')->first();
        if (!$ketuaPanitia) {
            $ketuaPanitia = new \stdClass();
            $ketuaPanitia->nama = 'M. Zakaria Yahya, S.P.';
            $ketuaPanitia->jabatan = 'Ketua Panitia Pelaksana KKN';
            $ketuaPanitia->signatur_path = null;
        }

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
            $kaprodi = new \stdClass();
            $kaprodi->nama = 'Dr. Hj. Fatimah Azzahra, M.Pd.';
            $kaprodi->jabatan = 'Kepala Program Studi';
            $kaprodi->signatur_path = null;
        }

        $data = [
            'univ_name' => 'UNIVERSITAS WAHIDIYAH',
            'panitia' => 'PANITIA PELAKSANA KULIAH KERJA NYATA (KKN) TAHUN ' . $tahun,
            'lokasi' => "KABUPATEN {$kabupaten}",
            'nomor_sertifikat' => $nomorSertifikat,
            'nama_mahasiswa' => strtoupper($namaMahasiswa),
            'nim' => $nim,
            'prodi' => $prodi,
            'kelompok' => $kelompok,
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
            'tempat_tanggal' => 'Kediri, ' . now()->format('d F Y'),
            'tahun' => $tahun,
            'capaian_hafalan' => $capaianHafalan,
            'total_nilai' => $totalNilai,
            'max_nilai' => $maxNilai,
            'persentase' => $persentase,
            'predikat' => $predikat,
            'ketua_panitia' => [
                'nama' => $ketuaPanitia->nama ?? 'M. Zakaria Yahya, S.P.',
                'jabatan' => 'Ketua Panitia Pelaksana KKN',
                'signatur_path' => $ketuaPanitia->signatur_path ?? null,
            ],
            'kaprodi' => [
                'nama' => $kaprodi->nama ?? 'Dr. Hj. Fatimah Azzahra, M.Pd.',
                'jabatan' => 'Kepala Program Studi',
                'signatur_path' => $kaprodi->signatur_path ?? null,
            ]
        ];

        if ($isNewRecord) {
            Sertifikat::create([
                'user_id' => $mahasiswaId,
                'periode_id' => $periode_id,
                'kegiatan_id' => $kegiatan_id,
                'nomor_sertifikat' => $nomorSertifikat,
                'nama_mahasiswa' => strtoupper($namaMahasiswa),
                'nim' => $nim,
                'prodi' => $prodi,
                'kelompok' => $kelompok,
                'kabupaten' => $kabupaten,
                'provinsi' => $provinsi,
                'predikat' => $predikat,
                'total_nilai' => $totalNilai,
                'persentase' => $persentase,
                'capaian_hafalan' => json_encode($capaianHafalan),
                'data_penanda_tangan' => json_encode([
                    'ketua_panitia' => $data['ketua_panitia'],
                    'kaprodi' => $data['kaprodi']
                ]),
                'tanggal_terbit' => now(),
            ]);
        }

        if ($request->has('download')) {
            $pdf = Pdf::loadView('pdf.sertifikat-kkn', $data);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('sertifikat_kkn_' . $nim . '.pdf');
        }

        return view('pdf.sertifikat-kkn', $data);
    }

    // Method download
    public function download($role, $jenisKegiatan, $mahasiswaId)
    {
        $request = new \Illuminate\Http\Request();
        $request->merge(['download' => true]);
        return $this->preview($request, $role, $jenisKegiatan, $mahasiswaId);
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
        return $romans[(int) $month];
    }
}