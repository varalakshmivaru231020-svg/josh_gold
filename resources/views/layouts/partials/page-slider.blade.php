{{--
    Slider for a public page, managed in Admin → Sliders (each slider is assigned to a page).
    Expects: $slides (collection of Slider), $variant ('banner' below the menu on inner pages, 'hero' on the home page)
--}}
@php
    $variant = $variant ?? 'banner';
    $uid     = 'pageSlider' . ucfirst($variant);
    $many    = $slides->count() > 1;
@endphp

@if($slides->isNotEmpty())
<style>
    /* Dark bullion style */
    .ps { position: relative; font-family: var(--jb-sans); background: var(--jb-black, #000); border-bottom: 1px solid var(--jb-line-soft, #1d1a15); }
    .ps-slide {
        position: relative; display: flex; align-items: center; overflow: hidden;
        background: radial-gradient(800px 340px at 88% 0%, rgba(201, 163, 92, .16), transparent 60%), #000 center / cover no-repeat;
    }
    .ps--banner .ps-slide { min-height: clamp(280px, 34vw, 440px); }
    .ps--hero .ps-slide { min-height: max(520px, 72vh); }

    /* Dark wash on the left so the text stays readable on any photo */
    .ps-slide::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(0, 0, 0, .9) 0%, rgba(0, 0, 0, .7) 45%, rgba(0, 0, 0, .15) 100%);
    }
    .ps-slide--plain::before { display: none; }     /* image-only slide: show the photo as it is */
    .ps-inner { position: relative; z-index: 2; padding-top: 2.5rem; padding-bottom: 3.5rem; }
    .ps-copy { max-width: 640px; }
    .ps-eyebrow { display: inline-block; font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--jb-gold, #c9a35c); margin-bottom: .8rem; }
    .ps-title { font-family: var(--jb-serif); font-weight: 600; color: #fff; line-height: 1.12; margin: 0 0 1rem; font-size: clamp(1.9rem, 4.4vw, 3.2rem); }
    .ps--hero .ps-title { font-size: clamp(2.2rem, 5vw, 3.6rem); }
    .ps-text { color: var(--jb-text, #d9d9d9); font-size: clamp(.98rem, 1.4vw, 1.1rem); line-height: 1.65; margin: 0 0 1.5rem; }
    .ps-btn {
        display: inline-flex; align-items: center; gap: .6rem; min-height: 48px; padding: 0 22px; border-radius: 14px; text-decoration: none;
        background: var(--jb-champagne, #f3ead9); color: #111; font-weight: 700; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45);
        transition: background-color .15s;
    }
    .ps-btn:hover { background: #fff8ea; color: #111; }

    /* Controls */
    .ps .carousel-indicators { margin-bottom: 1rem; z-index: 3; }
    .ps .carousel-indicators [data-bs-target] { width: 34px; height: 3px; border: 0; border-radius: 2px; background: #ead7a6; opacity: .3; }
    .ps .carousel-indicators .active { opacity: 1; }
    .ps .carousel-control-prev, .ps .carousel-control-next { z-index: 3; width: 56px; opacity: 1; }
    .ps .ps-arrow {
        width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center;
        background: rgba(0, 0, 0, .6); color: #fff; border: 1px solid var(--jb-line, #2d2820);
    }
    .ps .carousel-control-prev:hover .ps-arrow, .ps .carousel-control-next:hover .ps-arrow { border-color: rgba(255, 255, 255, .7); }
    @media (max-width: 767px) {
        .ps--banner .ps-slide { min-height: 320px; }
        .ps--hero .ps-slide { min-height: 520px; }
        .ps-slide::before { background: linear-gradient(180deg, rgba(0, 0, 0, .55) 0%, rgba(0, 0, 0, .88) 100%); }
        .ps-inner { padding-bottom: 4rem; }
        .ps .carousel-control-prev, .ps .carousel-control-next { display: none; }
    }
    @media (prefers-reduced-motion: reduce) { .ps .carousel-item { transition: none !important; } }
</style>

<section class="ps ps--{{ $variant }}" aria-roledescription="carousel" aria-label="Featured">
    <div id="{{ $uid }}" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover">
        @if($many)
        <div class="carousel-indicators">
            @foreach($slides as $slide)
            <button type="button" data-bs-target="#{{ $uid }}" data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : '' }}" @if($loop->first) aria-current="true" @endif
                    aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
        @endif

        <div class="carousel-inner">
            @foreach($slides as $slide)
            @php
                $hasCopy = filled($slide->title) || filled($slide->subtitle) || filled($slide->description)
                    || (filled($slide->button_text) && filled($slide->button_link));
            @endphp
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <div class="ps-slide {{ $hasCopy ? '' : 'ps-slide--plain' }}"
                     @if($slide->image) style="background-image: url('{{ asset('storage/' . $slide->image) }}');" @endif>
                    @if($hasCopy)
                    <div class="container ps-inner">
                        <div class="ps-copy">
                            @if(filled($slide->subtitle))<span class="ps-eyebrow">{{ $slide->subtitle }}</span>@endif
                            @if(filled($slide->title))
                                <{{ $variant === 'hero' && $loop->first ? 'h1' : 'h2' }} class="ps-title">{{ $slide->title }}</{{ $variant === 'hero' && $loop->first ? 'h1' : 'h2' }}>
                            @endif
                            @if(filled($slide->description))<p class="ps-text">{{ $slide->description }}</p>@endif
                            @if(filled($slide->button_text) && filled($slide->button_link))
                                <a href="{{ $slide->button_link }}" class="ps-btn">{{ $slide->button_text }} <i class="fas fa-arrow-right"></i></a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($many)
        <button class="carousel-control-prev" type="button" data-bs-target="#{{ $uid }}" data-bs-slide="prev">
            <span class="ps-arrow"><i class="fas fa-chevron-left"></i></span><span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#{{ $uid }}" data-bs-slide="next">
            <span class="ps-arrow"><i class="fas fa-chevron-right"></i></span><span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</section>
@endif
