@extends('layouts.lawfirm')

@section('title', 'Central Anti-Conversion Law | Bengaluru Law House')

@section('content')
<section class="water-project-shell">
    <a href="{{ asset('letter-to-pm-modi.html#anti-conversion-law') }}" class="floating-contact-btn water-download-btn" target="_blank" rel="noopener" aria-label="Open anti-conversion law section" title="Open anti-conversion law section">
        <i class="fas fa-download"></i>
    </a>

    <iframe
        src="{{ asset('letter-to-pm-modi.html#anti-conversion-law') }}"
        title="Central Anti-Conversion Law"
        class="water-project-frame"
        loading="lazy"></iframe>
</section>
@endsection

@push('styles')
<style>
    .water-project-shell {
        position: relative;
        background: #081324;
        padding: calc(var(--nav-height) + 8px) 0 36px;
        min-height: calc(100vh - var(--announcement-offset));
    }

    .water-project-frame {
        display: block;
        width: 100%;
        height: calc(100vh - var(--nav-height) - var(--announcement-offset) - 44px);
        min-height: 880px;
        border: 0;
        background: #ffffff;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
    }

    .water-download-btn {
        position: fixed;
        right: 25px;
        bottom: 175px;
        z-index: 981;
        background: linear-gradient(135deg, #c9a84c, #4fc3f7);
        color: #081324;
    }

    .water-download-btn:hover {
        background: linear-gradient(135deg, #4fc3f7, #c9a84c);
        color: #081324;
    }

    .water-download-btn .contact-btn-label {
        background: rgba(12, 20, 40, 0.88);
    }

    @media (max-width: 991px) {
        .water-project-frame {
            height: calc(100vh - var(--nav-height) - var(--announcement-offset) - 36px);
            min-height: 760px;
        }
    }

    @media (max-width: 768px) {
        .water-project-shell {
            padding-top: calc(var(--nav-height) + 4px);
            padding-bottom: 82px;
        }

        .water-project-frame {
            height: calc(100vh - var(--nav-height) - var(--announcement-offset) - 86px);
            min-height: 680px;
        }

        .water-download-btn {
            right: 20px;
            bottom: 160px;
        }
    }
</style>
@endpush
