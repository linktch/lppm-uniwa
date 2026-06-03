<?php

namespace App\Livewire\Kegiatan\Kkn\Kknreg;

use App\Models\ScreeningAnswer;
use App\Models\ScreeningFile;
use App\Models\ScreeningQuestion;
use App\Models\TimelineKegiatan;
use App\Services\KKNService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    // =========================
    // SCREENING
    // =========================
    public $pertanyaan = [];
    public $jawaban = [];
    public $keterangan = [];
    // =========================
    // USER DATA
    // =========================
    public $user_id;
    public $periode_id;
    public $kegiatan_id;
    // =========================
    // STATUS FORM
    // =========================
    public $isSubmitted = false;
    public $sudahCetak = false;
    // =========================
    // FILE UPLOAD FIRST TIME
    // =========================
    public $surat_pernyataan;
    public $foto_ktp;
    public $bukti_spp;
    public $surat_pernyataan_path;
    public $foto_ktp_path;
    public $bukti_spp_path;
    // =========================
    // STATUS DOKUMEN
    // =========================
    public $status_surat = 'pending';
    public $status_ktp = 'pending';
    public $status_spp = 'pending';
    public $keterangan_surat;
    public $keterangan_ktp;
    public $keterangan_spp;
    // =========================
    // UPLOAD ULANG (FIXED NAME)
    // =========================
    public $isUploadFormOpen = false;
    public $uploadType = null;
    public $uploadFile = null;
    // =========================
    // TIMELINE PENDAFTARAN
    // =========================
    public $isPendaftaranActive = false;
    public $timelinePendaftaran = null;
    public $tanggalPendaftaranMulai = null;
    public $tanggalPendaftaranSelesai = null;
    public $sisaHariPendaftaran = 0;  // <-- TAMBAHKAN INI

    // =========================
    // MOUNT
    // =========================
    public function mount()
    {
        $this->user_id = Auth::id();
        $this->periode_id = KKNService::periodeId();
        $this->kegiatan_id = KKNService::kegiatanId('KKN');

        $this->pertanyaan = ScreeningQuestion::orderBy('created_at', 'asc')->get();

        $this->loadExistingAnswers();
        $this->loadScreeningFiles();
        $this->checkPendaftaranTimeline();  // Cek timeline pendaftaran
    }

    // =========================
    // CEK TIMELINE PENDAFTARAN
    // =========================
    private function checkPendaftaranTimeline()
    {
        // Cari timeline dengan jenis "Pendaftaran" untuk periode dan kegiatan ini
        $timeline = TimelineKegiatan::where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('jenis', 'Pendaftaran')
            ->where('status', 'AKTIF')
            ->first();

        if ($timeline) {
            $today = now()->startOfDay();
            $tanggalMulai = $timeline->tanggal_mulai->startOfDay();
            $tanggalSelesai = $timeline->tanggal_selesai->startOfDay();

            // Cek apakah hari ini dalam rentang pendaftaran
            if ($today >= $tanggalMulai && $today <= $tanggalSelesai) {
                $this->isPendaftaranActive = true;
                $this->timelinePendaftaran = $timeline;
                $this->tanggalPendaftaranMulai = $timeline->tanggal_mulai_formatted;
                $this->tanggalPendaftaranSelesai = $timeline->tanggal_selesai_formatted;
            } else {
                $this->isPendaftaranActive = false;
                $this->timelinePendaftaran = null;
            }
        } else {
            $this->isPendaftaranActive = false;
            $this->timelinePendaftaran = null;
        }
    }

    /**
     * Cek apakah pendaftaran masih buka
     */
    public function getIsPendaftaranOpenAttribute()
    {
        return $this->isPendaftaranActive;
    }

    /**
     * Get sisa hari pendaftaran
     */
    public function getSisaHariPendaftaranAttribute()
    {
        if (!$this->timelinePendaftaran || !$this->isPendaftaranActive) {
            return 0;
        }

        $today = now()->startOfDay();
        $tanggalSelesai = $this->timelinePendaftaran->tanggal_selesai->startOfDay();

        if ($today > $tanggalSelesai) {
            return 0;
        }

        return $today->diffInDays($tanggalSelesai) + 1;
    }

    // =========================
    // LOAD ANSWER
    // =========================
    private function loadExistingAnswers()
    {
        $existing = ScreeningAnswer::where('user_id', $this->user_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->get()
            ->keyBy('question_id');

        foreach ($this->pertanyaan as $item) {
            if (isset($existing[$item->id])) {
                $res = $existing[$item->id];
                $this->jawaban[$item->id] = $res->jawaban ? 'ya' : 'tidak';
                $this->keterangan[$item->id] = $res->keterangan;
            } else {
                $this->jawaban[$item->id] = null;
                $this->keterangan[$item->id] = null;
            }
        }

        $this->isSubmitted = $existing->count() > 0;
    }

    // =========================
    // LOAD FILE
    // =========================
    private function loadScreeningFiles()
    {
        $file = ScreeningFile::where('user_id', $this->user_id)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();

        if ($file) {
            $this->surat_pernyataan_path = $file->file_surat;
            $this->foto_ktp_path = $file->file_ktp;
            $this->bukti_spp_path = $file->file_spp;

            $this->status_surat = $file->status_surat ?? 'pending';
            $this->status_ktp = $file->status_ktp ?? 'pending';
            $this->status_spp = $file->status_spp ?? 'pending';

            $this->keterangan_surat = $file->keterangan_surat;
            $this->keterangan_ktp = $file->keterangan_ktp;
            $this->keterangan_spp = $file->keterangan_spp;
        }
    }

    // =========================
    // OPEN MODAL UPLOAD ULANG
    // =========================
    public function openUploadForm($type)
    {
        $this->isUploadFormOpen = true;
        $this->uploadType = $type;
        $this->uploadFile = null;
        $this->resetErrorBag();
    }

    public function hideUploadForm()
    {
        $this->isUploadFormOpen = false;
        $this->uploadType = null;
        $this->uploadFile = null;
    }

    // =========================
    // UPLOAD ULANG
    // =========================
    public function uploadUlang()
    {
        $this->validate([
            'uploadFile' => 'required|file|max:1024'
        ]);

        $file = ScreeningFile::where('user_id', $this->user_id)->first();

        if (!$file)
            return;

        $path = $this->uploadFile->store('kkn/' . $this->uploadType, 'public');

        if ($this->uploadType == 'surat') {
            $file->file_surat = $path;
            $file->status_surat = 'pending';
            $this->surat_pernyataan_path = $path;
            $this->status_surat = 'pending';
        }

        if ($this->uploadType == 'ktp') {
            $file->file_ktp = $path;
            $file->status_ktp = 'pending';
            $this->foto_ktp_path = $path;
            $this->status_ktp = 'pending';
        }

        if ($this->uploadType == 'spp') {
            $file->file_spp = $path;
            $file->status_spp = 'pending';
            $this->bukti_spp_path = $path;
            $this->status_spp = 'pending';
        }

        $file->save();

        $this->hideUploadForm();
        session()->flash('success', 'Upload ulang berhasil');
    }

    /**
     * Upload berkas for the first time
     */
    public function uploadBerkas()
    {
        // Prevent re-upload if files already exist
        if ($this->surat_pernyataan_path && $this->foto_ktp_path && $this->bukti_spp_path) {
            session()->flash('error', 'Berkas sudah diupload. Gunakan fitur upload ulang jika perlu mengganti.');
            return;
        }

        $this->validate([
            'surat_pernyataan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            'bukti_spp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ], [
            'surat_pernyataan.required' => 'Surat pernyataan wajib diupload',
            'surat_pernyataan.mimes' => 'Format surat pernyataan harus PDF, JPG, atau PNG',
            'surat_pernyataan.max' => 'Ukuran file surat pernyataan maksimal 1MB',
            'foto_ktp.required' => 'Foto KTP wajib diupload',
            'foto_ktp.image' => 'File KTP harus berupa gambar',
            'foto_ktp.max' => 'Ukuran file KTP maksimal 1MB',
            'bukti_spp.required' => 'Bukti pembayaran wajib diupload',
            'bukti_spp.mimes' => 'Format bukti pembayaran harus PDF, JPG, atau PNG',
            'bukti_spp.max' => 'Ukuran file bukti pembayaran maksimal 1MB',
        ]);

        try {
            $surat = $this->surat_pernyataan->store('kkn/surat', 'public');
            $ktp = $this->foto_ktp->store('kkn/ktp', 'public');
            $spp = $this->bukti_spp->store('kkn/spp', 'public');

            ScreeningFile::updateOrCreate(
                [
                    'user_id' => $this->user_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                ],
                [
                    'file_surat' => $surat,
                    'file_ktp' => $ktp,
                    'file_spp' => $spp,
                    'status_surat' => 'pending',
                    'status_ktp' => 'pending',
                    'status_spp' => 'pending',
                ]
            );

            // Update realtime ke view
            $this->surat_pernyataan_path = $surat;
            $this->foto_ktp_path = $ktp;
            $this->bukti_spp_path = $spp;
            $this->status_surat = 'pending';
            $this->status_ktp = 'pending';
            $this->status_spp = 'pending';

            session()->flash('success', 'Berkas berhasil diupload');

            // Reset file upload properties
            $this->reset(['surat_pernyataan', 'foto_ktp', 'bukti_spp']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal upload berkas: ' . $e->getMessage());
        }
    }

    // =========================
    // SUBMIT SCREENING
    // =========================
    public function submit()
    {
        // Validasi jawaban
        foreach ($this->pertanyaan as $item) {
            if ($item->is_required && empty($this->jawaban[$item->id])) {
                $this->addError(
                    "jawaban.{$item->id}",
                    'Jawaban wajib diisi'
                );
            }

            if (
                ($this->jawaban[$item->id] ?? null) == 'ya' &&
                empty($this->keterangan[$item->id])
            ) {
                $this->addError(
                    "keterangan.{$item->id}",
                    'Keterangan wajib diisi'
                );
            }
        }

        // Jika ada error hentikan
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        // Simpan jawaban
        foreach ($this->pertanyaan as $item) {
            ScreeningAnswer::updateOrCreate(
                [
                    'user_id' => $this->user_id,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'question_id' => $item->id,
                ],
                [
                    'jawaban' => ($this->jawaban[$item->id] ?? null) == 'ya',
                    'keterangan' => $this->keterangan[$item->id] ?? null,
                ]
            );
        }

        $this->isSubmitted = true;

        session()->flash(
            'success',
            'Jawaban screening berhasil disimpan'
        );
    }

    public function tandaiSudahCetak()
    {
        $this->sudahCetak = true;
    }

    // =========================

    // RENDER
    // =========================
    public function render()
    {
        return view('livewire.kegiatan.kkn.kknreg.index', [
            'isPendaftaranActive' => $this->isPendaftaranActive,
            'timelinePendaftaran' => $this->timelinePendaftaran,
            'tanggalPendaftaranMulai' => $this->tanggalPendaftaranMulai,
            'tanggalPendaftaranSelesai' => $this->tanggalPendaftaranSelesai,
            'sisaHariPendaftaran' => $this->sisaHariPendaftaran,  // <- perbaiki nama variable
        ]);
    }
}
