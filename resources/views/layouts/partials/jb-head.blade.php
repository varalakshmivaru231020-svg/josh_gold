{{-- Icons, the bullion theme and cookie-consent styles, shared by layouts/bullion and layouts/lawfirm.
     Fonts are self-hosted from public/fonts (see the @font-face rules at the top of bullion-theme.css). --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/brand/favicon-32.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/brand/favicon-192.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
<link rel="preload" href="{{ asset('fonts/inclusive-sans-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('fonts/noto-serif-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset('css/bullion-theme.css') }}?v={{ filemtime(public_path('css/bullion-theme.css')) }}">
<link rel="stylesheet" href="{{ asset('css/consent.css') }}?v={{ filemtime(public_path('css/consent.css')) }}">
