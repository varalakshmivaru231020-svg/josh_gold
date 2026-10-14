@extends('layouts.lawfirm')

@section('title', 'About Us | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="about-luxury-page overflow-hidden">
    <!-- Page heading -->
    <div class="about-head">
        @include('layouts.partials.page-head', [
            'eyebrow' => 'Management and organization',
            'title'   => 'About',
            'accent'  => 'Us',
            'sub'     => '"Building trust through exceptional service and unwavering dedication."',
            'crumbs'  => [['About Us']],
            'center'  => true,
        ])
        @if($about && $about->hero_image)
            <div class="about-hero-photo" style="background-image: url('{{ versioned_asset('storage/' . $about->hero_image) }}');" aria-hidden="true"></div>
        @endif
    </div>

    <div class="container about-intro position-relative z-3">
        {{-- Company story: managed in Admin → About Section (hidden when empty or switched off) --}}
        @if($about && (filled($about->title) || filled($about->subtitle) || filled($about->content) || $about->image))
        <div class="row g-4 g-lg-5 align-items-center mb-5 about-story">
            @if($about->image)
            <div class="col-lg-5" data-aos="fade-right">
                <figure class="about-story-media mb-0">
                    <img src="{{ versioned_asset('storage/' . $about->image) }}" alt="{{ $about->title ?: 'About JOSHA GOLD AND DIAMOND JEWELLERY' }}" loading="lazy">
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
        <div class="row g-4 g-lg-5 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <figure class="ld-portrait mb-0">
                    {{-- Founder photo supplied by the client (public/images/team/prasad-ashok.webp, 800 × 1000) --}}
                    <img src="{{ versioned_asset('images/team/prasad-ashok.webp') }}" alt="Mr. Prasad Ashok, Founder &amp; CEO of JOSHA GOLD AND DIAMOND JEWELLERY" width="800" height="1000" loading="lazy" decoding="async">
                    <figcaption class="ld-badge">Founder &amp; CEO</figcaption>
                </figure>
            </div>

            <div class="col-lg-7" data-aos="fade-left">
                <span class="label-brand">LEADERSHIP</span>
                <h2 class="title-premium text-navy mb-3">Meet Our Founder &amp; CEO</h2>
                <div class="gold-separator mb-4 w-25"></div>

                <div class="ld-name">Mr. Prasad Ashok</div>
                <div class="ld-role">Founder &amp; CEO, JOSHA GOLD AND DIAMOND JEWELLERY</div>

                <div class="ld-stats">
                    <div class="ld-stat"><strong>7+</strong><span>Years of experience</span></div>
                    <div class="ld-stat"><strong>2023</strong><span>Year founded</span></div>
                    <div class="ld-stat"><strong>200%+</strong><span>Business growth</span></div>
                </div>

                <div class="about-text-wrap text-muted lead-text">
                    <p>He brings with him more than <strong class="text-navy">7 years of hardcore experience</strong> in this trade and business. His track record is impeccable, and his management style is innovative, perfectly aligning with the JOSHA GOLD AND DIAMOND JEWELLERY standards.</p>
                </div>

                <blockquote class="ld-quote">
                    He draws enormous strength from the overwhelming support extended to him by our dedicated team, driving the company toward unprecedented growth.
                </blockquote>
            </div>
        </div>
    </div>

    <!-- Operating Section (gold-tinted band) -->
    <div class="about-band position-relative overflow-hidden">
        <div class="container position-relative z-2">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-12 text-center mb-2">
                    <h3 class="display-font about-band-title mb-3 fade-up-scroll">WHERE AND HOW ARE WE OPERATING?</h3>
                    <div class="accent-line-gold mx-auto mb-3"></div>
                </div>
                <div class="col-lg-6 fade-up-scroll">
                    <div class="p-4 glass-card-dark h-100">
                        <i class="fas fa-chart-line text-gold fs-1 mb-3"></i>
                        <h4 class="mb-3">Rapid Growth</h4>
                        <p class="about-band-text">
                            JOSHA GOLD AND DIAMOND JEWELLERY – Bangalore, ever since its inception in <strong>2023</strong>, has taken painstaking efforts to ensure it is a major force to reckon with. Our business growth of over <strong>200%</strong> stands as clear testimony to our passion, zeal, and enthusiasm in carving a niche in this burgeoning marketplace.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 fade-up-scroll">
                    <div class="p-4 glass-card-dark h-100">
                        <i class="fas fa-map-marker-alt text-gold fs-1 mb-3"></i>
                        <h4 class="mb-3">Headquarters</h4>
                        <p class="about-band-text">
                            JOSHA GOLD AND DIAMOND JEWELLERY is currently operated from:<br>
                            <strong>No 73, 2nd Floor, Nagavarapalya Main Road,<br>C V Raman Nagar Road,<br>Bangalore 560093</strong>
                        </p>
                        <p class="about-band-text about-band-quote mt-3 fst-italic ps-3">
                            "We strongly believe that we should practice what we preach with no let-up and take on further in ensuring our prime objectives never deviate."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Addition -->
    <div class="container about-section">
        <div class="row g-4 g-lg-5 align-items-center">
            <div class="col-lg-5 fade-up-scroll">
                <div class="position-relative about-value-media">
                    <img src="{{ versioned_asset('images/site/gold-rings-wide.webp') }}" class="img-fluid" alt="Value Addition" loading="lazy">
                    <div class="floating-badge pulse-anim">
                        <span class="fs-4 fw-bold">100%</span>
                        <br>Commitment
                    </div>
                </div>
            </div>
            <div class="col-lg-7 fade-up-scroll">
                <h3 class="title-premium text-navy mb-4">What is our value addition?</h3>
                <div class="value-list">
                    <div class="value-item d-flex align-items-start mb-3 hover-translate">
                        <div class="value-icon flex-shrink-0 me-3 me-sm-4">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">Extensive Experience</h5>
                            <p class="text-muted mb-0">We possess extensive years of experience in this trade and are well acclimatized to handling any job, be it small, medium, or mammoth.</p>
                        </div>
                    </div>
                    <div class="value-item d-flex align-items-start mb-3 hover-translate">
                        <div class="value-icon flex-shrink-0 me-3 me-sm-4">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">No Empty Promises</h5>
                            <p class="text-muted mb-0">We don't offer empty promises; our deliverables will conform to what we project and stand testimony to our ability of service.</p>
                        </div>
                    </div>
                    <div class="value-item d-flex align-items-start hover-translate">
                        <div class="value-icon flex-shrink-0 me-3 me-sm-4">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div>
                            <h5 class="text-navy fw-bold">Superior Workmanship</h5>
                            <p class="text-muted mb-0">We bring in not only experience but also good workmanship that is both considerate and conscientious.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strengths Section -->
    <div class="about-strengths position-relative">
        <div class="container">
            <div class="text-center mb-4 mb-md-5 fade-up-scroll">
                <h2 class="title-premium text-navy mb-2">Garden City Of Bengaluru</h2>
                <h5 class="about-strengths-sub">We List Some Of Our Key Strengths Below</h5>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-md-4 fade-up-scroll">
                    <div class="strength-card h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="text-navy mb-3">QUALITY & CHOICES</h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">Quality control is carefully monitored by high standards, by our specialized crew in various catering and related services requirements.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up-scroll">
                    <div class="strength-card h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="text-navy mb-3">HEALTH & SAFETY</h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">Our primary focus is protecting partners, staff, and the environment. We have specialists versed in safety laws, conducting regular training and professional audits.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up-scroll">
                    <div class="strength-card h-100 text-center">
                        <div class="icon-wrapper mb-4 mx-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h5 class="text-navy mb-3">OUR VISION</h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">We focus on the core business of providing timely work and related services, allowing our clients to concentrate entirely on their own core businesses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values and Mission -->
    <div class="container about-section">
        <div class="row g-0 align-items-stretch mission-vision-container overflow-hidden fade-up-scroll">
            <div class="col-lg-6 position-relative">
                <div class="mv-pane mv-pane--values h-100 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-gem fs-2 text-gold me-3"></i>
                        <h3 class="mb-0 display-font">OUR VALUES</h3>
                    </div>
                    <ul class="custom-check-list mb-0">
                        <li><span>Excellent Customer Care & Service</span></li>
                        <li><span>Excellent listening skills</span></li>
                        <li><span>Excellent timely Work</span></li>
                        <li><span>Excellent motivated personnel teams and professionalism</span></li>
                        <li><span>Excellent long term clients partnership and trust.</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mv-pane mv-pane--mission h-100 d-flex flex-column justify-content-center position-relative overflow-hidden">
                    <div class="position-relative z-2">
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-bullseye fs-2 text-gold me-3"></i>
                            <h3 class="mb-0 display-font">OUR MISSION</h3>
                        </div>
                        <p class="mv-mission-text">
                            We make sure that every customer is happy and most satisfied at the end of each workmanship.
                        </p>
                        <div class="mv-tag mt-4 d-inline-block">
                            <strong class="tracking-wide">(WIN WIN skeeye)</strong>
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
    .about-luxury-page { background: var(--jb-ground); color: var(--jb-text); }
    .tracking-wide { letter-spacing: 2px; }

    /* Page heading: optional admin hero photo shown faintly behind the heading */
    .about-head { position: relative; }
    .about-head .jb-pagehead .jb-wrap { position: relative; z-index: 1; }
    .about-hero-photo { position: absolute; inset: 0; z-index: 0; background: center / cover no-repeat; opacity: .16; pointer-events: none; }

    /* Section rhythm */
    .about-intro { padding-top: 48px; padding-bottom: 56px; }
    .about-section { padding-top: 56px; padding-bottom: 56px; }
    @media (max-width: 767px) {
        .about-intro { padding-top: 32px; padding-bottom: 40px; }
        .about-section { padding-top: 40px; padding-bottom: 40px; }
    }

    /* Headings */
    .about-luxury-page .title-premium { font-weight: 600; font-size: clamp(24px, 3vw, 32px); line-height: 1.2; }
    .about-luxury-page .display-font { font-weight: 600; color: var(--jb-ink); }
    .about-luxury-page h4, .about-luxury-page h5 { color: var(--jb-ink); font-weight: 600; }
    .about-luxury-page .text-muted { color: var(--jb-text) !important; }
    .about-luxury-page .lead-text { font-size: 15px; line-height: 1.75; }
    .about-luxury-page .gold-separator { margin-top: 4px; }

    /* ── Company story (Admin → About Section) ── */
    .about-story-media img {
        display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: var(--jb-r-card);
        border: 1px solid var(--jb-line); box-shadow: var(--jb-shadow-lg);
    }
    .about-story-body p { margin-bottom: 1rem; line-height: 1.85; }
    .about-story-body ul, .about-story-body ol { padding-left: 1.3rem; margin-bottom: 1rem; }
    .about-story-body a { color: var(--jb-gold-strong); }
    .about-story-body a:hover { color: var(--jb-ink); }
    .about-story-body strong, .about-story-body b { color: var(--jb-ink); }
    .about-story-body h3, .about-story-body h4 { font-family: var(--jb-serif); color: var(--jb-ink); margin: 1.25rem 0 .6rem; }
    .about-story-body blockquote { margin: 1rem 0; padding: .9rem 1.2rem; border-left: 2px solid var(--jb-gold); background: #fffaf0; border-radius: 0 12px 12px 0; color: var(--jb-text); }

    /* ── Leadership: Founder & CEO ── */
    .ld-portrait { position: relative; max-width: 400px; margin: 0 auto 1.5rem; }
    .ld-portrait::before {
        content: ''; position: absolute; inset: 20px -20px -20px 20px; z-index: 0;
        border: 1px solid var(--jb-line-strong); border-radius: 22px; background: linear-gradient(160deg, #fbf1da, #fff 70%);
    }
    .ld-portrait img {
        position: relative; z-index: 1; display: block; width: 100%; height: auto; aspect-ratio: 4 / 5; object-fit: cover; object-position: 50% 20%;
        border-radius: 22px; border: 4px solid #fff; box-shadow: var(--jb-shadow-lg);
    }
    .ld-badge {
        position: absolute; z-index: 2; left: 50%; bottom: -18px; transform: translateX(-50%);
        background: var(--jb-gold-band); color: #2a1d06; border: 1px solid #b48b3c; border-radius: 999px;
        padding: .65rem 1.5rem; font-size: .75rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; white-space: nowrap;
        box-shadow: 0 10px 24px -12px rgba(138, 101, 22, .8), inset 0 1px 0 rgba(255, 255, 255, .45);
    }
    .ld-name { font-family: var(--jb-serif); font-size: 1.9rem; font-weight: 600; color: var(--jb-ink); line-height: 1.15; }
    .ld-role { color: var(--jb-gold-strong); font-size: 13px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin: .45rem 0 1.5rem; }
    .ld-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-bottom: 1.5rem; }
    .ld-stat {
        border: 1px solid var(--jb-line); border-radius: 14px; padding: 1rem .9rem; text-align: center;
        background: linear-gradient(180deg, #fffaf0, #fff 70%); box-shadow: var(--jb-shadow);
    }
    .ld-stat strong { display: block; font-family: var(--jb-serif); font-weight: 600; font-size: 1.75rem; color: var(--jb-gold-strong); line-height: 1.1; font-variant-numeric: tabular-nums; }
    .ld-stat span { display: block; margin-top: .35rem; font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--jb-muted); }
    .about-text-wrap strong { color: var(--jb-ink); }
    .ld-quote {
        margin: 1.5rem 0 0; padding: 1.1rem 1.4rem; border-left: 3px solid var(--jb-gold); background: linear-gradient(90deg, #fbf1da, #fffaf0);
        border-radius: 0 14px 14px 0; font-family: var(--jb-serif); font-size: 1.15rem; font-style: italic; color: var(--jb-ink); line-height: 1.65;
    }
    @media (max-width: 575px) {
        .ld-portrait { max-width: 320px; }
        .ld-portrait::before { inset: 12px -10px -12px 10px; }
        .ld-name { font-size: 1.55rem; }
        .ld-stats { gap: 8px; }
        .ld-stat { padding: .8rem .35rem; }
        .ld-stat strong { font-size: 1.3rem; }
        .ld-stat span { font-size: 9.5px; letter-spacing: .08em; }
        .ld-quote { font-size: 1.05rem; padding: 1rem 1.1rem; }
    }

    /* ── Operating band ── */
    .about-band {
        padding: 56px 0; border-top: 1px solid var(--jb-line-soft); border-bottom: 1px solid var(--jb-line-soft);
        background:
            radial-gradient(800px 340px at 88% 0%, rgba(214, 178, 95, .22), transparent 60%),
            radial-gradient(600px 300px at 0% 100%, rgba(214, 178, 95, .14), transparent 60%),
            linear-gradient(180deg, #fffaf0, #fbf1da);
    }
    @media (max-width: 767px) { .about-band { padding: 40px 0; } }
    .about-band-title { font-size: clamp(20px, 2.6vw, 26px); letter-spacing: .02em; color: var(--jb-ink); }
    .about-band h4 { color: var(--jb-ink); }
    .about-band-text { color: var(--jb-text); line-height: 1.8; }
    .about-band-text strong { color: var(--jb-ink); }
    .about-band-quote { border-left: 2px solid var(--jb-gold); color: var(--jb-muted); }
    .about-luxury-page .glass-card-dark {
        background: #fff;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow);
        transition: border-color .2s, transform .2s, box-shadow .2s;
    }
    @media (hover: hover) {
        .about-luxury-page .glass-card-dark:hover { border-color: var(--jb-line-strong); transform: translateY(-3px); box-shadow: var(--jb-shadow-lg); }
    }
    .about-luxury-page .glass-card-dark .fs-1 { font-size: 1.9rem !important; }

    /* ── Value addition ── */
    .about-value-media { max-width: 520px; margin: 0 auto; }
    .about-value-media img {
        display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: var(--jb-r-card); border: 1px solid var(--jb-line);
        background: var(--jb-panel-3); color: var(--jb-muted); box-shadow: var(--jb-shadow-lg);
    }
    .floating-badge {
        position: absolute; bottom: -18px; right: -14px; z-index: 10;
        width: 118px; height: 118px; padding: 10px; border-radius: 50%;
        display: flex; flex-direction: column; justify-content: center; text-align: center; line-height: 1.2;
        background: var(--jb-gold-band); color: #2a1d06; font-size: 13px; font-weight: 600;
        border: 4px solid #fff; box-shadow: 0 14px 30px -12px rgba(138, 101, 22, .65);
    }
    .floating-badge .fs-4 { font-family: var(--jb-serif); }
    @media (max-width: 575px) {
        .floating-badge { width: 100px; height: 100px; padding: 8px; right: 10px; bottom: -14px; font-size: 11px; }
        .floating-badge .fs-4 { font-size: 1.2rem !important; }
    }

    .hover-translate { padding: 16px; border: 1px solid transparent; border-radius: 14px; transition: transform .25s, background-color .25s, border-color .25s; }
    @media (hover: hover) {
        .hover-translate:hover { transform: translateX(6px); background: #fff; border-color: var(--jb-line); box-shadow: var(--jb-shadow); }
    }
    .value-item h5 { font-size: 17px; margin-bottom: .4rem; }
    .value-item p { font-size: 14px; line-height: 1.65; }
    .value-icon {
        width: 52px; height: 52px; display: grid; place-items: center; border-radius: 14px;
        background: #fbf1da; border: 1px solid #ecd49c; color: var(--jb-gold-strong); font-size: 1.2rem;
    }
    @media (max-width: 575px) {
        .hover-translate { padding: 12px 4px; }
        .value-icon { width: 44px; height: 44px; font-size: 1rem; border-radius: 12px; }
    }

    /* ── Strengths ── */
    .about-strengths { padding: 56px 0; border-top: 1px solid var(--jb-line-soft); border-bottom: 1px solid var(--jb-line-soft); background: var(--jb-surface); }
    @media (max-width: 767px) { .about-strengths { padding: 40px 0; } }
    .about-strengths-sub { margin: 0; color: var(--jb-gold-strong) !important; font-family: var(--jb-sans); font-size: 14px; font-weight: 600; letter-spacing: .04em; }
    .strength-card {
        padding: 32px 26px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, #fffaf0, #fff 55%); box-shadow: var(--jb-shadow);
        transition: transform .25s, border-color .25s, box-shadow .25s;
    }
    @media (hover: hover) {
        .strength-card:hover { transform: translateY(-4px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
        .strength-card:hover .icon-wrapper { background: var(--jb-gold-band); border-color: #b48b3c; color: #2a1d06; }
    }
    .strength-card h5 { font-size: 15px; letter-spacing: .08em; }
    .strength-card .icon-wrapper {
        width: 68px; height: 68px; border-radius: 18px; font-size: 1.5rem;
        background: #fbf1da; border: 1px solid #ecd49c; color: var(--jb-gold-strong);
        transition: background-color .25s, border-color .25s, color .25s;
    }
    @media (max-width: 575px) { .strength-card { padding: 26px 20px; } }

    /* ── Values and mission ── */
    .mission-vision-container { border: 1px solid var(--jb-line); border-radius: 22px; background: #fff; box-shadow: var(--jb-shadow-lg); }
    .mv-pane { padding: 40px; color: var(--jb-text); }
    @media (max-width: 575px) { .mv-pane { padding: 28px 22px; } }
    .mv-pane h3 { font-size: clamp(20px, 2.4vw, 24px); }
    .mv-pane--values { background: #fff; }
    .mv-pane--mission {
        background:
            radial-gradient(520px 260px at 100% 0%, rgba(214, 178, 95, .26), transparent 65%),
            linear-gradient(180deg, #fffaf0, #fbf1da);
        border-left: 1px solid var(--jb-line);
    }
    @media (max-width: 991px) { .mv-pane--mission { border-left: 0; border-top: 1px solid var(--jb-line); } }
    .mv-mission-text { margin: 0; color: var(--jb-ink); font-family: var(--jb-serif); font-size: 1.2rem; line-height: 1.75; }
    .mv-tag { padding: 10px 16px; border-radius: 12px; color: var(--jb-gold-deep); background: #fff; border: 1px solid var(--jb-line-strong); box-shadow: var(--jb-shadow); font-size: 15px; }

    .custom-check-list { list-style: none; padding: 0; color: var(--jb-text); font-size: 1.02rem; line-height: 1.9; }
    .custom-check-list li { position: relative; padding-left: 30px; margin-bottom: 10px; transition: transform .25s, color .25s; }
    .custom-check-list li:hover { transform: translateX(5px); color: var(--jb-ink); }
    .custom-check-list li::before {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free', 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 1px;
        color: var(--jb-gold);
        font-size: 0.85rem;
    }

    /* CSS Animations */
    .pulse-anim { animation: pulse 3s infinite; }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

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
    @media (prefers-reduced-motion: reduce) {
        .pulse-anim { animation: none; }
        .fade-up-scroll { opacity: 1; transform: none; transition: none; }
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
