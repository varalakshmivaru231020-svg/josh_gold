@extends('layouts.lawfirm')

@section('title', 'Our Services | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="pas-page">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Our services',
        'title'   => 'Gold & Silver Services',
        'accent'  => 'You Can Trust',
        'sub'     => 'Transparent pricing, dependable quality and service you can count on.',
        'crumbs'  => [['Services']],
    ])

    <div class="jb-wrap pas-page-body">
        {{-- One row per service: photo and text side by side, alternating sides on desktop --}}
        <div class="pas-list">
            @forelse($services as $index => $service)
            @php $num = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT); @endphp
            <article class="pas-row{{ $index % 2 ? ' pas-row--flip' : '' }}" data-aos="fade-up">
                <figure class="pas-figure">
                    <div class="pas-media">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">
                        @else
                            {{-- No photo yet: a gold panel with the service icon --}}
                            <div class="pas-media__ph" aria-hidden="true">
                                <span class="pas-media__icon"><i class="{{ filled($service->icon) ? $service->icon : 'fas fa-gem' }}"></i></span>
                            </div>
                        @endif
                    </div>
                </figure>

                <div class="pas-body">
                    <span class="pas-num" aria-hidden="true">{{ $num }}</span>
                    <h2 class="pas-title">{{ $service->title }}</h2>
                    <span class="pas-rule" aria-hidden="true"></span>
                    @if(filled($service->description))<p class="pas-text">{{ $service->description }}</p>@endif
                    <a href="{{ route('contact') }}" class="jb-btn jb-btn--ghost pas-cta">Enquire <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </article>
            @empty
            <div class="pas-empty text-center">
                <h3 class="text-navy">Services Coming Soon</h3>
                <p class="text-muted mb-0">Please contact us for details of our services.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pas-page { background: var(--jb-ground); }
    .pas-page-body { padding-top: 56px; padding-bottom: 80px; }
    @media (max-width: 767px) { .pas-page-body { padding-top: 32px; padding-bottom: 48px; } }

    .pas-list { display: grid; gap: 88px; }
    @media (max-width: 991px) { .pas-list { gap: 56px; } }

    /* ── One service ── */
    .pas-row { display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(0, .88fr); gap: 64px; align-items: center; }
    .pas-row--flip { grid-template-columns: minmax(0, .88fr) minmax(0, 1.12fr); }
    .pas-row--flip .pas-figure { order: 2; }
    @media (max-width: 991px) {
        .pas-row, .pas-row--flip { grid-template-columns: 1fr; gap: 26px; }
        .pas-row--flip .pas-figure { order: 0; }
    }

    /* Photo, with a gold frame offset behind it */
    .pas-figure { position: relative; margin: 0; padding: 0 18px 18px 0; }
    .pas-figure::before {
        content: ''; position: absolute; inset: 18px 0 0 18px; z-index: 0;
        border: 1px solid var(--jb-line-strong); border-radius: 26px;
        background: linear-gradient(160deg, #fbf1da, #fffaf0 70%);
    }
    .pas-row--flip .pas-figure { padding: 0 0 18px 18px; }
    .pas-row--flip .pas-figure::before { inset: 18px 18px 0 0; }
    .pas-media {
        position: relative; z-index: 1; aspect-ratio: 3 / 2; overflow: hidden;
        border: 4px solid #fff; border-radius: 24px; background: var(--jb-panel-3);
        box-shadow: 0 0 0 1px var(--jb-line), var(--jb-shadow-lg);
    }
    .pas-media img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
    @media (hover: hover) { .pas-row:hover .pas-media img { transform: scale(1.04); } }

    /* Gold panel shown until a photo is added */
    .pas-media__ph {
        position: absolute; inset: 0; display: grid; place-items: center;
        background:
            radial-gradient(circle at 28% 22%, rgba(255, 255, 255, .6), transparent 42%),
            radial-gradient(circle at 80% 85%, rgba(110, 81, 18, .22), transparent 50%),
            repeating-linear-gradient(45deg, rgba(255, 255, 255, .08) 0 2px, transparent 2px 22px),
            linear-gradient(135deg, #f8ecca 0%, #e6c983 38%, #c9a35c 68%, #b08636 100%);
    }
    .pas-media__icon {
        width: 112px; height: 112px; display: grid; place-items: center; border-radius: 50%;
        background: rgba(255, 255, 255, .78); border: 1px solid rgba(255, 255, 255, .9);
        box-shadow: 0 18px 40px -18px rgba(110, 81, 18, .7), inset 0 0 0 6px rgba(214, 178, 95, .18);
        color: var(--jb-gold-deep); font-size: 2.6rem;
    }

    /* Text */
    .pas-body { position: relative; }
    .pas-num {
        display: block; margin-bottom: 6px;
        font: 600 clamp(56px, 7vw, 92px)/.9 var(--jb-serif); letter-spacing: -.02em;
        background: var(--jb-foil); -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .pas-title { margin: 0; font-family: var(--jb-serif); font-size: clamp(24px, 2.8vw, 34px); font-weight: 600; line-height: 1.18; color: var(--jb-ink); }
    .pas-rule { display: block; width: 64px; height: 3px; margin: 18px 0 18px; border-radius: 2px; background: var(--jb-gold-band); }
    .pas-text { margin: 0; max-width: 480px; color: var(--jb-text); font-size: 16px; line-height: 1.75; }
    .pas-cta { margin-top: 24px; }
    .pas-cta i { font-size: .85em; transition: transform .15s; }
    .pas-cta:hover i { transform: translateX(3px); }

    @media (max-width: 991px) {
        .pas-num { font-size: 52px; margin-bottom: 4px; }
        .pas-text { max-width: none; }
    }
    @media (max-width: 575px) {
        .pas-figure, .pas-row--flip .pas-figure { padding: 0 12px 12px 0; }
        .pas-figure::before, .pas-row--flip .pas-figure::before { inset: 12px 0 0 12px; border-radius: 20px; }
        .pas-media { border-radius: 18px; border-width: 3px; }
        .pas-media__icon { width: 88px; height: 88px; font-size: 2rem; }
        .pas-num { font-size: 44px; }
        .pas-rule { margin: 14px 0; }
        .pas-text { font-size: 15px; }
    }

    .pas-empty {
        padding: 56px 24px;
        border: 1px dashed var(--jb-line-strong); border-radius: var(--jb-r-card); background: #fff; box-shadow: var(--jb-shadow);
    }
</style>
@endpush
