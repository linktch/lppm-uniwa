<div>
    <div class="content-wrapper">
        <!-- Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="academic-page-title">
                            <i class="fas fa-clipboard-list me-2"></i> Rekap Laporan Harian Mahasiswa
                        </h1>
                        <p class="text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Monitoring Laporan Harian mahasiswa per minggu (Senin - Kamis)
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="content">
            <div class="academic-card">
                <!-- Header Card -->
                <div class="academic-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3 class="card-title-academic mb-0">Data Laporan Harian Mahasiswa</h3>
                            <span class="total-badge mt-1 d-inline-block">
                                Total: {{ $dataMahasiswa->total() ?? $dataMahasiswa->count() }} Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="academic-card-body">
                    <!-- FILTER -->
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
                            <!-- MINGGU -->
                            <div class="col-md-3">
                                <label class="filter-label">
                                    <i class="fas fa-calendar-week me-1"></i> Minggu
                                </label>
                                <select wire:model.change="week" class="filter-select">
                                    <option value="1" {{ $weekAktif == 1 ? 'selected' : '' }}>
                                        Minggu ke-1
                                    </option>
                                    <option value="2" {{ $weekAktif == 2 ? 'selected' : '' }}>
                                        Minggu ke-2
                                    </option>
                                    <option value="3" {{ $weekAktif == 3 ? 'selected' : '' }}>
                                        Minggu ke-3
                                    </option>
                                    <option value="4" {{ $weekAktif == 4 ? 'selected' : '' }}>
                                        Minggu ke-4
                                    </option>
                                    <option value="5" {{ $weekAktif == 5 ? 'selected' : '' }}>
                                        Minggu ke-5
                                    </option>
                                </select>
                            </div>

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

                    <!-- SUMMARY CARDS -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="summary-card-modern">
                                <div class="summary-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="summary-info">
                                    <div class="summary-label">Total Mahasiswa</div>
                                    <h3 class="summary-value">{{ $dataMahasiswa->total() ?? $dataMahasiswa->count() }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-card-modern">
                                <div class="summary-icon bg-info">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div class="summary-info">
                                    <div class="summary-label">Minggu Aktif</div>
                                    <h3 class="summary-value">Minggu ke-{{ $week }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-card-modern">
                                <div class="summary-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="summary-info">
                                    <div class="summary-label">Total Laporan Mahasiswa</div>
                                    <h3 class="summary-value">
                                        @php $grand = 0; @endphp
                                        @foreach ($dataMahasiswa as $d)
                                            @php $grand += $d['total_laporan']; @endphp
                                        @endforeach
                                        {{ $grand }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-card-modern">
                                <div class="summary-icon bg-warning">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="summary-info">
                                    <div class="summary-label">Rata-rata Laporan harian Mahasiswa</div>
                                    <h3 class="summary-value">
                                        @php
                                            $total = $dataMahasiswa->total() ?? $dataMahasiswa->count();
                                            $avg = $total > 0 ? round(($grand / ($total * 4)) * 100, 1) : 0;
                                        @endphp
                                        {{ $avg }}%
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive-modern">
                        <table class="academic-table">
                            <thead>
                                <tr class="main-header">
                                    <th rowspan="2" width="50">No</th>
                                    <th rowspan="2" width="250">Mahasiswa</th>
                                    <th rowspan="2" width="200">Mitra</th>
                                    <th colspan="4" class="text-center">Minggu ke-{{ $week }}</th>
                                    <th rowspan="2" width="80">Total</th>
                                </tr>
                                <tr class="sub-header">
                                    <th width="100">
                                        Senin
                                        <small>{{ \Carbon\Carbon::parse($startDate)->format('d/m') }}</small>
                                    </th>
                                    <th width="100">
                                        Selasa
                                        <small>{{ \Carbon\Carbon::parse($startDate)->addDay()->format('d/m') }}</small>
                                    </th>
                                    <th width="100">
                                        Rabu
                                        <small>{{ \Carbon\Carbon::parse($startDate)->addDays(2)->format('d/m') }}</small>
                                    </th>
                                    <th width="100">
                                        Kamis
                                        <small>{{ \Carbon\Carbon::parse($startDate)->addDays(3)->format('d/m') }}</small>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($dataMahasiswa->count() > 0)
                                    @foreach ($dataMahasiswa as $index => $data)
                                        <tr>
                                            <td class="text-center">{{ $dataMahasiswa->firstItem() + $index }}</td>
                                            <td>
                                                <div class="entity-info">
                                                    <span class="entity-name">{{ $data['nama'] }}</span>
                                                    <span class="entity-nim">{{ $data['nim'] }}</span>
                                                    <span class="entity-prodi">{{ $data['prodi'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mitra-info">
                                                    <i class="fas fa-building"></i>
                                                    <span class="mitra-name">{{ $data['mitra'] }}</span>
                                                </div>
                                            </td>

                                            <!-- Senin -->
                                            <td class="text-center">
                                                @if ($data['laporan']['senin'])
                                                    <span class="attendance-badge present">
                                                        <i class="fas fa-check-circle"></i> 
                                                        {{$data['laporan']['selasa']}}
                                                    </span>
                                                
                                                    
                                                @endif
                                            </td>

                                            <!-- Selasa -->
                                            <td class="text-center">
                                                @if ($data['laporan']['selasa'])
                                                    <span class="attendance-badge present">
                                                        <i class="fas fa-check-circle"></i> 
                                                        {{$data['laporan']['selasa']}}
                                                    </span>
                                                
                                                    
                                                @endif
                                            </td>

                                            <!-- Rabu -->
                                            <td class="text-center">
                                                @if ($data['laporan']['rabu'])
                                                    <span class="attendance-badge present">
                                                        <i class="fas fa-check-circle"></i> 
                                                        {{$data['laporan']['selasa']}}
                                                    </span>
                                                
                                                   
                                                @endif
                                            </td>

                                            <!-- Kamis -->
                                            <td class="text-center">
                                                @if ($data['laporan']['kamis'])
                                                    <span class="attendance-badge present">
                                                        <i class="fas fa-check-circle"></i> 
                                                        {{$data['laporan']['selasa']}}
                                                    </span>
                                                
                                                    
                                                @endif
                                            </td>

                                            <!-- Total -->
                                            <td class="text-center">
                                                <span class="total-badge-table">
                                                    {{ $data['total_laporan'] }} / 4
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="empty-data-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <h5>Tidak Ada Data</h5>
                                            <p>Belum ada data kehadiran untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>

                            @if ($dataMahasiswa->count() > 0)
                            <tfoot>
                                <tr class="table-footer">
                                    <th colspan="7" class="text-end">Total Kehadiran Keseluruhan</th>
                                    <th class="text-center">
                                        <span class="total-badge-table">
                                            {{ $grand }}
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if ($dataMahasiswa->hasPages())
                        <div class="pagination-modern-wrapper mt-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="pagination-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Menampilkan {{ $dataMahasiswa->firstItem() }} - {{ $dataMahasiswa->lastItem() }} 
                                    dari {{ $dataMahasiswa->total() }} data
                                </div>
                                <div class="pagination-modern">
                                    {{ $dataMahasiswa->links() }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    </div>

    <style>
    /* Reset & Base */
    .content-wrapper {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 20px;
    }

    /* Page Title */
    .academic-page-title {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }

    /* Academic Card Styles */
    .academic-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .academic-card:hover {
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }
    
    .academic-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e9ecef;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    .card-title-academic {
        font-size: 1.35rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }
    
    .total-badge {
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        padding: 5px 14px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .academic-card-body {
        padding: 28px;
    }
    
    /* Filter Section */
    .filter-section {
        margin-bottom: 28px;
        padding-bottom: 24px;
        border-bottom: 1px solid #eef2f6;
    }
    
    .filter-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6c757d;
        margin-bottom: 8px;
        display: block;
    }
    
    .filter-select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        background: white;
        cursor: pointer;
    }
    
    .filter-select:hover {
        border-color: #cbd5e0;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }
    
    /* Search Wrapper */
    .search-wrapper {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 14px;
    }
    
    .search-input {
        width: 100%;
        padding: 10px 40px 10px 40px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }
    
    .search-clear {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .search-clear:hover {
        color: #e74c3c;
    }
    
    /* Summary Cards */
    .summary-card-modern {
        background: white;
        border-radius: 20px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #eef2f6;
        transition: all 0.3s;
    }
    
    .summary-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }
    
    .summary-icon {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    
    .bg-primary { background: linear-gradient(135deg, #2c3e50, #3498db); }
    .bg-success { background: linear-gradient(135deg, #27ae60, #2ecc71); }
    .bg-warning { background: linear-gradient(135deg, #e67e22, #f39c12); }
    .bg-info { background: linear-gradient(135deg, #00bcd4, #26c6da); }
    
    .summary-info {
        flex: 1;
    }
    
    .summary-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    
    .summary-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin: 0;
    }
    
    /* Table Styles */
    .table-responsive-modern {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid #eef2f6;
    }
    
    .academic-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        min-width: 900px;
    }
    
    .academic-table .main-header th {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        padding: 16px 12px;
        font-weight: 700;
        font-size: 0.9rem;
        border-bottom: 3px solid #3498db;
    }
    
    .academic-table .sub-header th {
        background: #34495e;
        color: #ecf0f1;
        padding: 10px 8px;
        font-weight: 600;
        font-size: 0.8rem;
        text-align: center;
    }
    
    .academic-table .sub-header th small {
        font-size: 0.7rem;
        font-weight: normal;
        color: #bdc3c7;
        display: block;
        margin-top: 4px;
    }
    
    .academic-table tbody tr {
        border-bottom: 1px solid #eef2f6;
        transition: background 0.2s;
    }
    
    .academic-table tbody tr:hover {
        background: #f8fafc;
    }
    
    .academic-table tbody td {
        padding: 16px 12px;
        color: #2c3e50;
        vertical-align: middle;
    }
    
    /* Entity Info */
    .entity-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .entity-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .entity-nim {
        font-size: 0.7rem;
        color: #6c757d;
        font-family: 'Courier New', monospace;
    }
    
    .entity-prodi {
        font-size: 0.7rem;
        color: #3498db;
        font-weight: 600;
    }
    
    /* Mitra Info */
    .mitra-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .mitra-info i {
        color: #3498db;
        font-size: 14px;
    }
    
    .mitra-name {
        font-size: 0.85rem;
        color: #2c3e50;
        font-weight: 500;
    }
    
    /* Attendance Badge */
    .attendance-badge {
        padding: 6px 12px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    
    .attendance-badge.present {
        background: #d5f4e6;
        color: #27ae60;
    }
    
    .attendance-badge.absent {
        background: #fde2e2;
        color: #e74c3c;
    }
    
    .total-badge-table {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: white;
        padding: 5px 12px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.8rem;
        display: inline-block;
    }
    
    /* Table Footer */
    .table-footer th {
        background: #f8fafc;
        padding: 14px 12px;
        font-weight: 700;
        border-top: 2px solid #eef2f6;
    }
    
    /* Empty State */
    .empty-data-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-icon {
        font-size: 70px;
        color: #cbd5e0;
        margin-bottom: 20px;
    }
    
    .empty-data-state h5 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .empty-data-state p {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Pagination */
    .pagination-modern-wrapper {
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }
    
    .pagination-info {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .pagination-modern nav {
        display: inline-block;
    }
    
    .pagination-modern .pagination {
        margin: 0;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .pagination-modern .page-item .page-link {
        border: none;
        padding: 8px 14px;
        border-radius: 10px;
        color: #2c3e50;
        background: #f1f3f5;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .pagination-modern .page-item .page-link:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: white;
        box-shadow: 0 4px 12px rgba(52,152,219,0.3);
    }
    
    .pagination-modern .page-item.disabled .page-link {
        color: #adb5bd;
        background: #f8f9fa;
        cursor: not-allowed;
    }
    
    /* Utilities */
    .text-center {
        text-align: center;
    }
    
    .text-end {
        text-align: right;
    }
    
    .fw-semibold {
        font-weight: 600;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 15px;
        }
        
        .academic-card-header,
        .academic-card-body {
            padding: 20px;
        }
        
        .summary-card-modern {
            padding: 14px 16px;
        }
        
        .summary-value {
            font-size: 1.2rem;
        }
        
        .summary-icon {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }
    }
    </style>
</div>