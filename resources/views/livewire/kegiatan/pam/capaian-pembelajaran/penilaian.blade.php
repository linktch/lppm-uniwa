<div>
    <div class="content-wrapper">
        <!-- Header dengan desain akademik -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        @if ($jenis == 'PAM')
                            <h1 class="academic-page-title">
                                <i class="fas fa-handshake me-2"></i> Manajemen Capaian Pembelajaran Mahasiswa
                                {{ $jenis }}
                            </h1>
                        @else
                            <h1 class="academic-page-title">
                                <i class="fas fa-users-class me-2"></i> Manajemen Kelompok {{ $jenis }}
                            </h1>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb academic-breadcrumb justify-content-sm-end">
                                <li class="breadcrumb-item"><a href="#"><i
                                            class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                @if ($jenis == 'PAM')
                                    <li class="breadcrumb-item active" aria-current="page">Capaian Pembelajaran
                                        {{ $jenis }}
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
                <div class="academic-card-body" style="padding: 1px;">
                    <div class="academic-card-body p-0">
                        <div class="academic-card-body">
                            <!-- Kolom Search -->
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

                            {{-- table mahasiswa dengan 3 data dummy --}}
                            <div class="table-responsive-modern desktop-view">
                                <table class="academic-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th width="20%">Program Studi</th>
                                            <th width="12%">Mitra</th>
                                            <th width="15%" class="text-center">Progress</th>
                                            <th width="18%" class="text-center">Penilaian CP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mahasiswas as $mhs)
                                            @php
                                                // Contoh nilai progress (bisa diambil dari database)
                                                // Asumsikan progress dalam persentase 0-100
                                                $progress = $mhs['progress'];
                                                $progressColor =
                                                    $progress >= 80
                                                        ? '#28a745'
                                                        : ($progress >= 50
                                                            ? '#ffc107'
                                                            : '#dc3545');
                                            @endphp
                                            <tr class="data-row">
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <span class="nim">{{ $mhs['nim'] }}</span>
                                                </td>
                                                <td>
                                                    <div class="entity-info">
                                                        <i class="fas fa-user"
                                                            style="color:#2c7da0; margin-right:8px;"></i>
                                                        <strong class="entity-name">{{ $mhs['nama'] }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="role-badge">
                                                        <i class="fas fa-graduation-cap"></i>
                                                        {{ $mhs['prodi'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="role-badge">
                                                        {{ $mhs['kelompok'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="progress-wrapper" style="min-width: 120px;">
                                                        <div class="progress-info"
                                                            style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                            <span
                                                                style="font-size: 11px; font-weight: 600; color: {{ $progressColor }};">{{ $progress }}%</span>
                                                        </div>
                                                        <div class="progress-bar-container"
                                                            style="background-color: #e9ecef; border-radius: 10px; height: 8px; overflow: hidden;">
                                                            <div class="progress-bar-fill"
                                                                style="width: {{ $progress }}%; background-color: {{ $progressColor }}; height: 100%; border-radius: 10px; transition: width 0.3s ease;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a wire:navigate
                                                        href="{{ route('kegiatan.pam.kelompok.pamcp.detailpenilaian', [
                                                            'role' => auth()->user()->role,
                                                            'mahasiswaID' => $mhs['id'],
                                                            
                                                        ]) }}"
                                                        class="action-btn action-view" title="Berikan Penilaian"
                                                        style="background: #e6f0fa; color: #1e4a76; width: auto; padding: 6px 16px; gap: 8px;">
                                                        <i class="fas fa-star"></i>
                                                        @if (Auth()->User()->role == 'mahasiswa')
                                                            <span>Periksa Nilai</span>
                                                            @else
                                                            <span>Berikan Nilai</span>
                                                        @endif
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View (only visible on mobile) -->
                            <div class="mobile-view">
                                @foreach ($mahasiswas as $index => $mhs)
                                    @php
                                        $progress = $mhs['progress'] ?? rand(0, 100);
                                        $progressColor =
                                            $progress >= 80 ? '#28a745' : ($progress >= 50 ? '#ffc107' : '#dc3545');
                                    @endphp
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
                                                        <i class="fas fa-graduation-cap"></i> {{ $mhs['prodi'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mobile-card-progress"
                                            style="margin-bottom: 1rem; padding: 0 0.5rem;">
                                            <div
                                                style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                <span style="font-size: 11px; color: #666;">Progress</span>
                                                <span
                                                    style="font-size: 11px; font-weight: 600; color: {{ $progressColor }};">{{ $progress }}%</span>
                                            </div>
                                            <div class="progress-bar-container"
                                                style="background-color: #e9ecef; border-radius: 10px; height: 6px; overflow: hidden;">
                                                <div class="progress-bar-fill"
                                                    style="width: {{ $progress }}%; background-color: {{ $progressColor }}; height: 100%; border-radius: 10px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mobile-card-actions">
                                            <a class="mobile-action-btn mobile-hadir"
                                               href="{{ route('kegiatan.pam.kelompok.pamcp.detailpenilaian', [
                                                            'role' => auth()->user()->role,
                                                            'mahasiswaID' => $mhs['id'],
                                                            'kelompokID' => $mhs['kelompokID'],
                                                            
                                                        ]) }}"
                                                style="background: #e6f0fa; color: #1e4a76; width: 100%;">
                                                <i class="fas fa-star"></i>
                                                <span>Berikan Nilai</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- CSS untuk mobile view dan progress bar -->
    <style>
        .mobile-view {
            display: none;
        }

        .mobile-presensi-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
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

        .mobile-user-prodi {
            font-size: 0.7rem;
            color: #5b6e8c;
        }

        .mobile-card-progress {
            margin-bottom: 1rem;
        }

        .mobile-card-actions {
            display: flex;
            gap: 10px;
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

        /* Progress bar styling tambahan */
        .progress-wrapper {
            width: 100%;
        }

        .progress-bar-container {
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .progress-bar-fill {
            transition: width 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .desktop-view {
                display: none;
            }

            .mobile-view {
                display: block;
            }
        }
    </style>
</div>
