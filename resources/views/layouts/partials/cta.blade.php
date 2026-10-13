{{--
    Call-to-action banner shown above the footer on public pages.
    Editable in Admin → Call To Action (photo, text, buttons). Skipped on pages where it makes no
    sense: Contact Us (the enquiry form is right there), login / account pages and checkout.
--}}
@php
    $skipCta = request()->routeIs(
        'contact', 'contact.submit', 'login', 'admin.*', 'agent.*',
        'checkout', 'orders.*', 'purchase', 'track.order'
    );
    $cta      = \App\Support\CtaContent::get();
    $heading  = trim((string) $cta['cta_heading']);
    $subtext  = trim((string) $cta['cta_subtext']);
    $buttons  = \App\Support\CtaContent::buttons($cta);
    $show     = ! $skipCta && $cta['cta_enabled'] !== '0' && ($heading !== '' || $subtext !== '' || count($buttons) > 0);
@endphp

@if($show)
<style>
    /* Dark bullion style (matches the home page contact band) */
    .cta-wrap { padding: 3.5rem 0; border-top: 1px solid var(--jb-line-soft); }
    .cta-card {
        position: relative; overflow: hidden; isolation: isolate;
        padding: 3.25rem 1.5rem; border-radius: 22px; border: 1px solid var(--jb-line);
        display: flex; align-items: center; justify-content: center; text-align: center;
        background: radial-gradient(600px 260px at 100% 0%, rgba(201, 163, 92, .16), transparent 65%), linear-gradient(180deg, #111114, #0a0a0b);
        background-size: cover; background-position: center;
    }
    /* With a photo: darken it so the text stays readable */
    .cta-card.has-photo::before {
        content: ''; position: absolute; inset: 0; z-index: -1;
        background: linear-gradient(100deg, rgba(0, 0, 0, .88) 0%, rgba(0, 0, 0, .72) 55%, rgba(0, 0, 0, .55) 100%);
    }
    .cta-content { max-width: 820px; }
    .cta-heading { color: #fff; font-family: var(--jb-serif); font-weight: 600; font-size: clamp(1.6rem, 3.6vw, 2.6rem); line-height: 1.15; margin: 0; }
    .cta-text { color: var(--jb-muted); font-size: 15px; line-height: 1.65; max-width: 640px; margin: 1rem auto 0; }
    .cta-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 1.75rem; }
    .cta-btn {
        display: inline-flex; align-items: center; gap: 8px; min-height: 48px; padding: 0 22px; border-radius: var(--jb-r-btn);
        font-size: 14.5px; font-weight: 700; text-decoration: none; border: 1px solid transparent;
        transition: background-color .15s, border-color .15s, color .15s;
    }
    .cta-btn--ghost { color: #fff; border-color: var(--jb-line); background: transparent; }
    .cta-btn--ghost:hover { color: #fff; border-color: rgba(255, 255, 255, .7); background: rgba(255, 255, 255, .08); }
    .cta-btn--solid { color: #111; background: var(--jb-champagne); box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); }
    .cta-btn--solid:hover { color: #111; background: #fff8ea; }
    @media (max-width: 767px) {
        .cta-wrap { padding: 2.5rem 0; }
        .cta-card { padding: 2.5rem 1.1rem; }
        .cta-actions { flex-direction: column; align-items: stretch; }
        .cta-btn { justify-content: center; }
    }
</style>

<section class="cta-wrap" aria-label="Get in touch">
    <div class="container">
        <div class="cta-card {{ $cta['cta_image'] ? 'has-photo' : '' }}"
             @if($cta['cta_image']) style="background-image: url('{{ asset('storage/' . $cta['cta_image']) }}');" @endif>
            <div class="cta-content">
                @if($heading !== '')<h2 class="cta-heading">{{ $heading }}</h2>@endif
                @if($subtext !== '')<p class="cta-text">{{ $subtext }}</p>@endif

                @if(count($buttons) > 0)
                <div class="cta-actions">
                    @foreach($buttons as $btn)
                        <a href="{{ $btn['url'] }}" class="cta-btn {{ $btn['n'] === 1 ? 'cta-btn--ghost' : 'cta-btn--solid' }}"
                           @if($btn['external']) target="_blank" rel="noopener" @endif>
                            @if($btn['n'] === 1 && $btn['icon'] !== '')<i class="{{ $btn['icon'] }}"></i>@endif
                            {{ $btn['label'] }}
                            @if($btn['n'] === 2)<i class="fas fa-arrow-right"></i>@endif
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
