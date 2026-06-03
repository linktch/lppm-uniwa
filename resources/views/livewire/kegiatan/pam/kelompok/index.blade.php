<div>
    <div class="content-wrapper">
        <!-- Header dengan desain akademik -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="academic-page-title">
                            <i class="fas fa-user-graduate me-2"></i> Daftar Mahasiswa
                        </h1>
                        <p class="text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Kelola data mahasiswa yang terdaftar dalam sistem
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb academic-breadcrumb justify-content-sm-end">
                                <li class="breadcrumb-item"><a href="#"><i
                                            class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <button>Tambah Mitra</button>
            </div>
        </section>
        @if (Auth()->User()->role == 'superadmin')
            <!-- Statistik Total Mahasiswa -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="academic-card">
                                <div class="academic-card-body" style="padding: 1rem;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="info-label">TOTAL Mitra Terdaftar</span>
                                            <h3 class="mb-0 mt-1" style="font-weight: 700; color: #1e3a5f;">
                                                {{ count($kelompoks) }}</h3>
                                            <small class="text-muted">Seluruh Mitra aktif</small>
                                        </div>
                                        <div class="header-icon"
                                            style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                            <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Daftar Mahasiswa -->
                    <div class="row">
                        <div class="col-12">
                            <div class="academic-card">
                                <div class="academic-card-header" style="padding: 1rem 1.5rem;">
                                    <div class="card-title-academic" style="display: flex; align-items: center;">
                                        <div style="display: flex; align-items: center;">
                                            <div class="header-icon"
                                                style="width: 36px; height: 36px; background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                                <i class="fas fa-list"></i>
                                            </div>
                                            <h5 style="margin: 0 0 0 8px; font-weight: 600;">Daftar Mitra</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="academic-card-body" style="padding: 0 1.5rem 1.5rem 1.5rem;">
                                    <!-- Search Filter -->
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
                                                        placeholder="Cari Mitra..." wire:model.live="search">
                                                    <button class="search-clear" wire:click="$set('search', '')"
                                                        style="display: {{ $search ? 'block' : 'none' }};">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabel Desktop -->
                                    <div class="table-responsive-modern desktop-view">
                                        <table class="academic-table">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Nama</th>
                                                    <th width="20%">Periode</th>
                                                    <th width="20%" class="text-center">Total Mahasiwa Bergabung</th>
                                                    <th width="20%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($kelompoks as $index => $data)
                                                    <tr class="data-row">
                                                        <td>
                                                            <div class="entity-info">
                                                                <i class="fas fa-user"
                                                                    style="color:#2c7da0; margin-right:8px;"></i>
                                                                <strong
                                                                    class="entity-name">{{ $data->nama_kelompok }}</strong>
                                                            </div>
                                                        </td>
                                                        <td><span
                                                                class="nim">{{ $data->periode->nama_periode }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="role-badge"><i
                                                                    class="fas fa-graduation-cap"></i>
                                                            </span>
                                                        </td>


                                                        <td class="text-center">
                                                            <div class="action-group">

                                                                <a wire:navigate
                                                                    href="{{ route('kegiatan.pam.kelompok.detail', [
                                                                        'role' => auth()->user()->role,
                                                                        'kelompokID' => $data->id,
                                                                    ]) }}"
                                                                    class="action-btn action-view" title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                    <span class="tooltip-text">Lihat Detail</span>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="empty-row">
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-user-graduate"></i>
                                                                <p>Belum ada mahasiswa</p>
                                                                <small>Silakan tambahkan mahasiswa terlebih
                                                                    dahulu</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile Card View (DIPERBAIKI) -->
                                    <div class="mobile-view">
                                        @foreach ($dataMahasiswa as $index => $mhs)
                                            <div class="mobile-presensi-card">
                                                <div class="mobile-card-header">
                                                    <div class="mobile-user-info">
                                                        <div class="mobile-avatar">
                                                            <i class="fas fa-user-graduate"></i>
                                                        </div>
                                                        <div class="mobile-user-detail">
                                                            <div class="mobile-user-name">
                                                                {{ $mhs['nama'] }}
                                                            </div>
                                                            <div class="mobile-user-nim">
                                                                <i class="fas fa-id-card"></i> {{ $mhs['nim'] }}
                                                            </div>
                                                            <div class="mobile-user-prodi">
                                                                <i class="fas fa-graduation-cap"></i>
                                                                {{ $mhs['prodi'] }}
                                                            </div>
                                                            <div class="mobile-user-mitra">
                                                                <i class="fas fa-building"></i> {{ $mhs['mitra'] }}
                                                            </div>
                                                            <div class="mobile-user-status">
                                                                @if ($mhs['status_plotting'] == 'Sudah Plotting')
                                                                    <span class="status-badge status-plotted"
                                                                        style="font-size: 0.7rem;">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        {{ $mhs['status_plotting'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="status-badge status-not-plotted"
                                                                        style="font-size: 0.7rem;">
                                                                        <i class="fas fa-clock"></i>
                                                                        {{ $mhs['status_plotting'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mobile-card-actions">
                                                    <button class="mobile-action-btn mobile-plot"
                                                        onclick="openPlottingModal({{ $index }}, '{{ $mhs['nama'] }}', '{{ $mhs['nim'] }}')">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>Plotting</span>
                                                    </button>
                                                    
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Pagination -->
                                    <div class="pagination-modern-wrapper mt-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap"
                                            style="gap: 15px;">
                                            <div class="pagination-info">
                                                <i class="fas fa-chart-bar"></i> Menampilkan
                                                <strong>1</strong> -
                                                <strong>{{ count($dataMahasiswa) }}</strong>
                                                dari <strong>{{ count($dataMahasiswa) }}</strong> Mahasiswa
                                            </div>
                                            <div class="pagination-modern">
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <!-- Statistik Total Mahasiswa -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="academic-card">
                                <div class="academic-card-body" style="padding: 1rem;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="info-label">TOTAL MAHASISWA</span>
                                            <h3 class="mb-0 mt-1" style="font-weight: 700; color: #1e3a5f;">
                                                {{ $dataMahasiswa->total() }}</h3>
                                            <small class="text-muted">Seluruh mahasiswa aktif</small>
                                        </div>
                                        <div class="header-icon"
                                            style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                            <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Daftar Mahasiswa -->
                    <div class="row">
                        <div class="col-12">
                            <div class="academic-card">
                                <div class="academic-card-header" style="padding: 1rem 1.5rem;">
                                    <div class="card-title-academic" style="display: flex; align-items: center;">
                                        <div style="display: flex; align-items: center;">
                                            <div class="header-icon"
                                                style="width: 36px; height: 36px; background: linear-gradient(135deg, #1e3a5f, #2c7da0);">
                                                <i class="fas fa-list"></i>
                                            </div>
                                            <h5 style="margin: 0 0 0 8px; font-weight: 600;">Daftar Mahasiswa</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="academic-card-body" style="padding: 0 1.5rem 1.5rem 1.5rem;">
                                    <!-- Search Filter -->
                                   <div class="filter-section">
                                        <div class="d-flex justify-content-between align-items-end gap-3 flex-wrap">
                                            <div style="min-width: 250px;">
                                                <label class="filter-label">
                                                    <i class="fas fa-calendar-alt me-1"></i> Filter Periode
                                                </label>
                                                <select class="filter-select w-100" wire:model.live="periodeFilter">
                                                    <option value="">Semua Periode</option>
                                                    @foreach ($periodes as $periode)
                                                        <option value="{{ $periode->id }}">
                                                            {{ $periode->nama_periode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Filter Prodi dan Angkatan (hanya untuk role selain prodi, mitra, mahasiswa) --}}
                                            @if(!in_array(auth()->user()->role, ['prodi', 'mitra', 'mahasiswa']))
                                                <div style="min-width: 250px;">
                                                    <label class="filter-label">
                                                        <i class="fas fa-graduation-cap me-1"></i> Filter Prodi
                                                    </label>
                                                    <select class="filter-select w-100" wire:model.live="prodiFilter">
                                                        <option value="">Semua Prodi</option>
                                                        @foreach ($prodis as $prodi)
                                                            <option value="{{ $prodi->id_prodi }}">
                                                                {{ $prodi->nama_program_studi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div style="min-width: 250px;">
                                                    <label class="filter-label">
                                                        <i class="fas fa-layer-group me-1"></i> Filter Angkatan
                                                    </label>
                                                    <select class="filter-select w-100" wire:model.live="angkatanFilter">
                                                        <option value="">Semua Angkatan</option>
                                                        
                                                            <option value="">
                                                               2025
                                                            </option>
                                                        
                                                    </select>
                                                </div>
                                            @endif

                                            <div style="min-width: 250px;">
                                                <label class="filter-label">
                                                    <i class="fas fa-search me-1"></i> Pencarian
                                                </label>
                                                <input type="text" 
                                                    class="filter-select w-100" 
                                                    wire:model.live="search" 
                                                    placeholder="Cari...">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabel Desktop -->
                                    <div class="table-responsive-modern desktop-view">
                                        <table class="academic-table">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Nama Mahasiswa</th>
                                                    <th width="10%">NIM</th>
                                                    <th width="20%">Program Studi</th>
                                                    <th width="25%">Mitra</th>
                                                    <th width="10%" class="text-center">Status Plotting</th>
                                                    <th width="15%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($dataMahasiswa as $index => $mhs)
                                         
                                                    <tr class="data-row">
                                                        <td>
                                                            <div class="entity-info">
                                                                <i class="fas fa-user"
                                                                    style="color:#2c7da0; margin-right:8px;"></i>
                                                                <strong
                                                                    class="entity-name">{{ $mhs['nama'] }}</strong>
                                                            </div>
                                                        </td>
                                                        <td><span class="nim">{{ $mhs['nim'] }}</span></td>
                                                        <td><span class="role-badge"><i
                                                                    class="fas fa-graduation-cap"></i>
                                                                {{ $mhs['prodi'] }}</span></td>
                                                        <td>
                                                            <span class="mitra-badge">
                                                                <i class="fas fa-building"></i> {{ $mhs['mitra'] }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($mhs['status_plotting'] == 'Sudah Plotting')
                                                                <span class="status-badge status-plotted">
                                                                    <i class="fas fa-check-circle"></i>
                                                                    {{ $mhs['status_plotting'] }}
                                                                </span>
                                                            @else
                                                                <span class="status-badge status-not-plotted">
                                                                    <i class="fas fa-clock"></i>
                                                                    {{ $mhs['status_plotting'] }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="action-group">
                                                                <button class="action-btn action-plot"
                                                                    wire:click="openModal({{ $mhs['id'] }})"
                                                                    title="Plotting Mitra">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                    <span class="tooltip-text">Plotting Mitra</span>
                                                                </button>
                                                                
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="empty-row">
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-user-graduate"></i>
                                                                <p>Belum ada mahasiswa</p>
                                                                <small>Silakan tambahkan mahasiswa terlebih
                                                                    dahulu</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile Card View (DIPERBAIKI) -->
                                    <div class="mobile-view">
                                        @foreach ($dataMahasiswa as $index => $mhs)
                                            <div class="mobile-presensi-card">
                                                <div class="mobile-card-header">
                                                    <div class="mobile-user-info">
                                                        <div class="mobile-avatar">
                                                            <i class="fas fa-user-graduate"></i>
                                                        </div>
                                                        <div class="mobile-user-detail">
                                                            <div class="mobile-user-name">
                                                                {{ $mhs['nama'] }}
                                                            </div>
                                                            <div class="mobile-user-nim">
                                                                <i class="fas fa-id-card"></i> {{ $mhs['nim'] }}
                                                            </div>
                                                            <div class="mobile-user-prodi">
                                                                <i class="fas fa-graduation-cap"></i>
                                                                {{ $mhs['prodi'] }}
                                                            </div>
                                                            <div class="mobile-user-mitra">
                                                                <i class="fas fa-building"></i> {{ $mhs['mitra'] }}
                                                            </div>
                                                            <div class="mobile-user-status">
                                                                @if ($mhs['status_plotting'] == 'Sudah Plotting')
                                                                    <span class="status-badge status-plotted"
                                                                        style="font-size: 0.7rem;">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        {{ $mhs['status_plotting'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="status-badge status-not-plotted"
                                                                        style="font-size: 0.7rem;">
                                                                        <i class="fas fa-clock"></i>
                                                                        {{ $mhs['status_plotting'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mobile-card-actions">
                                                    <button class="mobile-action-btn mobile-plot"
                                                        wire:click="$set('showModal', false)">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>Plotting</span>
                                                    </button>
                                                    <button class="mobile-action-btn mobile-view"
                                                        wire:click="viewMahasiswa({{ $index }})">
                                                        <i class="fas fa-eye"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                    <button class="mobile-action-btn mobile-edit"
                                                        wire:click="editMahasiswa({{ $index }})">
                                                        <i class="fas fa-edit"></i>
                                                        <span>Edit</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Pagination -->
                                    <div class="pagination-modern-wrapper mt-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap"
                                            style="gap: 15px;">
                                            <div class="pagination-info">
                                                    <i class="fas fa-chart-bar"></i> Menampilkan
                                                    <strong>{{ $dataMahasiswa->firstItem() }}</strong> -
                                                    <strong>{{ $dataMahasiswa->lastItem() }}</strong>
                                                    dari <strong>{{ $dataMahasiswa->total() }}</strong> Mahasiswa
                                                </div>
                                            <div class="pagination-modern">
                                               {{ $dataMahasiswa->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1"
            style="background: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #1e3a5f, #2c7da0); color: white;">

                        <h5 class="modal-title">
                            <i class="fas fa-map-marker-alt me-2"></i> Plotting Mitra Mahasiswa
                        </h5>

                        <button type="button" class="close" wire:click="$set('showModal', false)"
                            style="color: white;">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="selected-student-info"
                            style="background: #f0f7ff; padding: 15px; border-radius: 12px; margin-bottom: 20px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <label>NAMA MAHASISWA</label>
                                    <p>
                                        {{ $selectedMahasiswa['nama'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label>NIM</label>
                                    <p>
                                        {{ $selectedMahasiswa['nim'] ?? '-' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label>Pilih Mitra</label>

                            <select class="form-control" wire:model="mitra">
                                <option value="">-- Pilih Mitra --</option>
                                @foreach ($kelompoks as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_kelompok }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary" wire:click="$set('showModal', false)">
                            Batal
                        </button>

                        <button class="btn btn-primary" wire:click="savePlotting">
                            Simpan Plotting
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- CSS Styling yang DIPERBAIKI untuk mobile & desktop -->
    <style>
        /* RESET & BASE */
        * {
            box-sizing: border-box;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
            width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }

        .col-sm-6,
        .col-md-12,
        .col-12,
        .col-md-6 {
            position: relative;
            width: 100%;
            padding-right: 10px;
            padding-left: 10px;
        }

        @media (min-width: 768px) {
            .col-sm-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Academic Card */
        .academic-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .academic-card-body {
            padding: 1rem;
        }

        /* Header icon */
        .header-icon {
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
        }

        /* Desktop Table Styles */
        .desktop-view {
            display: block;
            width: 100%;
            overflow-x: auto;
        }

        .academic-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .academic-table th {
            text-align: left;
            padding: 12px 12px;
            background: #f8fafc;
            color: #1e3a5f;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .academic-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        .nim {
            font-family: monospace;
            background: #f1f3f5;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e3a5f;
            display: inline-block;
        }

        .role-badge,
        .mitra-badge {
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

        .mitra-badge {
            background: #e8f0fe;
            color: #1e3a5f;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-plotted {
            background: #d4edda;
            color: #155724;
        }

        .status-not-plotted {
            background: #fff3cd;
            color: #856404;
        }

        .action-group {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn.action-plot {
            background: #e8f0fe;
            color: #2c7da0;
        }

        .action-btn.action-plot:hover {
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            color: white;
            transform: translateY(-2px);
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
            color: #2c7da0;
        }

        .action-btn.action-edit:hover {
            background: #1e3a5f;
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

        /* MOBILE VIEW - DIPERBAIKI TAMPILANNYA */
        .mobile-view {
            display: none;
        }

        .mobile-presensi-card {
            background: white;
            border-radius: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f8;
            overflow: hidden;
            transition: all 0.2s;
        }

        .mobile-card-header {
            padding: 16px;
            background: #fefefe;
        }

        .mobile-user-info {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .mobile-avatar {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #1e3a5f, #2c7da0);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .mobile-user-detail {
            flex: 1;
        }

        .mobile-user-name {
            font-weight: 700;
            font-size: 1rem;
            color: #0f2b3f;
            margin-bottom: 6px;
        }

        .mobile-user-nim,
        .mobile-user-prodi,
        .mobile-user-mitra {
            font-size: 0.75rem;
            color: #475569;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mobile-user-nim i,
        .mobile-user-prodi i,
        .mobile-user-mitra i {
            width: 18px;
            color: #2c7da0;
        }

        .mobile-user-status {
            margin-top: 8px;
        }

        .mobile-card-actions {
            display: flex;
            gap: 10px;
            padding: 12px 16px;
            background: #fafcff;
            border-top: 1px solid #ecf3fa;
            flex-wrap: wrap;
        }

        .mobile-action-btn {
            flex: 1;
            background: #f1f5f9;
            border: none;
            border-radius: 40px;
            padding: 8px 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
            color: #1e293b;
        }

        .mobile-action-btn.mobile-plot {
            background: #e0f2fe;
            color: #0284c7;
        }

        .mobile-action-btn.mobile-plot:hover {
            background: #0284c7;
            color: white;
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

        /* Responsive Breakpoints - Perbaikan Utama */
        @media (max-width: 768px) {
            .desktop-view {
                display: none !important;
            }

            .mobile-view {
                display: block !important;
            }

            .academic-card-body {
                padding: 0 1rem 1rem 1rem;
            }

            .filter-section .row {
                flex-direction: column;
                gap: 12px;
            }

            .search-wrapper {
                width: 100%;
            }

            .search-input {
                width: 100%;
                padding: 10px 38px 10px 40px;
                border-radius: 40px;
                border: 1px solid #cbd5e1;
                font-size: 0.85rem;
            }

            .action-group {
                justify-content: flex-start;
            }

            .pagination-info {
                font-size: 0.7rem;
            }

            .header-icon {
                width: 45px;
                height: 45px;
            }

            .info-label {
                font-size: 0.6rem;
            }

            .academic-page-title {
                font-size: 1.35rem;
            }
        }

        /* Untuk tablet kecil & mobile landscape */
        @media (min-width: 769px) and (max-width: 1024px) {
            .desktop-view {
                display: block;
            }

            .mobile-view {
                display: none;
            }

            .academic-table th,
            .academic-table td {
                padding: 10px 6px;
                font-size: 0.75rem;
            }

            .action-group {
                gap: 4px;
            }

            .action-btn {
                width: 30px;
                height: 30px;
            }
        }

        /* Extra small fixes */
        .search-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 8px 38px 8px 38px;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 0.85rem;
            transition: 0.2s;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
        }

        .filter-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #1e3a5f;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .pagination-modern-wrapper {
            padding-top: 12px;
            border-top: 1px solid #edf2f7;
        }

        .pagination-info {
            background: #f8fafc;
            padding: 5px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
        }

        .text-center {
            text-align: center;
        }

        .me-2 {
            margin-right: 8px;
        }

        .me-1 {
            margin-right: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .gap-2 {
            gap: 8px;
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: 0.2s;
        }

        .btn-secondary {
            background: #e2e8f0;
            border: none;
            color: #1e293b;
        }

        .btn-primary {
            background: #1e3a5f;
            color: white;
            border: none;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        .close {
            font-size: 1.5rem;
            background: transparent;
            border: 0;
            cursor: pointer;
        }

        /* fix row pada modal */
        .selected-student-info .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
        }

        .selected-student-info .col-md-6 {
            margin-bottom: 8px;
        }
    </style>


</div>
