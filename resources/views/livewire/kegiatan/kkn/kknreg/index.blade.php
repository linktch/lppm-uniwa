<div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="header-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0" style="font-weight: 600; color: #1e3a5f;">Form Screening KKN</h4>
                                    <p class="mb-0" style="color: #6c757d; font-size: 0.85rem;">
                                        Isi formulir screening sesuai dengan data yang benar
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Timeline Pendaftaran -->
            <div class="row mb-3">
                <div class="col-12">
                    @if(isset($isPendaftaranActive) && $isPendaftaranActive)
                        <div class="alert alert-success" style="border-radius: 12px; background: #dcfce7; border: none; color: #166534;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">✅ Pendaftaran KKN Sedang Dibuka!</h6>
                                    <p class="mb-0 small">
                                        Periode pendaftaran: {{ $tanggalPendaftaranMulai }} s/d {{ $tanggalPendaftaranSelesai }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning" style="border-radius: 12px; background: #fef3c7; border: none; color: #b45309;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-times fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">⏰ Periode Pendaftaran Belum Tersedia</h6>
                                    <p class="mb-0 small">
                                        Mohon maaf, periode pendaftaran KKN belum dibuka. Silakan cek jadwal pendaftaran atau hubungi administrator.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="row">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header" style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                            <div class="card-title-academic">
                                <div class="header-icon-small">
                                    <i class="fas fa-questions"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Pertanyaan Screening</h5>
                                <span class="badge-info-review">Total: {{ $pertanyaan->count() }} Pertanyaan</span>
                            </div>
                        </div>
                        
                        @if(isset($isPendaftaranActive) && !$isPendaftaranActive)
                            <div class="academic-card-body text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-lock fa-3x mb-3" style="color: #b45309;"></i>
                                    <h5 class="text-warning">Pendaftaran Belum Dibuka</h5>
                                    <p class="text-muted">Form screening dan upload berkas akan tersedia saat periode pendaftaran dibuka.</p>
                                    <small class="text-muted">Silakan cek kembali jadwal pendaftaran KKN.</small>
                                </div>
                            </div>
                        @else
                            <div class="academic-card-body" style="padding: 1.5rem;">
                                
                                {{-- PERTANYAAN --}}
                                @foreach($pertanyaan as $index => $item)
                                    <div class="academic-card mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                        <div class="academic-card-body" style="padding: 1.25rem;">
                                            <div class="d-flex align-items-start">
                                                <div class="question-number me-3">
                                                    <span class="badge bg-primary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                        {{ $index+1 }}
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="fw-bold fs-6" style="color: #1e3a5f;">
                                                        {{ $item->pertanyaan }}
                                                        @if($item->is_required)
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    </label>
                                                    @if($item->deskripsi)
                                                        <small class="d-block text-muted mt-1" style="font-size: 0.75rem;">
                                                            <i class="fas fa-info-circle"></i> {{ $item->deskripsi }}
                                                        </small>
                                                    @endif

                                                    <div class="mt-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio"
                                                                   class="form-check-input"
                                                                   id="ya_{{ $item->id }}"
                                                                   wire:model.live="jawaban.{{ $item->id }}"
                                                                   value="ya"
                                                                   @disabled($isSubmitted)>
                                                            <label class="form-check-label" for="ya_{{ $item->id }}">
                                                                <span class="badge" style="background: #dcfce7; color: #166534; padding: 4px 12px;">
                                                                    <i class="fas fa-check-circle"></i> Ya
                                                                </span>
                                                            </label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input type="radio"
                                                                   class="form-check-input"
                                                                   id="tidak_{{ $item->id }}"
                                                                   wire:model.live="jawaban.{{ $item->id }}"
                                                                   value="tidak"
                                                                   @disabled($isSubmitted)>
                                                            <label class="form-check-label" for="tidak_{{ $item->id }}">
                                                                <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 4px 12px;">
                                                                    <i class="fas fa-times-circle"></i> Tidak
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    @error("jawaban.{$item->id}")
                                                        <div class="text-danger small mt-1">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                        </div>
                                                    @enderror

                                                    @if(($jawaban[$item->id] ?? null) == 'ya')
                                                        <div class="mt-3 pt-2">
                                                            <label class="form-label fw-semibold" style="color: #4a6fa5; font-size: 0.75rem;">
                                                                <i class="fas fa-pen me-1"></i>Keterangan <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea class="form-control" 
                                                                      rows="3"
                                                                      wire:model.live="keterangan.{{ $item->id }}"
                                                                      placeholder="Silakan jelaskan detail jawaban Anda..."
                                                                      style="border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.85rem;"
                                                                      @disabled($isSubmitted)></textarea>
                                                            @error("keterangan.{$item->id}")
                                                                <div class="text-danger small mt-1">
                                                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- BUTTON SIMPAN --}}
                                @if(!$isSubmitted)
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn-create-academic" wire:click="submit" style="background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                            <i class="fas fa-save me-2"></i> Simpan Jawaban
                                        </button>
                                    </div>
                                @endif

                                {{-- SETELAH SUBMIT --}}
                                @if($isSubmitted)
                                    <div class="mt-4 pt-3">
                                        <div class="alert alert-info" style="border-radius: 12px; background: #e8f0fe; border: none; color: #1e3a5f;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Form sudah disubmit. Silakan cetak PDF dan upload berkas yang diperlukan.
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                            <button class="btn-create-academic" style="background: linear-gradient(135deg, #dc2626, #b91c1c);"
                                                    onclick="window.open('{{ route('screening.pdf') }}','_blank')">
                                                <i class="fas fa-file-pdf me-2"></i> Cetak PDF
                                            </button>
                                        </div>

                                        {{-- UPLOAD / HASIL --}}
                                        <div class="academic-card mt-4">
                                            <div class="academic-card-header" style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                                                <div class="card-title-academic">
                                                    <div class="header-icon-small">
                                                        <i class="fas fa-upload"></i>
                                                    </div>
                                                    <h5 class="mb-0" style="font-weight: 600;">Upload Berkas Persyaratan</h5>
                                                </div>
                                            </div>
                                            <div class="academic-card-body" style="padding: 1.5rem;">

                                                @if($surat_pernyataan_path && $foto_ktp_path && $bukti_spp_path)

                                                    {{-- SUDAH UPLOAD --}}
                                                    <div class="alert alert-success" style="border-radius: 10px; background: #dcfce7; border: none; color: #166534;">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        Semua berkas sudah diupload. Terima kasih!
                                                    </div>

                                                    <div class="row g-3 mb-4">
                                                        <!-- Surat Pernyataan -->
                                                        <div class="col-md-4">
                                                            <div class="academic-card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                                <div class="academic-card-body" style="padding: 1rem; text-align: center;">
                                                                    <i class="fas fa-file-signature fs-1" style="color: #2c7da0;"></i>
                                                                    <p class="fw-bold mb-2 mt-2" style="font-size: 0.85rem;">Surat Pernyataan</p>
                                                                    <a href="{{ Storage::url($surat_pernyataan_path) }}" target="_blank" class="action-btn action-view" style="display: inline-flex;">
                                                                        <i class="fas fa-eye"></i> Lihat
                                                                    </a>
                                                                    @if(($status_surat ?? 'pending') == 'ditolak')
                                                                    <button class="btn-upload-ulang mt-2" wire:click="openUploadForm('surat')" style="background: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; width: 100%;">
                                                                        <i class="fas fa-upload me-1"></i> Upload Ulang
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- KTP -->
                                                        <div class="col-md-4">
                                                            <div class="academic-card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                                <div class="academic-card-body" style="padding: 1rem; text-align: center;">
                                                                    <i class="fas fa-id-card fs-1" style="color: #2c7da0;"></i>
                                                                    <p class="fw-bold mb-2 mt-2" style="font-size: 0.85rem;">KTP</p>
                                                                    <a href="{{ Storage::url($foto_ktp_path) }}" target="_blank" class="action-btn action-view" style="display: inline-flex;">
                                                                        <i class="fas fa-eye"></i> Lihat
                                                                    </a>
                                                                    @if(($status_ktp ?? 'pending') == 'ditolak')
                                                                    <button class="btn-upload-ulang mt-2" wire:click="openUploadForm('ktp')" style="background: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; width: 100%;">
                                                                        <i class="fas fa-upload me-1"></i> Upload Ulang
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bukti Pembayaran -->
                                                        <div class="col-md-4">
                                                            <div class="academic-card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                                <div class="academic-card-body" style="padding: 1rem; text-align: center;">
                                                                    <i class="fas fa-receipt fs-1" style="color: #2c7da0;"></i>
                                                                    <p class="fw-bold mb-2 mt-2" style="font-size: 0.85rem;">Bukti Pembayaran</p>
                                                                    <a href="{{ Storage::url($bukti_spp_path) }}" target="_blank" class="action-btn action-view" style="display: inline-flex;">
                                                                        <i class="fas fa-eye"></i> Lihat
                                                                    </a>
                                                                    @if(($status_spp ?? 'pending') == 'ditolak')
                                                                    <button class="btn-upload-ulang mt-2" wire:click="openUploadForm('spp')" style="background: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; width: 100%;">
                                                                        <i class="fas fa-upload me-1"></i> Upload Ulang
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- FORM UPLOAD ULANG --}}
                                                    @if($isUploadFormOpen)
                                                    <div class="upload-ulang-form mt-4 p-3" style="background: #fef3c7; border-radius: 12px; border: 1px solid #f59e0b;">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="mb-0" style="color: #b45309;">
                                                                <i class="fas fa-upload me-2"></i> Upload Ulang {{ $uploadType == 'surat' ? 'Surat Pernyataan' : ($uploadType == 'ktp' ? 'KTP' : 'Bukti Pembayaran') }}
                                                            </h6>
                                                            <button type="button" class="btn-close-sm" wire:click="hideUploadForm" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-8 mb-2 mb-md-0">
                                                                <input type="file" wire:model="uploadFile" class="form-control-modern" accept="{{ $uploadType == 'surat' ? '.pdf,.doc,.docx' : 'image/*' }}">
                                                                @error('uploadFile') 
                                                                    <div class="error-message">{{ $message }}</div> 
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button class="btn-create-academic" wire:click="uploadUlang" style="background: linear-gradient(135deg, #0f766e, #14b8a6); width: 100%;" wire:loading.attr="disabled">
                                                                    <div wire:loading.remove wire:target="uploadUlang">
                                                                        <i class="fas fa-cloud-upload-alt me-2"></i> Upload Ulang
                                                                    </div>
                                                                    <div wire:loading wire:target="uploadUlang">
                                                                        <i class="fas fa-spinner fa-pulse me-2"></i> Mengupload...
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Format: {{ $uploadType == 'surat' ? 'PDF, DOC (Max. 1MB)' : 'JPG, PNG (Max. 1MB)' }}</small>
                                                    </div>
                                                    @endif

                                                    {{-- STATUS VALIDASI DOKUMEN --}}
                                                    <div class="mt-4">
                                                        <div class="academic-card" style="background: #f8fafc;">
                                                            <div class="academic-card-header" style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6; background: #f8fafc;">
                                                                <div class="card-title-academic">
                                                                    <div class="header-icon-small" style="background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                                                        <i class="fas fa-check-double"></i>
                                                                    </div>
                                                                    <h5 class="mb-0" style="font-weight: 600;">Status Validasi Dokumen</h5>
                                                                </div>
                                                            </div>
                                                            <div class="academic-card-body" style="padding: 1.5rem;">
                                                                <div class="row g-4">
                                                                    <!-- Surat Pernyataan -->
                                                                    <div class="col-md-4">
                                                                        <div class="status-card p-3 rounded-3 text-center">
                                                                            <i class="fas fa-file-signature fs-2 mb-2" style="color: #2c7da0;"></i>
                                                                            <p class="fw-bold mb-2" style="font-size: 0.85rem;">Surat Pernyataan</p>
                                                                            <span class="status-badge status-{{ $status_surat ?? 'pending' }} mb-2">
                                                                                @if(($status_surat ?? 'pending') == 'valid')
                                                                                    <i class="fas fa-check-circle"></i> Valid
                                                                                @elseif(($status_surat ?? 'pending') == 'ditolak')
                                                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                                                @else
                                                                                    <i class="fas fa-clock"></i> Pending
                                                                                @endif
                                                                            </span>
                                                                            @if(($status_surat ?? 'pending') == 'ditolak' && ($keterangan_surat ?? null))
                                                                                <div class="alert alert-danger mt-2 p-2 small" style="font-size: 0.7rem;">
                                                                                    <i class="fas fa-info-circle me-1"></i> {{ $keterangan_surat }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <!-- KTP -->
                                                                    <div class="col-md-4">
                                                                        <div class="status-card p-3 rounded-3 text-center">
                                                                            <i class="fas fa-id-card fs-2 mb-2" style="color: #2c7da0;"></i>
                                                                            <p class="fw-bold mb-2" style="font-size: 0.85rem;">KTP</p>
                                                                            <span class="status-badge status-{{ $status_ktp ?? 'pending' }} mb-2">
                                                                                @if(($status_ktp ?? 'pending') == 'valid')
                                                                                    <i class="fas fa-check-circle"></i> Valid
                                                                                @elseif(($status_ktp ?? 'pending') == 'ditolak')
                                                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                                                @else
                                                                                    <i class="fas fa-clock"></i> Pending
                                                                                @endif
                                                                            </span>
                                                                            @if(($status_ktp ?? 'pending') == 'ditolak' && ($keterangan_ktp ?? null))
                                                                                <div class="alert alert-danger mt-2 p-2 small" style="font-size: 0.7rem;">
                                                                                    <i class="fas fa-info-circle me-1"></i> {{ $keterangan_ktp }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <!-- Bukti Pembayaran -->
                                                                    <div class="col-md-4">
                                                                        <div class="status-card p-3 rounded-3 text-center">
                                                                            <i class="fas fa-receipt fs-2 mb-2" style="color: #2c7da0;"></i>
                                                                            <p class="fw-bold mb-2" style="font-size: 0.85rem;">Bukti Pembayaran</p>
                                                                            <span class="status-badge status-{{ $status_spp ?? 'pending' }} mb-2">
                                                                                @if(($status_spp ?? 'pending') == 'valid')
                                                                                    <i class="fas fa-check-circle"></i> Valid
                                                                                @elseif(($status_spp ?? 'pending') == 'ditolak')
                                                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                                                @else
                                                                                    <i class="fas fa-clock"></i> Pending
                                                                                @endif
                                                                            </span>
                                                                            @if(($status_spp ?? 'pending') == 'ditolak' && ($keterangan_spp ?? null))
                                                                                <div class="alert alert-danger mt-2 p-2 small" style="font-size: 0.7rem;">
                                                                                    <i class="fas fa-info-circle me-1"></i> {{ $keterangan_spp }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if(($status_surat ?? 'pending') == 'pending' || ($status_ktp ?? 'pending') == 'pending' || ($status_spp ?? 'pending') == 'pending')
                                                                <div class="alert alert-warning mt-3 small" style="border-radius: 10px;">
                                                                    <i class="fas fa-clock me-2"></i>
                                                                    Dokumen sedang dalam proses validasi oleh administrator. Mohon tunggu.
                                                                </div>
                                                                @endif

                                                                @if(($status_surat ?? 'pending') == 'valid' && ($status_ktp ?? 'pending') == 'valid' && ($status_spp ?? 'pending') == 'valid')
                                                                <div class="alert alert-success mt-3 small" style="border-radius: 10px;">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    Selamat! Semua dokumen Anda telah divalidasi. Silakan lanjut ke tahap berikutnya.
                                                                </div>
                                                                @endif

                                                                @if(($status_surat ?? 'pending') == 'ditolak' || ($status_ktp ?? 'pending') == 'ditolak' || ($status_spp ?? 'pending') == 'ditolak')
                                                                <div class="alert alert-danger mt-3 small" style="border-radius: 10px;">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    Terdapat dokumen yang ditolak. Silakan perbaiki dan upload ulang dokumen yang ditolak.
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                @else

                                                    {{-- FORM UPLOAD PERTAMA KALI --}}
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold" style="color: #4a6fa5; font-size: 0.75rem;">
                                                                <i class="fas fa-file-signature me-1"></i>Surat Pernyataan
                                                            </label>
                                                            <input type="file" wire:model="surat_pernyataan" 
                                                                   class="form-control-modern @error('surat_pernyataan') is-invalid @enderror"
                                                                   accept=".pdf,.doc,.docx">
                                                            <small class="text-muted" style="font-size: 0.65rem;">Format: PDF, DOC (Max. 1MB)</small>
                                                            @error('surat_pernyataan') 
                                                                <div class="error-message">{{ $message }}</div> 
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold" style="color: #4a6fa5; font-size: 0.75rem;">
                                                                <i class="fas fa-id-card me-1"></i>KTP
                                                            </label>
                                                            <input type="file" wire:model="foto_ktp" 
                                                                   class="form-control-modern @error('foto_ktp') is-invalid @enderror"
                                                                   accept="image/*">
                                                            <small class="text-muted" style="font-size: 0.65rem;">Format: JPG, PNG (Max. 1MB)</small>
                                                            @error('foto_ktp') 
                                                                <div class="error-message">{{ $message }}</div> 
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold" style="color: #4a6fa5; font-size: 0.75rem;">
                                                                <i class="fas fa-receipt me-1"></i>Bukti Pembayaran Semester
                                                            </label>
                                                            <input type="file" wire:model="bukti_spp" 
                                                                   class="form-control-modern @error('bukti_spp') is-invalid @enderror"
                                                                   accept="image/*">
                                                            <small class="text-muted" style="font-size: 0.65rem;">Format: JPG, PNG (Max. 1MB)</small>
                                                            @error('bukti_spp') 
                                                                <div class="error-message">{{ $message }}</div> 
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    {{-- LOADING INDICATOR --}}
                                                    <div wire:loading wire:target="surat_pernyataan,foto_ktp,bukti_spp" class="mt-3">
                                                        <div class="alert alert-info py-2" style="background: #e8f0fe; border: none; border-radius: 10px;">
                                                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                                            <small>Mengupload berkas...</small>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end mt-4">
                                                        <button class="btn-create-academic" wire:click="uploadBerkas" style="background: linear-gradient(135deg, #0f766e, #14b8a6);" wire:loading.attr="disabled">
                                                            <div wire:loading.remove wire:target="uploadBerkas">
                                                                <i class="fas fa-cloud-upload-alt me-2"></i> Upload Berkas
                                                            </div>
                                                            <div wire:loading wire:target="uploadBerkas">
                                                                <i class="fas fa-spinner fa-pulse me-2"></i> Mengupload...
                                                            </div>
                                                        </button>
                                                    </div>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Academic Card Styles */
        .academic-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
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
        
        .badge-info-review {
            background: #e8f0fe;
            color: #2c7da0;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .btn-create-academic {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            padding: 8px 24px;
            border-radius: 40px;
            font-weight: 500;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-create-academic:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .form-control-modern {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: #2c7da0;
            box-shadow: 0 0 0 2px rgba(44, 125, 160, 0.1);
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.7rem;
            margin-top: 5px;
        }
        
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #2563eb;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-upload-ulang {
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .btn-upload-ulang:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .btn-close-sm {
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-close-sm:hover {
            opacity: 0.7;
        }
        
        /* Status Card */
        .status-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .status-badge.status-pending {
            background: #fef3c7;
            color: #b45309;
        }
        
        .status-badge.status-valid {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-badge.status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .upload-ulang-form {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
        }
        
        .empty-state h5 {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .empty-state p {
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        /* Form Check */
        .form-check-input:checked {
            background-color: #2c7da0;
            border-color: #2c7da0;
        }
        
        .form-check-input {
            cursor: pointer;
        }
        
        .form-check-label {
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .academic-card-body {
                padding: 1rem;
            }
            
            .btn-create-academic {
                padding: 6px 16px;
                font-size: 0.75rem;
            }
            
            .badge.bg-primary.rounded-circle {
                width: 28px !important;
                height: 28px !important;
                font-size: 12px !important;
            }
            
            .status-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</div>