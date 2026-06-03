<div>

    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="academic-page-title">
                            <i class="fas fa-fingerprint mr-2"></i> Presensi {{ $jenis }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb academic-breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Presensi {{ $jenis }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Informasi Kelompok - 1 baris kecil -->
                <div class="info-panel mb-4" style="padding: 0.75rem 1.25rem;">
                    <div class="row align-items-center">
                        <div class="col-md-3 col-6">
                            <div class="info-item-academic">
                                <div class="info-label">
                                    <i class="fas fa-tag"></i> Kelompok
                                </div>
                                <div class="info-value" style="font-size: 0.9rem;">
                                    <strong>{{ $kelompok->nama_kelompok ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="info-item-academic">
                                <div class="info-label">
                                    <i class="fas fa-code-branch"></i> Kode
                                </div>
                                <div class="info-value" style="font-size: 0.9rem;">
                                    <span class="badge-academic" style="background: #e8f0fe; font-family: monospace;">
                                        {{ $kelompok->kode_kelompok ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mt-2 mt-md-0">
                            <div class="info-item-academic">
                                <div class="info-label">
                                    <i class="fas fa-chalkboard-user"></i> Dosen Pembimbing
                                </div>
                                <div class="info-value" style="font-size: 0.9rem;">
                                    <i class="fas fa-user-check" style="color: #2c7da0; margin-right: 6px;"></i>
                                    {{ $kelompok->dosen_pembimbing ?? 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2 mt-md-0">
                            <div class="info-item-academic">
                                <div class="info-label">
                                    <i class="fas fa-calendar-alt"></i> Periode
                                </div>
                                <div class="info-value" style="font-size: 0.9rem;">
                                    <span class="period-badge" style="padding: 2px 10px; font-size: 0.75rem;">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ $kelompok->periode->nama_periode ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Daftar Mahasiswa & Presensi -->
                <div class="academic-card">
                    <div class="academic-card-header"
                        style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-bottom: 2px solid #e9edf2;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                            <div class="card-title-academic alternate" style="color: #1e3a5f; margin: 0;">
                                <div class="header-icon alternate"
                                    style="background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                Daftar Mahasiswa & Presensi
                            </div>
                            <div class="total-badge">
                                <i class="fas fa-users"></i> Total: {{ $mahasiswas->total() }} Mahasiswa
                            </div>
                        </div>
                    </div>

                    <div class="academic-card-body p-0">
                        @if ($mahasiswas->isEmpty())
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash" style="font-size: 3rem; color: #cbd5e1;"></i>
                                    <p class="mt-3 mb-1" style="font-weight: 500; color: #64748b;">Belum ada mahasiswa
                                    </p>
                                    <small style="color: #94a3b8;">Kelompok ini belum memiliki anggota</small>
                                </div>
                            </div>
                        @else
                            <div class="academic-card-body">
                                <!-- Kolom Search -->
                                <div class="filter-section" style="margin-bottom: 1.5rem;">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="filter-label">
                                                <i class="fas fa-filter"></i> Filter Data
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="search-wrapper">
                                                <i class="fas fa-search search-icon"></i>
                                                <input type="text" class="search-input"
                                                    placeholder="Cari mahasiswa berdasarkan nama atau NIM..."
                                                    wire:model.live="search">
                                                <button class="search-clear" wire:click="$set('search', '')"
                                                    style="display: {{ $search ? 'block' : 'none' }};">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Table (hidden on mobile) -->
                                <div class="table-responsive-modern desktop-view">
                                    <table class="academic-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="12%">NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th width="20%">Program Studi</th>
                                                <th width="25%" class="text-center">Status Presensi</th>
                                                @if(Auth()->User()->role == "mitra")
                                                <th width="20%" class="text-center">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $index => $user)
                                                @php
                                                    $status = $user['status'] ?? 'belum';
                                                    $statusClass = '';
                                                    $statusText = '';

                                                    if ($status == 'hadir') {
                                                        $statusClass = 'status-hadir';
                                                        $statusText = 'Hadir';
                                                    } elseif ($status == 'izin') {
                                                        $statusClass = 'status-izin';
                                                        $statusText = 'Izin';
                                                    } elseif ($status == 'alpha') {
                                                        $statusClass = 'status-alpha';
                                                        $statusText = 'Alpha';
                                                    } else {
                                                        $statusClass = 'status-belum';
                                                        $statusText = 'Belum Presensi';
                                                    }
                                                @endphp
                                                <tr class="data-row">
                                                    <td class="text-center">
                                                        <span class="badge-academic">
                                                            {{ $mahasiswas->firstItem() + $index }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="nim">
                                                            {{ $user['nim'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="entity-info">
                                                            <i class="fas fa-user"
                                                                style="color:#2c7da0; margin-right:8px;"></i>
                                                            {{ $user['name'] ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="role-badge">
                                                            <i class="fas fa-graduation-cap"></i>
                                                            {{ $user['prodi'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <span class="status-badge {{ $statusClass }}">
                                                            <i
                                                                class="fas 
                                                            {{ $status == 'hadir' ? 'fa-check-circle' : '' }}
                                                            {{ $status == 'izin' ? 'fa-clock' : '' }}
                                                            {{ $status == 'alpha' ? 'fa-times-circle' : '' }}
                                                            {{ $status == 'belum' ? 'fa-hourglass-half' : '' }}">
                                                            </i>
                                                            {{ $statusText }}
                                                        </span>
                                                    </td>
                                                    @if(Auth()->User()->role == "mitra")
                                                    <td class="text-center">
                                                        <div class="action-group justify-content-center">
                                                            <button class="action-btn action-view"
                                                                wire:click="presensiHadir({{ $user['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                {{ $status == 'hadir' ? 'disabled' : '' }}>
                                                                <i class="fas fa-check-circle"></i>
                                                                <span class="tooltip-text">Hadir</span>
                                                            </button>

                                                            <button class="action-btn action-edit"
                                                                wire:click="presensiIzin({{ $user['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                {{ $status == 'izin' ? 'disabled' : '' }}>
                                                                <i class="fas fa-clock"></i>
                                                                <span class="tooltip-text">Izin</span>
                                                            </button>

                                                            <button class="action-btn action-delete"
                                                                wire:click="presensiAlpha({{ $user['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                {{ $status == 'alpha' ? 'disabled' : '' }}>
                                                                <i class="fas fa-times-circle"></i>
                                                                <span class="tooltip-text">Alpha</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Card View (only visible on mobile) -->
                                <div class="mobile-view">
                                    @foreach ($users as $index => $user)
                                        @php
                                            $status = $user['status'] ?? 'belum';
                                            $statusClass = '';
                                            $statusText = '';

                                            if ($status == 'hadir') {
                                                $statusClass = 'status-hadir';
                                                $statusText = 'Hadir';
                                            } elseif ($status == 'izin') {
                                                $statusClass = 'status-izin';
                                                $statusText = 'Izin';
                                            } elseif ($status == 'alpha') {
                                                $statusClass = 'status-alpha';
                                                $statusText = 'Alpha';
                                            } else {
                                                $statusClass = 'status-belum';
                                                $statusText = 'Belum Presensi';
                                            }
                                        @endphp
                                        <div class="mobile-presensi-card">
                                            <div class="mobile-card-header">
                                                <div class="mobile-user-info">
                                                    <div class="mobile-avatar">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </div>
                                                    <div class="mobile-user-detail">
                                                        <div class="mobile-user-name">
                                                            {{ $user['name'] ?? '-' }}
                                                        </div>
                                                        <div class="mobile-user-nim">
                                                            <i class="fas fa-id-card"></i> {{ $user['nim'] ?? '-' }}
                                                        </div>
                                                        <div class="mobile-user-prodi">
                                                            <i class="fas fa-graduation-cap"></i> {{ $user['prodi'] ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mobile-status">
                                                    <span class="status-badge {{ $statusClass }}">
                                                        <i class="fas 
                                                            {{ $status == 'hadir' ? 'fa-check-circle' : '' }}
                                                            {{ $status == 'izin' ? 'fa-clock' : '' }}
                                                            {{ $status == 'alpha' ? 'fa-times-circle' : '' }}
                                                            {{ $status == 'belum' ? 'fa-hourglass-half' : '' }}">
                                                        </i>
                                                        {{ $statusText }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mobile-card-actions">
                                                <button class="mobile-action-btn mobile-hadir"
                                                    wire:click="presensiHadir({{ $user['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    {{ $status == 'hadir' ? 'disabled' : '' }}>
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>Hadir</span>
                                                </button>
                                                <button class="mobile-action-btn mobile-izin"
                                                    wire:click="presensiIzin({{ $user['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    {{ $status == 'izin' ? 'disabled' : '' }}>
                                                    <i class="fas fa-clock"></i>
                                                    <span>Izin</span>
                                                </button>
                                                <button class="mobile-action-btn mobile-alpha"
                                                    wire:click="presensiAlpha({{ $user['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    {{ $status == 'alpha' ? 'disabled' : '' }}>
                                                    <i class="fas fa-times-circle"></i>
                                                    <span>Alpha</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer dengan Pagination -->
                    <div class="pagination-modern-wrapper" style="border-top: 1px solid #eef2f6;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                            <div class="pagination-info">
                                <i class="fas fa-chart-bar"></i> Menampilkan
                                <strong>{{ $mahasiswas->firstItem() }}</strong> -
                                <strong>{{ $mahasiswas->lastItem() }}</strong>
                                dari <strong>{{ $mahasiswas->total() }}</strong> Mahasiswa
                            </div>
                            <div class="pagination-info">
                                <i class="fas fa-calendar-alt"></i> Tanggal Presensi:
                                <strong>{{ date('d/m/Y') }}</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- CSS Styling with Mobile Responsive -->
    <style>
        .text-center {
            text-align: center;
        }

        /* Desktop view - hidden on mobile */
        .desktop-view {
            display: block;
        }

        .mobile-view {
            display: none;
        }

        /* Mobile Card Styles */
        .mobile-presensi-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
            transition: all 0.2s;
        }

        .mobile-presensi-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f2f5;
        }

        .mobile-user-info {
            display: flex;
            gap: 12px;
            flex: 1;
        }

        .mobile-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-avatar i {
            font-size: 1.5rem;
            color: white;
        }

        .mobile-user-detail {
            flex: 1;
        }

        .mobile-user-name {
            font-weight: 600;
            color: #1e3a5f;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .mobile-user-nim {
            font-size: 0.7rem;
            color: #5b6e8c;
            font-family: monospace;
            margin-bottom: 2px;
        }

        .mobile-user-nim i {
            margin-right: 4px;
            font-size: 0.65rem;
        }

        .mobile-user-prodi {
            font-size: 0.7rem;
            color: #5b6e8c;
        }

        .mobile-user-prodi i {
            margin-right: 4px;
            font-size: 0.65rem;
        }

        .mobile-status {
            flex-shrink: 0;
        }

        .mobile-card-actions {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .mobile-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border: none;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-action-btn i {
            font-size: 0.9rem;
        }

        .mobile-action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .mobile-hadir {
            background: #dcfce7;
            color: #15803d;
        }

        .mobile-hadir:hover:not(:disabled) {
            background: #15803d;
            color: white;
            transform: translateY(-2px);
        }

        .mobile-izin {
            background: #fef3c7;
            color: #d97706;
        }

        .mobile-izin:hover:not(:disabled) {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .mobile-alpha {
            background: #fee2e2;
            color: #dc2626;
        }

        .mobile-alpha:hover:not(:disabled) {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .desktop-view {
                display: none;
            }

            .mobile-view {
                display: block;
            }

            .action-group {
                gap: 5px;
            }

            .info-panel .row>div {
                margin-bottom: 8px;
            }

            .pagination-modern-wrapper .d-flex {
                flex-direction: column;
                align-items: center !important;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .mobile-card-header {
                flex-direction: column;
                gap: 12px;
            }

            .mobile-status {
                align-self: flex-start;
            }

            .mobile-card-actions {
                flex-direction: column;
                gap: 8px;
            }

            .mobile-action-btn {
                padding: 8px 12px;
            }
        }

        /* Entity Name */
        .entity-name {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e3a5f;
        }

        /* Empty State */
        .empty-row td {
            text-align: center;
            padding: 2rem;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 2.2rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-weight: 500;
        }

        .empty-state small {
            font-size: 0.7rem;
        }

        /* Action Group */
        .action-group {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Action Buttons */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .action-btn .tooltip-text {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 10;
        }

        .action-btn:hover .tooltip-text {
            opacity: 1;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .action-view {
            background: #dcfce7;
            color: #15803d;
        }

        .action-view:hover:not(:disabled) {
            background: #15803d;
            color: white;
        }

        .action-edit {
            background: #fef3c7;
            color: #d97706;
        }

        .action-edit:hover:not(:disabled) {
            background: #d97706;
            color: white;
        }

        .action-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-delete:hover:not(:disabled) {
            background: #dc2626;
            color: white;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-hadir {
            background: #dcfce7;
            color: #15803d;
        }

        .status-izin {
            background: #fef3c7;
            color: #d97706;
        }

        .status-alpha {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-belum {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Pagination */
        .pagination-modern-wrapper nav {
            display: inline-block;
        }

        .pagination-modern-wrapper .pagination {
            flex-wrap: wrap;
            gap: 5px;
            margin: 0;
        }

        .pagination-modern-wrapper .page-link {
            border-radius: 30px !important;
            border: none;
            background: #f1f5f9;
            color: #2c3e66;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .pagination-modern-wrapper .page-link:hover {
            background: #e8f0fe;
            transform: translateY(-1px);
        }

        .pagination-modern-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
        }

        /* NIM Badge */
        .nim {
            font-family: monospace;
            background: #f1f3f5;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #1e3a5f;
        }

        .role-badge {
            background: #e6f0fa;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e4a76;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</div>