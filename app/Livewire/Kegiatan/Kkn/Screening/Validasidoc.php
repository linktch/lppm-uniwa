<?php

namespace App\Livewire\Kegiatan\Kkn\Screening;

use App\Models\Kegiatan;
use App\Models\KelompokUser;
use App\Models\Periode;
use App\Models\ScreeningFile;
use App\Models\TimelineKegiatan;
use App\Models\User;
use App\Services\KKNService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Validasidoc extends Component
{
    public $selectedFile;
    public $selectedType;
    public $keterangan = [];
    public $periode_id;
    public $kegiatan_id;
    public $hasilCekKRS = null;
    public $files;  // Property untuk menyimpan data files
    public $modalActive = false;

    public function mount()
    {
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId('KKN');
    }

    public function render()
    {
        $this->files = ScreeningFile::with('user')
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->latest()
            ->get()
            ->map(function ($file) {
                $user = $file->user;
                $dm = is_array($user->data_mahasiswa)
                    ? $user->data_mahasiswa
                    : json_decode($user->data_mahasiswa ?? '{}', true);

                $file->nim = $dm['nim'] ?? ($user->nim ?? '-');
                $file->nama = $dm['nama_mahasiswa'] ?? ($user->name ?? '-');
                $file->prodi = $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-');
                $file->status_surat = $file->status_surat ?? 'pending';
                $file->status_ktp = $file->status_ktp ?? 'pending';
                $file->status_spp = $file->status_spp ?? 'pending';
                $file->id_registrasi_mahasiswa = $user->id_registrasi_mahasiswa ?? null;

                return $file;
            })
            ->sortBy(function ($file) {
                // Define priority order: pending (1), ditolak (2), valid (3)
                $priority = [
                    'pending' => 1,
                    'ditolak' => 2,
                    'valid' => 3
                ];

                // Get the highest priority status from all three status fields
                $statuses = [$file->status_surat, $file->status_ktp, $file->status_spp];
                $minPriority = min(array_map(function ($status) use ($priority) {
                    return $priority[$status] ?? 4;
                }, $statuses));

                return $minPriority;
            })
            ->values();  // Re-index the collection after sorting

        return view('livewire.kegiatan.kkn.screening.validasidoc', [
            'files' => $this->files
        ]);
    }

    public function lihatFile($id, $type)
    {
        // dd($id, $type);
        $this->hasilCekKRS = null;
        $file = ScreeningFile::with('user')->find($id);
        if (!$file)
            return;

        $user = $file->user;
        $dm = json_decode($user->data_mahasiswa ?? '{}', true);

        $file->nim = $dm['nim'] ?? ($user->nim ?? '-');
        $file->nama = $dm['nama_mahasiswa'] ?? ($user->name ?? '-');
        $file->prodi = $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-');  // TAMBAHKAN INI
        $file->id_registrasi_mahasiswa = $user->id_registrasi_mahasiswa ?? null;

        $this->selectedFile = $file;
        $this->selectedType = $type;

        $this->modalActive = true;
    }

    public function closeModal()
    {
        $this->modalActive = false;
        $this->selectedFile = null;
        $this->selectedType = null;
        $this->hasilCekKRS = null;
    }

    public function valid($id, $type)
    {
        $file = ScreeningFile::findOrFail($id);
        $file->update([
            "status_$type" => 'valid',
            "keterangan_$type" => null
        ]);

        
        $this->closeModal();
        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Berhasil',
            'text' => 'Dokumen berhasil divalidasi'
        ]);
    }

    public function tolak($id, $type)
    {
        $this->validate([
            "keterangan.$id.$type" => 'required|min:5'
        ], [
            "keterangan.$id.$type.required" => 'Keterangan wajib diisi',
            "keterangan.$id.$type.min" => 'Keterangan minimal 5 karakter'
        ]);

        $file = ScreeningFile::findOrFail($id);
        $file->update([
            "status_$type" => 'ditolak',
            "keterangan_$type" => $this->keterangan[$id][$type]
        ]);

        $this->closeModal();
        $this->dispatch('swal', [
            'type' => 'error',
            'title' => 'Ditolak',
            'text' => 'Dokumen telah ditolak'
        ]);
    }

    public function cekValidasiKRSViaAPI($id_reg_mahasiswa)
    {
        $this->hasilCekKRS = null;

        try {
            $periode_id = session('periode_id') ?? 20252;

            $apiUrl = env('VALIDASI_KRS_URL');
            $apiKey = env('FEEDER_API_KEY');

            Log::info('Memanggil API Validasi KRS', [
                'url' => $apiUrl,
                'id_reg_mahasiswa' => $id_reg_mahasiswa,
                'periode_id' => $periode_id
            ]);

            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($apiUrl, [
                'id_reg_mahasiswa' => $id_reg_mahasiswa,
                'periode_id' => $periode_id
            ]);

            if (!$response->successful()) {
                Log::error('API Validasi KRS gagal', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                $this->hasilCekKRS = [
                    'success' => false,
                    'message' => 'Gagal menghubungi API (HTTP ' . $response->status() . ')'
                ];

                return;
            }

            $result = $response->json();

            if (
                isset($result['success']) &&
                $result['success'] &&
                !empty($result['data'])
            ) {
                $this->hasilCekKRS = [
                    'success' => true,
                    'message' => '✓ Mahasiswa sudah melakukan validasi pembayaran KRS',
                    'data' => $result['data']
                ];
            } else {
                $this->hasilCekKRS = [
                    'success' => false,
                    'message' => $result['message']
                        ?? '✗ Mahasiswa belum melakukan validasi pembayaran KRS'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error cekValidasiKRS: ' . $e->getMessage());

            $this->hasilCekKRS = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function exportToPDF()
    {
        // Get data with additional fields and sorting
        $files = ScreeningFile::with('user')
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->latest()
            ->get()
            ->map(function ($file) {
                $user = $file->user;
                $dm = is_array($user->data_mahasiswa)
                    ? $user->data_mahasiswa
                    : json_decode($user->data_mahasiswa ?? '{}', true);

                $file->nim = $dm['nim'] ?? ($user->nim ?? '-');
                $file->nama = $dm['nama_mahasiswa'] ?? ($user->name ?? '-');
                $file->prodi = $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-');
                $file->fakultas = $dm['nama_fakultas'] ?? ($dm['fakultas'] ?? '-');
                $file->status_surat = $file->status_surat ?? 'pending';
                $file->status_ktp = $file->status_ktp ?? 'pending';
                $file->status_spp = $file->status_spp ?? 'pending';

                return $file;
            })
            ->sortBy(function ($file) {
                // Priority order: pending (1), ditolak (2), valid (3)
                $priority = [
                    'pending' => 1,
                    'ditolak' => 2,
                    'valid' => 3
                ];

                // Get minimum priority from all three status fields
                $statuses = [$file->status_surat, $file->status_ktp, $file->status_spp];
                $minPriority = min(array_map(function ($status) use ($priority) {
                    return $priority[$status] ?? 4;
                }, $statuses));

                return $minPriority;
            })
            ->values();  // Re-index after sorting
        $mahasiswaTerplotting = KelompokUser::with([
            'kelompok',
            'kelompok.periode',
            'kelompok.kegiatan'
        ])
            ->where('role', 'mahasiswa')
            ->whereHas('kelompok', function ($q) {
                $q
                    ->where('periode_id', $this->periode_id)
                    ->where('kegiatan_id', $this->kegiatan_id);
            })
            ->count();
        // dd($mahasiswaTerplotting);
        // Get related data
        $periode = Periode::find($this->periode_id);
        $kegiatan = Kegiatan::find($this->kegiatan_id);
        $timeline = TimelineKegiatan::where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        $pdf = Pdf::loadView('pdf.kkn-registration', [
            'mahasiswaTerplotting' => $mahasiswaTerplotting,
            'files' => $files,
            'periode_id' => $this->periode_id,
            'periode_nama' => $periode->nama_periode ?? 'Periode ' . $this->periode_id,
            'kegiatan_id' => $this->kegiatan_id,
            'kegiatan_nama' => $kegiatan->nama ?? 'Kuliah Kerja Nyata (KKN)',
            'tahun_akademik' => $periode->tahun_akademik ?? date('Y'),
            'lokasi_kkn' => $kegiatan->lokasi ?? 'Seluruh Wilayah Binaan',
            'tanggal_mulai' => $timeline->tanggal_mulai ?? null,
            'tanggal_selesai' => $timeline->tanggal_selesai ?? null,
            'nomor_ba' => '001/LP2M/KKN/' . date('Y'),
            'date' => now()->format('d/m/Y H:i:s'),
            'need_stamp' => true
        ]);

        $pdf->setPaper('F4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'times',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return Response::streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            'Berita_Acara_Verifikasi_KKN_' . date('Y-m-d_His') . '.pdf'
        );
    }

    public function exportKTPValid()
    {
        // Get data with status_ktp = valid
        $files = ScreeningFile::with('user')
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('status_ktp', 'valid')
            ->latest()
            ->get()
            ->map(function ($file) {
                $user = $file->user;
                $dm = is_array($user->data_mahasiswa)
                    ? $user->data_mahasiswa
                    : json_decode($user->data_mahasiswa ?? '{}', true);

                $file->nim = $dm['nim'] ?? ($user->nim ?? '-');
                $file->nama = $dm['nama_mahasiswa'] ?? ($user->name ?? '-');
                $file->prodi = $dm['nama_program_studi'] ?? ($dm['prodi'] ?? '-');
                $file->status_ktp = $file->status_ktp ?? 'pending';

                // Convert KTP image to base64
                $file->foto_ktp_base64 = $this->getImageBase64($file->file_ktp);

                return $file;
            });

        // Get periode and kegiatan names
        $periode = Periode::find($this->periode_id);
        $kegiatan = Kegiatan::find($this->kegiatan_id);

        // Load view for PDF
        $pdf = Pdf::loadView('pdf.ktp-valid-export', [
            'files' => $files,
            'periode_nama' => $periode->nama ?? 'Periode ' . $this->periode_id,
            'kegiatan_nama' => $kegiatan->nama ?? 'Kuliah Kerja Nyata Reguler',
            'date' => now()->format('d/m/Y H:i:s'),
            'total_valid' => $files->count()
        ]);

        // Set paper F4 and 2 columns layout
        $pdf->setPaper('F4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'times',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        // Download the PDF
        return Response::streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            'KTP_Valid_KKN_' . date('Y-m-d_His') . '.pdf'
        );
    }

    private function getImageBase64($filePath)
    {
        if (empty($filePath)) {
            return null;
        }

        // Coba cari file di berbagai lokasi
        $pathsToTry = [
            storage_path('app/public/' . $filePath),
            public_path('storage/' . $filePath),
            public_path($filePath),
            storage_path('app/' . $filePath),
        ];

        foreach ($pathsToTry as $path) {
            if (file_exists($path)) {
                try {
                    // Load image based on file type
                    $imageInfo = getimagesize($path);
                    $mimeType = $imageInfo['mime'];

                    switch ($mimeType) {
                        case 'image/jpeg':
                            $image = imagecreatefromjpeg($path);
                            break;
                        case 'image/png':
                            $image = imagecreatefrompng($path);
                            break;
                        case 'image/gif':
                            $image = imagecreatefromgif($path);
                            break;
                        case 'image/webp':
                            $image = imagecreatefromwebp($path);
                            break;
                        default:
                            // If not supported, return original without rotation
                            $imageData = file_get_contents($path);
                            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                    }

                    // Rotate if needed (make landscape)
                    $image = $this->fixImageOrientation($image, $path);
                    $image = $this->ensureLandscape($image);

                    // Convert to JPEG and save to temporary stream
                    ob_start();
                    imagejpeg($image, null, 90);
                    $imageData = ob_get_clean();
                    imagedestroy($image);

                    return 'data:image/jpeg;base64,' . base64_encode($imageData);
                } catch (\Exception $e) {
                    // Fallback: return original file
                    $imageData = file_get_contents($path);
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $path);
                    finfo_close($finfo);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }
        }

        return null;
    }

    // Helper function to fix image orientation based on EXIF data
    private function fixImageOrientation($image, $filePath)
    {
        // Only for JPEG images that have EXIF data
        $exif = @exif_read_data($filePath);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];

            switch ($orientation) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }
        }

        return $image;
    }

    // Helper function to ensure image is landscape (width > height)
    private function ensureLandscape($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // If image is portrait (height > width), rotate to landscape
        if ($height > $width) {
            $image = imagerotate($image, 90, 0);
        }

        return $image;
    }
}
