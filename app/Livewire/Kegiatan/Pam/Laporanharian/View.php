<?php

namespace App\Livewire\Kegiatan\Pam\Laporanharian;

use App\Models\Kegiatan;
use App\Models\Kehadiran;
use App\Models\Kelompok;
use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class View extends Component
{
    use WithFileUploads;

    public $data = [];
    public $lapID;
    public $reviews = [];
    public $laporanId;
    public $showReviewForm = false;
    public $reviewStatus = '';
    public $reviewKomentar = '';
    public $parentId = null;  // untuk balasan review
    public $reviewerRole = '';
    public $replyText = '';      // Untuk menyimpan teks balasan
    
    // 🔥 TAMBAHKAN PROPERTY INI
    public $replyReviewId = null;  // Untuk menyimpan ID review yang akan dibalas

    public function mount($lapharID)
    {
        $this->laporanId = $lapharID;

        $laporanHarian = LaporanHarian::with(['user', 'kelompok'])
            ->findOrFail($lapharID);

        $mahasiswa = is_string($laporanHarian->user?->data_mahasiswa)
            ? json_decode($laporanHarian->user->data_mahasiswa, true)
            : ($laporanHarian->user?->data_mahasiswa ?? []);

        // 🔥 ambil review (thread)
        $this->reviews = Review::with(['user', 'replies.user', 'laporan'])
            ->where('laporan_id', $lapharID)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $this->data = [
            'id' => $laporanHarian->id,
            'tanggal' => $laporanHarian->tanggal,
            'user' => $laporanHarian->user,
            'mahasiswa' => $mahasiswa,
            'kelompok' => $laporanHarian->kelompok,
            'aktivitas' => $laporanHarian->aktivitas,
            'status' => $laporanHarian->status,
            'foto' => $laporanHarian->foto,
        ];
    }

    public function toggleReviewForm($parentId = null)
    {
        $this->showReviewForm = !$this->showReviewForm;
        $this->parentId = $parentId;

        if (!$this->showReviewForm) {
            $this->resetReviewForm();
        }
    }

    // 🔥 Reset form
    public function resetReviewForm()
    {
        $this->reviewStatus = '';
        $this->reviewKomentar = '';
        $this->parentId = null;
        $this->reviewerRole = '';
    }

    // 🔥 Submit review ke database
    public function submitReview()
    {
        // Validasi berdasarkan jenis review (utama atau balasan)
        if ($this->parentId) {
            // Validasi untuk balasan
            $this->validate([
                'reviewKomentar' => 'required|min:5',
            ]);
        } else {
            // Validasi untuk review utama
            $this->validate([
                'reviewStatus' => 'required|in:approved,revisi,ditolak',
                'reviewKomentar' => 'required|min:10',
            ]);
        }

        try {
            $reviewData = [
                'laporan_id' => $this->laporanId,
                'user_id' => Auth::id(),
                'role' => Auth()->User()->role,
                'komentar' => $this->reviewKomentar,
                'parent_id' => $this->parentId,
            ];

            // Hanya review utama yang punya status
            if (!$this->parentId) {
                $reviewData['status'] = $this->reviewStatus;
            }

            Review::create($reviewData);
            
            $laporanStatus = LaporanHarian::findOrFail($this->laporanId);

            $laporanStatus->update([
                'status' => $this->reviewStatus,
            ]);

            // Update status laporan jika perlu (review utama)
            if (!$this->parentId && $this->reviewStatus) {
                $this->updateLaporanStatus($this->reviewStatus);
            }

            session()->flash('success', 'Review berhasil dikirim');
            $this->toggleReviewForm();
            $this->resetReviewForm();

            // Refresh data review
            $this->reviews = Review::with(['user', 'replies.user'])
                ->where('laporan_id', $this->laporanId)
                ->whereNull('parent_id')
                ->latest()
                ->get();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🔥 Update status laporan berdasarkan review
    public function updateLaporanStatus($status)
    {
        // Update status di tabel laporan_harian
        LaporanHarian::where('id', $this->laporanId)->update(['status' => $status]);
    }

    public function sendReply($reviewId)
    {
        // Validasi
        $this->validate([
            'replyText' => 'required|min:5',
        ]);

        try {
            $replyData = [
                'laporan_id' => $this->laporanId,
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'komentar' => $this->replyText,
                'parent_id' => $reviewId,
            ];

            Review::create($replyData);

            session()->flash('success', 'Balasan berhasil dikirim');

            // Reset form
            $this->replyText = '';
            $this->replyReviewId = null;

            // Refresh reviews
            $this->reviews = Review::with(['user', 'replies.user'])
                ->where('laporan_id', $this->laporanId)
                ->whereNull('parent_id')
                ->latest()
                ->get();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🔥 Set review yang akan dibalas
    public function setReply($reviewId)
    {
        if ($this->replyReviewId == $reviewId) {
            // Jika sudah aktif, klik lagi untuk menutup
            $this->replyReviewId = null;
            $this->replyText = '';
        } else {
            // Set review yang akan dibalas
            $this->replyReviewId = $reviewId;
            $this->replyText = '';
        }
    }

    public function back()
    {
        return redirect()->route('kegiatan.pam.laporanharian.index', [
            'role' => Auth()->user()->role,
        ]);
    }

    public function render()
    {
        return view('livewire.kegiatan.pam.laporanharian.view');
    }
}