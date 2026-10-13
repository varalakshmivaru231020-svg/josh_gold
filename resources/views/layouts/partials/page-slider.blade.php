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
    /* White-and-gold theme. Photo slides keep white text on a dark wash; slides without a photo are ivory with dark text. */
    .ps { position: relative; font-family: var(--jb-sans); background: var(--jb-surface, #fff); border-bottom: 1px solid var(--jb-line, #ebdfc4); }
    .ps-slide {
        position: relative; display: flex; align-items: center; overflow: hidden;
        background: radial-gradient(800px 340px at 88% 0%, rgba(214, 178, 95, .2), transparent 60%), #2a2014 center / cover no-repeat;
        /* the photo is set inline as the only background image, so it needs these as single values */
        background-size: cover; background-position: center; background-repeat: no-repeat;
    }
    .ps--banner .ps-slide { min-height: clamp(280px, 34vw, 440px); }
    .ps--hero .ps-slide { min-height: max(520px, 72vh); }

    /* Dark wash on the left so the text stays readable on any photo */
    .ps-slide::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(20, 14, 4, .86) 0%, rgba(20, 14, 4, .62) 45%, rgba(20, 14, 4, .1) 100%);
    }
    .ps-slide--plain::before { display: none; }     /* image-only slide: show the photo as it is */
    .ps-inner { position: relative; z-index: 2; padding-top: 2.5rem; padding-bottom: 3.5rem; }
    .ps-copy { max-width: 640px; }
    .ps-eyebrow { display: inline-block; font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #f0d79b; margin-bottom: .8rem; }
    .ps-title { font-family: var(--jb-serif); font-weight: 600; color: #fff; line-height: 1.12; margin: 0 0 1rem; font-size: clamp(1.9rem, 4.4vw, 3.2rem); }
    .ps--hero .ps-title { font-size: clamp(2.2rem, 5vw, 3.6rem); }
    .ps-text { color: rgba(255, 255, 255, .86); font-size: clamp(.98rem, 1.4vw, 1.1rem); line-height: 1.65; margin: 0 0 1.5rem; }
    .ps-btn {
        display: inline-flex; align-items: center; gap: .6rem; min-height: 48px; padding: 0 22px; border-radius: 14px; text-decoration: none;
        background: var(--jb-gold-band); color: #2a1d06; border: 1px solid #b48b3c; font-weight: 700;
        box-shadow: 0 10px 24px -14px rgba(138, 101, 22, .8), inset 0 1px 0 rgba(255, 255, 255, .45);
        transition: filter .15s;
    }
    .ps-btn:hover { filter: brightness(1.06); color: #2a1d06; }

    /* Slide without a photo: ivory and gold, dark text */
    .ps-slide--noimg {
        background: radial-gradient(800px 340px at 88% 0%, rgba(214, 178, 95, .24), transparent 60%),
            radial-gradient(600px 300px at 0% 100%, rgba(214, 178, 95, .14), transparent 60%), linear-gradient(180deg, #fff, #fbf6ea);
    }
    .ps-slide--noimg::before { display: none; }
    .ps-slide--noimg .ps-eyebrow { color: var(--jb-gold-strong); }
    .ps-slide--noimg .ps-title { color: var(--jb-ink); }
    .ps-slide--noimg .ps-text { color: var(--jb-text); }

    /* Controls */
    .ps .carousel-indicators { margin-bottom: 1rem; z-index: 3; }
    .ps .carousel-indicators [data-bs-target] { width: 34px; height: 3px; border: 0; border-radius: 2px; background: #d6b25f; opacity: .45; }
    .ps .carousel-indicators .active { opacity: 1; }
    .ps .carousel-control-prev, .ps .carousel-control-next { z-index: 3; width: 56px; opacity: 1; }
    .ps .ps-arrow {
        width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center;
        background: rgba(255, 255, 255, .92); color: var(--jb-ink); border: 1px solid var(--jb-line-strong); box-shadow: var(--jb-shadow);
    }
    .ps .carousel-control-prev:hover .ps-arrow, .ps .carousel-control-next:hover .ps-arrow { border-color: var(--jb-gold); background: #fff; }
    @media (max-width: 767px) {
        .ps--banner .ps-slide { min-height: 320px; }
        .ps--hero .ps-slide { min-height: 520px; }
        .ps-slide::before { background: linear-gradient(180deg, rgba(20, 14, 4, .5) 0%, rgba(20, 14, 4, .86) 100%); }
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
                <div class="ps-slide {{ $hasCopy ? '' : 'ps-slide--plain' }} {{ $slide->image ? '' : 'ps-slide--noimg' }}"
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
