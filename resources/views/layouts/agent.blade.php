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
    <title>@yield('title', config('app.name'))</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Glide.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="{{ $logoUrl }}">
    <!-- Law Firm Premium Theme CSS -->
    <link href="{{ asset('css/lawfirm-theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="law-header" id="mainHeader">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ $logoUrl }}" alt="Bengaluru Law House" class="logo-img">
                    <span class="brand-text d-none d-md-inline">Bengaluru Law House</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                                <i class="fas fa-home nav-icon"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://news.blrlawhouse.com" target="_blank">
                                <i class="fas fa-newspaper nav-icon"></i> News
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/about-us') }}">
                                <i class="fas fa-info-circle nav-icon"></i> About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contact-us') }}">
                                <i class="fas fa-envelope nav-icon"></i> Contact Us
                            </a>
                        </li>

                        @auth('agent')
                            <!-- Distributor Dropdown -->
                            <li class="nav-item dropdown more-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-tie nav-icon"></i> {{ Auth::guard('agent')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}" href="{{ route('agent.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('agent.commissions') ? 'active' : '' }}" href="{{ route('agent.commissions') }}">
                                            <i class="fas fa-money-bill-wave me-2"></i> Commissions
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('agent.profile') ? 'active' : '' }}" href="{{ route('agent.profile') }}">
                                            <i class="fas fa-user-cog me-2"></i> Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('agent.logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <!-- More Dropdown for Non-Logged Users -->
                            <li class="nav-item dropdown more-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h nav-icon"></i> More
                                </a>
                                <ul class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('agent.login') }}">
                                            <i class="fas fa-sign-in-alt me-2"></i> Distributor Login
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item highlight-item" href="{{ route('agent.register') }}">
                                            <i class="fas fa-user-plus me-2"></i> Become Distributor
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <div class="header-accent"></div>
    </header>

    <!-- Main Content -->
    <main class="agent-main-content">
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="premium-footer">
        <!-- Footer Top Wave -->
        <div class="footer-wave">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,50 C360,100 720,0 1080,50 C1260,75 1380,62.5 1440,50 L1440,100 L0,100 Z" fill="#0a1628"/>
            </svg>
        </div>

        <div class="footer-main-content">
            <div class="container">
                <!-- Footer Top Section -->
                <div class="footer-top">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-6">
                            <div class="footer-brand-section">
                                <img src="{{ $logoUrl }}" alt="Bengaluru Law House" class="footer-logo">
                                <div class="footer-brand-info">
                                    <h3>Bengaluru Law House</h3>
                                    <p>Publications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Main Links -->
                <div class="footer-links-section">
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">About Us</h5>
                                <p class="footer-about-text">
                                    Bengaluru Law House Publications is one of India's leading providers of authoritative legal books and academic resources for students, advocates, law firms, and judicial institutions.
                                </p>
                                <div class="footer-contact-quick">
                                    <a href="tel:+918217215195" class="quick-contact">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>+91 8217215195</span>
                                    </a>
                                    <a href="mailto:bengalurulawhouse@gmail.com" class="quick-contact">
                                        <i class="fas fa-envelope"></i>
                                        <span>bengalurulawhouse@gmail.com</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Quick Links</h5>
                                <ul class="footer-links">
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ route('products.index') }}">Publications</a></li>
                                    <li><a href="{{ url('/about-us') }}">About Us</a></li>
                                    <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Legal</h5>
                                <ul class="footer-links">
                                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                                    <li><a href="{{ url('/terms-conditions') }}">Terms & Conditions</a></li>
                                    <li><a href="{{ url('/shipping-policy') }}">Shipping Policy</a></li>
                                    <li><a href="{{ url('/refund-return-policy') }}">Refund Policy</a></li>
                                    <li><a href="{{ url('/disclamer') }}">Disclaimer</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Distributor</h5>
                                <ul class="footer-links">
                                    <li><a href="{{ route('agent.login') }}">Distributor Login</a></li>
                                    <li><a href="{{ route('agent.register') }}">Become Distributor</a></li>
                                    <li><a href="{{ route('track.order') }}">Track Order</a></li>
                                </ul>
                                <div class="footer-badge">
                                    <img src="https://razorpay.com/assets/razorpay-logo-new.svg" alt="Razorpay" style="height: 25px; filter: brightness(0) invert(1);">
                                    <span>Secure Payments</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright-text">
                            &copy; {{ date('Y') }} Bengaluru Law House Publications. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="developed-by">
                            Developed by <a href="#">Supremeone Software</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="footer-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/918217215195" class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp">
        <div class="whatsapp-pulse"></div>
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Theme JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header scroll effect
            const header = document.getElementById('mainHeader');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Back to top button
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

    <style>
        /* Agent Main Content */
        .agent-main-content {
            padding-top: 90px;
            min-height: calc(100vh - 300px);
            background: var(--cream-primary);
        }

        /* Header Enhancements */
        .nav-icon {
            margin-right: 0.5rem;
            font-size: 0.85em;
        }

        .more-dropdown .dropdown-menu-animated {
            background: var(--navy-deep);
            border: 1px solid rgba(201, 162, 39, 0.2);
            border-radius: 12px;
            padding: 0.75rem 0;
            margin-top: 0.75rem;
            min-width: 220px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: dropdownFade 0.3s ease;
            right: 0;
            left: auto;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .more-dropdown .dropdown-item {
            color: var(--cream-primary);
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .more-dropdown .dropdown-item:hover {
            background: rgba(201, 162, 39, 0.15);
            color: var(--gold-primary);
            padding-left: 2rem;
        }

        .more-dropdown .dropdown-item.active {
            background: rgba(201, 162, 39, 0.2);
            color: var(--gold-primary);
        }

        .more-dropdown .dropdown-item.highlight-item {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            color: var(--navy-deep);
            margin: 0.5rem 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
            font-size: 0.85rem;
            width: calc(100% - 1.5rem);
            box-sizing: border-box;
        }

        .more-dropdown .dropdown-item.highlight-item:hover {
            transform: scale(1.02);
            padding-left: 1rem;
        }

        .more-dropdown .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
        }

        .more-dropdown .dropdown-divider {
            border-color: rgba(201, 162, 39, 0.15);
            margin: 0.5rem 0;
        }

        /* Premium Footer Styles */
        .premium-footer {
            position: relative;
            background: var(--navy-deep);
            overflow: hidden;
        }

        .footer-wave {
            position: relative;
            margin-bottom: -5px;
        }

        .footer-wave svg {
            display: block;
            width: 100%;
            height: 80px;
        }

        .footer-main-content {
            padding: 0 0 3rem;
        }

        .footer-top {
            padding: 2rem 0 3rem;
            border-bottom: 1px solid rgba(201, 162, 39, 0.15);
        }

        .footer-brand-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .footer-logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 2px solid var(--gold-primary);
            padding: 5px;
            background: white;
        }

        .footer-brand-info h3 {
            color: var(--gold-primary);
            font-family: var(--font-heading);
            font-size: 1.75rem;
            margin-bottom: 0.25rem;
        }

        .footer-brand-info p {
            color: rgba(250, 248, 243, 0.7);
            margin: 0;
            font-size: 1rem;
        }

        .footer-social-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1.5rem;
        }

        .social-label {
            color: rgba(250, 248, 243, 0.7);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .social-icons {
            display: flex;
            gap: 0.75rem;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            background: rgba(201, 162, 39, 0.1);
            border: 1px solid rgba(201, 162, 39, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--gold-primary);
            color: var(--navy-deep);
            transform: translateY(-5px) rotate(5deg);
            box-shadow: 0 10px 25px rgba(201, 162, 39, 0.3);
        }

        .footer-links-section {
            padding: 3rem 0;
        }

        .footer-widget {
            margin-bottom: 1.5rem;
        }

        .widget-title {
            color: var(--gold-primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .widget-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--gold-primary);
        }

        .footer-about-text {
            color: rgba(250, 248, 243, 0.7);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-contact-quick {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .quick-contact {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--cream-primary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }

        .quick-contact i {
            color: var(--gold-primary);
            width: 20px;
        }

        .quick-contact:hover {
            color: var(--gold-primary);
            transform: translateX(5px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(250, 248, 243, 0.7);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--gold-primary);
            transform: translateX(5px);
        }

        .footer-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(201, 162, 39, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(201, 162, 39, 0.2);
        }

        .footer-badge span {
            color: rgba(250, 248, 243, 0.7);
            font-size: 0.85rem;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem 0;
        }

        .copyright-text {
            color: rgba(250, 248, 243, 0.5);
            font-size: 0.9rem;
            margin: 0;
        }

        .developed-by {
            color: rgba(250, 248, 243, 0.5);
            font-size: 0.9rem;
            margin: 0;
        }

        .developed-by a {
            color: var(--gold-primary);
            text-decoration: none;
        }

        .developed-by a:hover {
            text-decoration: underline;
        }

        .footer-decoration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.1) 0%, transparent 70%);
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
        }

        /* WhatsApp Float Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            transition: transform 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
        }

        .whatsapp-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #25D366;
            animation: whatsappPulse 2s infinite;
        }

        @keyframes whatsappPulse {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 998;
            width: 45px;
            height: 45px;
            background: var(--navy-primary);
            border: 2px solid var(--gold-primary);
            border-radius: 50%;
            color: var(--gold-primary);
            font-size: 1rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--gold-primary);
            color: var(--navy-deep);
            transform: translateY(-5px);
        }

        /* Mobile responsive */
        @media (max-width: 991px) {
            .law-header .navbar-collapse {
                background: var(--navy-deep);
                padding: 1.5rem;
                margin-top: 1rem;
                border-radius: 12px;
                border: 1px solid rgba(201, 162, 39, 0.1);
            }

            .law-header .nav-link {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid rgba(201, 162, 39, 0.1);
            }

            .navbar-toggler {
                border-color: var(--gold-primary);
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(201, 162, 39, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }

            .footer-social-section {
                justify-content: flex-start;
                margin-top: 1.5rem;
            }

            .footer-brand-section {
                flex-direction: column;
                text-align: center;
            }

            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 55px;
                height: 55px;
            }

            .back-to-top {
                bottom: 85px;
                right: 20px;
                width: 40px;
                height: 40px;
            }
        }
    </style>

    @stack('scripts')
</body>
</html>
