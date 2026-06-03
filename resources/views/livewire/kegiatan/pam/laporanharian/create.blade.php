<div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header Form -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div class="header-icon" style="width: 45px; height: 45px;">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0" style="font-weight: 600; color: #1e3a5f;">
                                            {{ $isEditing ? 'Edit Laporan' : 'Tambah Laporan Harian' }}
                                        </h4>
                                        <p class="mb-0" style="color: #6c757d; font-size: 0.85rem;">
                                            Isi form di bawah ini untuk membuat laporan kegiatan harian
                                        </p>
                                    </div>
                                </div>
                                <button class="btn-cancel" wire:click="back" style="width: auto;">
                                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Informasi Dasar -->
            <div class="row">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header" style="padding: 1.25rem 1.5rem;">
                            <div class="card-title-academic">
                                <div class="header-icon" style="width: 40px; height: 40px;">
                                    <i class="fas fa-info-circle" style="font-size: 1.2rem;"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Informasi Dasar</h5>
                            </div>
                        </div>
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i> Tanggal Laporan
                                            <span class="required-star">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <span class="input-icon">
                                                <i class="fas fa-calendar-day"></i>
                                            </span>
                                            <input type="date"
                                                class="form-control-modern @error('tanggal') is-invalid @enderror"
                                                wire:model="tanggal" wire:change="updateTanggal">
                                        </div>
                                        @error('tanggal')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-clock"></i> Jam Pelaksanaan
                                            <span class="required-star">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <span class="input-icon">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <input type="time"
                                                class="form-control-modern @error('jam') is-invalid @enderror"
                                                wire:model="jam">
                                        </div>
                                        @error('jam')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Narasi Kegiatan -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header" style="padding: 1.25rem 1.5rem;">
                            <div class="card-title-academic">
                                <div class="header-icon" style="width: 40px; height: 40px;">
                                    <i class="fas fa-pen-alt" style="font-size: 1.2rem;"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Narasi Kegiatan</h5>
                            </div>
                        </div>
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-align-left"></i> Deskripsi Kegiatan
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon" style="top: 20px;">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <textarea class="form-control-modern @error('narasi') is-invalid @enderror" wire:model="narasi" rows="6"
                                        style="padding-top: 12px; resize: vertical;"
                                        placeholder="Tuliskan deskripsi kegiatan yang Anda lakukan hari ini..."></textarea>
                                </div>
                                <div class="field-hint">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Minimal 500 karakter, jelaskan kegiatan secara detail dan spesifik</span>
                                </div>
                                <div class="text-muted-small mt-1" style="text-align: right;">
                                    <i class="fas fa-keyboard"></i> {{ strlen($narasi ?? '') }} / Minimal 500 karakter
                                </div>
                                @error('narasi')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Upload Foto -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header" style="padding: 1.25rem 1.5rem;">
                            <div class="card-title-academic">
                                <div class="header-icon" style="width: 40px; height: 40px;">
                                    <i class="fas fa-camera" style="font-size: 1.2rem;"></i>
                                </div>
                                <h5 class="mb-0" style="font-weight: 600;">Dokumentasi Foto</h5>
                            </div>
                        </div>
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-image"></i> Upload Foto Kegiatan
                                    <span class="required-star">*</span>
                                </label>

                                <!-- Area Upload -->
                                <div x-data class="upload-area"
                                    style="border: 2px dashed #cbd5e1; border-radius: 16px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #f8fafc;"
                                    @click="$refs.fotoInput.click()"
                                    @dragover.prevent="$event.target.style.borderColor = '#2c7da0'"
                                    @dragleave.prevent="$event.target.style.borderColor = '#cbd5e1'">

                                    <input type="file" wire:model="foto" accept="image/*" style="display: none;"
                                        x-ref="fotoInput">

                                    <div wire:loading.remove wire:target="foto">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                                        <p style="color: #1e3a5f; font-weight: 500; margin-bottom: 0.5rem;">
                                            Klik atau drag & drop foto di sini
                                        </p>
                                        <small style="color: #94a3b8;">
                                            Format: JPG, PNG, JPEG (Max. 2MB)
                                        </small>
                                    </div>
                                    <div wire:loading wire:target="foto">
                                        <i class="fas fa-spinner fa-pulse"
                                            style="font-size: 2rem; color: #2c7da0;"></i>
                                        <p style="margin-top: 1rem;">Mengupload foto...</p>
                                    </div>
                                </div>

                                <!-- Preview Foto -->
                                @if ($foto && !is_string($foto))
                                    <div class="mt-3" style="position: relative; display: inline-block;">
                                        <div style="position: relative;">
                                            <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto"
                                                style="width: 200px; height: 150px; object-fit: cover; border-radius: 12px; border: 2px solid #e2e8f0;">
                                            <button type="button" wire:click="removeFoto"
                                                style="position: absolute; top: -10px; right: -10px; background: #ef4444; border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; transition: all 0.2s;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @elseif($foto && is_string($foto))
                                    <div class="mt-3" style="position: relative; display: inline-block;">
                                        <div style="position: relative;">
                                            <img src="{{ Storage::url($foto) }}" alt="Foto Kegiatan"
                                                style="width: 200px; height: 150px; object-fit: cover; border-radius: 12px; border: 2px solid #e2e8f0;">
                                            <button type="button" wire:click="removeFoto"
                                                style="position: absolute; top: -10px; right: -10px; background: #ef4444; border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; transition: all 0.2s;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @error('foto')
                                    <div class="error-message mt-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror

                                <div class="field-hint mt-2">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Upload foto dokumentasi kegiatan Anda (wajib diisi)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-body" style="padding: 1.5rem;">
                            <div style="display: flex; gap: 1rem; justify-content: flex-end; flex-wrap: wrap;">
                                <button type="button" class="btn-cancel" wire:click="back">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="button" class="btn-save" wire:click="saveLaporan"
                                    wire:loading.attr="disabled">
                                    <div wire:loading.remove wire:target="saveLaporan">
                                        <i class="fas fa-save"></i>
                                        {{ $isEditing ? 'Update Laporan' : 'Simpan Laporan' }}
                                    </div>
                                    <div wire:loading wire:target="saveLaporan">
                                        <i class="fas fa-spinner fa-pulse"></i> Menyimpan...
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
