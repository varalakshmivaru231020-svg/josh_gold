{{-- Fonts, icons and the bullion theme, shared by layouts/bullion and layouts/lawfirm --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/brand/favicon-32.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/brand/favicon-192.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inclusive+Sans:ital@0;1&family=Noto+Serif:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bullion-theme.css') }}?v={{ filemtime(public_path('css/bullion-theme.css')) }}">
