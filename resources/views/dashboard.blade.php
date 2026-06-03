<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Dashboard - Sistem Informasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Your provided CSS styles go here */
        * {
            box-sizing: border-box;
        }

        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
            width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }

        [class*="col-"] {
            position: relative;
            width: 100%;
            padding-right: 10px;
            padding-left: 10px;
        }

        .col-sm-6, .col-md-12, .col-12, .col-md-6 {
            position: relative;
            width: 100%;
            padding-right: 10px;
            padding-left: 10px;
        }

        @media (min-width: 768px) {
            .col-sm-6 { flex: 0 0 50%; max-width: 50%; }
            .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            .col-md-12 { flex: 0 0 100%; max-width: 100%; }
        }

        .content-wrapper {
            background: #f5f7fb;
            min-height: calc(100vh - 120px);
        }

        /* --------------------------------------------
           TYPOGRAPHY & TITLES
        -------------------------------------------- */
        .academic-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1a3a5c;
            letter-spacing: -0.3px;
            margin-bottom: 1.25rem;
            position: relative;
            display: inline-block;
        }

        .academic-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #2c7da0, #61a5c2);
            border-radius: 3px;
        }

        .academic-page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 0;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
        }

        .academic-page-title i {
            color: #2c7da0;
            font-size: 1.6rem;
        }

        /* --------------------------------------------
           NAVBAR STYLES
        -------------------------------------------- */
        .academic-navbar {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .navbar-brand-custom:hover {
            color: rgba(255, 255, 255, 0.9);
        }

        .navbar-brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-brand-icon i {
            font-size: 1.3rem;
            color: white;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-avatar:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .user-avatar i {
            font-size: 1.2rem;
            color: white;
        }

        .user-info {
            color: white;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .user-role {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .mobile-menu-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* --------------------------------------------
           SIDEBAR STYLES
        -------------------------------------------- */
        .academic-sidebar {
            background: linear-gradient(180deg, #1a2f3f 0%, #0f1a24 100%);
            min-height: calc(100vh - 72px);
            transition: all 0.3s ease;
            position: sticky;
            top: 72px;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .nav-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .sidebar-item {
            margin: 0.25rem 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .sidebar-link i {
            width: 24px;
            font-size: 1.1rem;
        }

        .sidebar-link span {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-item:hover .sidebar-link {
            color: white;
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, #2c7da0, #1e3a5f);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-item.active .sidebar-link {
            color: white;
        }

        /* --------------------------------------------
           BREADCRUMB
        -------------------------------------------- */
        .academic-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .academic-breadcrumb .breadcrumb-item a {
            color: #4a6fa5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .academic-breadcrumb .breadcrumb-item a:hover {
            color: #1e3a5f;
        }

        .academic-breadcrumb .breadcrumb-item.active {
            color: #2c7da0;
            font-weight: 600;
        }

        /* --------------------------------------------
           MAIN CARD COMPONENT
        -------------------------------------------- */
        .academic-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .academic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .academic-card-header {
            padding: 1.5rem 1.75rem;
            border-bottom: none;
        }

        .academic-card-header.alternate {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-bottom: 2px solid #e9edf2;
        }

        .academic-card-body {
            padding: 1.75rem;
        }

        .card-title-academic {
            font-size: 1.35rem;
            font-weight: 600;
            color: #1e3a5f;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-description {
            color: #4a6fa5;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* --------------------------------------------
           HEADER ICON
        -------------------------------------------- */
        .header-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .header-icon.alternate {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);
        }

        /* --------------------------------------------
           BADGES & LABELS
        -------------------------------------------- */
        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4a6fa5;
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

        .status-plotted { background: #d4edda; color: #155724; }
        .status-not-plotted { background: #fff3cd; color: #856404; }

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

        /* --------------------------------------------
           BUTTONS
        -------------------------------------------- */
        .btn-create-academic {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);
        }

        .btn-create-academic:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(28, 78, 108, 0.25);
            color: white;
        }

        .action-group {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
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

        .action-btn.action-view { background: #dbeafe; color: #2563eb; }
        .action-btn.action-view:hover { background: #2563eb; color: white; transform: translateY(-2px); }
        .action-btn.action-edit { background: #fef3c7; color: #d97706; }
        .action-btn.action-edit:hover { background: #d97706; color: white; transform: translateY(-2px); }
        .action-btn.action-delete { background: #fee2e2; color: #dc2626; }
        .action-btn.action-delete:hover { background: #dc2626; color: white; transform: translateY(-2px); }

        /* --------------------------------------------
           FOOTER STYLES
        -------------------------------------------- */
        .academic-footer {
            background: linear-gradient(135deg, #1a2f3f 0%, #0f1a24 100%);
            color: rgba(255, 255, 255, 0.7);
            padding: 2rem 0 1rem 0;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .footer-brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-brand-icon i {
            font-size: 1.2rem;
            color: #61a5c2;
        }

        .footer-brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
            display: inline-block;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            margin-top: 1rem;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }

        .social-icon:hover {
            background: #2c7da0;
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.75rem;
        }

        /* --------------------------------------------
           TABLE STYLES
        -------------------------------------------- */
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

        .academic-table tr:hover {
            background-color: #fafcff;
        }

        /* --------------------------------------------
           MOBILE VIEW
        -------------------------------------------- */
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

        .mobile-user-detail { flex: 1; }
        .mobile-user-name { font-weight: 700; font-size: 1rem; color: #0f2b3f; margin-bottom: 6px; }
        .mobile-user-nim, .mobile-user-prodi {
            font-size: 0.75rem;
            color: #475569;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mobile-card-actions {
            display: flex;
            gap: 10px;
            padding: 12px 16px;
            background: #fafcff;
            border-top: 1px solid #ecf3fa;
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
        }

        .mobile-action-btn.mobile-view { background: #e8f0fe; color: #2c7da0; }
        .mobile-action-btn.mobile-view:hover { background: #2c7da0; color: white; }
        .mobile-action-btn.mobile-edit { background: #e8f0fe; color: #1e3a5f; }
        .mobile-action-btn.mobile-edit:hover { background: #1e3a5f; color: white; }

        /* --------------------------------------------
           FILTER SECTION
        -------------------------------------------- */
        .filter-section {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 16px;
            margin-bottom: 1.75rem;
            border: 1px solid #e9edf2;
        }

        .filter-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #4a6fa5;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .filter-select, .search-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #dce3ec;
            border-radius: 12px;
            font-size: 0.85rem;
            background: white;
            transition: all 0.2s;
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            padding-left: 38px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* --------------------------------------------
           MODAL STYLES
        -------------------------------------------- */
        .academic-modal {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }

        .academic-modal-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-icon {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .modal-title {
            color: white;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .modal-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
        }

        .btn-close-modal {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
        }

        .academic-modal-body {
            padding: 1.75rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .form-group-modern { margin-bottom: 1.5rem; }
        .form-label-modern {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a6fa5;
            margin-bottom: 0.5rem;
        }

        .form-control-modern {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #2c7da0;
            box-shadow: 0 0 0 3px rgba(44, 125, 160, 0.1);
        }

        .academic-modal-footer {
            padding: 1rem 1.75rem 1.5rem;
            border-top: 1px solid #f0f2f5;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #f1f5f9;
            border: none;
            padding: 10px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #5b6e8c;
            cursor: pointer;
        }

        .btn-save {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c7da0 100%);
            border: none;
            padding: 10px 28px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            cursor: pointer;
        }

        /* --------------------------------------------
           RESPONSIVE
        -------------------------------------------- */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
            
            .academic-sidebar {
                position: fixed;
                left: -280px;
                top: 72px;
                width: 280px;
                z-index: 999;
                transition: left 0.3s ease;
                min-height: calc(100vh - 72px);
            }
            
            .academic-sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 72px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .desktop-view { display: none !important; }
            .mobile-view { display: block !important; }
            
            .user-info { display: none; }
            
            .academic-card-body { padding: 1rem; }
            
            .footer-content .row > div {
                margin-bottom: 1.5rem;
            }
        }

        @media (min-width: 769px) {
            .desktop-view { display: block; }
            .mobile-view { display: none; }
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="academic-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="#" class="navbar-brand-custom">
                        <div class="navbar-brand-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span>Academic System</span>
                    </a>
                </div>
                <div class="navbar-user">
                    <div class="user-info">
                        <p class="user-name">Admin Akademik</p>
                        <p class="user-role">Administrator</p>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2" id="sidebar">
                <div class="academic-sidebar">
                    <div class="sidebar-menu">
                        <div class="nav-header">MAIN NAVIGATION</div>
                        <div class="sidebar-item active" data-page="dashboard">
                            <a href="#" class="sidebar-link" onclick="showDashboard('dashboard'); return false;">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </div>
                        <div class="sidebar-item" data-page="mahasiswa">
                            <a href="#" class="sidebar-link" onclick="showDashboard('mahasiswa'); return false;">
                                <i class="fas fa-users"></i>
                                <span>Data Mahasiswa</span>
                            </a>
                        </div>
                        <div class="sidebar-item" data-page="dosen">
                            <a href="#" class="sidebar-link" onclick="showDashboard('dosen'); return false;">
                                <i class="fas fa-chalkboard-user"></i>
                                <span>Data Dosen</span>
                            </a>
                        </div>
                        <div class="sidebar-item" data-page="matakuliah">
                            <a href="#" class="sidebar-link" onclick="showDashboard('matakuliah'); return false;">
                                <i class="fas fa-book"></i>
                                <span>Mata Kuliah</span>
                            </a>
                        </div>
                        <div class="nav-header mt-3">REPORTS</div>
                        <div class="sidebar-item" data-page="laporan">
                            <a href="#" class="sidebar-link" onclick="showDashboard('laporan'); return false;">
                                <i class="fas fa-chart-bar"></i>
                                <span>Laporan</span>
                            </a>
                        </div>
                        <div class="sidebar-item" data-page="pengaturan">
                            <a href="#" class="sidebar-link" onclick="showDashboard('pengaturan'); return false;">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="content-wrapper p-4">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb academic-breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" id="breadcrumb-active">Dashboard</li>
                        </ol>
                    </nav>

                    <!-- Dynamic Content -->
                    <div id="dashboard-content">
                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="academic-card">
                                    <div class="academic-card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="info-label">Total Mahasiswa</span>
                                                <h3 class="mt-2 mb-0" id="total-mahasiswa">0</h3>
                                            </div>
                                            <div class="header-icon alternate">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="academic-card">
                                    <div class="academic-card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="info-label">Total Dosen</span>
                                                <h3 class="mt-2 mb-0" id="total-dosen">0</h3>
                                            </div>
                                            <div class="header-icon alternate">
                                                <i class="fas fa-chalkboard-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="academic-card">
                                    <div class="academic-card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="info-label">Mata Kuliah</span>
                                                <h3 class="mt-2 mb-0" id="total-matakuliah">0</h3>
                                            </div>
                                            <div class="header-icon alternate">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="academic-card">
                                    <div class="academic-card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="info-label">Kelas Aktif</span>
                                                <h3 class="mt-2 mb-0" id="total-kelas">0</h3>
                                            </div>
                                            <div class="header-icon alternate">
                                                <i class="fas fa-school"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Management Card -->
                        <div class="academic-card" id="main-card">
                            <div class="academic-card-header alternate">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="card-title-academic">
                                        <div class="header-icon alternate">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h5 class="mb-0" id="card-title">Manajemen Data Mahasiswa</h5>
                                    </div>
                                    <button class="btn-create-academic" onclick="openCreateModal()" id="btn-create">
                                        <i class="fas fa-plus me-2"></i>Tambah Data
                                    </button>
                                </div>
                            </div>
                            <div class="academic-card-body">
                                <!-- Filter Section -->
                                <div class="filter-section" id="filter-section">
                                    <div class="row">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label class="filter-label">Cari Data</label>
                                            <div class="search-wrapper">
                                                <i class="fas fa-search search-icon"></i>
                                                <input type="text" class="search-input" id="search-input" placeholder="Cari nama atau NIM...">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label class="filter-label">Filter Program Studi</label>
                                            <select class="filter-select" id="filter-prodi">
                                                <option value="">Semua Prodi</option>
                                                <option value="Teknik Informatika">Teknik Informatika</option>
                                                <option value="Sistem Informasi">Sistem Informasi</option>
                                                <option value="Teknik Elektro">Teknik Elektro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="filter-label">Status</label>
                                            <select class="filter-select" id="filter-status">
                                                <option value="">Semua Status</option>
                                                <option value="Aktif">Aktif</option>
                                                <option value="Cuti">Cuti</option>
                                                <option value="Lulus">Lulus</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Table -->
                                <div class="desktop-view" id="desktop-view">
                                    <table class="academic-table">
                                        <thead>
                                            <tr><th>No</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>Status</th><th>Aksi</th></tr>
                                        </thead>
                                        <tbody id="table-body"></tbody>
                                    </table>
                                </div>

                                <!-- Mobile View -->
                                <div class="mobile-view" id="mobile-view"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="academic-footer">
            <div class="footer-content">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="footer-brand">
                            <div class="footer-brand-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <p class="footer-brand-text">Academic System</p>
                        </div>
                        <p style="font-size: 0.85rem;">Sistem informasi akademik terintegrasi untuk manajemen data mahasiswa, dosen, dan mata kuliah.</p>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4 mb-md-0">
                        <h6 style="color: white; margin-bottom: 1rem;">Menu</h6>
                        <ul class="footer-links">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Mahasiswa</a></li>
                            <li><a href="#">Dosen</a></li>
                            <li><a href="#">Mata Kuliah</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h6 style="color: white; margin-bottom: 1rem;">Lainnya</h6>
                        <ul class="footer-links">
                            <li><a href="#">Tentang Kami</a></li>
                            <li><a href="#">Bantuan</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Hubungi Kami</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6 style="color: white; margin-bottom: 1rem;">Kontak</h6>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123</li>
                            <li><i class="fas fa-phone me-2"></i> (021) 1234567</li>
                            <li><i class="fas fa-envelope me-2"></i> info@academic.com</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="mb-0">&copy; 2024 Academic System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal Create/Edit -->
    <div class="modal fade" id="crudModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content academic-modal">
                <div class="academic-modal-header">
                    <div class="d-flex gap-3">
                        <div class="modal-icon"><i class="fas fa-user-plus"></i></div>
                        <div><h5 class="modal-title" id="modal-title">Tambah Mahasiswa</h5><p class="modal-subtitle" id="modal-subtitle">Isi data dengan lengkap</p></div>
                    </div>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                </div>
                <div class="academic-modal-body">
                    <form id="crud-form">
                        <input type="hidden" id="edit-id">
                        <div class="form-group-modern">
                            <label class="form-label-modern">NIM</label>
                            <div class="position-relative">
                                <i class="fas fa-id-card position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                                <input type="text" class="form-control-modern" id="nim" required>
                            </div>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label-modern">Nama Lengkap</label>
                            <div class="position-relative">
                                <i class="fas fa-user position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                                <input type="text" class="form-control-modern" id="nama" required>
                            </div>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label-modern">Program Studi</label>
                            <select class="form-control-modern" id="prodi" style="padding-left: 42px;" required>
                                <option value="">Pilih Prodi</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Elektro">Teknik Elektro</option>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label-modern">Status</label>
                            <select class="form-control-modern" id="status" style="padding-left: 42px;" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Lulus">Lulus</option>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label-modern">Email</label>
                            <div class="position-relative">
                                <i class="fas fa-envelope position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                                <input type="email" class="form-control-modern" id="email" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="academic-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-save" onclick="saveData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content academic-modal">
                <div class="academic-modal-header">
                    <div class="d-flex gap-3">
                        <div class="modal-icon"><i class="fas fa-user-circle"></i></div>
                        <div><h5 class="modal-title">Detail Mahasiswa</h5><p class="modal-subtitle">Informasi lengkap</p></div>
                    </div>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                </div>
                <div class="academic-modal-body" id="view-details"></div>
                <div class="academic-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast-notification" style="display:none; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
        <div class="p-3 d-flex align-items-center gap-2">
            <i class="fas fa-check-circle text-success fs-4"></i>
            <div><strong id="toast-title">Berhasil</strong><p class="mb-0 small" id="toast-message"></p></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let mahasiswaData = [
            { id: 1, nim: '20210001', nama: 'Ahmad Fauzi', prodi: 'Teknik Informatika', status: 'Aktif', email: 'ahmad@example.com' },
            { id: 2, nim: '20210002', nama: 'Budi Santoso', prodi: 'Sistem Informasi', status: 'Aktif', email: 'budi@example.com' },
            { id: 3, nim: '20210003', nama: 'Citra Dewi', prodi: 'Teknik Informatika', status: 'Cuti', email: 'citra@example.com' },
            { id: 4, nim: '20210004', nama: 'Dian Pratama', prodi: 'Teknik Elektro', status: 'Aktif', email: 'dian@example.com' },
            { id: 5, nim: '20210005', nama: 'Eka Wulandari', prodi: 'Sistem Informasi', status: 'Lulus', email: 'eka@example.com' }
        ];

        let currentFilter = { search: '', prodi: '', status: '' };

        function updateStats() {
            document.getElementById('total-mahasiswa').textContent = mahasiswaData.length;
            document.getElementById('total-dosen').textContent = '12';
            document.getElementById('total-matakuliah').textContent = '8';
            document.getElementById('total-kelas').textContent = '15';
        }

        function getFilteredData() {
            return mahasiswaData.filter(item => {
                const matchSearch = !currentFilter.search || item.nama.toLowerCase().includes(currentFilter.search.toLowerCase()) || item.nim.includes(currentFilter.search);
                const matchProdi = !currentFilter.prodi || item.prodi === currentFilter.prodi;
                const matchStatus = !currentFilter.status || item.status === currentFilter.status;
                return matchSearch && matchProdi && matchStatus;
            });
        }

        function renderTable() {
            const filtered = getFilteredData();
            const tbody = document.getElementById('table-body');
            const mobileView = document.getElementById('mobile-view');
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                mobileView.innerHTML = '<div class="text-center p-4">Tidak ada data</div>';
                return;
            }
            
            tbody.innerHTML = filtered.map((item, idx) => `
                <tr>
                    <td>${idx + 1}</td>
                    <td><span class="nim">${item.nim}</span></td>
                    <td><strong>${item.nama}</strong></td>
                    <td>${item.prodi}</td>
                    <td><span class="status-badge ${item.status === 'Aktif' ? 'status-plotted' : 'status-not-plotted'}">${item.status}</span></td>
                    <td>
                        <div class="action-group">
                            <button class="action-btn action-view" onclick="viewData(${item.id})"><i class="fas fa-eye"></i></button>
                            <button class="action-btn action-edit" onclick="editData(${item.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-btn action-delete" onclick="deleteData(${item.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            mobileView.innerHTML = filtered.map(item => `
                <div class="mobile-presensi-card">
                    <div class="mobile-card-header">
                        <div class="mobile-user-info">
                            <div class="mobile-avatar"><i class="fas fa-user-graduate"></i></div>
                            <div class="mobile-user-detail">
                                <div class="mobile-user-name">${item.nama}</div>
                                <div class="mobile-user-nim"><i class="fas fa-id-card"></i> ${item.nim}</div>
                                <div class="mobile-user-prodi"><i class="fas fa-graduation-cap"></i> ${item.prodi}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-card-actions">
                        <button class="mobile-action-btn mobile-view" onclick="viewData(${item.id})"><i class="fas fa-eye"></i> Detail</button>
                        <button class="mobile-action-btn mobile-edit" onclick="editData(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="mobile-action-btn mobile-edit" style="background:#fee2e2;color:#dc2626;" onclick="deleteData(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>
            `).join('');
        }

        function openCreateModal() {
            document.getElementById('edit-id').value = '';
            document.getElementById('crud-form').reset();
            new bootstrap.Modal(document.getElementById('crudModal')).show();
        }

        function editData(id) {
            const data = mahasiswaData.find(d => d.id === id);
            if (data) {
                document.getElementById('edit-id').value = data.id;
                document.getElementById('nim').value = data.nim;
                document.getElementById('nama').value = data.nama;
                document.getElementById('prodi').value = data.prodi;
                document.getElementById('status').value = data.status;
                document.getElementById('email').value = data.email;
                new bootstrap.Modal(document.getElementById('crudModal')).show();
            }
        }

        function saveData() {
            const id = document.getElementById('edit-id').value;
            const newData = {
                nim: document.getElementById('nim').value,
                nama: document.getElementById('nama').value,
                prodi: document.getElementById('prodi').value,
                status: document.getElementById('status').value,
                email: document.getElementById('email').value
            };
            
            if (id) {
                const index = mahasiswaData.findIndex(d => d.id == id);
                if (index !== -1) mahasiswaData[index] = { ...mahasiswaData[index], ...newData };
                showToast('Berhasil', 'Data diupdate');
            } else {
                newData.id = Math.max(...mahasiswaData.map(d => d.id), 0) + 1;
                mahasiswaData.push(newData);
                showToast('Berhasil', 'Data ditambahkan');
            }
            
            bootstrap.Modal.getInstance(document.getElementById('crudModal')).hide();
            updateStats();
            renderTable();
        }

        function viewData(id) {
            const data = mahasiswaData.find(d => d.id === id);
            if (data) {
                document.getElementById('view-details').innerHTML = `
                    <div class="info-panel p-3">
                        <p><strong>NIM:</strong> ${data.nim}</p>
                        <p><strong>Nama:</strong> ${data.nama}</p>
                        <p><strong>Prodi:</strong> ${data.prodi}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('viewModal')).show();
            }
        }

        function deleteData(id) {
            if (confirm('Yakin hapus data ini?')) {
                mahasiswaData = mahasiswaData.filter(d => d.id !== id);
                updateStats();
                renderTable();
                showToast('Berhasil', 'Data dihapus');
            }
        }

        function showToast(title, message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-title').textContent = title;
            document.getElementById('toast-message').textContent = message;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000);
        }

        function showDashboard(page) {
            const breadcrumb = document.getElementById('breadcrumb-active');
            const cardTitle = document.getElementById('card-title');
            const btnCreate = document.getElementById('btn-create');
            const filterSection = document.getElementById('filter-section');
            const desktopView = document.getElementById('desktop-view');
            const mobileView = document.getElementById('mobile-view');
            
            // Update active sidebar
            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
            document.querySelector(`.sidebar-item[data-page="${page}"]`).classList.add('active');
            
            if (page === 'dashboard') {
                breadcrumb.textContent = 'Dashboard';
                cardTitle.innerHTML = '<div class="header-icon alternate"><i class="fas fa-chart-line"></i></div><h5 class="mb-0">Dashboard Akademik</h5>';
                btnCreate.style.display = 'none';
                filterSection.style.display = 'none';
                desktopView.style.display = 'none';
                mobileView.style.display = 'none';
                document.querySelector('.row.mb-4').style.display = 'flex';
            } else if (page === 'mahasiswa') {
                breadcrumb.textContent = 'Data Mahasiswa';
                cardTitle.innerHTML = '<div class="header-icon alternate"><i class="fas fa-users"></i></div><h5 class="mb-0">Manajemen Data Mahasiswa</h5>';
                btnCreate.style.display = 'flex';
                filterSection.style.display = 'block';
                desktopView.style.display = 'block';
                mobileView.style.display = 'block';
                document.querySelector('.row.mb-4').style.display = 'flex';
                renderTable();
            } else {
                breadcrumb.textContent = page.charAt(0).toUpperCase() + page.slice(1);
                cardTitle.innerHTML = `<div class="header-icon alternate"><i class="fas fa-${page === 'dosen' ? 'chalkboard-user' : page === 'matakuliah' ? 'book' : 'chart-bar'}"></i></div><h5 class="mb-0">Manajemen Data ${page.charAt(0).toUpperCase() + page.slice(1)}</h5>`;
                btnCreate.style.display = 'flex';
                filterSection.style.display = 'none';
                desktopView.style.display = 'none';
                mobileView.style.display = 'none';
                document.querySelector('.row.mb-4').style.display = 'flex';
                showToast('Info', `Fitur ${page} akan segera hadir`);
            }
            
            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('show');
                document.getElementById('sidebarOverlay').classList.remove('show');
            }
        }

        // Event listeners
        document.getElementById('search-input').addEventListener('input', e => { currentFilter.search = e.target.value; renderTable(); });
        document.getElementById('filter-prodi').addEventListener('change', e => { currentFilter.prodi = e.target.value; renderTable(); });
        document.getElementById('filter-status').addEventListener('change', e => { currentFilter.status = e.target.value; renderTable(); });
        
        // Mobile sidebar toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        });
        document.getElementById('sidebarOverlay').addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        });

        // Initialize
        updateStats();
        renderTable();
    </script>
</body>
</html>