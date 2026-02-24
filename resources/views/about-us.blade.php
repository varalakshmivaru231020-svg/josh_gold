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
                    <h2 class="title-premium text-navy mb-4">{{ $about?->title ?? 'Bridging the Gap Between Law & Literature' }}</h2>
                    <div class="gold-separator"></div>
                    <div class="about-text-wrap text-muted">
                        {!! $about?->content ?? 'Adv. Veerendrababu Nanjegowda is a distinguished legal luminary with multifaceted expertise spanning Law, Business, Journalism, and Cinema. With a career dedicated to the pursuit of justice and the empowerment of the voiceless, he has established himself as a prominent voice in India\'s legal and cultural landscape.' !!}
                    </div>
                    
                    @if($about?->subtitle)
                        <div class="quote-box-lux mt-5">
                            <p class="h4 italic mb-0">"{{ $about->subtitle }}"</p>
                        </div>
                    @endif
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
    
    .quote-box-lux {
        padding: 40px; background: white; border-left: 5px solid var(--gold);
        border-radius: 0 30px 30px 0; box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        font-family: var(--font-display); color: var(--navy);
    }

    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration:none; }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    .mt-n5 { margin-top: -100px !important; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; position: relative; z-index: 2; }
</style>
@endpush
