@extends('layouts.lawfirm')

@section('title', 'Letter to PM Modi | Bengaluru Law House')

@section('content')
<div class="jb-doc">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Letter',
        'title'   => 'Letter to PM Modi',
        'crumbs'  => [['Notices & letters'], ['Letter to PM Modi']],
    ])

    <section class="water-project-shell">
        <a href="{{ asset('letter-to-pm-modi.html') }}" class="floating-contact-btn water-download-btn" target="_blank" rel="noopener" aria-label="Download document" title="Download document">
            <i class="fas fa-download"></i>
        </a>

        <div class="jb-wrap">
            <iframe
                src="{{ asset('letter-to-pm-modi.html') }}"
                title="Letter to PM Modi"
                class="water-project-frame jb-paper"
                loading="lazy"></iframe>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Document viewer: the letter sits on the dark page as a white sheet (.jb-paper) */
    .jb-doc .jb-wrap { max-width: 1048px; }
    .jb-doc .jb-pagehead { padding: 26px 0 22px; }
    .jb-doc .jb-pagehead__crumbs { margin-bottom: 12px; }
    .jb-doc .jb-pagehead__title { font-size: clamp(24px, 3.2vw, 34px); }

    .water-project-shell { padding: 24px 0 40px; }

    /* Once scrolled to, the sheet fills the screen below the sticky header */
    .water-project-frame {
        display: block;
        width: 100%;
        height: calc(100vh - var(--jb-header-h) - 32px);
        height: calc(100dvh - var(--jb-header-h) - 32px);
        min-height: 520px;
        border: 0;
    }

    /* Champagne round button, stacked above the WhatsApp button (54px at right 18px, bottom 18px) */
    .jb-inner .water-download-btn {
        right: 18px;
        bottom: 84px;
        border: 1px solid rgba(255, 255, 255, .55);
        background: var(--jb-champagne);
        color: #111;
        font-size: 1.15rem;
    }

    .jb-inner .water-download-btn:hover,
    .jb-inner .water-download-btn:focus-visible {
        background: #fff8ea;
        color: #111;
    }

    @media (min-width: 768px) {
        .jb-doc .jb-pagehead { padding: 34px 0 28px; }
        .water-project-shell { padding: 32px 0 56px; }
    }
</style>
@endpush
