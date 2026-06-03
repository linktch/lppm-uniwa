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
                        <p class="text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Kelola Indikator Capaian Pembelajaran Mahasiswa 
                            @if (Auth()->User()->role == 'prodi')
                                Program Studi
                            @elseif(Auth()->User()->role == 'mitra')
                                Mitra
                            @else
                            SUperadmin
                            @endif 
                            Akuntansi yang terdaftar dalam sistem
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb academic-breadcrumb justify-content-sm-end">
                                <li class="breadcrumb-item"><a href="#">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Capaian Pembelajaran
                                    PAM
                                </li>
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
                                    Daftar Indikator CP Mahasiswa @if (Auth()->User()->role == 'prodi')
                                Program Studi
                            @elseif(Auth()->User()->role == 'mitra')
                                Mitra
                            @else
                            Superadmin
                            @endif  Akuntansi
                                </h3>
                                <span class="total-badge mt-1 d-inline-block">
                                    <i class="fas fa-database me-1"></i> Total: <span id="totalIndikatorCount">0</span>
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Tambah Indikator - Paling Kanan -->
                        <div class="ms-auto">
                            <button type="button" class="btn-add-indikator" wire:click="openModal">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Indikator
                            </button>
                        </div>
                    </div>
                </div>

                <div class="academic-card-body">
                    <!-- Filter dan Search Section -->
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

                    <!-- Table Indikator Capaian Pembelajaran Mahasiswa -->
                    <div class="table-responsive mt-4">
                        <table class="academic-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Indikator</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($indikators as $indikator)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $indikator->indikator }}</td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button class="btn-action btn-edit" title="Edit"
                                                    wire:click="editIndikator(6)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-action btn-delete" title="Hapus"
                                                    wire:click="confirmDelete({{ $indikator->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-modern">
                            {{ $indikators->links() }}
                        </div>
                    </div>
                    <!-- End Table -->

                </div>
            </div>

            <!-- Modal Tambah Indikator -->
            @if ($showModal)
                <div class="modal-overlay" wire:click.self="closeModal">
                    <div class="modal-container">
                        <div class="modal-header-custom">
                            <h5 class="modal-title-custom">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Indikator Baru
                            </h5>
                            <button type="button" class="modal-close" wire:click="closeModal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body-custom">
                            <div class="form-group-custom">
                                <label class="form-label-custom">
                                    Indikator Capaian Pembelajaran <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model="indikatorText" class="form-control-custom" rows="4"
                                    placeholder="Tulis indikator capaian pembelajaran di sini..."></textarea>
                                @error('indikatorText')
                                    <small class="text-danger-custom">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer-custom">
                            <button type="button" class="btn-custom btn-secondary-custom" wire:click="closeModal">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                            <button type="button" class="btn-custom btn-primary-custom" wire:click="simpan">
                                <i class="fas fa-save me-2"></i>Simpan Indikator
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <!-- Additional CSS for Table and Actions -->
    <style>
        /* Academic Table Styling */
        .academic-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        .academic-table thead th {
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2b44 100%);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 2px solid #ffc107;
        }

        .academic-table thead th:first-child {
            border-top-left-radius: 8px;
        }

        .academic-table thead th:last-child {
            border-top-right-radius: 8px;
        }

        .academic-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .academic-table tbody tr:hover {
            background-color: #f8f9fc;
            transform: scale(1.002);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .academic-table tbody td {
            padding: 14px 16px;
            font-size: 0.9rem;
            color: #2c3e50;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }

        /* Action Buttons Styling */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .btn-edit {
            color: #0d6efd;
            background: #e7f1ff;
        }

        .btn-edit:hover {
            background: #0d6efd;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            color: #dc3545;
            background: #ffe7e9;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
        }

        /* Academic Card Styling */
        .academic-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .academic-card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eef2f6;
            background: #ffffff;
        }

        .academic-card-body {
            padding: 0 25px 25px 25px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #1a3a5c, #0f2b44);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-title-academic {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e2f3e;
        }

        .total-badge {
            font-size: 0.75rem;
            background: #eef2ff;
            padding: 4px 12px;
            border-radius: 30px;
            color: #1a3a5c;
        }

        /* Filter Section Styling */
        .filter-section {
            background: #f9fafc;
            padding: 18px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .filter-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #5a6e7c;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #dce5ef;
            background: white;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .filter-select:focus {
            border-color: #1a3a5c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 58, 92, 0.1);
        }

        /* Button Add Indikator Styling */
        .btn-add-indikator {
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2b44 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            color: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .btn-add-indikator:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(26, 58, 92, 0.25);
            background: linear-gradient(135deg, #0f2b44 0%, #091d2f 100%);
            color: white;
        }

        /* Modal Styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header-custom {
            padding: 20px 25px;
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2b44 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title-custom {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body-custom {
            padding: 25px;
        }

        .modal-footer-custom {
            padding: 15px 25px 25px 25px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid #eef2f6;
        }

        /* Form Styling */
        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-label-custom {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
            font-family: inherit;
            resize: vertical;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #1a3a5c;
            box-shadow: 0 0 0 3px rgba(26, 58, 92, 0.1);
        }

        /* Button Custom Styling */
        .btn-custom {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2b44 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 58, 92, 0.3);
        }

        .btn-secondary-custom {
            background: #f1f3f5;
            color: #6c757d;
        }

        .btn-secondary-custom:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .text-danger-custom {
            color: #dc3545;
            font-size: 0.75rem;
            margin-top: 5px;
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .academic-card-header {
                padding: 15px 20px;
            }

            .academic-card-body {
                padding: 0 20px 20px 20px;
            }

            .btn-add-indikator {
                width: 100%;
                justify-content: center;
            }

            .ms-auto {
                width: 100%;
            }

            .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .modal-container {
                width: 95%;
                margin: 20px;
            }

            .modal-header-custom,
            .modal-body-custom,
            .modal-footer-custom {
                padding: 15px 20px;
            }
        }
    </style>
</div>
