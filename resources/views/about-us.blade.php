@extends('layouts.lawfirm')

@section('title', 'About Us | JOSHA INFINITY INC SERVICE')

@section('content')
<section class="about-luxury-page overflow-hidden">
    <!-- Hero Section -->
    <div class="about-hero-header position-relative">
        @if($about && $about->hero_image)
            <div class="about-hero-photo" style="background-image: url('{{ asset('storage/' . $about->hero_image) }}');" aria-hidden="true"></div>
        @endif
        <div class="hero-bg-overlay"></div>
        <div class="container text-center position-relative z-2">
            <div class="label-gold fade-up-anim delay-1">MANAGEMENT AND ORGANIZATION</div>
            <h1 class="heading-premium text-white reveal-text">About Us</h1>
            <div class="accent-line-gold mx-auto fade-up-anim delay-2"></div>
            <p class="hero-subheader text-white opacity-90 italic mt-4 fade-up-anim delay-3">"Building trust through exceptional service and unwavering dedication."</p>
        </div>
        <div class="hero-particles"></div>
    </div>

    <div class="container py-5 mt-n5 position-relative z-3">
        <!-- Breadcrumbs -->
        <div class="breadcrumb-luxury mb-5 fade-up-anim delay-4">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">About Us</span>
        </div>

        {{-- Company story: managed in Admin → About Section (hidden when empty or switched off) --}}
        @if($about && (filled($about->title) || filled($about->subtitle) || filled($about->content) || $about->image))
        <div class="row g-5 align-items-center mb-5 mt-4 about-story">
            @if($about->image)
            <div class="col-lg-5" data-aos="fade-right">
                <figure class="about-story-media mb-0">
                    <img src="{{ asset('storage/' . $about->image) }}" alt="{{ $about->title ?: 'About JOSHA INFINITY INC SERVICE' }}" loading="lazy">
                </figure>
            </div>
            @endif
            <div class="{{ $about->image ? 'col-lg-7' : 'col-lg-10 mx-auto' }}" data-aos="fade-left">
                @if(filled($about->subtitle))<span class="label-brand">{{ $about->subtitle }}</span>@endif
                @if(filled($about->title))<h2 class="title-premium text-navy mb-3">{{ $about->title }}</h2>@endif
                <div class="gold-separator mb-4 w-25"></div>
                @if(filled($about->content))
                <div class="about-text-wrap about-story-body text-muted lead-text">{!! strip_tags($about->content, '<p><br><strong><b><em><i><u><ul><ol><li><a><h3><h4><blockquote>') !!}</div>
                @endif
            </div>
        </div>
        @endif

        <!-- Leadership: Founder & CEO -->
        <div class="row g-5 align-items-center mb-5 mt-4">
            <div class="col-lg-5" data-aos="fade-right">
                <figure class="ld-portrait mb-0">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=700" alt="Mr. Prasad Ashok, Founder &amp; CEO of JOSHA INFINITY INC SERVICE">
                    <figcaption class="ld-badge">Founder &amp; CEO</figcaption>
                </figure>
            </div>

            <div class="col-lg-7" data-aos="fade-left">
                <span class="label-brand">LEADERSHIP</span>
                <h2 class="title-premium text-navy mb-3">Meet Our Founder &amp; CEO</h2>
                <div class="gold-separator mb-4 w-25"></div>

                <div class="ld-name">Mr. Prasad Ashok</div>
                <div class="ld-role">Founder &amp; CEO, JOSHA INFINITY INC SERVICE</div>

                <div class="ld-stats">
                    <div class="ld-stat"><strong>7+</strong><span>Years of experience</span></div>
                    <div class="ld-stat"><strong>2023</strong><span>Year founded</span></div>
                    <div class="ld-stat"><strong>200%+</strong><span>Business growth</span></div>
                </div>

                <div class="about-text-wrap text-muted lead-text">
                    <p>He brings with him more than <strong class="text-navy">7 years of hardcore experience</strong> in this trade and business. His track record is impeccable, and his management style is innovative, perfectly aligning with the JOSHA INFINITY INC SERVICE standards.</p>
                </div>

                <blockquote class="ld-quote">
                    He draws enormous strength from the overwhelming support extended to him by our dedicated team, driving the company toward unprecedented growth.
                </blockquote>
            </div>
        </div>
    </div>

    <!-- Operating Section (Dark Band) -->
    <div class="bg-navy py-5 position-relative overflow-hidden mb-5">
        <div class="abstract-shape shape-1"></div>
        <div class="container py-4 position-relative z-2">
            <div class="row g-5 align-items-center">
                <div class="col-lg-12 text-center mb-4">
                    <h3 class="display-font text-white mb-3 fade-up-scroll">WHERE AND HOW ARE WE OPERATING?</h3>
                    <div class="accent-line-gold mx-auto mb-4"></div>
                </div>
                <div class="col-lg-6 fade-up-scroll">
                    <div class="p-4 glass-card-dark h-100">
                        <i class="fas fa-chart-line text-gold fs-1 mb-3"></i>
                        <h4 class="text-white mb-3">Rapid Growth</h4>
                        <p class="text-white-50" style="line-height: 1.8;">
                            JOSHA INFINITY – Bangalore, ever since its inception in <strong>2023</strong>, has taken painstaking efforts to ensure it is a major force to reckon with. Our business growth of over <strong>200%</strong> stands as clear testimony to our passion, zeal, and enthusiasm in carving a niche in this burgeoning marketplace.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 fade-up-scroll delay-1">
                    <div class="p-4 glass-card-dark h-100">
                        <i class="fas fa-map-marker-alt text-gold fs-1 mb-3"></i>
                        <h4 class="text-white mb-3">Headquarters</h4>
                        <p class="text-white-50" style="line-height: 1.8;">
                            JOSHA INFINITY is currently operated from:<br>
                            <strong>No.194 1st Floor, AMR Complex,<br>Horamavu Agara, Main Road,<br>Bangalore 560043</strong>
                        </p>
                        <p class="text-white-50 mt-3 fst-italic border-start border-gold ps-3">
                            "We strongly believe that we should practice what we preach with no let-up and take on further in ensuring our prime objectives never deviate."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Addition -->
    <div class="container mb-5">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-5 fade-up-scroll">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32d7?auto=format&fit=crop&q=80&w=600" class="img-fluid rounded-4 shadow-lg" alt="Value Addition">
                    <div class="floating-badge pulse-anim">
                        <span class="fs-4 fw-bold">100%</span>
                        <br>Commitment
                    </div>
                </div>
            </div>
            <div class="col-lg-7 fade-up-scroll delay-1">
                <h3 class="title-premium text-navy mb-4">What is our value addition?</h3>
                <div class="value-list">
                    <div class="value-item d-flex align-items-start mb-4 hover-translate">
                        <div class="value-icon flex-shrink-0 bg-gold text-white rounded-circle p-3 me-4 shadow">
                            <i class="fas fa-briefcase fs-4"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">Extensive Experience</h5>
                            <p class="text-muted">We possess extensive years of experience in this trade and are well acclimatized to handling any job, be it small, medium, or mammoth.</p>
                        </div>
                    </div>
                    <div class="value-item d-flex align-items-start mb-4 hover-translate delay-1">
                        <div class="value-icon flex-shrink-0 bg-gold text-white rounded-circle p-3 me-4 shadow">
                            <i class="fas fa-handshake fs-4"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">No Empty Promises</h5>
                            <p class="text-muted">We don't offer empty promises; our deliverables will conform to what we project and stand testimony to our ability of service.</p>
                        </div>
                    </div>
                    <div class="value-item d-flex align-items-start hover-translate delay-2">
                        <div class="value-icon flex-shrink-0 bg-gold text-white rounded-circle p-3 me-4 shadow">
                            <i class="fas fa-tools fs-4"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">Superior Workmanship</h5>
                            <p class="text-muted">We bring in not only experience but also good workmanship that is both considerate and conscientious.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strengths Section -->
    <div class="bg-light py-5 mb-5 position-relative">
        <div class="container py-4">
            <div class="text-center mb-5 fade-up-scroll">
                <h2 class="title-premium text-navy mb-2">Garden City Of Bengaluru</h2>
                <h5 class="text-gold">We List Some Of Our Key Strengths Below</h5>
            </div>
            <div class="row g-4">
                <div class="col-md-4 fade-up-scroll">
                    <div class="strength-card bg-white p-5 rounded-4 shadow-sm h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto bg-navy-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-star text-gold fs-2"></i>
                        </div>
                        <h5 class="text-navy font-weight-bold mb-3">QUALITY & CHOICES</h5>
                        <p class="text-muted small" style="line-height: 1.6;">Quality control is carefully monitored by high standards, by our specialized crew in various catering and related services requirements.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up-scroll delay-1">
                    <div class="strength-card bg-white p-5 rounded-4 shadow-sm h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto bg-navy-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt text-gold fs-2"></i>
                        </div>
                        <h5 class="text-navy font-weight-bold mb-3">HEALTH & SAFETY</h5>
                        <p class="text-muted small" style="line-height: 1.6;">Our primary focus is protecting partners, staff, and the environment. We have specialists versed in safety laws, conducting regular training and professional audits.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up-scroll delay-2">
                    <div class="strength-card bg-white p-5 rounded-4 shadow-sm h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto bg-navy-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-eye text-gold fs-2"></i>
                        </div>
                        <h5 class="text-navy font-weight-bold mb-3">OUR VISION</h5>
                        <p class="text-muted small" style="line-height: 1.6;">We focus on the core business of providing timely work and related services, allowing our clients to concentrate entirely on their own core businesses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values and Mission -->
    <div class="container mb-5 pb-5">
        <div class="row g-0 align-items-stretch mission-vision-container shadow-lg rounded-4 overflow-hidden fade-up-scroll">
            <div class="col-lg-6 position-relative">
                <div class="p-5 h-100 d-flex flex-column justify-content-center text-white" style="background: var(--navy); z-index: 2;">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-gem fs-2 text-gold me-3"></i>
                        <h3 class="mb-0 display-font">OUR VALUES</h3>
                    </div>
                    <ul class="custom-check-list text-white-50" style="font-size: 1.1rem; line-height: 2.2;">
                        <li><span>Excellent Customer Care & Service</span></li>
                        <li><span>Excellent listening skills</span></li>
                        <li><span>Excellent timely Work</span></li>
                        <li><span>Excellent motivated personnel teams and professionalism</span></li>
                        <li><span>Excellent long term clients partnership and trust.</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5 h-100 d-flex flex-column justify-content-center bg-gold position-relative overflow-hidden">
                    <div class="abstract-shape shape-2"></div>
                    <div class="position-relative z-2">
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-bullseye fs-2 text-navy me-3"></i>
                            <h3 class="mb-0 display-font text-navy">OUR MISSION</h3>
                        </div>
                        <p class="text-navy fs-5" style="line-height: 1.8; font-weight: 500;">
                            We make sure that every customer is happy and most satisfied at the end of each workmanship. 
                        </p>
                        <div class="mt-4 p-3 bg-white bg-opacity-25 rounded-3 d-inline-block border border-navy border-opacity-25">
                            <strong class="text-navy fs-5 tracking-wide">(WIN WIN skeeye)</strong>
                        </div>
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
        --navy-light: #162444;
        --gold: #c9a84c;
        --gold-dark: #b08d38;
        --cream: #fbfaf8;
        --font-display: 'Playfair Display', serif;
    }

    .about-luxury-page { background: var(--cream); font-family: 'Inter', sans-serif; }
    .display-font { font-family: var(--font-display); }
    .tracking-wide { letter-spacing: 2px; }
    
    /* Hero Section */
    .about-hero-header { 
        background: url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&q=80&w=1200') center/cover fixed; 
        padding: 180px 0 140px; 
    }
    .hero-bg-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(12,20,40,0.92) 0%, rgba(12,20,40,0.75) 100%);
    }
    .hero-particles {
        position: absolute; inset: 0;
        background-image: radial-gradient(var(--gold) 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: 0.1;
        animation: panBg 20s linear infinite;
    }
    
    @keyframes panBg {
        0% { background-position: 0 0; }
        100% { background-position: 100px 100px; }
    }

    /* Typography & Utilities */
    .label-gold { font-size: 0.85rem; letter-spacing: 4px; color: var(--gold); font-weight: 800; margin-bottom: 15px; }
    .heading-premium { font-family: var(--font-display); font-size: 4rem; font-weight: 700; }
    .title-premium { font-family: var(--font-display); font-weight: 700; font-size: 2.5rem; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; }
    .border-gold-thick { border: 4px solid var(--gold); padding: 5px; background: white; }
    .bg-navy { background-color: var(--navy); }
    .bg-navy-light { background-color: rgba(12,20,40,0.05); }
    .bg-gold { background-color: var(--gold); }
    .text-navy { color: var(--navy); }
    .text-gold { color: var(--gold); }
    
    /* Breadcrumb */
    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration:none; transition: color 0.3s; }
    .breadcrumb-luxury a:hover { color: var(--navy); }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    /* Glass Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 16px;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s;
    }
    .glass-card-dark {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        border-top: 2px solid var(--gold);
        transition: transform 0.3s, background 0.3s;
    }
    .glass-card-dark:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-5px);
    }

    /* Image wrapper & hover */
    .img-wrapper { position: relative; overflow: hidden; border-radius: 16px; }
    .img-wrapper img { transition: transform 0.7s; }
    .img-wrapper:hover img { transform: scale(1.05); }
    
    /* Animations & Hover Effects */
    .hover-float { transition: transform 0.4s; }
    .hover-float:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    
    .hover-translate { transition: transform 0.3s, background 0.3s; padding: 15px; border-radius: 12px; }
    .hover-translate:hover { transform: translateX(10px); background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    
    .strength-card {
        border-top: 4px solid transparent;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .strength-card:hover {
        border-top-color: var(--gold);
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0,0,0,0.08) !important;
    }
    .strength-card .icon-wrapper { transition: all 0.4s; }
    .strength-card:hover .icon-wrapper { background: var(--gold); color: white !important; transform: rotateY(180deg); }
    .strength-card:hover .icon-wrapper i { color: white !important; }

    /* Custom Check List */
    .custom-check-list { list-style: none; padding: 0; }
    .custom-check-list li { position: relative; padding-left: 30px; margin-bottom: 10px; transition: transform 0.3s; }
    .custom-check-list li:hover { transform: translateX(5px); color: white !important; }
    .custom-check-list li::before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 2px;
        color: var(--gold);
        font-size: 0.9rem;
    }

    /* Floating Badge */
    .floating-badge {
        position: absolute;
        bottom: -20px;
        right: -20px;
        background: var(--gold);
        color: white;
        padding: 20px;
        border-radius: 50%;
        text-align: center;
        box-shadow: 0 15px 30px rgba(201,168,76,0.4);
        border: 4px solid white;
        z-index: 10;
        width: 120px;
        height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.2;
    }

    /* CSS Animations */
    .pulse-anim { animation: pulse 3s infinite; }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .reveal-text {
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
        animation: reveal 1.5s cubic-bezier(0.77, 0, 0.175, 1);
    }
    @keyframes reveal {
        0% { transform: translateY(100%); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .fade-up-anim {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUp 1s forwards cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .slide-in-right {
        opacity: 0;
        transform: translateX(50px);
        animation: slideInRight 1s forwards cubic-bezier(0.2, 0.8, 0.2, 1);
        animation-delay: 0.5s;
    }
    
    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }
    .delay-4 { animation-delay: 0.8s; }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInRight {
        to { opacity: 1; transform: translateX(0); }
    }

    /* Abstract Shapes */
    .abstract-shape { position: absolute; border-radius: 50%; filter: blur(60px); z-index: 1; opacity: 0.5; }
    .shape-1 { width: 300px; height: 300px; background: rgba(201,168,76,0.15); top: -100px; right: -100px; }
    .shape-2 { width: 400px; height: 400px; background: rgba(255,255,255,0.3); bottom: -150px; left: -150px; }

    /* Scroll Animation Utility */
    .fade-up-scroll {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .fade-up-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Company story (Admin → About Section) ── */
    .about-hero-photo { position: absolute; inset: 0; background: center / cover no-repeat; opacity: .16; }
    .about-story-media img {
        display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 22px;
        border: 1px solid rgba(201, 168, 76, .45); box-shadow: 0 24px 50px rgba(139, 105, 20, .18);
    }
    .about-story-body p { margin-bottom: 1rem; line-height: 1.85; }
    .about-story-body ul, .about-story-body ol { padding-left: 1.3rem; margin-bottom: 1rem; }
    .about-story-body a { color: var(--gold-dark); }
    .about-story-body h3, .about-story-body h4 { font-family: var(--font-display); color: var(--navy); margin: 1.25rem 0 .6rem; }

    /* ── Leadership: Founder & CEO ── */
    .ld-portrait { position: relative; max-width: 400px; margin: 0 auto 1.5rem; }
    .ld-portrait::before {
        content: ''; position: absolute; inset: 20px -20px -20px 20px; z-index: 0;
        border: 2px solid var(--gold); border-radius: 26px;
    }
    .ld-portrait img {
        position: relative; z-index: 1; display: block; width: 100%; aspect-ratio: 4 / 5; object-fit: cover;
        border-radius: 26px; box-shadow: 0 28px 60px rgba(12, 20, 40, .22);
    }
    .ld-badge {
        position: absolute; z-index: 2; left: 50%; bottom: -20px; transform: translateX(-50%);
        background: linear-gradient(135deg, #d8b95a, #c9a84c); color: #2b2417; border: 2px solid #fff; border-radius: 999px;
        padding: .7rem 1.7rem; font-size: .8rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; white-space: nowrap;
        box-shadow: 0 10px 24px rgba(12, 20, 40, .25);
    }
    .ld-name { font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--navy); line-height: 1.15; }
    .ld-role { color: var(--gold-dark); font-weight: 600; letter-spacing: .04em; margin: .35rem 0 1.5rem; }
    .ld-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .ld-stat { background: #fff; border: 1px solid rgba(201, 168, 76, .35); border-top: 3px solid var(--gold); border-radius: 12px; padding: 1rem .9rem; text-align: center; }
    .ld-stat strong { display: block; font-family: var(--font-display); font-size: 1.9rem; color: var(--navy); line-height: 1.1; }
    .ld-stat span { display: block; margin-top: .3rem; font-size: .78rem; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted, #666); }
    .ld-quote {
        margin: 1.5rem 0 0; padding: 1.1rem 1.4rem; border-left: 4px solid var(--gold); background: rgba(201, 168, 76, .08);
        border-radius: 0 12px 12px 0; font-family: var(--font-display); font-size: 1.25rem; font-style: italic; color: var(--navy); line-height: 1.6;
    }
    @media (max-width: 575px) {
        .ld-portrait::before { inset: 14px -12px -14px 12px; }
        .ld-name { font-size: 1.6rem; }
        .ld-stats { gap: .6rem; }
        .ld-stat { padding: .8rem .4rem; }
        .ld-stat strong { font-size: 1.5rem; }
        .ld-stat span { font-size: .68rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up-scroll').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
@endpush
