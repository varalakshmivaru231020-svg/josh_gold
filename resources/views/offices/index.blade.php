@extends('layouts.lawfirm')

@section('title', 'Our Chambers | Global Presence')

@section('content')
<section class="offices-luxury-page">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Our presence',
        'title'   => 'Chamber',
        'accent'  => 'Locations',
        'sub'     => 'Strategically located for state and national advocacy.',
        'crumbs'  => [['Chambers']],
    ])

    <div class="jb-wrap offices-content-container">
        <div class="row g-3 g-md-4">
            @forelse($offices as $index => $office)
                @php
                    $isBlr = str_contains(strtolower($office->title), 'bengaluru');
                    $isDelhi = str_contains(strtolower($office->title), 'delhi');

                    // Card accent colour (Admin → Offices → Card Accent Color)
                    $cCol = $isDelhi ? '#8b1a24' : ($isBlr ? '#1a2e5e' : ($office->color ?? '#1a2e5e'));

                    $hIcon = $isBlr ? 'fa-solid fa-city' : ($isDelhi ? 'fa-solid fa-landmark' : 'fa-solid fa-building');
                    $hIconClass = $isBlr ? 'ocl-header-icon-blue' : 'ocl-header-icon-white';
                @endphp
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                    <div class="office-card-lux h-100">
                        <div class="ocl-header" style="--ocl-accent: {{ $cCol }};">
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
                            <div class="office-map-mini overflow-hidden mt-3">
                                {!! $office->map_embed !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
            <div class="col-12">
                <div class="offices-empty text-center">
                    <h3 class="text-navy">Chamber Information Updating</h3>
                    <p class="mb-0">Please contact our principal office for immediate assistance.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .offices-luxury-page { background: var(--jb-ground); }

    /* Content below the heading */
    .offices-content-container { padding-top: 40px; padding-bottom: 64px; }
    @media (max-width: 767px) { .offices-content-container { padding-top: 28px; padding-bottom: 44px; } }

    /* ── Office cards ── */
    .office-card-lux {
        display: flex; flex-direction: column; overflow: hidden;
        background: #fff;
        border: 1px solid var(--jb-line);
        border-radius: var(--jb-r-card);
        box-shadow: var(--jb-shadow);
        transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    @media (hover: hover) {
        .office-card-lux:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
    }
    .ocl-header {
        padding: 22px 26px;
        display: flex;
        align-items: center;
        gap: 16px;
        color: var(--jb-ink);
        border-bottom: 1px solid var(--jb-line);
        background: linear-gradient(180deg, #fffaf0, #fff);
        /* The card's accent colour shows as a soft glow in the corner */
        background:
            radial-gradient(420px 180px at 0% 0%, color-mix(in srgb, var(--ocl-accent, #1a2e5e) 10%, transparent), transparent 70%),
            radial-gradient(420px 180px at 100% 0%, rgba(214, 178, 95, .22), transparent 70%),
            linear-gradient(180deg, #fffaf0, #fff);
    }
    .ocl-icon-box {
        width: 48px;
        height: 48px;
        background: #fbf1da;
        border: 1px solid #ecd49c;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .ocl-header-text {
        display: flex;
        flex-direction: column;
        text-align: left;
        min-width: 0;
    }
    .ocl-title {
        font-family: var(--jb-serif);
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--jb-ink);
        margin: 0;
        line-height: 1.25;
    }
    .ocl-subtitle {
        margin: 5px 0 0;
        color: var(--jb-gold-strong);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
    }
    .ocl-body {
        flex: 1;
        padding: 22px 26px 26px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        text-align: left;
    }
    .ocl-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        color: var(--jb-text);
        font-size: 14.5px;
        line-height: 1.55;
    }
    .ocl-row + .ocl-row { padding-top: 14px; border-top: 1px solid var(--jb-line-soft); }
    .ocl-icon {
        width: 18px;
        font-size: 1rem;
        margin-top: 3px;
        text-align: center;
        flex-shrink: 0;
        color: var(--jb-gold-strong);
    }

    .ocl-header-icon-white,
    .ocl-header-icon-blue { color: var(--jb-gold-strong) !important; }

    .ocl-link {
        color: var(--jb-text);
        text-decoration: none;
        transition: color .15s;
        overflow-wrap: anywhere;
    }
    .ocl-link:hover {
        color: var(--jb-gold-strong);
    }

    .office-map-mini { height: 260px; border: 1px solid var(--jb-line); border-radius: 14px; background: var(--jb-panel-2); }
    .office-map-mini iframe { display: block; width: 100% !important; height: 100% !important; border: 0; }

    .offices-empty { padding: 56px 24px; border: 1px dashed var(--jb-line-strong); border-radius: var(--jb-r-card); background: #fff; box-shadow: var(--jb-shadow); color: var(--jb-muted); }

    @media (max-width: 575px) {
        .ocl-header { padding: 18px; gap: 14px; }
        .ocl-body { padding: 18px 18px 22px; }
        .ocl-title { font-size: 1.15rem; }
    }
</style>
@endpush
