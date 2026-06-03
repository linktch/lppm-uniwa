<div>

    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="academic-page-title">
                            <i class="fas fa-tasks mr-2"></i> Manajemen {{ $jenis }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb academic-breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ $jenis }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Card Kelompok -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="academic-card card-kelompok">
                            <div class="academic-card-header">
                                <div class="card-title-academic">
                                    <div class="header-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    Kelompok
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <p class="card-description">Kelola data kelompok mahasiswa.</p>
                                <a wire:navigate href="{{ url('/prodi/kegiatan/' . $jenis . '/kelompok') }}"
                                    class="btn-create-academic btn-kelompok">
                                    Kelola Kelompok
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Laporan -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="academic-card card-laporan">
                            <div class="academic-card-header">
                                <div class="card-title-academic">
                                    <div class="header-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    Laporan
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <p class="card-description">Lihat dan unduh laporan kegiatan mahasiswa.</p>
                                <a href="{{ route('prodi.kegiatan.laporanMHS.index') }}"
                                    class="btn-create-academic btn-kelompok">
                                    Kelola Kelompok
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Presensi/Kehadiran -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="academic-card card-presensi">
                            <div class="academic-card-header">
                                <div class="card-title-academic">
                                    <div class="header-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    Presensi/Kehadiran
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <p class="card-description">Kelola presensi dan kehadiran mahasiswa.</p>
                                <button class="btn-create-academic btn-presensi">
                                    Kelola Presensi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>
