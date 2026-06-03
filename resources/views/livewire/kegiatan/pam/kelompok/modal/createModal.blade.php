<div class="modal fade @if ($isModalOpen) show d-block @endif" tabindex="-1"
    style="@if ($isModalOpen) background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); @endif">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content academic-modal">
            <div class="modal-header academic-modal-header">
                <div class="modal-header-content">
                    <div class="modal-icon">
                        @if ($jenis == 'PAM')
                            <i class="fas fa-handshake"></i>
                        @else
                            <i class="fas fa-users"></i>
                        @endif
                    </div>
                    <div>
                        @if ($jenis == 'PAM')
                            <h5 class="modal-title">
                                {{ $kelompok_id ? 'Edit Data Mitra' : 'Tambah Mitra Baru' }}
                            </h5>
                            <p class="modal-subtitle">
                                {{ $kelompok_id ? 'Perbarui informasi mitra' : 'Isi formulir untuk menambahkan mitra baru' }}
                            </p>
                        @else
                            <h5 class="modal-title">
                                {{ $kelompok_id ? 'Edit Data Kelompok' : 'Tambah Kelompok Baru' }}
                            </h5>
                            <p class="modal-subtitle">
                                {{ $kelompok_id ? 'Perbarui informasi kelompok' : 'Isi formulir untuk menambahkan kelompok baru' }}
                            </p>
                        @endif
                    </div>
                </div>
                <button type="button" class="btn-close-modal" wire:click="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body academic-modal-body">
                <form wire:submit.prevent="store">
                    <!-- Nama Kelompok/Mitra -->
                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="fas fa-tag me-2"></i>
                            @if ($jenis == 'PAM')
                                Nama Mitra
                            @else
                                Nama Kelompok
                            @endif
                            <span class="required-star">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-building input-icon"></i>
                            <input type="text"
                                class="form-control-modern @error('nama_kelompok') is-invalid @enderror"
                                placeholder="{{ $jenis == 'PAM' ? 'Masukkan nama mitra...' : 'Masukkan nama kelompok...' }}"
                                wire:model.defer="nama_kelompok">
                            @error('nama_kelompok')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Periode
                            <span class="required-star">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar-week input-icon"></i>
                            <select class="form-control-modern @error('periode_id') is-invalid @enderror"
                                wire:model.defer="periode_id">
                                <option value="">Pilih Periode</option>
                                @foreach ($periodes as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kegiatan (disabled, hanya untuk info) -->
                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="fas fa-chalkboard-user me-2"></i>
                            Kegiatan
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-tasks input-icon"></i>
                            <select class="form-control-modern disabled-field" wire:model.defer="kegiatan_id" disabled>
                                <option value="">Pilih Kegiatan</option>
                                @foreach ($kegiatan as $k)
                                    <option value="{{ $k->id }}"
                                        {{ $k->nama_kegiatan == $jenis ? 'selected' : '' }}>
                                        {{ $k->nama_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="field-hint">
                                <i class="fas fa-info-circle"></i>
                                Kegiatan ditentukan berdasarkan tipe {{ $jenis }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer academic-modal-footer">
                <button type="button" class="btn-cancel" wire:click="closeModal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <button type="button" class="btn-save" wire:click="store">
                    <i class="fas fa-save me-2"></i>
                    {{ $kelompok_id ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>
        </div>
    </div>
</div>
