<div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div class="header-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0" style="font-weight: 600; color: #1e3a5f;">
                                            Detail Laporan & Review
                                        </h4>
                                        <p class="mb-0" style="color: #6c757d; font-size: 0.85rem;">
                                            Lihat detail laporan dan review dari Mitra, Prodi, & Kemahasiswaan
                                        </p>
                                    </div>
                                </div>
                                <button class="btn-cancel" wire:click="back">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== DATA LAPORAN ==================== -->
            <div class="row">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header"
                            style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                            <div class="card-title-academic">
                                <div class="header-icon-small">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Data Laporan</h5>
                            </div>
                        </div>
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <!-- Informasi Dasar -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <div class="info-label">Tanggal Laporan</div>
                                        <div class="info-value">
                                            {{ \Carbon\Carbon::parse($data['tanggal'])->format('Y/m/d') }}
                                            {{ \Carbon\Carbon::parse($data['tanggal'])->format('H:i') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <div class="info-label">Mahasiswa</div>
                                        <div class="info-value">{{ $data['mahasiswa']['nama_mahasiswa'] ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <div class="info-label">NIM</div>
                                        <div class="info-value">{{ $data['mahasiswa']['nim'] ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <div class="info-label">Status</div>
                                        <div class="info-value">
                                            @php
                                                $status = $data['status'] ?? 'draft';
                                                $statusClass = '';
                                                $statusIcon = '';

                                                switch ($status) {
                                                    case 'draft':
                                                        $statusClass = 'status-draft';
                                                        $statusIcon = 'fa-pen-fancy';
                                                        $statusText = 'Draft';
                                                        break;
                                                    case 'submitted':
                                                        $statusClass = 'status-submitted';
                                                        $statusIcon = 'fa-paper-plane';
                                                        $statusText = 'Submitted';
                                                        break;
                                                    case 'revisi':
                                                        $statusClass = 'status-revisi';
                                                        $statusIcon = 'fa-undo-alt';
                                                        $statusText = 'Revisi';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'status-approved';
                                                        $statusIcon = 'fa-check-circle';
                                                        $statusText = 'Approved';
                                                        break;
                                                    default:
                                                        $statusClass = 'status-draft';
                                                        $statusIcon = 'fa-pen-fancy';
                                                        $statusText = ucfirst($status);
                                                }
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">
                                                <i class="fas {{ $statusIcon }}"></i>
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto & Narasi -->
                            <div class="row g-4">
                                <div class="col-md-5">
                                    <div class="info-label mb-2"><i class="fas fa-camera"></i> Dokumentasi Foto</div>
                                    <div class="foto-container">
                                        @if (!empty($data['foto']) && file_exists(storage_path('app/public/' . $data['foto'])))
                                            <div class="foto-preview text-center">
                                                <img src="{{ asset('storage/' . $data['foto']) }}" alt="Foto Kegiatan"
                                                    class="img-fluid rounded-3">
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $data['foto']) }}" target="_blank"
                                                        class="btn-view-foto">
                                                        <i class="fas fa-expand"></i> Lihat Fullsize
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="empty-foto text-center p-4">
                                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                                <p class="mb-0 text-muted">Belum ada foto</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="info-label mb-2"><i class="fas fa-align-left"></i> Narasi Kegiatan</div>
                                    <div class="narasi-container p-3 bg-light rounded-3">
                                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">
                                            {{ $data['aktivitas'] }}
                                        </p>
                                        <div class="text-end mt-2">
                                            <small class="text-muted"><i class="fas fa-keyboard"></i> 245
                                                karakter</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($reviews->isNotEmpty())
                <!-- ==================== REVIEW SECTION ==================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="academic-card">
                            <div class="academic-card-header"
                                style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                                <div class="card-title-academic">
                                    <div class="header-icon-small">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h5 class="mb-0" style="font-weight: 600;">Review Laporan</h5>
                                    <span class="badge-info-review">3 Review</span>
                                </div>
                            </div>
                            <div class="academic-card-body" style="padding: 1.5rem;">

                                @foreach ($reviews as $review)
                                    <!-- ========== REVIEW 2: DARI PRODI ========== -->
                                    <div class="review-section review-prodi">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <div class="reviewer-avatar kemahasiswaan">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="reviewer-name">Review dari {{ $review->role }}</div>
                                                    <div class="reviewer-instansi">
                                                        {{ $review->user->first_name }}
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($review->laporan->status == 'approved')
                                                <div class="review-status status-approved">
                                                    <i class="fas fa-check-circle"></i> Disetujui
                                                </div>
                                            @elseif($review->laporan->status == 'revisi')
                                                <div class="review-status status-revisi">
                                                    <i class="fas fa-undo-alt"></i> Revisi
                                                </div>
                                            @elseif($review->laporan->status == 'submitted')
                                                <div class="review-status status-submitted">
                                                    <i class="fas fa-paper-plane"></i> Submitted
                                                </div>
                                            @else
                                                <div class="review-status status-draft">
                                                    <i class="fas fa-pen-fancy"></i> Draft
                                                </div>
                                            @endif
                                        </div>
                                        <div class="review-body">
                                            <p>{{ $review->komentar }}</p>
                                            <div class="review-date"><i class="fas fa-clock"></i>
                                                {{ $review->created_at }}
                                            </div>
                                        </div>


                                        <!-- Balasan (Replies) yang sudah ada -->
                                        @if ($review->replies->count() > 0)
                                            @foreach ($review->replies as $reply)
                                                <div class="reply-item">
                                                    <div class="reply-header">
                                                        <div class="reviewer-info">
                                                            <div class="reviewer-avatar {{ $reply->role }}">
                                                                <i
                                                                    class="fas 
                                                                        {{ $reply->role == 'mitra' ? 'fa-handshake' : '' }}
                                                                        {{ $reply->role == 'prodi' ? 'fa-university' : '' }}
                                                                        {{ $reply->role == 'kemahasiswaan' ? 'fa-user-friends' : '' }}
                                                                        {{ $reply->role == 'mahasiswa' ? 'fa-user-graduate' : '' }}
                                                                        {{ $reply->role == 'superadmin' ? 'fa-crown' : '' }}
                                                                        {{ $reply->role == 'admin' ? 'fa-user-shield' : '' }}">
                                                                </i>
                                                            </div>
                                                            <div>
                                                                <div class="reviewer-name">Balasan dari
                                                                    {{ ucfirst($reply->role) }}</div>
                                                                <div class="reviewer-instansi">
                                                                    {{ $reply->user->name ?? '-' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="review-body">
                                                        <p>{{ $reply->komentar }}</p>
                                                        <div class="review-date">
                                                            <i class="fas fa-clock"></i>
                                                            {{ $reply->created_at->format('d M Y, H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <!-- Form Balasan untuk Superadmin -->
                                        @if (Auth()->user()->role == 'superadmin')
                                            @if ($replyReviewId == $review->id)
                                                <div class="reply-form">
                                                    <textarea class="form-control" rows="2" placeholder="Tulis balasan Anda untuk {{ ucfirst($review->role) }}..."
                                                        wire:model="replyText"></textarea>
                                                    <div class="text-end mt-2">
                                                        <button class="btn-send"
                                                            wire:click="sendReply({{ $review->id }})">
                                                            <i class="fas fa-paper-plane"></i> Kirim Balasan
                                                        </button>
                                                        <button class="btn-cancel-small" wire:click="setReply(null)">
                                                            <i class="fas fa-times"></i> Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="reply-form-trigger">
                                                    <button class="btn-reply"
                                                        wire:click="setReply({{ $review->id }})">
                                                        <i class="fas fa-reply"></i> Balas Review
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Divider -->
                                    <div class="review-divider"></div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Auth()->User()->role !== 'mahasiswa')
                <!-- Tombol Aksi -->
                <div class="row mt-4 mb-5">
                    <div class="col-12">
                        <div class="academic-card">
                            <div class="academic-card-body" style="padding: 1.5rem;">
                                <div style="display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap;">
                                    <button class="btn-cancel" wire:click="back">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </button>

                                    <button class="btn-review" wire:click="toggleReviewForm">
                                        <i class="fas fa-star me-1"></i> Review Laporan
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Auth()->User()->role == 'mahasiswa')
                @if ($data['status'] == 'revisi')
                    <!-- Tombol Aksi -->
                    <div class="row mt-4 mb-5">
                        <div class="col-12">
                            <div class="academic-card">
                                <div class="academic-card-body" style="padding: 1.5rem;">
                                    <div style="display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap;">
                                        <a wire:navigate href="{{ route('kegiatan.pam.laporanharian.update', ['role' => auth()->user()->role, 'lapharID' => $data['id']]) }}"
                                         class="btn-review">
                                            <i class="fas fa-undo me-1"></i> Revisi Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            <!-- Form Review Laporan (Muncul saat tombol diklik) -->
            @if ($showReviewForm)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="academic-card">
                            <div class="academic-card-header"
                                style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                                <div class="card-title-academic">
                                    <div class="header-icon-small">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h5 class="mb-0" style="font-weight: 600;">Form Review Laporan</h5>
                                </div>
                                <button class="btn-close-form" wire:click="toggleReviewForm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="academic-card-body" style="padding: 1.5rem;">
                                <form>
                                    <!-- Status Review -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status Review <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="reviewStatus">
                                            <option value="">Pilih Status</option>
                                            <option value="approved">✅ Disetujui</option>
                                            <option value="revisi">🔄 Revisi</option>
                                            <option value="ditolak">❌ Ditolak</option>
                                        </select>
                                        @error('reviewStatus')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Komentar Review -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Komentar / Catatan Review <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" wire:model="reviewKomentar" rows="5"
                                            placeholder="Tuliskan komentar, masukan, atau catatan review untuk mahasiswa..."></textarea>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            @if ($reviewStatus == 'approved')
                                                Berikan apresiasi dan masukan untuk kebaikan kedepannya
                                            @elseif($reviewStatus == 'revisi')
                                                Jelaskan bagian mana yang perlu direvisi secara spesifik
                                            @elseif($reviewStatus == 'ditolak')
                                                Berikan alasan yang jelas mengapa laporan ditolak
                                            @else
                                                Berikan komentar yang konstruktif untuk mahasiswa
                                            @endif
                                        </small>
                                        @error('reviewKomentar')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn-cancel" wire:click="toggleReviewForm">
                                            <i class="fas fa-times me-1"></i> Batal
                                        </button>
                                        <button type="button" class="btn-submit-review" wire:click="submitReview"
                                            wire:loading.attr="disabled">
                                            <div wire:loading.remove wire:target="submitReview">
                                                <i class="fas fa-paper-plane me-1"></i> Kirim Review
                                            </div>
                                            <div wire:loading wire:target="submitReview">
                                                <i class="fas fa-spinner fa-pulse me-1"></i> Mengirim...
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* ============================================
       GLOBAL STYLES
    ============================================ */

        /* Header Icon */
        .header-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-icon i {
            font-size: 1.3rem;
        }

        .header-icon-small {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-icon-small i {
            font-size: 1rem;
        }

        /* Card */
        .academic-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* ============================================
       INFO ITEM
    ============================================ */
        .info-item {
            margin-bottom: 0.5rem;
        }

        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e3a5f;
        }

        /* Badge Info */
        .badge-info-review {
            background: #e8f0fe;
            color: #2c7da0;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 10px;
        }

        /* ============================================
       STATUS BADGE
    ============================================ */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }

        .status-submitted {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-revisi {
            background: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        /* ============================================
       FOTO & NARASI
    ============================================ */
        .foto-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #eef2f6;
        }

        .empty-foto {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
        }

        .narasi-container {
            min-height: 200px;
            max-height: 250px;
            overflow-y: auto;
        }

        /* ============================================
       REVIEW SECTION
    ============================================ */
        .review-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.25rem;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .reviewer-name {
            font-weight: 600;
            color: #1e3a5f;
            margin-bottom: 2px;
        }

        .reviewer-instansi {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .review-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .review-body {
            font-size: 0.9rem;
            color: #334155;
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .review-body ul {
            margin-top: 8px;
            margin-left: 1.5rem;
            margin-bottom: 0;
        }

        .review-date {
            font-size: 0.65rem;
            color: #94a3b8;
        }

        .review-date i {
            margin-right: 4px;
        }

        /* Review Divider */
        .review-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
            margin: 1.5rem 0;
        }

        /* ============================================
       REVIEWER AVATAR
    ============================================ */
        .reviewer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .reviewer-avatar i {
            font-size: 1.2rem;
        }

        .reviewer-avatar.mitra {
            background: linear-gradient(135deg, #2c7da0, #61a5c2);
        }

        .reviewer-avatar.prodi {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
        }

        .reviewer-avatar.kemahasiswaan {
            background: linear-gradient(135deg, #f4a261, #e76f51);
        }

        .reviewer-avatar.mahasiswa {
            background: linear-gradient(135deg, #48bb78, #38a169);
        }

        .reviewer-avatar.superadmin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .reviewer-avatar.admin {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        /* ============================================
       REPLY ITEM & FORM
    ============================================ */
        .reply-item {
            background: #fefce8;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            margin-left: 2rem;
            border-left: 3px solid #eab308;
        }

        .reply-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 0.75rem;
        }

        .reply-form {
            margin-left: 2rem;
            margin-top: 1rem;
            padding: 1rem;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            animation: fadeInUp 0.2s ease-out;
        }

        .reply-form-trigger {
            margin-top: 12px;
            text-align: right;
        }

        /* ============================================
       BUTTONS
    ============================================ */
        .btn-cancel,
        .btn-save,
        .btn-send,
        .btn-review,
        .btn-submit-review,
        .btn-reply,
        .btn-cancel-small {
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #5b6e8c;
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .btn-save {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 78, 108, 0.2);
        }

        .btn-send {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            padding: 6px 18px;
            border-radius: 40px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-send:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 78, 108, 0.2);
        }

        .btn-review {
            background: linear-gradient(135deg, #f4a261, #e76f51);
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .btn-review:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(244, 162, 97, 0.3);
        }

        .btn-submit-review {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            padding: 8px 24px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .btn-submit-review:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 78, 108, 0.2);
        }

        .btn-reply {
            background: #e8f0fe;
            color: #2c7da0;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-reply:hover {
            background: #2c7da0;
            color: white;
            transform: translateY(-1px);
        }

        .btn-cancel-small {
            background: #f1f5f9;
            color: #5b6e8c;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 0.7rem;
            margin-left: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-cancel-small:hover {
            background: #e2e8f0;
            color: #dc2626;
            transform: translateY(-1px);
        }

        .btn-close-form {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .btn-close-form:hover {
            background: #f1f5f9;
            color: #dc2626;
        }

        /* ============================================
       FORM CONTROL
    ============================================ */
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2c7da0;
            box-shadow: 0 0 0 3px rgba(44, 125, 160, 0.1);
        }

        .reply-form .form-control {
            padding: 12px 14px;
            resize: vertical;
        }

        .reply-form .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        /* ============================================
       REVISI POINTS
    ============================================ */
        .revisi-point-item {
            background: #fef3c7;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        .revisi-point-item button {
            background: none;
            border: none;
            color: #d97706;
            cursor: pointer;
        }

        .revisi-point-item button:hover {
            color: #dc2626;
        }

        .btn-add-point {
            background: #e8f0fe;
            border: none;
            padding: 0 16px;
            border-radius: 8px;
            color: #2c7da0;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-add-point:hover {
            background: #2c7da0;
            color: white;
        }

        /* ============================================
       ANIMATION
    ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
       RESPONSIVE
    ============================================ */
        @media (max-width: 768px) {
            .review-header {
                flex-direction: column;
            }

            .reply-item,
            .reply-form {
                margin-left: 0.5rem;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
                text-align: center;
            }

            .reviewer-avatar {
                width: 36px;
                height: 36px;
            }

            .reviewer-name {
                font-size: 0.85rem;
            }

            .reply-form-trigger {
                text-align: left;
            }
        }

        @media (max-width: 480px) {
            .reply-form .text-end {
                display: flex;
                flex-direction: column;
                gap: 8px;
                text-align: center !important;
            }

            .btn-send,
            .btn-cancel-small {
                width: 100%;
                justify-content: center;
                margin-left: 0;
            }

            .btn-reply {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</div>
