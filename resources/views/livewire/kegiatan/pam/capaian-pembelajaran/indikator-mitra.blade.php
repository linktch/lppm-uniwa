<div class="academic-card">
        {{-- Indikator From MITRA --}}
    <div class="academic-card-header">
        <div class="card-title-academic">
            <div class="header-icon-small">
                <i class="fas fa-chart-line"></i>
            </div>
            <h5 class="mb-0 fw-bold">Indikator Capaian Pembelajaran Mitra</h5>
        </div>
    </div>

    <div class="academic-card-body">

        <!-- Progress Bar -->
        @php
            $total = count($indikatorMitra);
            $done = collect($indikatorMitra)->where('sudah_dinilai', true)->count();
            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
        @endphp

        <div class="progress-section">
            <div class="progress-header">
                <small class="progress-label">Progress Penilaian</small>
                <small class="progress-stats">{{ $done }}/{{ $total }}
                    ({{ $percent }}%)</small>
            </div>

            <div class="progress-bar-wrapper">
                <div class="progress-bar-custom 
                                        {{ $percent >= 80 ? 'bg-success' : ($percent >= 50 ? 'bg-warning' : 'bg-danger') }}"
                    style="width: {{ $percent }}%">
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive-modern desktop-view">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="65%">Indikator</th>
                        <th width="30%" class="text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indikatorMitra as $index => $item)
                        @php
                            $currentValue = $nilai[$item['id']] ?? ($item['progres'] ?? '');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="indikator-text">{{ $item['indikator'] }}</td>
                            <td class="text-center">
                                <select wire:change="updateNilai({{ $item['id'] }}, $event.target.value)"
                                    class="form-select-custom
                                                            {{ $currentValue == 'tercapai' ? 'select-success' : '' }}
                                                            {{ $currentValue == 'belum_tercapai' ? 'select-danger' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="tercapai" {{ $currentValue == 'tercapai' ? 'selected' : '' }}>✔
                                        Tercapai</option>
                                    <option value="belum_tercapai"
                                        {{ $currentValue == 'belum_tercapai' ? 'selected' : '' }}>✘
                                        Belum tercapai</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Belum ada indikator</p>
                                    <small>Silakan tambahkan indikator terlebih dahulu</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- INDIKATOR DARI PRODI --}}
        <!-- Mobile Card View -->
        <div class="mobile-view">
            @forelse($indikatorMitra as $index => $item)
                @php
                    $currentValue = $nilai[$item['id']] ?? ($item['progres'] ?? '');
                @endphp
                <div class="mobile-indikator-card">
                    <div class="mobile-card-number">{{ $index + 1 }}</div>
                    <div class="mobile-card-content">
                        <div class="mobile-indikator-text">
                            {{ $item['indikator'] }}
                        </div>
                        <div class="mobile-select-wrapper">
                            <select wire:change="updateNilai({{ $item['id'] }}, $event.target.value)"
                                class="form-select-custom mobile-select
                                                        {{ $currentValue == 'tercapai' ? 'select-success' : '' }}
                                                        {{ $currentValue == 'belum_tercapai' ? 'select-danger' : '' }}">
                                <option value="">-- Pilih Nilai --</option>
                                <option value="tercapai" {{ $currentValue == 'tercapai' ? 'selected' : '' }}>
                                    ✔ Tercapai
                                </option>
                                <option value="belum_tercapai"
                                    {{ $currentValue == 'belum_tercapai' ? 'selected' : '' }}>
                                    ✘ Belum tercapai
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Belum ada indikator</p>
                    <small>Silakan tambahkan indikator terlebih dahulu</small>
                </div>
            @endforelse
        </div>

        <!-- Loading Indicator -->
        <div wire:loading wire:target="updateNilai" class="loading-indicator">
            <div class="loading-spinner"></div>
            <small class="text-muted">Menyimpan...</small>
        </div>

    </div>
    {{-- Indikator From Prodi --}}
    <div class="academic-card-header">
        <div class="card-title-academic">
            <div class="header-icon-small">
                <i class="fas fa-chart-line"></i>
            </div>
            <h5 class="mb-0 fw-bold">Indikator Capaian Pembelajaran Prodi</h5>
        </div>
    </div>

    <div class="academic-card-body">

        <!-- Progress Bar -->
        @php
            $total = count($indikatorProdi);
            $done = collect($indikatorProdi)->where('sudah_dinilai', true)->count();
            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
        @endphp

        <div class="progress-section">
            <div class="progress-header">
                <small class="progress-label">Progress Penilaian</small>
                <small class="progress-stats">{{ $done }}/{{ $total }}
                    ({{ $percent }}%)</small>
            </div>

            <div class="progress-bar-wrapper">
                <div class="progress-bar-custom 
                                        {{ $percent >= 80 ? 'bg-success' : ($percent >= 50 ? 'bg-warning' : 'bg-danger') }}"
                    style="width: {{ $percent }}%">
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive-modern desktop-view">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="65%">Indikator</th>
                        <th width="30%" class="text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indikatorProdi as $index => $item)
                        @php
                            $currentValue = $nilai[$item['id']] ?? ($item['progres'] ?? '');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="indikator-text">{{ $item['indikator'] }}</td>
                            <td class="text-center">
                                <select disabled wire:change="updateNilai({{ $item['id'] }}, $event.target.value)"
                                    class="form-select-custom
                                                            {{ $currentValue == 'tercapai' ? 'select-success' : '' }}
                                                            {{ $currentValue == 'belum_tercapai' ? 'select-danger' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="tercapai" {{ $currentValue == 'tercapai' ? 'selected' : '' }}>✔
                                        Tercapai</option>
                                    <option value="belum_tercapai"
                                        {{ $currentValue == 'belum_tercapai' ? 'selected' : '' }}>✘
                                        Belum tercapai</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Belum ada indikator</p>
                                    <small>Silakan tambahkan indikator terlebih dahulu</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- INDIKATOR DARI PRODI --}}
        <!-- Mobile Card View -->
        <div class="mobile-view">
            @forelse($indikatorProdi as $index => $item)
                @php
                    $currentValue = $nilai[$item['id']] ?? ($item['progres'] ?? '');
                @endphp
                <div class="mobile-indikator-card">
                    <div class="mobile-card-number">{{ $index + 1 }}</div>
                    <div class="mobile-card-content">
                        <div class="mobile-indikator-text">
                            {{ $item['indikator'] }}
                        </div>
                        <div class="mobile-select-wrapper">
                            <select disabled wire:change="updateNilai({{ $item['id'] }}, $event.target.value)"
                                class="form-select-custom mobile-select
                                                        {{ $currentValue == 'tercapai' ? 'select-success' : '' }}
                                                        {{ $currentValue == 'belum_tercapai' ? 'select-danger' : '' }}">
                                <option value="">-- Pilih Nilai --</option>
                                <option value="tercapai" {{ $currentValue == 'tercapai' ? 'selected' : '' }}>
                                    ✔ Tercapai
                                </option>
                                <option value="belum_tercapai"
                                    {{ $currentValue == 'belum_tercapai' ? 'selected' : '' }}>
                                    ✘ Belum tercapai
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Belum ada indikator</p>
                    <small>Silakan tambahkan indikator terlebih dahulu</small>
                </div>
            @endforelse
        </div>

        <!-- Loading Indicator -->
        <div wire:loading wire:target="updateNilai" class="loading-indicator">
            <div class="loading-spinner"></div>
            <small class="text-muted">Menyimpan...</small>
        </div>

    </div>


</div>
