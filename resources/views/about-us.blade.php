@extends('layouts.lawfirm')

@section('title', 'About Our History | Adv. Veerendrababu Nanjegowda')

@section('content')
<section class="about-luxury-page">
    <div class="about-hero-header" style="background-image: linear-gradient(rgba(12,20,40,0.85), rgba(12,20,40,0.85)), url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">THE LEGACY</div>
            <h1 class="heading-premium text-white">Our History & Mission</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="hero-subheader text-white opacity-90 italic mt-3">"A relentless guardian of justice, a storyteller of truth."</p>
        </div>
    </div>

    <div class="container py-5 mt-n5">
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Our History</span>
        </div>

        <div class="row g-5 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="about-page-visual">
                    @if($about && $about->image)
                        <img src="{{ asset('storage/' . $about->image) }}" alt="Biography" class="img-fluid rounded-4 shadow-lg border border-gold border-4">
                    @else
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=500" alt="Biography" class="img-fluid rounded-4 shadow-lg">
                    @endif
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="about-page-content">
                    <span class="label-brand">BIOGRAPHY</span>
                    <h2 class="title-premium text-navy mb-4">{{ $about?->title == 'About Us' ? 'Our Commitment to Justice' : ($about?->title ?? 'Bridging the Gap Between Law & Literature') }}</h2>
                    <div class="gold-separator"></div>
                    <div class="about-text-wrap text-muted">
                        {!! $about?->content ?? 'Adv. Veerendrababu Nanjegowda is a distinguished legal luminary with multifaceted expertise spanning Law, Business, Journalism, and Cinema.' !!}
                    </div>
                    
                    <div class="about-quote-block mt-5">
                        <span class="about-quote-mark">&ldquo;</span>
                        <p class="about-quote-text">Justice is not a privilege for the few — it is the birthright of every citizen. My practice is a promise to make it accessible, fair, and meaningful.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .about-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    
    .about-hero-header { 
        background: var(--navy); 
        padding: 160px 0 120px; 
        position:relative; 
        overflow:hidden; 
    }
    .about-hero-header::before {
        content: ''; position: absolute; inset: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.04) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    
    .hero-subheader { max-width: 700px; margin: 0 auto; font-family: var(--font-display); font-size: 1.4rem; font-style: italic; position: relative; z-index: 2; }

    .about-page-visual img { width: 100%; border-radius: 40px; }
    
    .label-brand { font-size: 0.85rem; letter-spacing: 4px; color: var(--gold-dark); font-weight: 800; display: block; margin-bottom: 15px; }
    
    .about-text-wrap { font-size: 1.1rem; line-height: 1.8; }
    
    /* ── ABOUT QUOTE BLOCK ── */
    .about-quote-block {
        position: relative;
        background: var(--navy, #0c1428);
        border-radius: 12px;
        padding: 32px 36px 32px 44px;
        margin-top: 28px;
        border-left: 4px solid var(--gold, #c9a84c);
        box-shadow: 0 12px 40px rgba(12,20,40,0.18);
        overflow: hidden;
    }
    .about-quote-block::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 50%, rgba(201,168,76,0.06) 0%, transparent 70%);
        pointer-events: none;
    }
    .about-quote-mark {
        font-family: var(--font-display, Georgia, serif);
        font-size: 5rem;
        line-height: 0.6;
        color: var(--gold, #c9a84c);
        opacity: 0.6;
        display: block;
        margin-bottom: 8px;
    }
    .about-quote-text {
        font-family: var(--font-display, Georgia, serif);
        font-size: 1.15rem;
        font-style: italic;
        line-height: 1.75;
        color: rgba(255,255,255,0.95);
        margin: 0;
    }

    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration:none; }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    .mt-n5 { margin-top: -100px !important; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; position: relative; z-index: 2; }
</style>
@endpush
