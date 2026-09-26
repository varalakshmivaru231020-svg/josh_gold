@extends('layouts.lawfirm')

@section('title', 'JOSHA INFINITY INC SERVICE - Gold & Silver Solutions')

@section('content')
<!-- Ticker Strip -->
<div class="ticker-wrap bg-navy text-gold py-2 overflow-hidden border-bottom border-gold border-2">
    <div class="ticker-move d-flex align-items-center">
        <div class="ticker-item mx-4"><i class="fas fa-chart-line me-2"></i><strong>LIVE RATES</strong></div>
        <div class="ticker-item mx-4">GOLD (24K) - 1g: <span class="fw-bold text-white">₹6,850</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 12</span></div>
        <div class="ticker-item mx-4">GOLD (22K) - 1g: <span class="fw-bold text-white">₹6,280</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 10</span></div>
        <div class="ticker-item mx-4">SILVER (999) - 1kg: <span class="fw-bold text-white">₹74,500</span> <span class="text-danger ms-1"><i class="fas fa-arrow-down"></i> 150</span></div>
        <div class="ticker-item mx-4">GOLD (18K) - 1g: <span class="fw-bold text-white">₹5,140</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 8</span></div>
        <!-- Duplicated for seamless loop -->
        <div class="ticker-item mx-4"><i class="fas fa-chart-line me-2"></i><strong>LIVE RATES</strong></div>
        <div class="ticker-item mx-4">GOLD (24K) - 1g: <span class="fw-bold text-white">₹6,850</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 12</span></div>
        <div class="ticker-item mx-4">GOLD (22K) - 1g: <span class="fw-bold text-white">₹6,280</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 10</span></div>
        <div class="ticker-item mx-4">SILVER (999) - 1kg: <span class="fw-bold text-white">₹74,500</span> <span class="text-danger ms-1"><i class="fas fa-arrow-down"></i> 150</span></div>
        <div class="ticker-item mx-4">GOLD (18K) - 1g: <span class="fw-bold text-white">₹5,140</span> <span class="text-success ms-1"><i class="fas fa-arrow-up"></i> 8</span></div>
    </div>
</div>

{{-- Sliders for the Home page (Admin → Sliders). Without any, the original hero below is shown. --}}
@if($sliders->isNotEmpty())
    @include('layouts.partials.page-slider', ['slides' => $sliders, 'variant' => 'hero'])
@else
<!-- Custom Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&q=80&w=1920');"></div>
    <div class="hero-overlay"></div>
    <div class="container position-relative z-2 h-100 d-flex flex-column justify-content-center pt-5">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="badge bg-gold text-navy px-3 py-2 fw-bold letter-spacing-1 mb-4 rounded-pill">JOSHA INFINITY INC SERVICE</div>
                <h1 class="display-3 fw-bold text-white mb-4 hero-title" style="font-family: var(--font-display);">Your Trusted Partner in<br><span class="text-gold">Precious Metals</span></h1>
                <p class="text-white-50 fs-5 mb-5 w-75" style="line-height: 1.8;">Pioneering the gold and silver industry with 100% commitment, quality assurance, and a 200% growth trajectory. We don't just make promises; we deliver excellence.</p>
                
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('about-us') }}" class="btn btn-gold px-4 py-3 rounded-pill fw-bold text-navy text-nowrap hover-lift">Discover Our Journey <i class="fas fa-arrow-right ms-2"></i></a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light px-4 py-3 rounded-pill fw-bold text-nowrap hover-lift">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-bottom-curve">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L1440 120L1440 0C1440 0 1140 120 720 120C300 120 0 0 0 0L0 120Z" fill="var(--cream)"/>
        </svg>
    </div>
</section>
@endif

<!-- Latest News Strip -->
<div class="bg-gold py-3 shadow-sm position-relative z-3 mt-n5">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="badge bg-navy text-white px-3 py-2 me-3 flex-shrink-0">LATEST NEWS</div>
            <div class="news-ticker overflow-hidden w-100">
                <div class="news-track text-navy fw-medium">
                    {{-- The announcements start at the left edge and loop with no empty gap; the copies are only for the loop --}}
                    @for($copy = 0; $copy < 4; $copy++)
                    <div class="news-group" @if($copy > 0) aria-hidden="true" @endif>
                        <span><i class="fas fa-bullseye me-2 opacity-50"></i> JOSHA INFINITY celebrates 200% business growth in 2024!</span>
                        <span class="mx-4">|</span>
                        <span><i class="fas fa-award me-2 opacity-50"></i> Awarded Best Quality Control in Precious Metals Services.</span>
                        <span class="mx-4">|</span>
                        <span><i class="fas fa-gem me-2 opacity-50"></i> New specialized catering & related services successfully launched.</span>
                        <span class="mx-4">|</span>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Business Highlights -->
<section class="py-5" style="background: var(--cream);">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-gold letter-spacing-2 fw-bold mb-2">WHY CHOOSE US</h6>
            <h2 class="text-navy fw-bold" style="font-family: var(--font-display);">Our Business Highlights</h2>
            <div class="mx-auto mt-3" style="width: 60px; height: 3px; background: var(--gold);"></div>
        </div>
        
        <div class="row g-4">
            <!-- Highlight 1 -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="highlight-card bg-white p-4 rounded-4 shadow-sm text-center h-100 hover-lift-lg border-bottom border-gold border-4">
                    <div class="icon-box mx-auto bg-navy text-gold rounded-circle d-flex align-items-center justify-content-center mb-4 shadow" style="width: 70px; height: 70px; font-size: 1.5rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="text-navy fw-bold mb-3">200% Growth</h5>
                    <p class="text-muted small">Exceptional trajectory showcasing our market passion and dedication.</p>
                </div>
            </div>
            <!-- Highlight 2 -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="highlight-card bg-white p-4 rounded-4 shadow-sm text-center h-100 hover-lift-lg border-bottom border-gold border-4">
                    <div class="icon-box mx-auto bg-navy text-gold rounded-circle d-flex align-items-center justify-content-center mb-4 shadow" style="width: 70px; height: 70px; font-size: 1.5rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="text-navy fw-bold mb-3">Zero Empty Promises</h5>
                    <p class="text-muted small">Deliverables that strictly conform to our projections and estimates.</p>
                </div>
            </div>
            <!-- Highlight 3 -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="highlight-card bg-white p-4 rounded-4 shadow-sm text-center h-100 hover-lift-lg border-bottom border-gold border-4">
                    <div class="icon-box mx-auto bg-navy text-gold rounded-circle d-flex align-items-center justify-content-center mb-4 shadow" style="width: 70px; height: 70px; font-size: 1.5rem;">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h5 class="text-navy fw-bold mb-3">Superior Quality</h5>
                    <p class="text-muted small">Carefully monitored standards by our specialized crew.</p>
                </div>
            </div>
            <!-- Highlight 4 -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="highlight-card bg-white p-4 rounded-4 shadow-sm text-center h-100 hover-lift-lg border-bottom border-gold border-4">
                    <div class="icon-box mx-auto bg-navy text-gold rounded-circle d-flex align-items-center justify-content-center mb-4 shadow" style="width: 70px; height: 70px; font-size: 1.5rem;">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <h5 class="text-navy fw-bold mb-3">Health & Safety</h5>
                    <p class="text-muted small">Strict adherence to environmental health and safety laws with expert audits.</p>
                </div>
            </div>
        </div>
    </div>
</section>


@if($services->isNotEmpty())
<!-- Services Preview -->
<section class="py-5 bg-white position-relative overflow-hidden">
    <!-- Decor element -->
    <div class="position-absolute top-0 end-0 p-5 opacity-10" style="width: 400px; height: 400px; background: radial-gradient(circle, var(--gold) 0%, transparent 70%);"></div>
    
    <div class="container py-5 position-relative z-2">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h6 class="text-gold letter-spacing-2 fw-bold mb-2">OUR EXPERTISE</h6>
                <h2 class="text-navy fw-bold display-5" style="font-family: var(--font-display);">Services Preview</h2>
            </div>
            <div class="col-lg-6 text-lg-end" data-aos="fade-left">
                <a href="{{ route('services.index') }}" class="btn btn-outline-navy rounded-pill px-4 py-2 hover-lift">View All Services <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($services->take(3) as $service)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" @if($loop->index > 0) data-aos-delay="{{ $loop->index * 100 }}" @endif>
                <div class="service-card rounded-4 overflow-hidden position-relative group-hover shadow-sm">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="img-fluid w-100 transition-transform duration-500 group-hover-scale" style="height: 300px; object-fit: cover;" loading="lazy">
                    @else
                        <div class="w-100" style="height: 300px; background: linear-gradient(135deg, #f6ecd0, #c9a84c);"></div>
                    @endif
                    <div class="service-overlay position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(12,20,40,0.9), transparent);">
                        <h4 class="text-white mb-2">{{ $service->title }}</h4>
                        @if(filled($service->description))
                        <p class="text-white-50 small mb-0 opacity-0 transition-opacity duration-300 group-hover-opacity-100">{{ \Illuminate\Support\Str::limit($service->description, 110) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call-to-Action Banners -->
<section class="py-5" style="background: var(--cream);">
    <div class="container py-4">
        <div class="row g-4">
            <!-- CTA 1 -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="cta-banner rounded-4 overflow-hidden position-relative shadow-lg p-5 h-100 text-white" style="background: linear-gradient(135deg, var(--navy), #1a2f5a);">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="fas fa-handshake" style="font-size: 15rem; transform: translate(20%, -20%);"></i>
                    </div>
                    <div class="position-relative z-2">
                        <div class="badge bg-gold text-navy mb-3 px-3 py-2">PARTNERSHIP</div>
                        <h3 class="fw-bold mb-3" style="font-family: var(--font-display);">Looking for Long-Term Trust?</h3>
                        <p class="text-white-50 mb-4 w-75">We make sure that every customer is happy and most satisfied at the end of each workmanship.</p>
                        <a href="{{ route('contact') }}" class="btn btn-gold rounded-pill px-4 py-2 text-navy fw-bold shadow">Partner With Us</a>
                    </div>
                </div>
            </div>
            <!-- CTA 2 -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="cta-banner rounded-4 overflow-hidden position-relative shadow-lg p-5 h-100 text-navy" style="background: linear-gradient(135deg, var(--gold), #e0c169);">
                    <div class="position-absolute bottom-0 end-0 opacity-20">
                        <i class="fas fa-map-marked-alt" style="font-size: 15rem; transform: translate(10%, 20%);"></i>
                    </div>
                    <div class="position-relative z-2">
                        <div class="badge bg-navy text-white mb-3 px-3 py-2">VISIT US</div>
                        <h3 class="fw-bold mb-3" style="font-family: var(--font-display);">Garden City Of Bengaluru</h3>
                        <p class="text-navy opacity-75 mb-4 w-75">No.194 1st Floor, AMR Complex, Horamavu Agara, Main Road, Bangalore 560043</p>
                        <a href="{{ route('contact') }}" class="btn btn-navy rounded-pill px-4 py-2 text-white fw-bold shadow">Get Directions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    :root {
        --navy: #0c1428;
        --gold: #c9a84c;
        --cream: #fbfaf8;
        --font-display: 'Playfair Display', serif;
    }

    /* Ticker Strip */
    .ticker-wrap {
        width: 100%;
        box-sizing: content-box;
    }
    .ticker-move {
        display: inline-block;
        white-space: nowrap;
        animation: ticker 25s linear infinite;
    }
    .ticker-item {
        display: inline-block;
        font-size: 0.9rem;
    }
    @keyframes ticker {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* News Ticker */
    .news-ticker {
        position: relative;
    }
    .news-track {
        display: flex;
        width: max-content;
        animation: newsTicker 45s linear 2s infinite; /* holds the first announcement in place for 2s, then scrolls */
    }
    .news-group {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .news-ticker:hover .news-track { animation-play-state: paused; }
    @keyframes newsTicker {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
        .news-track { animation: none; }
    }

    /* Hero Section */
    .hero-section {
        /* at least 85% of the screen, but grows with its content so the buttons never end up under the curve */
        min-height: max(600px, 85vh);
        display: flex;
        flex-direction: column;
    }
    .hero-section > .container {
        flex: 1 1 auto;
        /* keep the text and buttons clear of the bottom curve (it is 1/12 of the page width tall) */
        padding-bottom: calc(100vw / 12 + 1.5rem);
    }
    .hero-bg {
        position: absolute; inset: 0;
        background-position: center;
        background-size: cover;
        transform: scale(1.05);
        animation: zoomBg 20s ease infinite alternate;
    }
    @keyframes zoomBg {
        100% { transform: scale(1); }
    }
    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(12,20,40,0.95) 0%, rgba(12,20,40,0.7) 50%, rgba(12,20,40,0.4) 100%);
    }
    .hero-title {
        line-height: 1.2;
    }
    .hero-bottom-curve {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        z-index: 10;
        line-height: 0;
        pointer-events: none; /* purely decorative: never block clicks on the buttons above it */
    }
    .hero-bottom-curve svg {
        display: block;
        width: 100%;
        height: auto;
    }

    /* Utilities */
    .bg-navy { background-color: var(--navy) !important; }
    .bg-gold { background-color: var(--gold) !important; }
    .text-navy { color: var(--navy) !important; }
    .text-gold { color: var(--gold) !important; }
    
    .btn-gold { background: var(--gold); color: var(--navy); border: none; }
    .btn-gold:hover { background: #b08d38; color: var(--navy); }
    .btn-navy { background: var(--navy); color: white; border: none; }
    .btn-navy:hover { background: #080d1a; color: white; }
    .btn-outline-navy { border: 2px solid var(--navy); color: var(--navy); }
    .btn-outline-navy:hover { background: var(--navy); color: white; }
    .btn-outline-light { border: 2px solid white; color: white; }
    .btn-outline-light:hover { background: white; color: var(--navy); }
    
    .letter-spacing-1 { letter-spacing: 1px; }
    .letter-spacing-2 { letter-spacing: 2px; }
    
    .hover-lift { transition: transform 0.3s, box-shadow 0.3s; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    
    .hover-lift-lg { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .hover-lift-lg:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    /* Service Cards Hover Effects */
    .transition-transform { transition: transform 0.5s ease; }
    .transition-opacity { transition: opacity 0.3s ease; }
    .group-hover:hover .group-hover-scale { transform: scale(1.1); }
    .group-hover:hover .group-hover-opacity-100 { opacity: 1 !important; }
    
    .cta-banner { transition: transform 0.3s; }
    .cta-banner:hover { transform: scale(1.02); }
</style>
@endpush
