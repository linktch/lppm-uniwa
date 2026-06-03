<?php

namespace App\Livewire\Kegiatan\Kkn\Laporanharian;

use App\Models\LaporanHarian;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class View extends Component
{
    public $data = [];
    public $reviews = [];
    public $laporanId;
    public $showReviewForm = false;
    public $reviewStatus = '';
    public $reviewKomentar = '';
    public $parentId = null;
    public $replyText = '';
    public $replyReviewId = null;

    public function mount($id)
    {
        $this->laporanId = $id;

        $laporanHarian = LaporanHarian::with(['user', 'kelompok', 'periode', 'kegiatan'])
            ->findOrFail($id);

        $mahasiswa = is_string($laporanHarian->user?->data_mahasiswa)
            ? json_decode($laporanHarian->user->data_mahasiswa, true)
            : ($laporanHarian->user?->data_mahasiswa ?? []);

        $this->reviews = Review::with(['user', 'replies.user'])
            ->where('laporan_id', $id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $this->data = [
            'id' => $laporanHarian->id,
            'tanggal' => $laporanHarian->tanggal,
            'jam' => $laporanHarian->jam,
            'user' => $laporanHarian->user,
            'mahasiswa' => $mahasiswa,
            'kelompok' => $laporanHarian->kelompok,
            'aktivitas' => $laporanHarian->aktivitas,
            'status' => $laporanHarian->status,
            'foto' => $laporanHarian->foto,
            'created_at' => $laporanHarian->created_at,
        ];
    }

    public function toggleReviewForm($parentId = null)
    {
        $this->showReviewForm = !$this->showReviewForm;
        
        if ($this->showReviewForm) {
            $this->parentId = $parentId;
            $this->reviewStatus = '';
            $this->reviewKomentar = '';
        } else {
            $this->resetReviewForm();
        }
    }

    public function resetReviewForm()
    {
        $this->reviewStatus = '';
        $this->reviewKomentar = '';
        $this->parentId = null;
    }

    public function submitReview()
    {
        $isReply = !empty($this->parentId);

        if ($isReply) {
            $this->validate([
                'reviewKomentar' => 'required|min:5',
            ], [
                'reviewKomentar.required' => 'Komentar tidak boleh kosong',
                'reviewKomentar.min' => 'Komentar minimal 5 karakter',
            ]);
        } else {
            $this->validate([
                'reviewStatus' => 'required|in:approved,revisi,ditolak',
                'reviewKomentar' => 'required|min:10',
            ], [
                'reviewStatus.required' => 'Status review wajib dipilih',
                'reviewKomentar.required' => 'Komentar tidak boleh kosong',
                'reviewKomentar.min' => 'Komentar minimal 10 karakter',
            ]);
        }

        try {
            $reviewData = [
                'laporan_id' => $this->laporanId,
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'komentar' => $this->reviewKomentar,
                'parent_id' => $isReply ? $this->parentId : null,
            ];

            if (!$isReply) {
                $reviewData['status'] = $this->reviewStatus;
            }

            Review::create($reviewData);

            if (!$isReply && $this->reviewStatus) {
                $statusMap = [
                    'approved' => 'approved',
                    'revisi' => 'revisi',
                    'ditolak' => 'submitted',
                ];
                $newStatus = $statusMap[$this->reviewStatus] ?? 'submitted';
                
                LaporanHarian::where('id', $this->laporanId)->update(['status' => $newStatus]);
                $this->data['status'] = $newStatus;
            }

            session()->flash('success', 'Review berhasil dikirim');
            $this->showReviewForm = false;
            $this->resetReviewForm();

            $this->reviews = Review::with(['user', 'replies.user'])
                ->where('laporan_id', $this->laporanId)
                ->whereNull('parent_id')
                ->latest()
                ->get();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function setReply($reviewId)
    {
        if ($this->replyReviewId == $reviewId) {
            $this->replyReviewId = null;
            $this->replyText = '';
        } else {
            $this->replyReviewId = $reviewId;
            $this->replyText = '';
        }
    }

    public function sendReply($reviewId)
    {
        $this->validate([
            'replyText' => 'required|min:5',
        ], [
            'replyText.required' => 'Balasan tidak boleh kosong',
            'replyText.min' => 'Balasan minimal 5 karakter',
        ]);

        try {
            Review::create([
                'laporan_id' => $this->laporanId,
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'komentar' => $this->replyText,
                'parent_id' => $reviewId,
            ]);

            session()->flash('success', 'Balasan berhasil dikirim');

            $this->replyReviewId = null;
            $this->replyText = '';

            $this->reviews = Review::with(['user', 'replies.user'])
                ->where('laporan_id', $this->laporanId)
                ->whereNull('parent_id')
                ->latest()
                ->get();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function back()
    {
        return redirect()->route('kegiatan.kkn.laporanharian.index', ['role' => Auth::user()->role]);
    }

    public function render()
    {
        return view('livewire.kegiatan.kkn.laporanharian.view');
    }
}