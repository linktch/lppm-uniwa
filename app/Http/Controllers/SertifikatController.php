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
    public function preview(Request $request, $role, $mahasiswaId)
    {
        $periode_id = KKNService::periodeId();
        $kegiatan_id = KKNService::kegiatanId('KKN');

        // ========== CEK APAKAH USER SUDAH PUNYA SERTIFIKAT ==========
        $existingSertifikat = Sertifikat::where('user_id', $mahasiswaId)
            ->where('periode_id', $periode_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->first();

        if ($existingSertifikat) {
            // Jika sudah ada, gunakan nomor sertifikat yang sudah ada
            $nomorSertifikat = $existingSertifikat->nomor_sertifikat;
            $isNewRecord = false;
        } else {
            // ========== BUAT NOMOR URUT BARU ==========
            // Cari nomor urut tertinggi untuk periode ini
            $lastSertifikat = Sertifikat::where('periode_id', $periode_id)
                ->where('kegiatan_id', $kegiatan_id)
                ->orderBy('id', 'desc')
                ->first();

            // Nomor urut dimulai dari 1
            $urutan = 1;
            if ($lastSertifikat) {
                // Ambil nomor urut dari nomor_sertifikat terakhir
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

        // Clean string function
        $cleanString = function ($str) {
            if ($str === null)
                return '-';
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

        // ========== AMBIL DATA KETUA PANITIA PELAKSANA ==========
        $ketuaPanitia = PejabatSignatur::where('jabatan', 'LIKE', '%Ketua Panitia%')->first();

        if (!$ketuaPanitia) {
            $ketuaPanitia = new \stdClass();
            $ketuaPanitia->nama = 'M. Zakaria Yahya, S.P.';
            $ketuaPanitia->jabatan = 'Ketua Panitia Pelaksana KKN';
            $ketuaPanitia->signatur_path = null;
        }

        // ========== AMBIL DATA KAPRODI ==========
        $kaprodi = null;
        if ($prodiFakultasId) {
            $idProdi = ProdiFakultas::where('id_prodi', $prodiFakultasId)->value('id');
            $kaprodi = PejabatSignatur::where('prodi_fakultas_id', $idProdi)
                ->where('jabatan', 'LIKE', '%Kaprodi%')
                ->first();
        }

        // Jika tidak ditemukan berdasarkan prodi, cari yang tanpa prodi_fakultas_id (default/universal)
        if (!$kaprodi) {
            $kaprodi = PejabatSignatur::where(function ($query) {
                $query->whereNull('prodi_fakultas_id')->orWhere('prodi_fakultas_id', 0);
            })
            ->where(function ($query) {
                $query->where('jabatan', 'LIKE', '%Kaprodi%')->orWhere('jabatan', 'LIKE', '%Kepala Program Studi%');
            })
            ->first();
        }

        // Jika masih tidak ditemukan, buat objek default
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

        // ========== SIMPAN KE DATABASE JIKA BELUM ADA ==========
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

        // Jika ingin langsung download PDF
        if ($request->has('download')) {
            $pdf = Pdf::loadView('pdf.sertifikat-kkn', $data);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('sertifikat_kkn_' . $nim . '.pdf');
        }

        return view('pdf.sertifikat-kkn', $data);
    }

    // ========== FUNGSI UNTUK GET DATA SERTIFIKAT ==========
    public function getSertifikatByUser($userId)
    {
        $sertifikat = Sertifikat::where('user_id', $userId)
            ->with(['user', 'periode', 'kegiatan'])
            ->first();
        
        if (!$sertifikat) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $sertifikat
        ]);
    }
    
    public function getAllSertifikat()
    {
        $sertifikats = Sertifikat::with(['user', 'periode', 'kegiatan'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $sertifikats
        ]);
    }
    
    public function downloadSertifikat($id)
    {
        $sertifikat = Sertifikat::findOrFail($id);
        
        $data = [
            'univ_name' => 'UNIVERSITAS WAHIDIYAH',
            'panitia' => 'PANITIA PELAKSANA KULIAH KERJA NYATA (KKN) TAHUN ' . $sertifikat->tahun,
            'lokasi' => "KABUPATEN {$sertifikat->kabupaten}",
            'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
            'nama_mahasiswa' => $sertifikat->nama_mahasiswa,
            'nim' => $sertifikat->nim,
            'prodi' => $sertifikat->prodi,
            'kelompok' => $sertifikat->kelompok,
            'kabupaten' => $sertifikat->kabupaten,
            'provinsi' => $sertifikat->provinsi,
            'tempat_tanggal' => 'Kediri, ' . $sertifikat->tanggal_terbit->format('d F Y'),
            'tahun' => date('Y', strtotime($sertifikat->tanggal_terbit)),
            'capaian_hafalan' => json_decode($sertifikat->capaian_hafalan, true),
            'total_nilai' => $sertifikat->total_nilai,
            'max_nilai' => count(json_decode($sertifikat->capaian_hafalan, true)) * 100,
            'persentase' => $sertifikat->persentase,
            'predikat' => $sertifikat->predikat,
            'ketua_panitia' => json_decode($sertifikat->data_penanda_tangan, true)['ketua_panitia'] ?? [],
            'kaprodi' => json_decode($sertifikat->data_penanda_tangan, true)['kaprodi'] ?? [],
        ];
        
        $pdf = Pdf::loadView('pdf.sertifikat-kkn', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('sertifikat_kkn_' . $sertifikat->nim . '.pdf');
    }

    private function getPredikat($persentase)
    {
        if ($persentase >= 90)
            return 'Sangat Baik (A)';
        if ($persentase >= 80)
            return 'Baik (B)';
        if ($persentase >= 70)
            return 'Cukup (C)';
        return 'Kurang (D)';
    }

    private function getRomanMonth($month)
    {
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romans[(int) $month];
    }
}