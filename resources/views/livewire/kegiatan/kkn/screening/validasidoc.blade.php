<div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div class="header-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0" style="font-weight: 600; color: #1e3a5f;">Validasi Dokumen KKN
                                        </h4>
                                        <p class="mb-0" style="color: #6c757d; font-size: 0.85rem;">
                                            Validasi dokumen persyaratan peserta KKN
                                        </p>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <!-- Tombol Export KTP Valid -->
                                    <button type="button" class="btn-export-ktp" wire:click="exportKTPValid"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-id-card"></i>
                                        <span>Export KTP Valid</span>

                                    </button>

                                    <!-- Tombol Export PDF -->
                                    <button type="button" class="btn-export-pdf" wire:click="exportToPDF"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>Export ke PDF</span>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Validasi -->
            <div class="row">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header"
                            style="padding: 1rem 1.5rem; border-bottom: 1px solid #eef2f6;">
                            <div class="card-title-academic">
                                <div class="header-icon-small">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Daftar Peserta KKN</h5>
                                <span class="badge-info-review">Total: {{ $files->count() }} Peserta</span>
                            </div>
                        </div>
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <div class="table-responsive-modern">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">NIM</th>
                                            <th width="15%">Prodi</th>
                                            <th width="20%">Nama Mahasiswa</th>
                                            <th width="15%">Surat Pernyataan</th>
                                            <th width="15%">KTP</th>
                                            <th width="18%">Bukti Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($files as $i => $file)
                                        <tr class="data-row">
                                            <td>{{ $i+1 }}</td>
                                            <td><span class="nim-text">{{ $file->nim }}</span></td>
                                            <td><span class="prodi-text">{{ $file->prodi }}</span></td>
                                            <td>
                                                <div class="mahasiswa-info">
                                                    <div class="mahasiswa-avatar">
                                                        <i class="fas fa-user-circle"></i>
                                                    </div>
                                                    <span class="mahasiswa-name">{{ $file->nama }}</span>
                                                </div>
                                            </td>

                                            {{-- SURAT --}}
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center gap-2">
                                                    <span class="status-badge status-{{ $file->status_surat }}">
                                                        @if($file->status_surat == 'valid')
                                                        <i class="fas fa-check-circle"></i> Valid
                                                        @elseif($file->status_surat == 'ditolak')
                                                        <i class="fas fa-times-circle"></i> Ditolak
                                                        @else
                                                        <i class="fas fa-clock"></i> Pending
                                                        @endif
                                                    </span>
                                                    <button class="action-btn action-view"
                                                        wire:click="lihatFile({{ $file->id }}, 'surat')"
                                                        title="Lihat Surat">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="tooltip-text">Lihat Surat</span>
                                                    </button>
                                                </div>
                                            </td>

                                            {{-- KTP --}}
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center gap-2">
                                                    <span class="status-badge status-{{ $file->status_ktp }}">
                                                        @if($file->status_ktp == 'valid')
                                                        <i class="fas fa-check-circle"></i> Valid
                                                        @elseif($file->status_ktp == 'ditolak')
                                                        <i class="fas fa-times-circle"></i> Ditolak
                                                        @else
                                                        <i class="fas fa-clock"></i> Pending
                                                        @endif
                                                    </span>
                                                    <button class="action-btn action-view"
                                                        wire:click="lihatFile({{ $file->id }}, 'ktp')"
                                                        title="Lihat KTP">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="tooltip-text">Lihat KTP</span>
                                                    </button>
                                                </div>
                                            </td>

                                            {{-- SPP --}}
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center gap-2">
                                                    <span class="status-badge status-{{ $file->status_spp }}">
                                                        @if($file->status_spp == 'valid')
                                                        <i class="fas fa-check-circle"></i> Valid
                                                        @elseif($file->status_spp == 'ditolak')
                                                        <i class="fas fa-times-circle"></i> Ditolak
                                                        @else
                                                        <i class="fas fa-clock"></i> Pending
                                                        @endif
                                                    </span>
                                                    <button class="action-btn action-view"
                                                        wire:click="lihatFile({{ $file->id }}, 'spp')"
                                                        title="Lihat Pembayaran">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="tooltip-text">Lihat Pembayaran</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="empty-row">
                                            <td colspan="7">
                                                <div class="empty-state">
                                                    <i class="fas fa-folder-open"></i>
                                                    <p>Belum ada data berkas</p>
                                                    <small>Belum ada peserta yang mengupload berkas</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL VALIDASI DOKUMEN --}}
    @if($modalActive)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content academic-modal">
                <div class="academic-modal-header">
                    <div class="modal-header-content">
                        <div class="modal-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h5 class="modal-title">Validasi Dokumen</h5>
                            <p class="modal-subtitle">
                                {{ $selectedType == 'surat' ? 'Surat Pernyataan' : ($selectedType == 'ktp' ? 'Foto KTP' : 'Bukti Pembayaran') }}
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="academic-modal-body" style="max-height: 70vh; overflow-y: auto;">
                    @if($selectedFile)
                    <div class="info-mahasiswa mb-3 p-3" style="background: #f8fafc; border-radius: 12px;">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">NIM</small>
                                <p class="fw-bold mb-0">{{ $selectedFile->nim }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Prodi</small>
                                <p class="fw-bold mb-0">{{ $selectedFile->prodi }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Nama Mahasiswa</small>
                                <p class="fw-bold mb-0">{{ $selectedFile->nama }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="document-preview mb-3">
                        @if($selectedType == 'surat')
                        <iframe src="{{ Storage::url($selectedFile->file_surat) }}" width="100%" height="400"
                            style="border: 1px solid #e2e8f0; border-radius: 12px;"></iframe>
                        @endif

                        @if($selectedType == 'ktp')
                        <div class="text-center">
                            <img src="{{ Storage::url($selectedFile->file_ktp) }}" class="img-fluid rounded"
                                style="max-height: 400px; border: 1px solid #e2e8f0; border-radius: 12px;">
                        </div>
                        @endif

                        @if($selectedType == 'spp')
                        <iframe src="{{ Storage::url($selectedFile->file_spp) }}" width="100%" height="400"
                            style="border: 1px solid #e2e8f0; border-radius: 12px;"></iframe>
                        @endif
                    </div>

                    @if($selectedType == 'spp')
                    <div class="mb-3">
                        <button type="button" class="btn-cek-krs"
                            wire:click="cekValidasiKRSViaAPI('{{ $selectedFile->id_registrasi_mahasiswa }}')"
                            wire:loading.attr="disabled">
                            <i class="fas fa-check-double"></i>
                            <span wire:loading.remove>Cek Validasi KRS</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Mengecek via API...
                            </span>
                        </button>

                        @if($hasilCekKRS)
                        <div
                            class="alert-cek-krs alert-cek-krs-{{ $hasilCekKRS['success'] ? 'success' : 'danger' }} mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <i
                                    class="fas {{ $hasilCekKRS['success'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                <div>
                                    <strong>{{ $hasilCekKRS['success'] ? 'Valid' : 'Tidak Valid' }}</strong>
                                    <p class="mb-0 small">{{ $hasilCekKRS['message'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="form-group-modern mb-3">
                        <label class="form-label-modern">
                            <i class="fas fa-comment"></i> Keterangan (jika ditolak)
                        </label>
                        <textarea class="form-control-modern" rows="3"
                            wire:model="keterangan.{{ $selectedFile->id }}.{{ $selectedType }}"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                        @error("keterangan.{$selectedFile->id}.{$selectedType}")
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                    @endif
                </div>

                <div class="academic-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    @if($selectedFile)
                    <button type="button" class="btn-valid"
                        wire:click="valid({{ $selectedFile->id }}, '{{ $selectedType }}')">
                        <i class="fas fa-check-circle"></i> Validasi
                    </button>
                    <button type="button" class="btn-tolak"
                        wire:click="tolak({{ $selectedFile->id }}, '{{ $selectedType }}')">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
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

    .academic-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
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

    .academic-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .academic-table th {
        padding: 12px;
        background: #f8fafc;
        color: #1e3a5f;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
    }

    .academic-table td {
        padding: 12px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .text-center {
        text-align: center;
    }

    .mahasiswa-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mahasiswa-avatar {
        width: 32px;
        height: 32px;
        background: #e8f0fe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2c7da0;
        font-size: 1rem;
    }

    .mahasiswa-name {
        font-weight: 500;
        color: #1e293b;
    }

    .nim-text {
        font-family: monospace;
        font-weight: 600;
        color: #475569;
    }

    .prodi-text {
        font-size: 0.8rem;
        color: #475569;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
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

    .status-badge.status-valid {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-badge.status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn .tooltip-text {
        visibility: hidden;
        background: #1e293b;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        position: absolute;
        bottom: -28px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .action-btn:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    .academic-modal {
        border-radius: 20px;
        overflow: hidden;
    }

    .academic-modal-header {
        background: linear-gradient(135deg, #1e3a5f, #2c7da0);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .modal-title {
        color: white;
        margin: 0;
        font-size: 1.1rem;
    }

    .btn-close-modal {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .academic-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel {
        background: #f1f5f9;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        cursor: pointer;
    }

    .btn-valid {
        background: #166534;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        cursor: pointer;
    }

    .btn-tolak {
        background: #991b1b;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        cursor: pointer;
    }

    .btn-export-pdf {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-export-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-export-ktp {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-export-ktp:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .btn-cek-krs {
        background: linear-gradient(135deg, #2563eb, #1e3a5f);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 40px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .alert-cek-krs {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
    }

    .alert-cek-krs-success {
        background: #dcfce7;
        border-left: 4px solid #166534;
        color: #166534;
    }

    .alert-cek-krs-danger {
        background: #fee2e2;
        border-left: 4px solid #991b1b;
        color: #991b1b;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {

        .academic-table th,
        .academic-table td {
            padding: 8px;
            font-size: 0.75rem;
        }

        .btn-valid,
        .btn-tolak,
        .btn-cancel,
        .btn-cek-krs,
        .btn-export-pdf,
        .btn-export-ktp {
            padding: 6px 12px;
            font-size: 0.7rem;
        }
    }
    </style>

    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('modalFile'));
            modal.show();
        });

        Livewire.on('closeModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalFile'));
            if (modal) modal.hide();
        });
    });
    </script>
</div>