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
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Admin Layout Styles */
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
            position: fixed;
            top: 80px;
            left: 0;
            height: calc(100vh - 80px);
            z-index: 1035;
        }
        
        .sb-nav-fixed #layoutSidenav #layoutSidenav_content {
            padding-left: 225px;
            top: 56px;
        }
        
        #layoutSidenav {
            display: flex;
        }
        
        #layoutSidenav_nav {
            flex-basis: 225px;
            flex-shrink: 0;
            transition: transform 0.15s ease-in-out;
            z-index: 1038;
        }
        
        #layoutSidenav_content {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            flex-grow: 1;
            min-height: calc(100vh - 56px);
            margin-left: -100px;
        }
        
        .sb-sidenav {
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-wrap: nowrap;
        }
        
        .sb-sidenav .sb-sidenav-menu {
            flex-grow: 1;
        }
        
        .sb-sidenav .sb-sidenav-menu .nav {
            flex-direction: column;
            flex-wrap: nowrap;
        }
        
        .sb-sidenav .sb-sidenav-menu .nav .sb-sidenav-menu-heading {
            padding: 1.75rem 1rem 0.75rem;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sb-sidenav .sb-sidenav-menu .nav .nav-link {
            display: flex;
            align-items: center;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            position: relative;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* FIX: Reset pagination icon size */
        
        .sb-sidenav .sb-sidenav-menu .nav .nav-link .sb-nav-link-icon {
            font-size: 0.9rem;
            padding-right: 0.5rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sb-sidenav .sb-sidenav-menu .nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.75);
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .sb-sidenav .sb-sidenav-menu .nav .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sb-sidenav .sb-sidenav-menu .nav .nav-link.active .sb-nav-link-icon {
            color: #fff;
        }
        
        .sb-sidenav .sb-sidenav-footer {
            padding: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sb-sidenav-dark {
            background-color: #212529;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            color: rgba(255, 255, 255, 0.75);
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
            color: #fff;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 767.98px) {
            .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
                transform: translateX(-225px);
            }
            
            .sb-nav-fixed #layoutSidenav #layoutSidenav_content {
                padding-left: 0;
            }
            
            .sb-nav-fixed.sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
                transform: translateX(0);
            }
            
            .sb-nav-fixed.sb-sidenav-toggled #layoutSidenav #layoutSidenav_content {
                padding-left: 0;
            }
        }

        @media (max-width: 767.98px) {
            #layoutSidenav_nav {
                transform: translateX(-225px);
            }

            #layoutSidenav_content {
                margin-left: 0;
            }

            body.sb-sidenav-toggled #layoutSidenav_nav {
                transform: translateX(0);
            }

            body.sb-sidenav-toggled #layoutSidenav_content {
                margin-left: 0;
                 width: 60%;
            }
        }
        
        /* Main content styling */
        .container-fluid {
            padding-top: 1.5rem;
        }
        
        /* Card styling */
        .card {
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            border-bottom: 1px solid #e3e6f0;
            background-color: #f8f9fc;
        }
        
        /* Alert styling */
        .alert {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        /* Table styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-top: none;
            border-bottom-width: 1px;
            font-weight: 600;
        }
        
        /* Badge styling */
        .badge {
            font-weight: 500;
        }
        
        /* Progress bar styling */
        .progress {
            margin-bottom: 1rem;
        }
        
        /* Form control styling */
        .form-control:focus, .form-select:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* Button styling */
        .btn {
            font-weight: 500;
        }
        
        /* Avatar */
        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .avatar-md {
            width: 3.5rem;
            height: 3.5rem;
        }
        
        .avatar-lg {
            width: 4.5rem;
            height: 4.5rem;
        }
        
        .avatar-xl {
            width: 5.5rem;
            height: 5.5rem;
        }
        
        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Footer */
        footer {
            border-top: 1px solid #e3e6f0;
        }
    </style>
    @stack('styles')
</head>
<body class="sb-nav-fixed">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{asset('blr.jpg')}}" height="40" style="padding-right:10px;">Admin
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-lg-0 text-white" id="sidebarToggle" href="#">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Right Side -->
            <div class="d-flex align-items-center ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::guard('admin')->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="dropdown-item p-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-decoration-none text-dark p-0 ps-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Main</div>
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link" href="{{ route('admin.products.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Products
                        </a>
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Orders
                        </a>
                        <a class="nav-link" href="{{ route('admin.agents.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Agents
                        </a>
                        <a class="nav-link" href="{{ route('admin.commissions.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Commissions
                        </a>
                        <a class="nav-link" href="{{ route('admin.settlements.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
                            Settlements
                        </a>
                      <a class="nav-link {{ Route::is('admin.pages.*') ? 'active' : '' }}"
                         href="{{ route('admin.pages.index') }}">
                          <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                          Pages
                      </a>
                        
                        <div class="sb-sidenav-menu-heading">System</div>
                        <a class="nav-link" href="{{ route('admin.profile') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            Settings
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ Auth::guard('admin')->user()->name }}
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto" style="padding-left:20px;">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </div>
                        <div>
                            <a href="#">Privacy Policy</a> &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebarToggle').click(function (e) {
                e.preventDefault();

                if ($('body').hasClass('sb-sidenav-toggled')) {
                    // Sidebar is open → close it
                    $('body').removeClass('sb-sidenav-toggled');
                  //alert("removed");
                } else {
                    // Sidebar is closed → open it
                    $('body').addClass('sb-sidenav-toggled');
                  //alert("opened");
                }
            });

            // Initialize DataTables
            $('table').each(function() {
                if ($(this).hasClass('dataTable')) {
                    $(this).DataTable();
                }
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