@php
    $siteLogo = \App\Models\SiteSetting::get('site_logo');
    $logoUrl = $siteLogo ? asset('storage/' . $siteLogo) : asset('images/brand/josha-logo.png');
    $adminUser = Auth::guard('admin')->user();
    $adminInitials = collect(preg_split('/\s+/', trim($adminUser->name ?? 'A')))
        ->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/brand/favicon-32.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 0px;
            --topbar-height: 64px;

            /* Brand */
            --gold: #c9a35c;
            --gold-600: #b08a43;
            --gold-ink: #8a6a2b;
            --gold-light: #e8cf98;
            --gold-soft: #f7f0e2;
            --gold-line: #e7d7b4;
            --charcoal: #15130f;
            --charcoal-2: #100e0b;

            /* Neutrals */
            --ink: #1d1b17;
            --ink-2: #4a463e;
            --muted: #777166;
            --line: #e9e6df;
            --page-bg: #f6f5f1;

            /* Kept for pages that read the old names */
            --primary-color: var(--gold);
            --primary-hover: var(--gold-600);
            --sidebar-bg: var(--charcoal);
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --sidebar-active: var(--gold);
            --text-muted: #94a3b8;

            /* Bootstrap theme: primary = brand gold */
            --bs-primary: #8a6a2b;
            --bs-primary-rgb: 138, 106, 43;
            --bs-primary-text-emphasis: #5c461b;
            --bs-primary-bg-subtle: #f7f0e2;
            --bs-primary-border-subtle: #e7d7b4;
            --bs-link-color: #8a6a2b;
            --bs-link-color-rgb: 138, 106, 43;
            --bs-link-hover-color: #5c461b;
            --bs-link-hover-color-rgb: 92, 70, 27;
            --bs-focus-ring-color: rgba(201, 163, 92, 0.25);
            --bs-body-color: #1d1b17;
            --bs-body-color-rgb: 29, 27, 23;
            --bs-border-color: #e9e6df;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            background-color: var(--page-bg);
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ---------------- Top bar ---------------- */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: var(--charcoal-2);
            border-bottom: 1px solid rgba(201, 163, 92, 0.22);
            display: flex;
            align-items: center;
            padding: 0 1.25rem 0 0.75rem;
            z-index: 1040;
        }

        .admin-topbar .brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            min-width: 0;
        }

        .admin-topbar .brand img {
            height: 50px;
            width: auto;
            display: block;
            filter: drop-shadow(0 1px 6px rgba(201, 163, 92, 0.18));
        }

        .admin-topbar .brand-label {
            padding-left: 0.8rem;
            border-left: 1px solid rgba(201, 163, 92, 0.35);
            color: var(--gold-light);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }

        .toggle-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            border-radius: 10px;
            color: #b9b2a3;
            transition: background 0.2s, color 0.2s;
            margin-right: 0.6rem;
            flex-shrink: 0;
        }

        .toggle-btn:hover,
        .toggle-btn:focus-visible {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            outline: none;
        }

        .toggle-btn i {
            font-size: 1.15rem;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .visit-site-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            height: 38px;
            padding: 0 0.95rem;
            border: 1px solid rgba(201, 163, 92, 0.45);
            border-radius: 9px;
            background: transparent;
            color: var(--gold-light);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
        }

        .visit-site-btn:hover {
            background: rgba(201, 163, 92, 0.12);
            border-color: var(--gold);
            color: #fff;
        }

        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            height: 40px;
            padding: 0 0.6rem 0 0.25rem;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 999px;
            color: #e9e4d9;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s, border-color 0.2s;
        }

        .user-dropdown .dropdown-toggle:hover,
        .user-dropdown .dropdown-toggle[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .user-dropdown .dropdown-toggle::after {
            margin-left: 0.15rem;
            color: #8d8576;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            background: linear-gradient(135deg, #e3c27f, #b08a43);
            color: #1a1408;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            flex-shrink: 0;
        }

        .dropdown-user-head {
            padding: 0.6rem 1rem 0.7rem;
            border-bottom: 1px solid var(--line);
            margin-bottom: 0.35rem;
        }

        .dropdown-user-head strong {
            display: block;
            font-size: 0.9rem;
            color: var(--ink);
        }

        .dropdown-user-head span {
            font-size: 0.78rem;
            color: var(--muted);
        }

        /* ---------------- Sidebar ---------------- */
        .admin-sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: linear-gradient(180deg, #17150f 0%, #110f0c 100%);
            border-right: 1px solid rgba(201, 163, 92, 0.1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
            transition: transform 0.3s ease, width 0.3s ease;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu {
            padding: 1rem 0 1.25rem;
        }

        .menu-group + .menu-group {
            margin-top: 0.35rem;
        }

        .menu-heading {
            padding: 1rem 1.5rem 0.45rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #8a826f;
        }

        .menu-item {
            padding: 0 0.75rem;
            margin-bottom: 2px;
        }

        .menu-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 0.85rem;
            color: #cfc8b9;
            text-decoration: none;
            border-radius: 9px;
            transition: background 0.18s, color 0.18s;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .menu-link i {
            width: 18px;
            text-align: center;
            font-size: 0.95rem;
            color: #8d8576;
            transition: color 0.18s;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-link:hover i {
            color: var(--gold);
        }

        .menu-link:focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: -2px;
        }

        .menu-link.active {
            background: linear-gradient(90deg, rgba(201, 163, 92, 0.2) 0%, rgba(201, 163, 92, 0.06) 100%);
            color: #f4e2bb;
            font-weight: 600;
        }

        .menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 9px;
            bottom: 9px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--gold);
        }

        .menu-link.active i {
            color: var(--gold);
        }

        .menu-badge {
            margin-left: auto;
            min-width: 22px;
            height: 20px;
            padding: 0 0.45rem;
            border-radius: 999px;
            background: #d64545;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-grid;
            place-items: center;
            line-height: 1;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-footer-text {
            font-size: 0.72rem;
            color: #8a826f;
        }

        .sidebar-footer-name {
            color: #e9e4d9;
            font-weight: 600;
            font-size: 0.85rem;
            line-height: 1.25;
        }

        /* ---------------- Main content ---------------- */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 1.75rem 2rem 1.25rem;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .admin-sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .admin-main {
            margin-left: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 9, 7, 0.55);
            z-index: 1025;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            .admin-main {
                margin-left: 0;
                padding: 1.25rem 1rem 1rem;
            }

            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
                box-shadow: 12px 0 40px rgba(0, 0, 0, 0.35);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }
        }

        @media (max-width: 575.98px) {
            .admin-topbar {
                padding: 0 0.75rem 0 0.4rem;
            }

            .admin-topbar .brand img {
                height: 40px;
            }

            .visit-site-btn {
                width: 38px;
                padding: 0;
                justify-content: center;
            }

            .topbar-right {
                gap: 0.4rem;
            }
        }

        /* ---------------- Shared components ---------------- */
        .card {
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(23, 20, 15, 0.04);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--line);
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-radius: 14px 14px 0 0 !important;
        }

        .card-body {
            padding: 1.25rem;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 0.95rem 1.25rem;
        }

        .alert-success {
            background: #e3f4e8;
            color: #1d6b39;
        }

        .alert-danger {
            background: #fde6e4;
            color: #9b2c22;
        }

        .alert-warning {
            background: #fdf1d8;
            color: #7a5410;
        }

        .alert-info {
            background: #eaf1f8;
            color: #2a4f74;
        }

        .table {
            margin-bottom: 0;
            --bs-table-hover-bg: #faf8f3;
        }

        .table thead th {
            background: #faf9f6;
            border-bottom: 1px solid var(--line);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6f695e;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1efe9;
        }

        .table tbody tr:hover {
            background: #faf8f3;
        }

        .btn {
            font-weight: 500;
            border-radius: 9px;
            padding: 0.5rem 1rem;
        }

        .btn-sm {
            border-radius: 7px;
            padding: 0.3rem 0.65rem;
        }

        .btn-primary {
            --bs-btn-color: #1a1408;
            --bs-btn-bg: var(--gold);
            --bs-btn-border-color: var(--gold);
            --bs-btn-hover-color: #120e05;
            --bs-btn-hover-bg: #ba944d;
            --bs-btn-hover-border-color: #ba944d;
            --bs-btn-focus-shadow-rgb: 201, 163, 92;
            --bs-btn-active-color: #120e05;
            --bs-btn-active-bg: #a8823d;
            --bs-btn-active-border-color: #a8823d;
            --bs-btn-disabled-color: #1a1408;
            --bs-btn-disabled-bg: var(--gold);
            --bs-btn-disabled-border-color: var(--gold);
            font-weight: 600;
        }

        .btn-outline-primary {
            --bs-btn-color: var(--gold-ink);
            --bs-btn-border-color: #d9c293;
            --bs-btn-hover-color: #1a1408;
            --bs-btn-hover-bg: var(--gold);
            --bs-btn-hover-border-color: var(--gold);
            --bs-btn-focus-shadow-rgb: 201, 163, 92;
            --bs-btn-active-color: #1a1408;
            --bs-btn-active-bg: #ba944d;
            --bs-btn-active-border-color: #ba944d;
            --bs-btn-disabled-color: var(--gold-ink);
            --bs-btn-disabled-border-color: #d9c293;
        }

        .btn-dark {
            --bs-btn-bg: var(--charcoal);
            --bs-btn-border-color: var(--charcoal);
            --bs-btn-hover-bg: #2a261f;
            --bs-btn-hover-border-color: #2a261f;
        }

        .pagination {
            --bs-pagination-color: var(--gold-ink);
            --bs-pagination-hover-color: #5c461b;
            --bs-pagination-focus-color: #5c461b;
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(201, 163, 92, 0.25);
            --bs-pagination-active-color: #1a1408;
            --bs-pagination-active-bg: var(--gold);
            --bs-pagination-active-border-color: var(--gold);
        }

        .badge {
            font-weight: 600;
            padding: 0.4em 0.7em;
            border-radius: 6px;
        }

        .form-control, .form-select {
            border-radius: 9px;
            border: 1px solid #e0dcd2;
            padding: 0.625rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 163, 92, 0.18);
        }

        .form-check-input:checked {
            background-color: var(--gold-600);
            border-color: var(--gold-600);
        }

        .form-check-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.25rem rgba(201, 163, 92, 0.2);
        }

        .form-switch .form-check-input:focus:not(:checked) {
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23c9a35c'/%3e%3c/svg%3e");
        }

        .form-label {
            font-weight: 500;
            color: #3a362f;
            margin-bottom: 0.5rem;
        }

        .page-title,
        .admin-main h1.h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.01em;
        }

        .page-title {
            margin-bottom: 0;
        }

        .nav-tabs .nav-link.active {
            color: var(--ink);
        }

        .admin-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 1.25rem 0.25rem 0.25rem;
            margin-top: 2.5rem;
            color: var(--muted);
            font-size: 0.8rem;
            border-top: 1px solid var(--line);
        }

        .dropdown-menu {
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(23, 20, 15, 0.12);
            padding: 0.4rem;
        }

        .dropdown-item {
            border-radius: 7px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: #f6f4ee;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Top Navbar -->
    <nav class="admin-topbar">
        <button class="toggle-btn" id="sidebarToggle" type="button" aria-label="Toggle menu" aria-controls="adminSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="brand" href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }} admin dashboard">
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}">
            <span class="brand-label">Admin</span>
        </a>

        <div class="topbar-right">
            <a class="visit-site-btn" href="{{ route('home') }}" target="_blank" rel="noopener" title="Open the website in a new tab">
                <i class="fas fa-up-right-from-square"></i><span class="d-none d-sm-inline">Visit Site</span>
            </a>
            <div class="dropdown user-dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar" aria-hidden="true">{{ $adminInitials }}</span>
                    <span class="d-none d-md-inline">{{ $adminUser->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-user-head">
                        <strong>{{ $adminUser->name }}</strong>
                        <span>{{ $adminUser->email ?? 'Administrator' }}</span>
                    </li>
                    @if(Route::has('admin.profile'))
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="fas fa-user-gear me-2 text-muted"></i>My Profile
                        </a>
                    </li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        @include('admin.partials.sidebar')

        <div class="sidebar-footer">
            <span class="avatar" aria-hidden="true">{{ $adminInitials }}</span>
            <div style="min-width: 0;">
                <div class="sidebar-footer-text">Logged in as</div>
                <div class="sidebar-footer-name text-truncate">{{ $adminUser->name }}</div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

        <footer class="admin-footer">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
            <span>Admin panel &middot; times in IST</span>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            // Check screen size
            function isMobile() {
                return window.innerWidth < 992;
            }

            // Toggle sidebar
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();

                if (isMobile()) {
                    body.classList.toggle('sidebar-open');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                }
            });

            // Close sidebar when clicking overlay (mobile)
            overlay.addEventListener('click', function() {
                body.classList.remove('sidebar-open');
            });

            // Close the mobile sidebar with Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && body.classList.contains('sidebar-open')) {
                    body.classList.remove('sidebar-open');
                    sidebarToggle.focus();
                }
            });

            // Handle resize
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    body.classList.remove('sidebar-open');
                }
            });

            // Keep the active menu item in view when the menu is long
            const activeLink = sidebar.querySelector('.menu-link.active');
            if (activeLink && activeLink.offsetTop > sidebar.clientHeight - 80) {
                sidebar.scrollTop = activeLink.offsetTop - sidebar.clientHeight / 2;
            }

            // Initialize DataTables
            $('table.dataTable').each(function() {
                $(this).DataTable();
            });

            // Initialize Select2
            $('.select2').select2();

            // Initialize Summernote
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
