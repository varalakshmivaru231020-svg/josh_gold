@php
    $siteLogo = \App\Models\SiteSetting::get('site_logo');
    $logoUrl = $siteLogo ? asset('storage/' . $siteLogo) : asset('blr.jpg');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>

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
            --topbar-height: 60px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #3b82f6;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        /* Top Navbar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            z-index: 1040;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .admin-topbar .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
            text-decoration: none;
            min-width: 200px;
        }

        .admin-topbar .brand img {
            height: 36px;
            border-radius: 6px;
        }

        .toggle-btn {
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 8px;
            color: #64748b;
            transition: all 0.2s;
            margin-right: 1rem;
        }

        .toggle-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .toggle-btn i {
            font-size: 1.25rem;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #1e293b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .user-dropdown .dropdown-toggle:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .user-dropdown .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: var(--sidebar-bg);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
            transition: transform 0.3s ease, width 0.3s ease;
        }

        /* Custom Scrollbar for Sidebar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-heading {
            padding: 1rem 1.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
        }

        .menu-item {
            padding: 0 0.75rem;
            margin-bottom: 2px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .menu-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #334155;
            margin-top: auto;
        }

        .sidebar-footer-text {
            font-size: 0.75rem;
            color: #64748b;
        }

        .sidebar-footer-name {
            color: #cbd5e1;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
        }

        /* Sidebar Toggle States */
        body.sidebar-collapsed .admin-sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .admin-main {
            margin-left: 0;
        }

        /* Mobile Styles */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-main {
                margin-left: 0;
            }

            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1025;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Alert styling */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Table styling */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 0.875rem 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Button styling */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Badge styling */
        .badge {
            font-weight: 500;
            padding: 0.4em 0.75em;
            border-radius: 6px;
        }

        /* Form styling */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.625rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Page Title */
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
        }

        /* Footer */
        .admin-footer {
            padding: 1.5rem;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            border-top: 1px solid #e2e8f0;
            background: #fff;
            margin-top: 2rem;
            border-radius: 12px;
        }

        /* Dropdown menu */
        .dropdown-menu {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Top Navbar -->
    <nav class="admin-topbar">
        <button class="toggle-btn" id="sidebarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ $logoUrl }}" alt="Logo">
            <span>Admin Panel</span>
        </a>

        <div class="topbar-right">
            <div class="dropdown user-dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                    <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
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
        <div class="sidebar-menu">
            <!-- Main -->
            <div class="menu-heading">Main</div>
            <div class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Management -->
            <div class="menu-heading">Management</div>
            <div class="menu-item">
                <a href="{{ route('admin.products.index') }}" class="menu-link {{ Route::is('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.orders.index') }}" class="menu-link {{ Route::is('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.agents.index') }}" class="menu-link {{ Route::is('admin.agents.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Agents</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.commissions.index') }}" class="menu-link {{ Route::is('admin.commissions.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Commissions</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.settlements.index') }}" class="menu-link {{ Route::is('admin.settlements.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Settlements</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.pages.index') }}" class="menu-link {{ Route::is('admin.pages.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Pages</span>
                </a>
            </div>

            <!-- Site Content -->
            <div class="menu-heading">Site Content</div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.sliders') }}" class="menu-link {{ Route::is('admin.site-content.sliders*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Sliders</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.about') }}" class="menu-link {{ Route::is('admin.site-content.about*') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>About Section</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.featured-book') }}" class="menu-link {{ Route::is('admin.site-content.featured-book*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Featured Book</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.testimonials') }}" class="menu-link {{ Route::is('admin.site-content.testimonials*') ? 'active' : '' }}">
                    <i class="fas fa-video"></i>
                    <span>Videos</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.order-flow') }}" class="menu-link {{ Route::is('admin.site-content.order-flow*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Order Flow</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.whatsapp-flow') }}" class="menu-link {{ Route::is('admin.site-content.whatsapp-flow*') ? 'active' : '' }}">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp Flow</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.promo-banners') }}" class="menu-link {{ Route::is('admin.site-content.promo-banners*') ? 'active' : '' }}">
                    <i class="fas fa-ad"></i>
                    <span>Promo Banners</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.settings') }}" class="menu-link {{ Route::is('admin.site-content.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Site Settings</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.blogs') }}" class="menu-link {{ Route::is('admin.site-content.blogs*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Blog Posts</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.contact-settings') }}" class="menu-link {{ Route::is('admin.site-content.contact-settings*') ? 'active' : '' }}">
                    <i class="fas fa-address-card"></i>
                    <span>Contact Settings</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('admin.site-content.contact-messages') }}" class="menu-link {{ Route::is('admin.site-content.contact-messages*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Contact Messages</span>
                    @php $unreadCount = \App\Models\ContactMessage::unread()->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </div>

            <!-- System -->
            <div class="menu-heading">System</div>
            <div class="menu-item">
                <a href="{{ route('admin.profile') }}" class="menu-link {{ Route::is('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-footer-text">Logged in as:</div>
            <div class="sidebar-footer-name">{{ Auth::guard('admin')->user()->name }}</div>
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
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            // Handle resize
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    body.classList.remove('sidebar-open');
                }
            });

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
