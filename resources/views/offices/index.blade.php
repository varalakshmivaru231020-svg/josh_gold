@extends('layouts.lawfirm')

@section('title', 'Our Chambers | Global Presence')

@section('content')
<section class="offices-luxury-page">
    <div class="office-hero-header">
        <div class="office-hero-deco" aria-hidden="true"><i class="fas fa-landmark-dome"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">OUR PRESENCE</div>
            <h1 class="heading-premium text-white">Chamber Locations</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="hero-subheader text-white opacity-90 italic mt-3">Strategically located for state and national advocacy.</p>
        </div>
    </div>

    <div class="container offices-content-container">
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Chambers</span>
        </div>

        <div class="row g-5">
            @forelse($offices as $index => $office)
                @php
                    $isBlr = str_contains(strtolower($office->title), 'bengaluru');
                    $isDelhi = str_contains(strtolower($office->title), 'delhi');
                    
                    // Same colors as home page
                    $cCol = $isDelhi ? '#8b1a24' : ($isBlr ? '#1a2e5e' : ($office->color ?? '#1a2e5e'));
                    
                    $hIcon = $isBlr ? 'fa-solid fa-city' : ($isDelhi ? 'fa-solid fa-landmark' : 'fa-solid fa-building');
                    $hIconClass = $isBlr ? 'ocl-header-icon-blue' : 'ocl-header-icon-white';
                @endphp
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                    <div class="office-card-lux h-100">
                        <div class="ocl-header" style="background: {{ $cCol }};">
                            <div class="ocl-icon-box">
                                <i class="{{ $hIcon }} {{ $hIconClass }}"></i>
                            </div>
                            <div class="ocl-header-text">
                                <h3 class="ocl-title">{{ $office->title }}</h3>
                                @if($office->subtitle)
                                    <p class="ocl-subtitle">{{ strtoupper($office->subtitle) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="ocl-body">
                            @if($office->address)
                                <div class="ocl-row">
                                    <i class="fas fa-map-marker-alt ocl-icon ocl-pin"></i>
                                    <span>{{ $office->address }}</span>
                                </div>
                            @endif
                            @if($office->phone)
                                <div class="ocl-row">
                                    <i class="fas fa-phone-alt ocl-icon ocl-phone"></i>
                                    <a href="tel:{{ str_replace(['+', ' ', '(', ')', '-'], '', $office->phone) }}" class="ocl-link">
                                        {{ $office->phone }}
                                    </a>
                                </div>
                            @endif
                            @if($office->mobile)
                                <div class="ocl-row">
                                    <i class="fas fa-mobile-alt ocl-icon ocl-mobile"></i>
                                    <a href="tel:{{ str_replace(['+', ' ', '(', ')', '-'], '', $office->mobile) }}" class="ocl-link">
                                        {{ $office->mobile }}
                                    </a>
                                </div>
                            @endif
                            @if($office->email)
                                <div class="ocl-row">
                                    <i class="fas fa-envelope ocl-icon ocl-email"></i>
                                    <a href="mailto:{{ $office->email }}" class="ocl-link">
                                        {{ $office->email }}
                                    </a>
                                </div>
                            @endif
                            @if($office->website)
                                <div class="ocl-row">
                                    <i class="fas fa-globe ocl-icon ocl-web"></i>
                                    <a href="http://{{ str_replace(['http://', 'https://'], '', $office->website) }}" target="_blank" class="ocl-link">
                                        {{ $office->website }}
                                    </a>
                                </div>
                            @endif

                            @if($office->map_embed)
                            <div class="office-map-mini rounded-4 overflow-hidden mt-4 border border-navy border-opacity-10">
                                {!! $office->map_embed !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
            <div class="col-12 text-center py-5">
                <h3 class="text-navy">Chamber Information Updating</h3>
                <p>Please contact our principal office for immediate assistance.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .offices-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    
    /* Hero */
    .office-hero-header { 
        background: 
            linear-gradient(rgba(8,14,28,0.82) 0%, rgba(10,18,36,0.85) 100%),
            url('/images/contact-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px;
        position: relative;
        overflow: hidden;
        z-index: 0;
    }
    .office-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.05) 1px, transparent 1px);
        background-size: 55px 55px;
    }
    .office-hero-header::after { 
        content:''; position:absolute; inset:0; z-index: 0;
        background: radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.22) 0%, transparent 50%); 
    }
    .office-hero-deco {
        position: absolute; right: 8%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.065);
        pointer-events: none; z-index: 0; line-height: 1;
    }
    
    .hero-subheader { max-width: 700px; margin: 0 auto; font-family: var(--font-display); font-size: 1.4rem; font-style: italic; position: relative; z-index: 2; }

    /* ── OFFICE CARDS LUX (MATCH SCREENSHOT) ── */
    .office-card-lux {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 45px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .office-card-lux:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.12);
    }
    .ocl-header {
        padding: 25px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: #ffffff;
    }
    .ocl-icon-box {
        width: 52px;
        height: 52px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        backdrop-filter: blur(8px);
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
    }
    .ocl-header-text {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    .ocl-title {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }
    .ocl-subtitle {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.8;
        margin-top: 5px;
    }
    .ocl-body {
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        text-align: left;
    }
    .ocl-row {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        color: #444;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .ocl-icon {
        width: 18px;
        font-size: 1rem;
        margin-top: 3px;
        text-align: center;
        flex-shrink: 0;
    }
    
    /* Icon Colors from Reference - EXACT SHADES */
    .ocl-pin { color: #ff4d4d; }    /* Vibrant Red/Pink */
    .ocl-phone { color: #8b1a24; }  /* Deep Maroon */
    .ocl-mobile { color: #8b1a24; } /* Deep Maroon */
    .ocl-email { color: #6f42c1; }  /* Purple */
    .ocl-web { color: #007bff; }    /* Bright Blue */

    .ocl-header-icon-white { color: #ffffff !important; }
    .ocl-header-icon-blue { color: #60c0ff !important; } /* Electric Blue for Bengaluru */

    .ocl-link {
        color: inherit;
        text-decoration: none;
        transition: 0.3s;
    }
    .ocl-link:hover {
        color: var(--gold-dark);
    }

    .office-map-mini { height: 260px; }
    .office-map-mini iframe { width: 100% !important; height: 100% !important; filter: grayscale(1) contrast(1.1); }

    /* Content sits below banner with proper spacing */
    .offices-content-container { padding-top: 60px; padding-bottom: 60px; }

    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; }
    .breadcrumb-luxury a { color: var(--gold-dark, #8b6914); text-decoration:none; }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    .accent-line-gold { width: 80px; height: 3px; background: var(--gold, #c9a84c); margin-top: 25px; position: relative; z-index: 1; border-radius: 2px; }

    @media (max-width: 768px) {
        .office-card-lux { flex-direction: column; padding: 30px; }
    }
</style>
@endpush
