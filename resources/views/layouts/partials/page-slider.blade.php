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
    .ps { position: relative; font-family: "Outfit", Arial, sans-serif; background: #fff; }
    .ps--banner { margin-top: var(--nav-height, 85px); }
    .ps--hero { margin-bottom: 3.5rem; } /* room for the news strip that overlaps the hero */
    .ps-slide {
        position: relative; display: flex; align-items: center; overflow: hidden;
        background: linear-gradient(135deg, #f6ecd0, #c9a84c) center / cover no-repeat;
    }
    .ps--banner .ps-slide { min-height: clamp(300px, 38vw, 480px); }
    .ps--hero .ps-slide { min-height: max(600px, 85vh); padding-top: var(--nav-height, 85px); }

    /* Light wash on the left so the dark text stays readable on any photo */
    .ps-slide::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, .94) 0%, rgba(255, 255, 255, .78) 42%, rgba(255, 250, 235, .08) 100%);
    }
    .ps-slide--plain::before { display: none; }     /* image-only slide: show the photo as it is */
    .ps-inner { position: relative; z-index: 2; padding-top: 2.5rem; padding-bottom: 3.5rem; }
    .ps-copy { max-width: 640px; }
    .ps-eyebrow { display: inline-block; font-size: .78rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #8b6914; margin-bottom: .9rem; }
    .ps-title { font-family: "Cormorant Garamond", Georgia, serif; font-weight: 700; color: #2b2417; line-height: 1.12; margin: 0 0 1rem; font-size: clamp(2rem, 4.6vw, 3.6rem); }
    .ps--hero .ps-title { font-size: clamp(2.4rem, 5.6vw, 4.4rem); }
    .ps-text { color: #4a412c; font-size: clamp(1rem, 1.5vw, 1.2rem); line-height: 1.7; margin: 0 0 1.6rem; }
    .ps-btn {
        display: inline-flex; align-items: center; gap: .6rem; padding: .85rem 1.8rem; border-radius: 999px; text-decoration: none;
        background: linear-gradient(135deg, #d8b95a, #c9a84c); color: #2b2417; font-weight: 700; box-shadow: 0 10px 24px rgba(139, 105, 20, .25);
        transition: transform .2s, filter .2s;
    }
    .ps-btn:hover { transform: translateY(-2px); filter: brightness(1.06); color: #2b2417; }

    /* Controls */
    .ps .carousel-indicators { margin-bottom: 1rem; z-index: 3; }
    .ps .carousel-indicators [data-bs-target] { width: 34px; height: 4px; border: 0; border-radius: 2px; background: #8b6914; opacity: .35; }
    .ps .carousel-indicators .active { opacity: 1; }
    .ps .carousel-control-prev, .ps .carousel-control-next { z-index: 3; width: 56px; opacity: 1; }
    .ps .ps-arrow {
        width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center;
        background: rgba(255, 255, 255, .9); color: #8b6914; border: 1px solid rgba(201, 168, 76, .6); box-shadow: 0 6px 16px rgba(139, 105, 20, .2);
    }
    .ps .carousel-control-prev:hover .ps-arrow, .ps .carousel-control-next:hover .ps-arrow { background: #c9a84c; color: #2b2417; }
    @media (max-width: 767px) {
        .ps--banner .ps-slide { min-height: 340px; }
        .ps--hero .ps-slide { min-height: 560px; }
        .ps-slide::before { background: linear-gradient(180deg, rgba(255, 255, 255, .92) 0%, rgba(255, 255, 255, .82) 100%); }
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
