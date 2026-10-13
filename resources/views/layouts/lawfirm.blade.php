{{--
    Layout for the inner pages. Same dark header, footer and theme as the home page
    (layouts/bullion), plus Bootstrap, Font Awesome and AOS for the page content.
    Dark overrides for Bootstrap and shared page styles: public/css/bullion-pages.css
--}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#000000">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @hasSection('meta_description')
    <meta name="description" content="@yield('meta_description')">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    @include('layouts.partials.jb-head')
    <link rel="stylesheet" href="{{ asset('css/bullion-pages.css') }}?v={{ filemtime(public_path('css/bullion-pages.css')) }}">
    @stack('styles')
</head>
<body class="jb-body jb-inner">
    @include('layouts.partials.jb-header')

    <main id="main-content" class="main-content{{ $pageSlides->isNotEmpty() ? ' has-page-slider' : '' }}">
        {{-- Sliders chosen for this page in Admin → Sliders --}}
        @if($pageSlides->isNotEmpty())
            @include('layouts.partials.page-slider', ['slides' => $pageSlides, 'variant' => 'banner'])
        @endif
        @yield('content')
    </main>

    {{-- Call to action (admin-managed) --}}
    @include('layouts.partials.cta')

    @include('layouts.partials.jb-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                if (typeof AOS !== 'undefined') {
                    AOS.init({ duration: 700, easing: 'ease-out', once: true, offset: 80 });
                }
            } catch (e) {
                console.warn('AOS failed to initialize:', e);
            }

            // Count-up numbers (.stat-number[data-count])
            var counters = document.querySelectorAll('.stat-number[data-count]');
            if (counters.length) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        var el = entry.target;
                        var target = parseInt(el.getAttribute('data-count'), 10);
                        var suffix = el.getAttribute('data-suffix') || '';
                        var current = 0, step = target / (2000 / 16);
                        (function tick() {
                            current += step;
                            if (current < target) { el.textContent = Math.floor(current) + suffix; requestAnimationFrame(tick); }
                            else { el.textContent = target + suffix; }
                        })();
                        observer.unobserve(el);
                    });
                }, { threshold: 0.5 });
                counters.forEach(function (c) { observer.observe(c); });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
