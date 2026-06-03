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

                    @if (auth()->user()->role !== 'mitra' && auth()->user()->role !== 'mahasiswa')

                        <!-- Card Kelompok (hidden for mahasiswa) -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="academic-card card-kelompok">
                                <div class="academic-card-header">
                                    <div class="card-title-academic">
                                        <div class="header-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        @if ($jenis == 'PAM')
                                            Mitra
                                        @else
                                            Kelompok
                                        @endif
                                    </div>
                                </div>

                                <div class="academic-card-body">
                                    <p class="card-description">Lihat Data Mahasiswa.</p>

                                    <a wire:navigate
                                        href="{{ route('kegiatan.pam.kelompok.index', [
                                            'role' => auth()->user()->role,
                                        ]) }}"
                                        class="btn-create-academic btn-kelompok mb-1">

                                        Kelola
                                        @if ($jenis == 'PAM')
                                            Mitra
                                        @else
                                            Kelompok
                                        @endif

                                    </a>
                                </div>
                            </div>
                        </div>

                    @endif
                    
                    <!-- Card Capaian Pembelajaran (visible for mahasiswa) -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="academic-card card-presensi">
                            <div class="academic-card-header">
                                <div class="card-title-academic">
                                    <div class="header-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    Capaian Pembelajaran Mahasiswa
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <p class="card-description">Kelola Capaian Pembelajaran Mahasiswa.</p>
                                @if(Auth()->User()->role == 'prodi' || Auth()->User()->role == 'mitra')
                                <a wire:navigate
                                href="{{ route('kegiatan.pam.kelompok.pamcp.index', [
                                        'role' => auth()->user()->role,
                                        ]) }}"
                                    class="btn-create-academic btn-kelompok-now mb-1">
                                    Kelola CP
                                </a>
                                @endif
                                @if(Auth()->User()->role != 'superadmin' )
                                <a wire:navigate
                                    href="{{ route('kegiatan.pam.kelompok.pamcp.penilaian', [
                                        'role' => auth()->user()->role,
                                    ]) }}"
                                    class="btn-create-academic btn-kelompok">
                                    Nilai Sekarang
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Laporan (visible for mahasiswa) -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="academic-card card-laporan">
                            <div class="academic-card-header">
                                <div class="card-title-academic">
                                    <div class="header-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    Laporan
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <p class="card-description">Lihat dan unduh laporan kegiatan mahasiswa.</p>
                                <a href="{{route('kegiatan.pam.laporanharian.index', ['role' => Auth()->User()->role,]);}}" class="btn-create-academic btn-kelompok-now mb-1">
                                    Kelola Laporan
                                </a>
                                <a href="{{route('kegiatan.pam.laporanharian.rekap', ['role' => Auth()->User()->role,]);}}" class="btn-create-academic btn-kelompok">
                                    Rekap Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Presensi/Kehadiran (visible for mahasiswa) -->
                    <div class="col-lg-3 col-md-6 mb-3">
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
                                @if (Auth()->User()->role == 'mitra')
                                    <a wire:navigate
                                        href="{{ route('kegiatan.pam.kelompok.kehadiran.detail', ['role' => Auth()->User()->role, 'kelompokID' => $kelompokID->id]) }}"
                                        class="btn-create-academic btn-presensi-now mb-1">
                                        Absen Sekarang
                                    </a>
                                @endif
                               <a href="{{route('kegiatan.pam.kehadiran.rekap', ['role' => Auth()->User()->role,]);}}" class="btn-create-academic btn-kelompok">
                                    Rekap Kehadiran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>