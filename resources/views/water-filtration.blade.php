@extends('layouts.lawfirm')

@section('title', 'Water Filtration Project | Bengaluru Law House')

@section('content')
<div class="jb-doc">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Project proposal',
        'title'   => 'Water Filtration Project',
        'crumbs'  => [['Notices & letters'], ['Water Filtration Project']],
    ])

    <section class="water-project-shell">
        <a href="{{ asset('water-filtration-project.html') }}" class="floating-contact-btn water-download-btn" target="_blank" rel="noopener" aria-label="Download document" title="Download document">
            <i class="fas fa-download"></i>
        </a>

        <div class="jb-wrap">
            <iframe
                src="{{ asset('water-filtration-project.html') }}"
                title="Water Filtration Project"
                class="water-project-frame jb-paper"
                loading="lazy"></iframe>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Document viewer: the letter sits on the ivory page as a white sheet (.jb-paper) */
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
    /* Once the document is restyled (script below) its own white sheet carries the shadow */
    .water-project-frame.is-embedded { background: transparent; border: 0; box-shadow: none; border-radius: 0; }

    /* Gold round button, stacked above the WhatsApp button (54px at right 18px, bottom 18px) */
    .jb-inner .water-download-btn {
        right: 18px;
        bottom: 84px;
        border: 2px solid #fff;
        background: var(--jb-gold-band);
        color: #2a1d06;
        font-size: 1.15rem;
    }

    .jb-inner .water-download-btn:hover,
    .jb-inner .water-download-btn:focus-visible {
        filter: brightness(1.06);
        color: #2a1d06;
    }

    @media (min-width: 768px) {
        .jb-doc .jb-pagehead { padding: 34px 0 28px; }
        .water-project-shell { padding: 32px 0 56px; }
    }
</style>
@endpush

@push('scripts')
<script>
    // The document file has its own tan backdrop; inside this page it sits straight on the ivory
    // ground as a white sheet with a soft gold shadow. The file itself is unchanged (download link).
    (function () {
        var frame = document.querySelector('.water-project-frame');
        if (!frame) return;
        function restyle() {
            try {
                var doc = frame.contentDocument;
                if (!doc || !doc.head || doc.getElementById('jb-embed-style')) return;
                var style = doc.createElement('style');
                style.id = 'jb-embed-style';
                style.textContent = 'html, body { background: transparent !important; }'
                    + ' .page, .letter { background: #fff !important; box-shadow: 0 0 0 1px #ebdfc4, 0 22px 48px -22px rgba(110, 81, 18, .45) !important; }';
                doc.head.appendChild(style);
                frame.classList.add('is-embedded');
            } catch (e) { /* different origin: leave the document as it is */ }
        }
        frame.addEventListener('load', restyle);
        restyle();
    })();
</script>
@endpush
