@php
    $siteLogo = \App\Models\SiteSetting::get('site_logo');
    $logoUrl = $siteLogo ? asset('storage/' . $siteLogo) : asset('blr.jpg');
    $announcementLinks = [
        ['label' => 'Central Anti-Conversion Law', 'url' => route('anti-conversion-law'), 'icon' => 'fa-scale-balanced'],
        ['label' => 'SC/ST (Prevention of Atrocities) Act, 1989', 'url' => route('sc-st-act-1989'), 'icon' => 'fa-gavel'],
        ['label' => 'Letter to PM Modi', 'url' => route('letter-to-pm-modi'), 'icon' => 'fa-landmark'],
        ['label' => 'Letter to CM Karnataka', 'url' => route('letter-to-cm-karnataka'), 'icon' => 'fa-file-signature'],
        ['label' => 'Water Filtration Project', 'url' => route('water-filtration'), 'icon' => 'fa-droplet'],
        ['label' => 'Water Filtration Kannada', 'url' => route('water-filtration-kannada'), 'icon' => 'fa-droplet'],
        ['label' => 'Browse Publications', 'url' => route('products.index'), 'icon' => 'fa-book-open'],
        ['label' => 'Visit Our Offices', 'url' => route('offices.index'), 'icon' => 'fa-building-columns'],
    ];
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

    <!-- Glide.js (carousel) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    
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
            --nav-height: 85px;
            --announcement-height: 20px;
            --announcement-offset: 20px;
        }

        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; scroll-behavior:smooth; }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
        }

        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--text);
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:var(--navy); }
        ::-webkit-scrollbar-thumb { background:var(--gold); border-radius:10px; }

        /* ── NAVIGATION ── */
        .nav-premium {
            position: fixed; top:0; left:0; right:0; z-index:1000;
            background: rgba(12,20,40,0.98);
            border-bottom: 1px solid rgba(201,168,76,0.2);
            padding: 0 60px;
            display: flex; align-items:center; justify-content:space-between;
            height: var(--nav-height);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            will-change: transform, height;
            width: 100%;
        }
        .nav-premium.scrolled { height: 75px; background: #0a0f1e; box-shadow: 0 10px 40px rgba(0,0,0,0.4); }

        .announcement-bar {
            position: fixed;
            top: var(--nav-height);
            left: 0;
            right: 0;
            z-index: 990;
            min-height: var(--announcement-height);
            background: linear-gradient(90deg, rgba(201,168,76,0.98) 0%, rgba(233,208,142,0.98) 50%, rgba(201,168,76,0.98) 100%);
            border-bottom: 2px solid #8b6914;
            box-shadow: 0 6px 14px rgba(12,20,40,0.15), inset 0 1px 0 rgba(255,255,255,0.2);
            overflow: hidden;
        }
        .announcement-bar-inner {
            min-height: var(--announcement-height);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
        }
        .announcement-kicker {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4f3d10;
            font-size: 0.42rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            white-space: nowrap;
            padding-right: 8px;
            border-right: 2px solid rgba(79, 61, 16, 0.3);
        }
        .announcement-marquee {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(90deg, transparent 0%, black 5%, black 95%, transparent 100%);
        }
        .announcement-track {
            --announcement-loop-distance: 0px;
            --announcement-duration: 30s;
            display: flex;
            align-items: center;
            width: max-content;
            animation: announcement-scroll var(--announcement-duration) linear infinite;
            gap: 0;
            will-change: transform;
        }
        .announcement-track:hover {
            animation-play-state: paused;
        }
        .announcement-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            padding-right: 0;
        }
        .announcement-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--navy);
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .announcement-link:hover {
            color: #6a5010;
            background: rgba(12, 20, 40, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(12, 20, 40, 0.12);
        }
        .announcement-divider {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(12,20,40,0.4);
            flex-shrink: 0;
        }
        .main-content {
            padding-top: var(--announcement-offset);
        }

        @keyframes announcement-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-1 * var(--announcement-loop-distance))); }
        }
        
        .nav-brand { display:flex; align-items:center; gap:15px; text-decoration:none; min-width:0; flex:1; overflow:hidden; }
        .nav-brand-seal {
            width:45px; height:45px;
            background: linear-gradient(135deg, #c9a84c, #8b6914);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #0c1428; 
            box-shadow: 0 0 15px rgba(201,168,76,0.3);
            border: 2px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .nav-brand-info { min-width: 0; overflow: hidden; }
        .nav-brand-text { 
            font-family: var(--font-display); color: #c9a84c; 
            font-size: 1.25rem; font-weight: 800; line-height: 1.1; 
            text-transform: uppercase; letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;
        }
        .nav-brand-sub { font-size: 0.68rem; color: rgba(255,255,255,0.6); letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }

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

        .nav-toggle { display:none; background:none; border:none; cursor:pointer; padding: 10px; z-index: 1001; width: 45px; height: 45px; }
        .nav-toggle span { display:block; width:25px; height:2px; background:#c9a84c !important; margin:6px 0; transition:all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); border-radius:2px; }
        
        .nav-toggle.active span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
        .nav-toggle.active span:nth-child(2) { opacity: 0; }
        .nav-toggle.active span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

        /* Ensure no horizontal scroll on all levels */
        html, body, .main-content, section { overflow-x: hidden; width: 100%; }

        @media (max-width: 1100px) {
            :root {
                --nav-height: 70px;
                --announcement-height: 18px;
                --announcement-offset: 18px;
            }
            .nav-premium { 
                padding: 0 15px; height: 70px;
                transition: height 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
                transform: translateZ(0);
            }
            .nav-brand { flex: 1; min-width: 0; gap: 10px; overflow: hidden; }
            .nav-brand-seal { width: 36px; height: 36px; font-size: 1rem; flex-shrink: 0; }
            .nav-brand-info { min-width: 0; overflow: hidden; }
            .nav-toggle { display: block; flex-shrink: 0; width: 44px; height: 44px; margin-left: 8px; }
            .nav-brand-text { font-size: 0.92rem; letter-spacing: 0.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .nav-brand-sub { font-size: 0.54rem; letter-spacing: 0.3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .nav-links {
                position: fixed; top: 0; right: -100%; width: 280px; height: 100vh;
                background: #080d1a; flex-direction: column; align-items: flex-start;
                padding: 100px 0 60px; transition: right 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                box-shadow: -10px 0 40px rgba(0,0,0,0.6); z-index: 1050;
                visibility: hidden; opacity: 0; pointer-events: none;
                overflow-y: auto; overflow-x: hidden;
            }
            .nav-links.active { right: 0; visibility: visible; opacity: 1; pointer-events: auto; display: flex; }
            .nav-link-custom { 
                width: 100%; font-size: 0.85rem; padding: 18px 30px; 
                border-bottom: 1px solid rgba(255,255,255,0.03); 
                border-radius: 0; display: block;
                opacity: 0; transform: translateX(20px); transition: all 0.3s ease;
            }
            .nav-links.active .nav-link-custom { opacity: 1; transform: translateX(0); }
            /* Staggered animation */
            .nav-links.active .nav-link-custom:nth-child(1) { transition-delay: 0.05s; }
            .nav-links.active .nav-link-custom:nth-child(2) { transition-delay: 0.1s; }
            .nav-links.active .nav-link-custom:nth-child(3) { transition-delay: 0.15s; }
            .nav-links.active .nav-link-custom:nth-child(4) { transition-delay: 0.2s; }
            .nav-links.active .nav-link-custom:nth-child(5) { transition-delay: 0.25s; }
            .nav-links.active .nav-link-custom:nth-child(6) { transition-delay: 0.3s; }
            .nav-links.active .nav-link-custom:nth-child(7) { transition-delay: 0.35s; }
            .nav-links.active .dropdown:nth-child(8) { transition-delay: 0.4s; }
            .nav-links.active .dropdown:nth-child(8) .nav-link-custom { transition-delay: 0.4s; }
            
            .nav-link-custom:hover { background: rgba(201,168,76,0.08); color: var(--gold); padding-left: 35px; }
            .nav-cta { margin: 25px 30px 0; width: calc(100% - 60px); text-align: center; border-radius: 50px; padding: 14px !important; font-size: 0.85rem; }
            
            .nav-overlay {
                position: fixed; inset: 0; background: rgba(0,0,0,0.8);
                backdrop-filter: blur(5px); z-index: 999;
                opacity: 0; visibility: hidden; transition: opacity 0.4s;
            }
            .nav-overlay.active { opacity: 1; visibility: visible; }

            .announcement-bar-inner {
                gap: 6px;
                padding: 0 6px;
            }
            .announcement-kicker {
                font-size: 0.36rem;
                letter-spacing: 0.1em;
            }
            .announcement-link {
                font-size: 0.56rem;
            }
        }

        /* Narrow header fixes */
        @media (max-width: 480px) {
            .nav-brand-seal { width: 30px !important; height: 30px !important; }
            .nav-brand-text { font-size: 0.78rem !important; }
            .nav-brand-sub { font-size: 0.45rem !important; }
        }

        @media (max-width: 480px) {
            .nav-premium { height: 68px; padding: 0 12px; }
            .nav-brand { gap: 10px; flex: 1; min-width: 0; width: calc(100% - 50px); }
            .nav-brand-seal { width: 32px; height: 32px; font-size: 0.85rem; }
            .nav-brand-text { font-size: 0.8rem; letter-spacing: 0.2px; white-space: nowrap; }
            .nav-brand-sub { font-size: 0.5rem; letter-spacing: 0; white-space: nowrap; max-width: 150px; }
            .nav-toggle { width: 44px; height: 44px; margin-left: 2px; }
            .nav-toggle span { width: 22px; margin: 5px 0; }
            .announcement-kicker { display: none; }
            .announcement-bar-inner { padding: 0 5px; }
            .announcement-link { font-size: 0.52rem; }
        }

        @media (max-width: 360px) {
            .nav-brand-text { font-size: 0.75rem; }
            .nav-brand-sub { font-size: 0.45rem; max-width: 120px; }
            .nav-brand-seal { width: 28px; height: 28px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .announcement-track {
                animation: none;
            }
        }




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

        /* ── FLOATING CONTACT BUTTONS ── */
        .floating-contact-btn {
            position: fixed;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #ffffff;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 980;
            border: none;
            cursor: pointer;
        }

        .floating-contact-btn:hover {
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .whatsapp-btn {
            bottom: 25px;
            right: 25px;
            background: linear-gradient(135deg, #25D366, #20BA5A);
        }

        .whatsapp-btn:hover {
            background: linear-gradient(135deg, #20BA5A, #1DA851);
        }

        .phone-btn {
            bottom: 100px;
            right: 25px;
            background: linear-gradient(135deg, var(--gold), #8b6914);
        }

        .phone-btn:hover {
            background: linear-gradient(135deg, #8b6914, #6a5010);
        }

        .contact-btn-label {
            position: absolute;
            right: 75px;
            background: rgba(0, 0, 0, 0.8);
            color: #ffffff;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            font-weight: 600;
        }

        .floating-contact-btn:hover .contact-btn-label {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .floating-contact-btn {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }

            .whatsapp-btn {
                bottom: 20px;
                right: 20px;
            }

            .phone-btn {
                bottom: 90px;
                right: 20px;
            }

            .contact-btn-label {
                right: 65px;
                font-size: 0.75rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="nav-premium" id="mainHeader">
        <a class="nav-brand" href="{{ url('/') }}">
            @if($siteLogo)
                <img src="{{ $logoUrl }}" alt="Bengaluru Law House" class="nav-brand-seal" style="object-fit:cover; padding:0;" loading="eager">
            @else
                <div class="nav-brand-seal">⚖</div>
            @endif
            <div class="nav-brand-info">
                <div class="nav-brand-text">Bengaluru Law House</div>
                <div class="nav-brand-sub">Adv. Veerendrababu Nanjegowda</div>
            </div>
        </a>
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about-us') }}" class="nav-link-custom {{ request()->routeIs('about-us') ? 'active' : '' }}">About</a>
            <a href="{{ route('blog.index') }}" class="nav-link-custom {{ request()->routeIs('blog.index') ? 'active' : '' }}">Blog</a>
            <a href="{{ route('offices.index') }}" class="nav-link-custom {{ request()->routeIs('offices.index') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('gallery') }}" class="nav-link-custom {{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
            <a href="{{ route('services.index') }}" class="nav-link-custom {{ request()->routeIs('services.index') ? 'active' : '' }}">Practice</a>
            <a href="{{ route('products.index') }}" class="nav-link-custom {{ request()->routeIs('products.index') ? 'active' : '' }}">Publications</a>
            <a href="{{ route('contact') }}" class="nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>

            <div class="dropdown d-inline-block d-none">
                <a class="nav-link-custom dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    More
                </a>
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
                        {{-- <li><a class="dropdown-item py-2" href="{{ route('agent.login') }}">Distributor Login</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('agent.register') }}">Become Distributor</a></li> --}}
                    @endauth
                </ul>
            </div>
        </div>
        <button class="nav-toggle" id="navToggle">
            <span></span><span></span><span></span>
        </button>
    </nav>
    <div class="announcement-bar" id="announcementBar" role="region" aria-label="Important links">
        <div class="announcement-bar-inner">
            <span class="announcement-kicker">
                <i class="fas fa-bullhorn"></i>
                Important Links
            </span>
            <div class="announcement-marquee">
                <div class="announcement-track" id="announcementTrack">
                    <div class="announcement-group" data-announcement-source="true">
                        @foreach($announcementLinks as $announcementLink)
                            <a href="{{ $announcementLink['url'] }}" class="announcement-link">
                                <i class="fas {{ $announcementLink['icon'] }}"></i>
                                <span>{{ $announcementLink['label'] }}</span>
                            </a>
                            <span class="announcement-divider"></span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-overlay" id="navOverlay"></div>

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
                    <p class="f-connect-text">+91 96325 27961</p>
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
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ url('/') }}#practice">Practice</a></li>
                    <li><a href="{{ route('products.index') }}">Publications</a></li>
                    <li><a href="{{ route('water-filtration') }}">Water Filtration</a></li>
                    <li><a href="{{ route('water-filtration-kannada') }}">Water Filtration Kannada</a></li>
                    <li><a href="{{ route('letter-to-pm-modi') }}">Letter to PM Modi</a></li>
                    <li><a href="{{ route('letter-to-cm-karnataka') }}">Letter to CM Karnataka</a></li>
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

    <!-- Floating Contact Buttons -->
    <a href="https://wa.me/919632527961" target="_blank" class="floating-contact-btn whatsapp-btn" title="WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="contact-btn-label">WhatsApp</span>
    </a>
    <a href="tel:+919632527961" class="floating-contact-btn phone-btn" title="Call">
        <i class="fas fa-phone"></i>
        <span class="contact-btn-label">Phone</span>
    </a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Glide.js (carousel) -->
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>

    <!-- Theme JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide preloader if it exists
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(function() {
                    preloader.classList.add('hidden');
                }, 800);
            }

            // Header scroll effect
            const header = document.getElementById('mainHeader');
            const announcementBar = document.getElementById('announcementBar');
            const announcementTrack = document.getElementById('announcementTrack');
            const syncAnnouncementBar = () => {
                if (!header || !announcementBar) {
                    return;
                }

                announcementBar.style.top = `${header.offsetHeight}px`;
                document.documentElement.style.setProperty('--announcement-offset', `${announcementBar.offsetHeight}px`);
            };

            const syncAnnouncementMarquee = () => {
                if (!announcementTrack) {
                    return;
                }

                const announcementMarquee = announcementTrack.parentElement;
                const sourceGroup = announcementTrack.querySelector('[data-announcement-source="true"]');

                if (!announcementMarquee || !sourceGroup) {
                    return;
                }

                announcementTrack.querySelectorAll('[data-announcement-clone="true"]').forEach((clone) => clone.remove());

                const sourceWidth = Math.ceil(sourceGroup.getBoundingClientRect().width);
                const marqueeWidth = Math.ceil(announcementMarquee.getBoundingClientRect().width);

                if (!sourceWidth || !marqueeWidth) {
                    return;
                }

                let totalWidth = sourceWidth;
                let cloneCount = 0;
                const minimumTrackWidth = marqueeWidth + sourceWidth;

                while (totalWidth < minimumTrackWidth && cloneCount < 10) {
                    const clone = sourceGroup.cloneNode(true);
                    clone.dataset.announcementClone = 'true';
                    clone.setAttribute('aria-hidden', 'true');
                    announcementTrack.appendChild(clone);
                    totalWidth += sourceWidth;
                    cloneCount += 1;
                }

                if (cloneCount === 0) {
                    const clone = sourceGroup.cloneNode(true);
                    clone.dataset.announcementClone = 'true';
                    clone.setAttribute('aria-hidden', 'true');
                    announcementTrack.appendChild(clone);
                }

                const pixelsPerSecond = window.innerWidth <= 480 ? 40 : 55;
                const duration = Math.max(16, Math.round(sourceWidth / pixelsPerSecond));

                announcementTrack.style.setProperty('--announcement-loop-distance', `${sourceWidth}px`);
                announcementTrack.style.setProperty('--announcement-duration', `${duration}s`);
            };

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                syncAnnouncementBar();
            });

            window.addEventListener('resize', function() {
                syncAnnouncementBar();
                syncAnnouncementMarquee();
            });
            syncAnnouncementBar();
            syncAnnouncementMarquee();

            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(syncAnnouncementMarquee);
            }

            // Mobile nav toggle
            const navToggle = document.getElementById('navToggle');
            const navLinks = document.querySelector('.nav-links');
            const navOverlay = document.getElementById('navOverlay');

            if (navToggle && navLinks) {
                function toggleMenu() {
                    navToggle.classList.toggle('active');
                    navLinks.classList.toggle('active');
                    navOverlay.classList.toggle('active');
                    document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
                }

                navToggle.addEventListener('click', toggleMenu);
                if (navOverlay) {
                    navOverlay.addEventListener('click', toggleMenu);
                }
            }

            // AOS Initialization
            try {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 800,
                        easing: 'ease-out-back',
                        once: true,
                        offset: 120
                    });
                }
            } catch (e) {
                console.warn('AOS failed to initialize:', e);
            }

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

            if (counters.length > 0) {
                counters.forEach(counter => counterObserver.observe(counter));
            }

            // Back to top button
            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
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
            }



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

    @stack('scripts')
</body>
</html>
