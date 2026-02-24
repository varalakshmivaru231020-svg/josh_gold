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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- AOS Animate on Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8c878;
            --gold-dark: #8b6914;
            --navy: #0c1428;
            --navy-mid: #152040;
            --navy-light: #1e2f58;
            --cream: #f8f5ef;
            --cream-dark: #ede8de;
            --text: #2a2a2a;
            --text-muted: #666;
            --white: #ffffff;
            --font-display: "Cormorant Garamond", Georgia, serif;
            --font-body: "Outfit", Arial, sans-serif;
        }

        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; scroll-behavior:smooth; }

        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:var(--navy); }
        ::-webkit-scrollbar-thumb { background:var(--gold); border-radius:10px; }

        /* ── NAVIGATION ── */
        .nav-premium {
            position: fixed; top:0; left:0; right:0; z-index:1000;
            background: rgba(12,20,40,0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(201,168,76,0.2);
            padding: 0 60px;
            display: flex; align-items:center; justify-content:space-between;
            height: 85px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .nav-premium.scrolled { height: 75px; background: rgba(10,15,30,0.98); box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        
        .nav-brand { display:flex; align-items:center; gap:15px; text-decoration:none; }
        .nav-brand-seal {
            width:45px; height:45px;
            background: linear-gradient(135deg, #c9a84c, #8b6914);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #0c1428; 
            box-shadow: 0 0 15px rgba(201,168,76,0.3);
            border: 2px solid rgba(255,255,255,0.1);
        }
        .nav-brand-text { 
            font-family: var(--font-display); color: #c9a84c; 
            font-size: 1.25rem; font-weight: 800; line-height: 1.1; 
            text-transform: uppercase; letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .nav-brand-sub { font-size: 0.68rem; color: rgba(255,255,255,0.6); letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-top: 2px; }

        .nav-links { display:flex; gap:10px; align-items:center; list-style: none; margin: 0; padding: 0; }
        .nav-link-custom {
            color: rgba(255,255,255,0.7); font-size: 0.72rem; letter-spacing: 2px;
            text-transform: uppercase; text-decoration: none; padding: 12px 18px; border-radius: 8px;
            transition: all 0.3s; font-weight: 700;
        }
        .nav-link-custom:hover { color: var(--gold); background: rgba(201,168,76,0.1); }
        .nav-link-custom.active { color: var(--gold); }
        
        .nav-cta {
            background: linear-gradient(135deg, var(--gold), #b39441);
            color: #0c1428 !important; font-weight: 800 !important;
            border-radius: 50px; padding: 12px 28px !important; margin-left: 15px;
            box-shadow: 0 10px 20px rgba(201,168,76,0.2);
            border: none;
        }
        .nav-cta:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(201,168,76,0.3); filter: brightness(1.1); }

        .nav-toggle { display:none; background:none; border:none; cursor:pointer; }
        .nav-toggle span { display:block; width:22px; height:2px; background:var(--gold); margin:5px 0; transition:all 0.3s; border-radius:2px; }


        /* ── NEW PREMIUM FOOTER ── */
        .footer-lux {
            background: #0a1122;
            padding: 100px 0 60px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .footer-lux::before {
            content: ''; position: absolute; inset: 0;
            background-image: 
                linear-gradient(rgba(201,168,76,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }
        
        .footer-lux-title {
            font-family: var(--font-display);
            font-size: 1.8rem;
            color: var(--gold);
            font-weight: 700;
            margin-bottom: 30px;
            display: block;
            text-align: center;
        }
        
        .footer-lux-nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 40px;
            list-style: none;
            padding: 0;
        }
        .footer-lux-nav a {
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .footer-lux-nav a:hover {
            color: var(--gold);
            transform: translateY(-2px);
        }
        
        .footer-lux-copyright {
            text-align: center;
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        .footer-lux-location {
            text-align: center;
            color: rgba(255,255,255,0.3);
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* ── CONNECT CARDS IN FOOTER ── */
        .footer-connect-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 80px;
        }
        .f-connect-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(201,168,76,0.15);
            border-radius: 12px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.4s;
        }
        .f-connect-card:hover {
            background: rgba(201,168,76,0.08);
            border-color: var(--gold);
            transform: translateY(-5px);
        }
        .f-connect-icon {
            font-size: 1.8rem;
            color: var(--gold);
            margin-bottom: 20px;
            display: block;
        }
        .f-connect-label {
            color: var(--gold);
            font-size: 0.7rem;
            letter-spacing: 2px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
            opacity: 0.8;
        }
        .f-connect-text {
            color: rgba(255,255,255,0.85);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
            white-space: pre-line;
        }

        @media (max-width: 991px) {
            .footer-connect-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 576px) {
            .footer-connect-grid { grid-template-columns: 1fr; }
            .footer-lux-nav { gap: 15px; }
            .footer-lux { padding: 60px 0 40px; }
        }

        /* ── FOLLOW ONLINE GLOBAL SECTION ── */
        .follow-online-global {
            background: #ffffff;
            padding: 80px 0;
            position: relative;
        }
        .follow-card-grid-lux {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .follow-card-lux {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 30px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            color: #ffffff;
        }
        .follow-card-lux:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            color: #ffffff;
        }
        .f-card-icon-lux {
            font-size: 2.2rem;
            flex-shrink: 0;
        }
        .f-card-info-lux {
            display: flex;
            flex-direction: column;
        }
        .f-card-platform-lux {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            opacity: 0.9;
            margin-bottom: 4px;
        }
        .f-card-handle-lux {
            font-size: 1.1rem;
            font-weight: 700;
            font-family: var(--font-display);
        }

        /* Specific Gradients from Screenshot */
        .fc-facebook { background: linear-gradient(135deg, #1877f2, #0a5bbf); }
        .fc-instagram { background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
        .fc-website { background: linear-gradient(135deg, #1a2e5e, #0c1428); border: 1px solid rgba(255,255,255,0.1); }

        @media (max-width: 991px) {
            .follow-card-grid-lux { grid-template-columns: repeat(2, 1fr); padding: 0 20px; }
        }
        @media (max-width: 768px) {
            .follow-card-grid-lux { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="nav-premium" id="mainHeader">
        <a class="nav-brand" href="{{ url('/') }}">
            <div class="nav-brand-seal">⚖</div>
            <div>
                <div class="nav-brand-text">Bengaluru Law House</div>
                <div class="nav-brand-sub">Adv. Veerendrababu Nanjegowda</div>
            </div>
        </a>
        <div class="nav-links">
            <a href="{{ url('/about-us') }}" class="nav-link-custom {{ request()->is('about-us') ? 'active' : '' }}">About</a>
            <a href="{{ url('/') }}#practice" class="nav-link-custom {{ request()->routeIs('services.*') ? 'active' : '' }}">Practice</a>
            <a href="{{ route('products.index') }}" class="nav-link-custom {{ request()->routeIs('products.*') ? 'active' : '' }}">Publications</a>
            <a href="{{ route('offices.index') }}" class="nav-link-custom {{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('gallery') }}" class="nav-link-custom {{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
            <a href="{{ url('/contact-us') }}" class="nav-link-custom {{ request()->is('contact-us') ? 'active' : '' }}">Contact Us</a>

            <div class="dropdown d-inline-block">
                <a class="nav-link-custom dropdown-toggle" href="#" data-bs-toggle="dropdown">More</a>
                <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0 mt-3" style="background: var(--navy-mid);">
                    @auth('agent')
                        <li><a class="dropdown-item py-2" href="{{ route('agent.dashboard') }}">Dashboard</a></li>
                        <li><hr class="dropdown-divider opacity-10"></li>
                        <li>
                            <form method="POST" action="{{ route('agent.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a class="dropdown-item py-2" href="{{ route('agent.login') }}">Distributor Login</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('agent.register') }}">Become Distributor</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <button class="nav-toggle" id="navToggle">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <main class="main-content" style="min-height: 80vh;">
        @yield('content')
    </main>

    <!-- ── FOLLOW ONLINE (Light Background) ── -->
    <section class="follow-online-global">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="label-gold" style="font-size: 0.7rem; letter-spacing: 4px;">FOLLOW ONLINE</span>
                <h2 class="font-display text-navy mb-4" style="font-size: 2.8rem;">Stay Connected with Advocate Veerendrababu</h2>
                <div class="pas-separator mx-auto" style="background: var(--gold); opacity: 0.5;"></div>
                <p class="text-muted mx-auto" style="max-width: 600px; font-size: 1.05rem;">Follow for legal insights, cultural updates, literary news and the latest from the practice.</p>
            </div>

            <div class="follow-card-grid-lux" data-aos="fade-up">
                <!-- Facebook -->
                <a href="https://facebook.com" target="_blank" class="follow-card-lux fc-facebook">
                    <div class="f-card-icon-lux"><i class="fab fa-facebook"></i></div>
                    <div class="f-card-info-lux">
                        <span class="f-card-platform-lux">FACEBOOK</span>
                        <span class="f-card-handle-lux">ವೀರೇಂದ್ರಬಾಬು ನಂಜೇಗೌಡ</span>
                    </div>
                </a>
                <!-- Instagram -->
                <a href="https://instagram.com/veerendrababunanjegowda" target="_blank" class="follow-card-lux fc-instagram">
                    <div class="f-card-icon-lux"><i class="fab fa-instagram"></i></div>
                    <div class="f-card-info-lux">
                        <span class="f-card-platform-lux">INSTAGRAM</span>
                        <span class="f-card-handle-lux">@veerendrababunanjegowda</span>
                    </div>
                </a>
                <!-- Website -->
                <a href="https://www.blrlawhouse.com" target="_blank" class="follow-card-lux fc-website">
                    <div class="f-card-icon-lux"><i class="fas fa-globe"></i></div>
                    <div class="f-card-info-lux">
                        <span class="f-card-platform-lux">OFFICIAL WEBSITE</span>
                        <span class="f-card-handle-lux">www.blrlawhouse.com</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <footer class="footer-lux">
        <div class="container" data-aos="fade-up">
            <!-- Connect Cards -->
            <div class="text-center mb-5">
                <span class="label-gold" style="font-size: 0.7rem; letter-spacing: 4px;">GET IN TOUCH</span>
                <h2 class="font-display mb-4" style="font-size: 2.5rem;">Connect with Advocate Veerendrababu Nanjegowda</h2>
                <div class="pas-separator mx-auto"></div>
                <p class="text-white opacity-60 mx-auto" style="max-width: 700px; font-size: 1rem;">For legal consultations, case inquiries, or professional engagements, reach out through any of the following channels.</p>
            </div>

            <div class="footer-connect-grid">
                <!-- Bengaluru Office -->
                <div class="f-connect-card">
                    <i class="fas fa-university f-connect-icon"></i>
                    <span class="f-connect-label">Bengaluru Office</span>
                    <p class="f-connect-text">#1652/6, 20th Cross, 8th Main, CQAL Layout, C-Block, Sahakaranagar, Bengaluru - 560092</p>
                </div>
                <!-- New Delhi Office -->
                <div class="f-connect-card">
                    <i class="fas fa-landmark f-connect-icon"></i>
                    <span class="f-connect-label">New Delhi Office</span>
                    <p class="f-connect-text">Lawyer's Chambers, Supreme Court of India, New Delhi - 110001</p>
                </div>
                <!-- Phone -->
                <div class="f-connect-card">
                    <i class="fas fa-phone-alt f-connect-icon"></i>
                    <span class="f-connect-label">Phone</span>
                    <p class="f-connect-text">+91 80 4150 2373<br>+91 96325 27961</p>
                </div>
                <!-- Email -->
                <div class="f-connect-card">
                    <i class="fas fa-envelope f-connect-icon"></i>
                    <span class="f-connect-label">Email</span>
                    <p class="f-connect-text">bengalurulawhouse@gmail.com</p>
                </div>
                <!-- Website -->
                <div class="f-connect-card">
                    <i class="fas fa-globe f-connect-icon"></i>
                    <span class="f-connect-label">Website</span>
                    <p class="f-connect-text">www.blrlawhouse.com</p>
                </div>
                <!-- Practice Courts -->
                <div class="f-connect-card">
                    <i class="fas fa-balance-scale f-connect-icon"></i>
                    <span class="f-connect-label">Practice Courts</span>
                    <p class="f-connect-text">Supreme Court of India • High Courts<br>NGT • All Courts, Karnataka</p>
                </div>
            </div>

            <!-- Minimal Footer -->
            <div class="pt-5 border-top border-white border-opacity-10">
                <span class="footer-lux-title">Bengaluru Law House</span>
                
                <ul class="footer-lux-nav">
                    <li><a href="{{ url('/') }}">About</a></li>
                    <li><a href="{{ url('/') }}#practice">Practice</a></li>
                    <li><a href="{{ route('products.index') }}">Publications</a></li>
                    <li><a href="{{ route('offices.index') }}">Offices</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ url('/contact-us') }}">Contact</a></li>
                    <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                    <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
                </ul>

                <div class="footer-lux-copyright">
                    © 2026 Adv. Veerendrababu Nanjegowda • MBA, LLB, PhD (Pursuing) • www.blrlawhouse.com
                </div>
                <div class="footer-lux-location">
                    Bengaluru, Karnataka • New Delhi
                </div>
            </div>
        </div>
    </footer>
    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Theme JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide preloader
            setTimeout(function() {
                document.getElementById('preloader').classList.add('hidden');
            }, 800);

            // Header scroll effect
            const header = document.getElementById('mainHeader');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // AOS Initialization
            AOS.init({
                duration: 800,
                easing: 'ease-out-back',
                once: true,
                offset: 120
            });

            // Counter animation
            const counters = document.querySelectorAll('.stat-number[data-count]');
            const counterObserver = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-count'));
                        const suffix = counter.getAttribute('data-suffix') || '';
                        const duration = 2000;
                        const step = target / (duration / 16);
                        let current = 0;

                        const updateCounter = () => {
                            current += step;
                            if (current < target) {
                                counter.textContent = Math.floor(current) + suffix;
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = target + suffix;
                            }
                        };

                        updateCounter();
                        counterObserver.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => counterObserver.observe(counter));

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

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        });
    </script>

    <style>
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
            overflow: hidden;
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
