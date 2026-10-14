{{--
    Layout for the home page (cinematic hero under a transparent header). Inner pages use layouts/lawfirm, which shares
    the same header and footer partials and adds Bootstrap for their content.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#171512">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @hasSection('description')
    <meta name="description" content="@yield('description')">
    <meta property="og:description" content="@yield('description')">
    @endif
    @include('layouts.partials.jb-head')
    @stack('styles')
    @stack('head')
</head>
<body class="jb-body{{ request()->routeIs('home') ? ' jb-home' : '' }}">
    @include('layouts.partials.jb-header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.partials.jb-footer')
    @stack('scripts')
</body>
</html>
