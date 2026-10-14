{{--
    Call-to-action banner shown above the footer on public pages.
    Editable in Admin → Call To Action (photo, text, buttons). Skipped on pages where it makes no
    sense: Contact Us (the enquiry form is right there) and the login / admin pages.
--}}
@php
    $skipCta = request()->routeIs('contact', 'contact.submit', 'login', 'admin.*');
    $cta      = \App\Support\CtaContent::get();
    $heading  = trim((string) $cta['cta_heading']);
    $subtext  = trim((string) $cta['cta_subtext']);
    $buttons  = \App\Support\CtaContent::buttons($cta);
    $show     = ! $skipCta && $cta['cta_enabled'] !== '0' && ($heading !== '' || $subtext !== '' || count($buttons) > 0);
@endphp

@if($show)
<style>
    /* Ivory band with a gold hairline (matches the home page quotation band). With a photo: ivory text on a dark wash. */
    .cta-wrap { padding: 3.5rem 0 3rem; }
    .cta-card {
        position: relative; overflow: hidden; isolation: isolate;
        padding: 3.75rem 1.5rem; border-radius: 32px; border: 1px solid var(--jb-line-strong);
        display: flex; align-items: center; justify-content: center; text-align: center;
        background: radial-gradient(600px 260px at 100% 0%, rgba(184, 138, 59, .12), transparent 65%), var(--jb-ivory);
        background-size: cover; background-position: center;
    }
    /* Gold hairline along the top edge */
    .cta-card::after {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: var(--jb-hairline);
    }
    /* With a photo: darken it so the text stays readable */
    .cta-card.has-photo::before {
        content: ''; position: absolute; inset: 0; z-index: -1;
        background: linear-gradient(100deg, rgba(20, 14, 4, .86) 0%, rgba(20, 14, 4, .7) 55%, rgba(20, 14, 4, .5) 100%);
    }
    .cta-content { max-width: 820px; }
    .cta-heading { color: var(--jb-ink); font-family: var(--jb-serif); font-weight: 500; font-size: clamp(2rem, 4vw, 3.1rem); line-height: 1.08; margin: 0; text-wrap: balance; }
    .cta-text { color: var(--jb-text); font-size: 15.5px; line-height: 1.7; max-width: 640px; margin: 1rem auto 0; }
    .cta-card.has-photo .cta-heading { color: var(--jb-ivory); }
    .cta-card.has-photo .cta-text { color: rgba(248, 243, 233, .88); }
    .cta-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 2rem; }
    .cta-btn {
        display: inline-flex; align-items: center; gap: 10px; min-height: 52px; padding: 0 28px; border-radius: 999px;
        font-size: 12.5px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; border: 1px solid transparent;
        transition: background-color .25s, border-color .25s, color .25s, transform .25s;
    }
    .cta-btn--ghost { color: var(--jb-ink); border-color: var(--jb-ink); background: transparent; }
    .cta-btn--ghost:hover { color: var(--jb-ivory); border-color: var(--jb-ink); background: var(--jb-ink); }
    .cta-card.has-photo .cta-btn--ghost { color: var(--jb-ivory); border-color: rgba(231, 214, 178, .6); }
    .cta-btn--solid { color: var(--jb-obsidian); background: var(--jb-gold); border-color: var(--jb-gold); }
    .cta-btn--solid:hover { color: var(--jb-obsidian); background: #c99c4c; border-color: #c99c4c; transform: translateY(-1px); }
    @media (max-width: 767px) {
        .cta-wrap { padding: 2.5rem 0; }
        .cta-card { padding: 2.5rem 1.1rem; border-radius: 24px; }
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
