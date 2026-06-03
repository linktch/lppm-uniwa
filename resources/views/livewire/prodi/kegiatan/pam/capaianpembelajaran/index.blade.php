<div>
    <div class="content-wrapper">
        <!-- Header dengan desain akademik -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="academic-page-title">
                            <i class="fas fa-handshake me-2"></i> Manajemen Capaian Pembelajaran Mahasiswa {{ $jenis }}
                        </h1>
                        <p class="text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Kelola data {{ $jenis == 'PAM' ? 'mitra' : 'kelompok' }} yang terdaftar dalam sistem
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb academic-breadcrumb justify-content-sm-end">
                                <li class="breadcrumb-item"><a href="#"><i
                                            class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                @if ($jenis == 'PAM')
                                    <li class="breadcrumb-item active" aria-current="page">Mitra {{ $jenis }}
                                    </li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">Kelompok {{ $jenis }}
                                    </li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="content">
            <div class="academic-card">
                <div class="academic-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon">
                                @if ($jenis == 'PAM')
                                    <i class="fas fa-handshake"></i>
                                @else
                                    <i class="fas fa-users"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="card-title-academic mb-0">
                                    {{ $jenis == 'PAM' ? 'Daftar Mitra' : 'Daftar Kelompok' }}
                                </h3>
                                <span class="total-badge mt-1 d-inline-block">
                                    <i class="fas fa-database me-1"></i> Total: {{ $kelompoks->total() }}
                                </span>
                            </div>
                        </div>
                        @if (auth()->User()->role == 'superadmin')
                            <button wire:click.prevent="openModal" class="btn-create-academic">
                                <i class="fas fa-plus-circle me-2"></i>
                                {{ $jenis == 'PAM' ? 'Tambah Mitra Baru' : 'Tambah Kelompok Baru' }}
                            </button>
                        @endif
                    </div>
                </div>

                <div class="academic-card-body">
                    <!-- Filter dan Search Section -->
                    <div class="filter-section">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4 col-lg-3">
                                <label class="filter-label">
                                    <i class="fas fa-calendar-alt me-1"></i> Filter Periode
                                </label>
                                <select class="filter-select" wire:model.live="periodeFilter">
                                    <option value="">Semua Periode</option>
                                    @foreach ($periodes as $periode)
                                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 col-lg-6"></div>
                            <div class="col-md-3 col-lg-3">
                                <label class="filter-label">
                                    <i class="fas fa-search me-1"></i> Pencarian
                                </label>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="search-input"
                                        placeholder="Cari {{ $jenis == 'PAM' ? 'mitra' : 'kelompok' }}..."
                                        wire:model.live.debounce.300ms="search">
                                    @if ($search)
                                        <button wire:click="$set('search', '')" class="search-clear">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modern Table -->
                    <div class="table-responsive-modern">
                        <table class="academic-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    @if ($jenis == 'PAM')
                                        <th width="35%">
                                            <i class="fas fa-building me-1"></i> Nama Mitra
                                        </th>
                                    @else
                                        <th width="35%">
                                            <i class="fas fa-tag me-1"></i> Nama Kelompok
                                        </th>
                                    @endif
                                    <th width="20%">
                                        <i class="fas fa-calendar-week me-1"></i> Periode
                                    </th>
                                    <th width="25%">
                                        <i class="fas fa-chalkboard me-1"></i> Kegiatan
                                    </th>
                                    <th width="15%">
                                        <i class="fas fa-cog me-1"></i> Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelompoks as $index => $kelompok)
                                    <tr class="data-row">
                                        <td class="text-center fw-semibold">
                                            {{ $kelompoks->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="entity-info">
                                                <strong class="entity-name">{{ $kelompok->nama_kelompok }}</strong>
                                                <small class="entity-id">ID: {{ $kelompok->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="period-badge">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $kelompok->periode->nama_periode ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="activity-badge">
                                                <i class="fas fa-tasks me-1"></i>
                                                {{ $kelompok->kegiatan->nama_kegiatan ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <!-- Tombol Edit -->
                                                <button wire:click="openModal({{ $kelompok->id }})"
                                                    class="action-btn action-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="tooltip-text">Edit</span>
                                                </button>

                                                <!-- Tombol Detail -->
                                                @php
                                                    $user = auth()->user();

                                                    $route =
                                                        $user->role == 'superadmin'
                                                            ? route(
                                                                'superadmin.kegiatan.kelompok.detail',
                                                                $kelompok->id,
                                                            )
                                                            : route('prodi.kegiatan.kelompok.detail', $kelompok->id);
                                                @endphp

                                                <a  href="{{ $route }}"
                                                    class="action-btn action-view" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="tooltip-text">Detail</span>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <button wire:click="delete({{ $kelompok->id }})"
                                                    class="action-btn action-delete" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="tooltip-text">Hapus</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-state-row">
                                        <td colspan="5">
                                            <div class="empty-data-state">
                                                <div class="empty-icon">
                                                    <i class="fas fa-folder-open"></i>
                                                </div>
                                                <h5>Belum Ada Data</h5>
                                                <p>{{ $jenis == 'PAM' ? 'Belum ada mitra yang terdaftar' : 'Belum ada kelompok yang terdaftar' }}
                                                </p>
                                                @if (auth()->User()->role == 'superadmin')
                                                    <button wire:click="openModal" class="btn-empty-create">
                                                        <i class="fas fa-plus-circle me-2"></i>
                                                        {{ $jenis == 'PAM' ? 'Tambah Mitra Sekarang' : 'Tambah Kelompok Sekarang' }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Modern -->
                    @if ($kelompoks->hasPages())
                        <div class="pagination-modern-wrapper mt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="pagination-info">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Menampilkan {{ $kelompoks->firstItem() }} - {{ $kelompoks->lastItem() }}
                                    dari {{ $kelompoks->total() }} data
                                </div>
                                <div class="pagination-modern">
                                    {{ $kelompoks->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Partial (tetap menggunakan file yang sama) -->
   
</div>
