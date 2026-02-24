@extends('layouts.lawfirm')

@section('title', 'Areas of Practice | Advocate Veerendrababu Nanjegowda')

@section('content')
<section class="pas-page">
    {{-- Cinematic Hero Header --}}
    <div class="pas-page-hero" style="background-image: linear-gradient(rgba(12,20,40,0.85), rgba(12,20,40,0.85)), url('https://images.unsplash.com/photo-1505664194779-8beaceb93744?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
        <div class="pas-page-hero-grid"></div>
        <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
            <span class="pas-eyebrow" style="color: var(--gold);">AREAS OF PRACTICE</span>
            <h1 class="pas-page-heading">Practicing at the Pinnacles<br>of India's Judiciary</h1>
            <div class="pas-separator mx-auto" style="background:var(--gold);"></div>
            <p class="pas-page-sub text-white opacity-90 italic">"Justice is not a compromise — it is wisdom in action."</p>
        </div>
    </div>

    <div class="container pas-page-body">
        {{-- Breadcrumb --}}
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Areas of Practice</span>
        </div>

        {{-- Cards Grid --}}
        <div class="pas-grid mb-5">
            @forelse($services as $index => $service)
            <div class="pas-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                <div class="pas-card-icon">
                    @if($service->icon && str_starts_with($service->icon, 'fa'))
                        <i class="{{ $service->icon }}"></i>
                    @else
                        <span class="pas-emoji">{{ $service->icon ?? '⚖️' }}</span>
                    @endif
                </div>
                <h3 class="pas-card-title">{{ $service->title }}</h3>
                <p class="pas-card-text">{{ $service->description }}</p>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-balance-scale fa-3x text-gold mb-4 d-block opacity-50"></i>
                <h3 class="text-navy">Practice Areas Coming Soon</h3>
                <p class="text-muted">Please contact the principal office for detailed information on practice areas.</p>
            </div>
            @endforelse
        </div>

        {{-- Quote Banner --}}
        <div class="pas-quote-banner" data-aos="fade-up">
            <i class="fas fa-quote-left pas-quote-mark"></i>
            <p class="pas-quote-text">"The best legal victory is one that heals, not one that merely wins. ADR is not a compromise — it is wisdom in action."</p>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pas-page { background: #fff; min-height: 80vh; padding-bottom: 120px; }

    .pas-page-hero {
        background: var(--navy, #0c1428);
        padding: 160px 0 120px;
        position: relative; overflow: hidden;
    }
    .pas-page-hero-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(201, 168, 76, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.04) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    .pas-page-heading {
        font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.4rem);
        color: white; font-weight: 700; margin-bottom: 20px; line-height: 1.2;
    }
    .pas-page-sub {
        max-width: 700px; margin: 0 auto; color: rgba(255,255,255,0.7);
        font-size: 1.1rem; line-height: 1.75;
    }

    .pas-page-body { padding-top: 60px; }

    /* ── Reuse shared PAS styles ── */
    .pas-eyebrow {
        display: block; font-size: 0.75rem; letter-spacing: 4px; font-weight: 800;
        color: var(--gold-dark, #b8913a); text-transform: uppercase; margin-bottom: 16px;
    }
    .pas-separator {
        width: 60px; height: 3px; background: var(--gold, #c9a84c);
        margin: 0 auto 24px; border-radius: 2px;
    }
    .pas-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    }
    .pas-card {
        background: #ffffff; border: 1px solid #e8e8e8;
        border-radius: 16px; padding: 36px 30px 32px;
        transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
        position: relative; overflow: hidden;
    }
    .pas-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--gold, #c9a84c), transparent);
        opacity: 0; transition: opacity 0.35s;
    }
    .pas-card:hover {
        box-shadow: 0 20px 60px rgba(0,0,0,0.10);
        border-color: rgba(201,168,76,0.35); transform: translateY(-6px);
    }
    .pas-card:hover::before { opacity: 1; }
    .pas-card-icon { margin-bottom: 18px; line-height: 1; }
    .pas-emoji { font-size: 2.2rem; display: block; }
    .pas-card-icon i { font-size: 2rem; color: var(--gold-dark, #b8913a); }
    .pas-card-title {
        font-family: var(--font-display); font-size: 1.08rem;
        color: var(--navy, #0c1428); font-weight: 700; margin-bottom: 12px; line-height: 1.3;
    }
    .pas-card-text { font-size: 0.9rem; color: #666; line-height: 1.7; margin: 0; }

    .pas-quote-banner {
        background: var(--navy, #0c1428); border-radius: 16px;
        padding: 40px 60px; margin-top: 50px;
        display: flex; align-items: center; gap: 28px;
        position: relative; overflow: hidden;
    }
    .pas-quote-banner::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(201,168,76,0.06) 0%, transparent 60%);
    }
    .pas-quote-mark {
        font-size: 2rem; color: var(--gold, #c9a84c); opacity: 0.6; flex-shrink: 0; position: relative; z-index: 1;
    }
    .pas-quote-text {
        font-family: var(--font-display); font-size: 1.08rem; font-style: italic;
        color: rgba(255,255,255,0.88); margin: 0; line-height: 1.65; position: relative; z-index: 1;
    }

    .breadcrumb-luxury {
        display: flex; align-items: center; gap: 12px;
        font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700;
    }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration: none; }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    @media (max-width: 991px) {
        .pas-grid { grid-template-columns: repeat(2, 1fr); }
        .pas-quote-banner { padding: 30px 35px; }
    }
    @media (max-width: 576px) {
        .pas-grid { grid-template-columns: 1fr; }
        .pas-quote-banner { flex-direction: column; text-align: center; padding: 28px 24px; }
    }
</style>
@endpush
