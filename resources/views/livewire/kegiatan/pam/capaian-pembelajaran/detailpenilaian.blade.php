<div>
    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 1rem 1.5rem;">
                            <div class="header-container">
                                <div class="header-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="header-text">
                                    <h4 class="mb-0 header-title">
                                        Penilaian Capaian Pembelajaran Mahasiswa
                                    </h4>
                                    <p class="mb-0 header-subtitle">
                                        Berikan penilaian capaian pembelajaran untuk mahasiswa
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Mahasiswa -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="academic-card" style="margin-bottom: 0;">
                        <div class="academic-card-body" style="padding: 0.75rem 1rem;">
                            <div class="info-grid">
                                <div class="info-item-academic info-item-full">
                                    <div class="info-label">
                                        <i class="fas fa-user-graduate"></i> Nama Mahasiswa
                                    </div>
                                    <div class="info-value fw-bold">
                                        {{ $dataMahasiswa['nama_mahasiswa'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="info-item-academic">
                                    <div class="info-label">
                                        <i class="fas fa-id-card"></i> NIM
                                    </div>
                                    <div class="info-value">
                                        {{ $dataMahasiswa['nim'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="info-item-academic info-item-full-mobile">
                                    <div class="info-label">
                                        <i class="fas fa-graduation-cap"></i> Program Studi
                                    </div>
                                    <div class="info-value">
                                        {{ $dataMahasiswa['nama_program_studi'] ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Indikator -->
            <div class="row">
                <div class="col-12">
                    @if (Auth()->User()->role == 'mitra')
                        @include('livewire.kegiatan.pam.capaian-pembelajaran.indikator-mitra')
                    @else
                        @include('livewire.kegiatan.pam.capaian-pembelajaran.indikator-prodi')
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- CSS Styling -->
    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary-dark: #1e3a5f;
            --primary-medium: #2c7da0;
            --primary-light: #e6f0fa;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --gray-light: #f8f9fa;
            --gray-border: #e9ecef;
        }

        /* ===== HEADER STYLES ===== */
        .header-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .header-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .header-icon i {
            font-size: 1.3rem;
            color: white;
        }

        .header-text {
            flex: 1;
        }

        .header-title {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: clamp(1rem, 4vw, 1.5rem);
        }

        .header-subtitle {
            color: #6c757d;
            font-size: clamp(0.7rem, 3vw, 0.85rem);
        }

        /* ===== INFO GRID STYLES ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .info-item-academic {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
        }

        .info-label i {
            width: 18px;
            font-size: 0.7rem;
            color: var(--primary-medium);
            margin-right: 4px;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
            word-break: break-word;
        }

        /* ===== CARD HEADER ===== */
        .academic-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-border);
        }

        .card-title-academic {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon-small {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .header-icon-small i {
            font-size: 1rem;
            color: white;
        }

        /* ===== PROGRESS SECTION ===== */
        .progress-section {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 12px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .progress-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #495057;
        }

        .progress-stats {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .progress-bar-wrapper {
            background-color: #e9ecef;
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .progress-bar-custom {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .bg-success {
            background-color: var(--success);
        }

        .bg-warning {
            background-color: var(--warning);
        }

        .bg-danger {
            background-color: var(--danger);
        }

        /* ===== TABLE STYLES ===== */
        .academic-table {
            width: 100%;
            border-collapse: collapse;
        }

        .academic-table th {
            background: #f8f9fa;
            padding: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e3a5f;
            border-bottom: 2px solid #dee2e6;
        }

        .academic-table td {
            padding: 12px;
            border-bottom: 1px solid #eef2f6;
            vertical-align: middle;
        }

        .indikator-text {
            font-size: 0.85rem;
            line-height: 1.4;
            color: #2c3e50;
        }

        /* ===== SELECT STYLES ===== */
        .form-select-custom {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
        }

        .form-select-custom:focus {
            outline: none;
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 3px rgba(44, 125, 160, 0.1);
        }

        .select-success {
            border-color: var(--success);
            background-color: #d4edda;
            color: #155724;
        }

        .select-danger {
            border-color: var(--danger);
            background-color: #f8d7da;
            color: #721c24;
        }

        /* ===== LOADING INDICATOR ===== */
        .loading-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
            padding: 0.75rem;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e9ecef;
            border-top-color: var(--primary-medium);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            padding: 2rem;
        }

        .empty-state i {
            font-size: 2rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-weight: 500;
        }

        .empty-state small {
            font-size: 0.7rem;
        }

        /* ===== MOBILE VIEW ===== */
        .mobile-view {
            display: none;
        }

        .mobile-indikator-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
            display: flex;
            gap: 12px;
            transition: all 0.2s;
        }

        .mobile-indikator-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .mobile-card-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .mobile-card-content {
            flex: 1;
        }

        .mobile-indikator-text {
            font-size: 0.85rem;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .mobile-select-wrapper {
            width: 100%;
        }

        .mobile-select {
            width: 100%;
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        /* ===== RESPONSIVE STYLES ===== */
        @media (max-width: 768px) {

            /* Grid adjustment */
            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .info-item-full-mobile {
                grid-column: span 1;
            }

            /* Hide desktop table, show mobile */
            .desktop-view {
                display: none;
            }

            .mobile-view {
                display: block;
            }

            /* Card body padding */
            .academic-card-body {
                padding: 1rem !important;
            }

            .academic-card-header {
                padding: 0.75rem 1rem;
            }

            /* Info item styling for mobile */
            .info-item-academic {
                padding: 0.5rem;
                background: var(--gray-light);
                border-radius: 8px;
            }

            .info-label {
                font-size: 0.6rem;
            }

            .info-value {
                font-size: 0.85rem;
            }

            /* Progress section */
            .progress-section {
                padding: 0.75rem;
            }

            .progress-label,
            .progress-stats {
                font-size: 0.7rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-item-full {
                grid-column: span 2;
            }

            .indikator-text {
                font-size: 0.8rem;
            }

            .form-select-custom {
                font-size: 0.8rem;
                padding: 6px 10px;
            }
        }

        @media (min-width: 1025px) {
            .info-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</div>
