<div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Dashboard Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div class="dashboard-header-flex">
                                <div class="dashboard-header-left">
                                    <div class="header-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-600 text-primary">Dashboard Mahasiswa</h4>
                                        <p class="mb-0 text-muted-small">PAM</p>
                                    </div>
                                </div>
                                @if(Auth()->User()->role == 'mahasiswa')
                                <button class="btn-create-academic" wire:click="tambahLaporanPage">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah Laporan
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Card -->
            <div class="row">
                <div class="col-12">
                    <div class="academic-card info-card">
                        <div class="academic-card-body p-0">
                            <!-- Row 1: Periode + Mitra -->
                            <div class="info-grid-2cols">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-alt"></i> Periode
                                    </div>
                                    <div class="info-value">
                                        <strong>{{ $periode->nama_periode ?? '-' }}</strong>
                                        @if (($periode->status ?? '') == 'AKTIF')
                                            <span class="status-badge status-aktif">AKTIF</span>
                                        @else
                                            <span
                                                class="status-badge status-nonaktif">{{ $periode->status ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-divider"></div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-handshake"></i>
                                        @if (Auth()->User()->role == 'mitra')
                                            Mitra
                                        @else
                                            Prodi
                                        @endif
                                    </div>
                                    <div class="info-value">
                                        @php
                                            $prodi = \App\Models\ProdiFakultas::where(
                                                'id_prodi',
                                                Auth()->User()->id_prodi,
                                            )->first();
                                            // dd($prodi);
                                        @endphp
                                        @if (Auth()->User()->role == 'mitra')
                                            <strong>{{ $this->kelompokUser->kelompok->nama_kelompok ?? '-' }}</strong>
                                        @elseif(Auth()->User()->role == 'prodi')
                                            <strong>
                                                {{ $prodi->nama_program_studi }}
                                            </strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if (Auth()->user()->role == 'mahasiswa')
                                <!-- Row 2: Mahasiswa -->
                                <div class="info-mahasiswa-section">
                                    <div class="info-item-full">
                                        <div class="info-label mb-2">
                                            <i class="fas fa-user-graduate"></i> Data Mahasiswa
                                        </div>
                                        <div class="mahasiswa-grid">
                                            <div class="mahasiswa-field">
                                                <div class="field-label">
                                                    <i class="fas fa-user"></i> Nama
                                                </div>
                                                <div class="field-value">
                                                    {{ $mahasiswa['nama_mahasiswa'] ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="mahasiswa-field">
                                                <div class="field-label">
                                                    <i class="fas fa-id-card"></i> NIM
                                                </div>
                                                <div class="field-value">
                                                    <span class="nim-badge">{{ $mahasiswa['nim'] ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="mahasiswa-field">
                                                <div class="field-label">
                                                    <i class="fas fa-graduation-cap"></i> Program Studi
                                                </div>
                                                <div class="field-value">
                                                    <span
                                                        class="prodi-badge">{{ $mahasiswa['nama_program_studi'] ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Laporan Mahasiswa -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="academic-card">
                        <div class="academic-card-header">
                            <div class="filter-section">
                                <div class="row g-3 align-items-end">

                                    <!-- PERIODE -->
                                    <div class="col-md-3">
                                        <label class="filter-label">
                                            <i class="fas fa-calendar-week me-1"></i> Periode
                                        </label>
                                        <select wire:model.live="periodeFilter" class="filter-select">
                                            @foreach($periodes as $p)
                                                <option value="{{ $p->id }}">
                                                    {{ $p->nama_periode ?? 'Periode '.$p->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(!in_array(auth()->user()->role, ['mitra', 'mahasiswa', 'prodi']))
                                        <div class="col-md-3">
                                            <label class="filter-label">
                                                <i class="fas fa-book me-1"></i> Prodi
                                            </label>

                                            <select wire:model.live="filterProdi" class="filter-select">
                                                <option value="">Semua Prodi</option>

                                                @foreach($prodis as $prodi)
                                                    <option value="{{ $prodi->id_prodi }}">
                                                        {{ $prodi->nama_program_studi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    

                                    <!-- SEARCH -->
                                    <div class="col-md-3 ms-auto">
                                        <label class="filter-label">
                                            <i class="fas fa-search me-1"></i> Pencarian
                                        </label>
                                        <div class="search-wrapper">
                                            <i class="fas fa-search search-icon"></i>
                                            <input 
                                                type="text" 
                                                wire:model.live="search" 
                                                class="search-input" 
                                                placeholder="Cari nama / NIM"
                                            >
                                            @if($search)
                                                <button wire:click="$set('search', '')" class="search-clear">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-header-flex">
                                <div class="card-title-academic">
                                    <div class="header-icon-small">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h5 class="mb-0 fw-600">Laporan Mahasiswa</h5>
                                </div>
                                <div class="total-badge">
                                    <i class="fas fa-chart-line"></i>
                                    Total: {{ count($laporans) }} Laporan
                                </div>
                            </div>
                        </div>
                        <div class="academic-card-body p-0">
                            <!-- Tabel Desktop -->
                            <div class="table-responsive-modern desktop-view">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Data Mahasiswa</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="12%">Status</th>
                                            <th width="18%">Keterangan</th>
                                            <th width="15%">Mitra</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($laporans as $index => $laporan)
                                            @php
                                                $mahasiswaData = is_string($laporan->user->data_mahasiswa ?? null)
                                                    ? json_decode($laporan->user->data_mahasiswa, true)
                                                    : $laporan->user->data_mahasiswa ?? [];
                                            @endphp
                                            <tr class="data-row">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="mahasiswa-cell">
                                                        <span class="nim-text">{{ $mahasiswaData['nim'] ?? '-' }}</span>
                                                        <span
                                                            class="nama-text">{{ $mahasiswaData['nama_mahasiswa'] ?? '-' }}</span>
                                                        <span
                                                            class="prodi-text">{{ $mahasiswaData['nama_program_studi'] ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="tanggal-cell">
                                                        <span
                                                            class="tanggal-date">{{ $laporan['tanggal']->format('d/m/Y') }}</span>
                                                        <span
                                                            class="tanggal-time">{{ $laporan['tanggal']->format('H:i') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusMap = [
                                                            'draft' => [
                                                                'class' => 'status-draft',
                                                                'text' => 'Draft',
                                                                'icon' => 'fa-pen-fancy',
                                                            ],
                                                            'submitted' => [
                                                                'class' => 'status-submitted',
                                                                'text' => 'Submitted',
                                                                'icon' => 'fa-paper-plane',
                                                            ],
                                                            'revisi' => [
                                                                'class' => 'status-revisi',
                                                                'text' => 'Revisi',
                                                                'icon' => 'fa-undo-alt',
                                                            ],
                                                            'approved' => [
                                                                'class' => 'status-approved',
                                                                'text' => 'Approved',
                                                                'icon' => 'fa-check-circle',
                                                            ],
                                                        ];
                                                        $status =
                                                            $statusMap[$laporan['status'] ?? 'draft'] ??
                                                            $statusMap['draft'];
                                                    @endphp
                                                    <span class="status-badge {{ $status['class'] }}">
                                                        <i class="fas {{ $status['icon'] }}"></i>
                                                        {{ $status['text'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if (!empty($laporan['keterangan_revisi']))
                                                        <span class="revisi-note">
                                                            <i class="fas fa-comment-dots"></i>
                                                            {{ Str::limit($laporan['keterangan_revisi'], 30) }}
                                                        </span>
                                                    @else
                                                        <span class="empty-note">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="mitra-name">{{ $laporan->kelompok->nama_kelompok ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <div class="action-group">
                                                        <a wire:navigate
                                                            href="{{ route('kegiatan.pam.laporanharian.view', ['role' => auth()->user()->role, 'lapharID' => $laporan->id]) }}"
                                                            class="action-btn action-view" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="tooltip-text">Lihat Detail</span>
                                                        </a>

                                                        @if (in_array($laporan['status_laporan'] ?? '', ['draft', 'revisi']))
                                                            <button class="action-btn action-edit"
                                                                wire:click="editLaporan({{ $laporan['id'] }})"
                                                                title="Edit Laporan">
                                                                <i class="fas fa-edit"></i>
                                                                <span class="tooltip-text">Edit Laporan</span>
                                                            </button>
                                                        @endif

                                                        @if (($laporan['status_laporan'] ?? '') == 'draft')
                                                            <button class="action-btn action-delete"
                                                                wire:click="deleteLaporan({{ $laporan['id'] }})"
                                                                wire:confirm="Apakah Anda yakin ingin menghapus laporan ini?"
                                                                title="Hapus Laporan">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <span class="tooltip-text">Hapus Laporan</span>
                                                            </button>
                                                            <button class="action-btn action-submit"
                                                                wire:click="submitLaporan({{ $laporan['id'] }})"
                                                                title="Submit Laporan">
                                                                <i class="fas fa-paper-plane"></i>
                                                                <span class="tooltip-text">Submit ke Dosen</span>
                                                            </button>
                                                        @endif

                                                        @if (($laporan['status_laporan'] ?? '') == 'revisi')
                                                            <button class="action-btn action-revisi"
                                                                wire:click="revisiUlang({{ $laporan['id'] }})"
                                                                title="Revisi Ulang">
                                                                <i class="fas fa-edit"></i>
                                                                <span class="tooltip-text">Revisi Ulang</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="7">
                                                    <div class="empty-state">
                                                        <i class="fas fa-file-alt"></i>
                                                        <p>Belum ada laporan</p>
                                                        @if(Auth()->User()->role == 'mahasiswa')
                                                        <small>Klik tombol "Tambah Laporan" untuk membuat laporan
                                                            harian</small>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-view">
                                @forelse ($laporans as $index => $laporan)
                                    @php
                                        $mahasiswaData = is_string($laporan->user->data_mahasiswa ?? null)
                                            ? json_decode($laporan->user->data_mahasiswa, true)
                                            : $laporan->user->data_mahasiswa ?? [];
                                        $statusMap = [
                                            'draft' => [
                                                'class' => 'status-draft',
                                                'text' => 'Draft',
                                                'icon' => 'fa-pen-fancy',
                                            ],
                                            'submitted' => [
                                                'class' => 'status-submitted',
                                                'text' => 'Submitted',
                                                'icon' => 'fa-paper-plane',
                                            ],
                                            'revisi' => [
                                                'class' => 'status-revisi',
                                                'text' => 'Revisi',
                                                'icon' => 'fa-undo-alt',
                                            ],
                                            'approved' => [
                                                'class' => 'status-approved',
                                                'text' => 'Approved',
                                                'icon' => 'fa-check-circle',
                                            ],
                                        ];
                                        $status = $statusMap[$laporan['status'] ?? 'draft'] ?? $statusMap['draft'];
                                    @endphp
                                    <div class="mobile-laporan-card">
                                        <div class="mobile-card-header">
                                            <div class="mobile-header-left">
                                                <div class="mobile-avatar">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="mobile-header-info">
                                                    <div class="mobile-title">Laporan #{{ $index + 1 }}</div>
                                                    <div class="mobile-date">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        {{ $laporan['tanggal']->format('d/m/Y') }}
                                                        <span
                                                            class="mobile-time">{{ $laporan['tanggal']->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="status-badge {{ $status['class'] }} mobile-status">
                                                <i class="fas {{ $status['icon'] }}"></i> {{ $status['text'] }}
                                            </span>
                                        </div>

                                        <div class="mobile-card-body">
                                            <div class="mobile-info-row">
                                                <span class="info-label-mobile"><i class="fas fa-user"></i>
                                                    Mahasiswa:</span>
                                                <span
                                                    class="info-value-mobile">{{ $mahasiswaData['nama_mahasiswa'] ?? '-' }}</span>
                                            </div>
                                            <div class="mobile-info-row">
                                                <span class="info-label-mobile"><i class="fas fa-id-card"></i>
                                                    NIM:</span>
                                                <span
                                                    class="info-value-mobile nim-text">{{ $mahasiswaData['nim'] ?? '-' }}</span>
                                            </div>
                                            <div class="mobile-info-row">
                                                <span class="info-label-mobile"><i class="fas fa-graduation-cap"></i>
                                                    Prodi:</span>
                                                <span
                                                    class="info-value-mobile">{{ $mahasiswaData['nama_program_studi'] ?? '-' }}</span>
                                            </div>
                                            <div class="mobile-info-row">
                                                <span class="info-label-mobile"><i class="fas fa-building"></i>
                                                    Mitra:</span>
                                                <span
                                                    class="info-value-mobile">{{ $laporan->kelompok->nama_kelompok ?? '-' }}</span>
                                            </div>
                                            @if (!empty($laporan['keterangan_revisi']))
                                                <div class="mobile-info-row">
                                                    <span class="info-label-mobile"><i
                                                            class="fas fa-comment-dots"></i> Keterangan:</span>
                                                    <span
                                                        class="info-value-mobile revisi-text">{{ $laporan['keterangan_revisi'] }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mobile-card-actions">
                                            <a wire:navigate
                                                href="{{ route('kegiatan.pam.laporanharian.view', ['role' => auth()->user()->role, 'lapharID' => $laporan->id]) }}"
                                                class="mobile-action-btn mobile-view">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if (in_array($laporan['status_laporan'] ?? '', ['draft', 'revisi']))
                                                <button class="mobile-action-btn mobile-edit"
                                                    wire:click="editLaporan({{ $laporan['id'] }})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @endif
                                            @if (($laporan['status_laporan'] ?? '') == 'draft')
                                                <button class="mobile-action-btn mobile-delete"
                                                    wire:click="deleteLaporan({{ $laporan['id'] }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus laporan ini?">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                                <button class="mobile-action-btn mobile-submit"
                                                    wire:click="submitLaporan({{ $laporan['id'] }})">
                                                    <i class="fas fa-paper-plane"></i> Submit
                                                </button>
                                            @endif
                                            @if (($laporan['status_laporan'] ?? '') == 'revisi')
                                                <button class="mobile-action-btn mobile-revisi"
                                                    wire:click="revisiUlang({{ $laporan['id'] }})">
                                                    <i class="fas fa-edit"></i> Revisi
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state-mobile">
                                        <i class="fas fa-file-alt"></i>
                                        <p>Belum ada laporan</p>
                                        @if(Auth()->User()->role == 'mahasiswa')
                                        <small>Klik tombol "Tambah Laporan" untuk membuat laporan harian</small>
                                        @endif
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ========== UTILITIES ========== */
        .fw-600 {
            font-weight: 600;
        }

        .text-primary {
            color: #1e3a5f;
        }

        .text-muted-small {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .me-1 {
            margin-right: 0.25rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .p-0 {
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        /* ========== FLEX & GRID ========== */
        .dashboard-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .dashboard-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .info-grid-2cols {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #eef2f6;
        }

        .info-item {
            flex: 1;
            padding: 1.25rem;
            min-width: 180px;
        }

        .info-divider {
            width: 1px;
            background: #eef2f6;
        }

        .info-item-full {
            padding: 1.25rem;
        }

        .mahasiswa-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 8px;
        }

        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title-academic {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* ========== CARD STYLES ========== */
        .academic-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .academic-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eef2f6;
        }

        .academic-card-body {
            padding: 1rem 1.5rem;
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

        /* ========== INFO STYLES ========== */
        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
            margin-bottom: 8px;
        }

        .info-label i {
            margin-right: 4px;
        }

        .info-value {
            font-size: 1rem;
        }

        .field-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
            margin-bottom: 4px;
        }

        .field-label i {
            margin-right: 4px;
            font-size: 0.65rem;
        }

        .field-value {
            font-weight: 500;
            color: #1e3a5f;
            word-break: break-word;
        }

        /* ========== BADGES ========== */
        .nim-badge,
        .nim-text {
            font-family: monospace;
            background: #f1f3f5;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e3a5f;
            display: inline-block;
        }

        .prodi-badge {
            background: #e6f0fa;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e4a76;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .total-badge {
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #1e3a5f;
        }

        .total-badge i {
            margin-right: 6px;
            color: #2c7da0;
        }

        .mitra-name {
            font-weight: 500;
            color: #1e3a5f;
        }

        /* ========== STATUS BADGES ========== */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        .status-aktif {
            background: #d4edda;
            color: #155724;
        }

        .status-nonaktif {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ========== TABLE STYLES ========== */
        .table-responsive-modern {
            overflow-x: auto;
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

        .data-row:hover {
            background: #fafcff;
        }

        /* ========== CELL STYLES ========== */
        .mahasiswa-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nama-text {
            font-weight: 600;
            color: #1e3a5f;
            font-size: 0.85rem;
        }

        .prodi-text {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .tanggal-cell {
            display: flex;
            flex-direction: column;
        }

        .tanggal-date {
            font-weight: 600;
            color: #1e3a5f;
        }

        .tanggal-time {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .revisi-note {
            font-size: 0.75rem;
            color: #d97706;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #fffbeb;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .empty-note {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* ========== ACTION BUTTONS ========== */
        .btn-create-academic {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .btn-create-academic:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            position: relative;
        }

        .action-btn.action-view {
            background: #e8f0fe;
            color: #2c7da0;
        }

        .action-btn.action-view:hover {
            background: #2c7da0;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.action-edit {
            background: #e8f0fe;
            color: #1e3a5f;
        }

        .action-btn.action-edit:hover {
            background: #1e3a5f;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.action-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.action-delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.action-submit {
            background: #e0f2fe;
            color: #0284c7;
        }

        .action-btn.action-submit:hover {
            background: #0284c7;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.action-revisi {
            background: #fef3c7;
            color: #d97706;
        }

        .action-btn.action-revisi:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .tooltip-text {
            visibility: hidden;
            background-color: #1e293b;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 5px 10px;
            position: absolute;
            z-index: 10;
            bottom: 130%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.7rem;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .action-btn:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state,
        .empty-state-mobile {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-state i,
        .empty-state-mobile i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .empty-state p,
        .empty-state-mobile p {
            margin-bottom: 4px;
            font-weight: 500;
        }

        .empty-state small,
        .empty-state-mobile small {
            font-size: 0.7rem;
        }

        /* ========== MOBILE STYLES ========== */
        .desktop-view {
            display: block;
        }

        .mobile-view {
            display: none;
        }

        .mobile-laporan-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f8;
            overflow: hidden;
        }

        .mobile-card-header {
            padding: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 1px solid #eef2f6;
        }

        .mobile-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .mobile-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .mobile-header-info {
            flex: 1;
        }

        .mobile-title {
            font-weight: 700;
            font-size: 0.85rem;
            color: #1e3a5f;
            margin-bottom: 4px;
        }

        .mobile-date {
            font-size: 0.65rem;
            color: #6c757d;
        }

        .mobile-date i {
            margin-right: 4px;
        }

        .mobile-time {
            margin-left: 6px;
            font-family: monospace;
        }

        .mobile-status {
            font-size: 0.65rem;
            white-space: nowrap;
        }

        .mobile-card-body {
            padding: 14px;
            background: #fafcff;
        }

        .mobile-info-row {
            display: flex;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 4px;
        }

        .info-label-mobile {
            font-size: 0.65rem;
            font-weight: 600;
            color: #4a6fa5;
            min-width: 80px;
        }

        .info-value-mobile {
            font-size: 0.75rem;
            color: #1e3a5f;
            flex: 1;
            word-break: break-word;
        }

        .revisi-text {
            color: #d97706;
            background: #fffbeb;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .mobile-card-actions {
            display: flex;
            gap: 8px;
            padding: 12px 14px;
            background: white;
            border-top: 1px solid #eef2f6;
            flex-wrap: wrap;
        }

        .mobile-action-btn {
            flex: 1;
            background: #f1f5f9;
            border: none;
            border-radius: 30px;
            padding: 8px 12px;
            font-size: 0.7rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
            color: #1e293b;
        }

        .mobile-action-btn.mobile-view {
            background: #e8f0fe;
            color: #2c7da0;
        }

        .mobile-action-btn.mobile-view:hover {
            background: #2c7da0;
            color: white;
        }

        .mobile-action-btn.mobile-edit {
            background: #e8f0fe;
            color: #1e3a5f;
        }

        .mobile-action-btn.mobile-edit:hover {
            background: #1e3a5f;
            color: white;
        }

        .mobile-action-btn.mobile-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .mobile-action-btn.mobile-delete:hover {
            background: #dc2626;
            color: white;
        }

        .mobile-action-btn.mobile-submit {
            background: #e0f2fe;
            color: #0284c7;
        }

        .mobile-action-btn.mobile-submit:hover {
            background: #0284c7;
            color: white;
        }

        .mobile-action-btn.mobile-revisi {
            background: #fef3c7;
            color: #d97706;
        }

        .mobile-action-btn.mobile-revisi:hover {
            background: #d97706;
            color: white;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .mahasiswa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .desktop-view {
                display: none !important;
            }

            .mobile-view {
                display: block !important;
            }

            .mahasiswa-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .info-grid-2cols {
                flex-direction: column;
            }

            .info-divider {
                display: none;
            }

            .info-item {
                border-bottom: 1px solid #eef2f6;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .dashboard-header-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .dashboard-header-left {
                justify-content: center;
            }

            .btn-create-academic {
                width: 100%;
                justify-content: center;
            }

            .card-header-flex {
                flex-direction: column;
                align-items: flex-start;
            }

            .academic-card-body {
                padding: 0 1rem 1rem 1rem;
            }

            .academic-card-header {
                padding: 1rem;
            }

            .action-group {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .mobile-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .mobile-status {
                align-self: flex-start;
            }

            .mobile-card-actions {
                flex-direction: column;
            }

            .mobile-action-btn {
                width: 100%;
            }

            .academic-table th,
            .academic-table td {
                padding: 8px;
                font-size: 0.75rem;
            }
        }
    </style>
</div>
