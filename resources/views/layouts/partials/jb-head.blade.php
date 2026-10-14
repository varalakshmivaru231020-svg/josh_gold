{{-- Icons, fonts, the theme, the motion script and social-sharing tags,
     shared by layouts/bullion and layouts/lawfirm. Fonts are self-hosted from public/fonts
     (see the @font-face rules at the top of bullion-theme.css).
     Pages may set @section('og_image', url) for a sharing image; the description comes from
     the layout's meta description. --}}
<script>document.documentElement.classList.add('jb-js');document.documentElement.setAttribute('data-spot-url', @json(route('live-rates.spot')));document.documentElement.setAttribute('data-candles-url', @json(route('live-rates.candles')));</script>
<link rel="icon" type="image/png" sizes="32x32" href="{{ versioned_asset('images/brand/favicon-32.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ versioned_asset('images/brand/favicon-192.png') }}">
<link rel="apple-touch-icon" href="{{ versioned_asset('images/brand/apple-touch-icon.png') }}">
<link rel="preload" href="{{ asset('fonts/inter-latin-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('fonts/cormorant-garamond-latin-500-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset('css/bullion-theme.css') }}?v={{ filemtime(public_path('css/bullion-theme.css')) }}">
<script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
<script src="{{ asset('js/live-spot.js') }}?v={{ filemtime(public_path('js/live-spot.js')) }}" defer></script>
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:site_name" content="{{ config('company.name') }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_IN">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', config('company.name'))">
<meta property="og:image" content="@yield('og_image', versioned_asset('images/site/hero-bullion-1200.webp'))">
<meta name="twitter:card" content="summary_large_image">
