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
    .cta-wrap { background: #fff; padding: 4.5rem 0 4rem; font-family: "Outfit", Arial, sans-serif; }
    .cta-card {
        position: relative; overflow: hidden; isolation: isolate;
        min-height: 420px; padding: 5rem 2rem; border-radius: 56px;
        display: flex; align-items: center; justify-content: center; text-align: center;
        background: #fff center / cover no-repeat;
        box-shadow: 0 40px 80px -32px rgba(139, 105, 20, .45); border: 1px solid rgba(201, 168, 76, .45);
    }
    /* Without a photo: a soft white + gold pattern instead */
    .cta-card:not(.has-photo) {
        background-image:
            radial-gradient(circle at 85% 20%, rgba(201, 168, 76, .22), transparent 45%),
            linear-gradient(rgba(201, 168, 76, .10) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, .10) 1px, transparent 1px);
        background-size: auto, 56px 56px, 56px 56px;
    }
    /* White-gold wash over the photo so the dark text stays readable on any image */
    .cta-card::before {
        content: ''; position: absolute; inset: 0; z-index: -2;
        background: linear-gradient(100deg, rgba(255, 255, 255, .92) 0%, rgba(255, 250, 236, .86) 50%, rgba(246, 228, 170, .74) 100%);
    }
    /* Decorative circle, bottom-left */
    .cta-card::after {
        content: ''; position: absolute; z-index: -1; left: -70px; bottom: -100px;
        width: 300px; height: 300px; border-radius: 50%; background: rgba(201, 168, 76, .55);
    }
    .cta-content { max-width: 920px; }
    .cta-heading { color: #2b2417; font-weight: 700; font-size: clamp(2.2rem, 5.2vw, 4rem); line-height: 1.1; letter-spacing: -.02em; margin: 0; }
    .cta-text { color: #4a412c; font-size: clamp(1rem, 1.6vw, 1.25rem); line-height: 1.6; max-width: 760px; margin: 1.25rem auto 0; }
    .cta-actions { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; margin-top: 2.25rem; }
    .cta-btn {
        display: inline-flex; align-items: center; gap: .65rem; padding: .95rem 1.9rem; border-radius: 999px;
        font-size: 1.05rem; font-weight: 600; text-decoration: none; border: 2px solid transparent;
        transition: transform .2s, background .2s, border-color .2s, color .2s;
    }
    .cta-btn:hover { transform: translateY(-2px); }
    .cta-btn--ghost { color: #8b6914; border-color: #8b6914; background: rgba(255, 255, 255, .7); }
    .cta-btn--ghost:hover { color: #2b2417; border-color: var(--gold, #c9a84c); background: var(--gold-light, #e8c878); }
    .cta-btn--solid { color: #2b2417; background: linear-gradient(135deg, #d8b95a, #c9a84c); border-color: #c9a84c; box-shadow: 0 10px 24px rgba(139, 105, 20, .25); }
    .cta-btn--solid:hover { color: #2b2417; background: linear-gradient(135deg, #e8c878, #d8b95a); border-color: #e8c878; }
    .cta-btn--solid i { transition: transform .2s; }
    .cta-btn--solid:hover i { transform: translateX(4px); }
    @media (max-width: 767px) {
        .cta-wrap { padding: 3rem 0 2.5rem; }
        .cta-card { border-radius: 32px; min-height: 0; padding: 3.5rem 1.25rem; }
        .cta-card::after { width: 200px; height: 200px; left: -60px; bottom: -80px; }
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
