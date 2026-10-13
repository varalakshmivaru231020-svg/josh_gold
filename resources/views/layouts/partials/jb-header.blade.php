{{--
    Site header (logo, main menu) and the mobile drawer, used by layouts/bullion and layouts/lawfirm.
    Styles: public/css/bullion-theme.css
--}}
@php
    use App\Support\RateBook;

    $jbLogo = \App\Models\SiteSetting::get('site_logo');
    $jbLogo = $jbLogo ? asset('storage/' . $jbLogo) : asset('images/brand/josha-logo-header.webp');

    $jbCityUrl = function (string $key) {
        [$state, $city] = explode('/', $key);
        return route('rates.city', ['state' => $state, 'city' => $city]);
    };
    $jbOnCity = fn (string $key) => request()->routeIs('rates.city')
        && request()->route('state') . '/' . request()->route('city') === $key;

    // [label, url, is current page]
    $jbNav = [
        'main' => [
            ['Shop',        route('home'),         request()->routeIs('home')],
            ['Gold rate',   route('rates.gold'),   request()->routeIs('rates.gold')],
            ['Silver rate', route('rates.silver'), request()->routeIs('rates.silver')],
            ['Charts',      route('rates.charts'), request()->routeIs('rates.charts')],
            ['Gold Pulse',  route('rates.pulse'),  request()->routeIs('rates.pulse')],
        ],
        'learn' => [
            ['Knowledge', route('knowledge'), request()->routeIs('knowledge')],
            ['News',      route('news'),      request()->routeIs('news', 'blog.*')],
            ['Videos',    route('videos'),    request()->routeIs('videos', 'testimonials')],
        ],
        'cities' => [
            ['Mumbai',  $jbCityUrl('maharashtra/mumbai'), $jbOnCity('maharashtra/mumbai')],
            ['Delhi',   $jbCityUrl('delhi/delhi'),        $jbOnCity('delhi/delhi')],
            ['Chennai', $jbCityUrl('tamil-nadu/chennai'), $jbOnCity('tamil-nadu/chennai')],
        ],
    ];
    $jbCompany = [
        ['About us',     route('about-us'),       request()->routeIs('about-us')],
        ['Services',     route('services.index'), request()->routeIs('services.index')],
        ['Our offices',  route('offices.index'),  request()->routeIs('offices.index')],
        ['Gallery',      route('gallery'),        request()->routeIs('gallery')],
        ['Bank details', route('bank-details'),   request()->routeIs('bank-details')],
        ['Contact',      route('contact'),        request()->routeIs('contact')],
    ];
    $jbNotices = [
        ['Central Anti-Conversion Law',               route('anti-conversion-law')],
        ['SC/ST (Prevention of Atrocities) Act, 1989', route('sc-st-act-1989')],
        ['Letter to PM Modi',                         route('letter-to-pm-modi')],
        ['Letter to CM Karnataka',                    route('letter-to-cm-karnataka')],
        ['Water Filtration Project',                  route('water-filtration')],
        ['Water Filtration (Kannada)',                route('water-filtration-kannada')],
    ];
@endphp

<a class="jb-skip" href="#main-content">Skip to content</a>

<header class="jb-header">
    <div class="jb-header__inner">
        <a class="jb-brand" href="{{ route('home') }}">
            <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="82" height="68">
        </a>

        <nav class="jb-nav" aria-label="Main">
            @foreach($jbNav['main'] as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-nav__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            @foreach(['learn', 'cities'] as $group)
                <div class="jb-nav__group">
                    @foreach($jbNav[$group] as [$label, $url, $active])
                        <a href="{{ $url }}" class="jb-nav__link jb-nav__link--sm" @if($active) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <button type="button" class="jb-menu-btn jb-menu-btn--open" id="jbMenuOpen" aria-controls="jbDrawer" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>

<div class="jb-drawer" id="jbDrawer" aria-hidden="true">
    <div class="jb-drawer__backdrop" data-drawer-close></div>
    <aside class="jb-drawer__panel" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="jb-drawer__head">
            <a class="jb-brand" href="{{ route('home') }}">
                <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}" width="82" height="68">
            </a>
            <button type="button" class="jb-menu-btn" data-drawer-close aria-label="Close menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <nav class="jb-drawer__nav" aria-label="Mobile">
            <a href="{{ route('home') }}" class="jb-drawer__link jb-drawer__link--primary">Shop gold &amp; silver</a>
            <a href="{{ route('rates.gold') }}" class="jb-drawer__link" @if(request()->routeIs('rates.gold')) aria-current="page" @endif>Gold rate today</a>
            <a href="{{ route('rates.silver') }}" class="jb-drawer__link" @if(request()->routeIs('rates.silver')) aria-current="page" @endif>Silver rate today</a>
            <a href="{{ route('rates.cities') }}" class="jb-drawer__link" @if(request()->routeIs('rates.cities')) aria-current="page" @endif>Rates by state &amp; city</a>
            <a href="{{ route('live-rates') }}" class="jb-drawer__link" @if(request()->routeIs('live-rates')) aria-current="page" @endif>Full rate table</a>
            <a href="{{ route('rates.pulse') }}" class="jb-drawer__link" @if(request()->routeIs('rates.pulse')) aria-current="page" @endif>Gold Pulse</a>
            <a href="{{ route('rates.charts') }}" class="jb-drawer__link" @if(request()->routeIs('rates.charts')) aria-current="page" @endif>Charts</a>

            <p class="jb-drawer__label">Learn</p>
            @foreach($jbNav['learn'] as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach

            <p class="jb-drawer__label">Cities</p>
            @foreach(RateBook::CITIES as $key => [$name])
                <a href="{{ $jbCityUrl($key) }}" class="jb-drawer__link" @if($jbOnCity($key)) aria-current="page" @endif>{{ $name }}</a>
            @endforeach

            <p class="jb-drawer__label">Company</p>
            @foreach($jbCompany as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach

            <p class="jb-drawer__label">Notices &amp; letters</p>
            @foreach($jbNotices as [$label, $url])
                <a href="{{ $url }}" class="jb-drawer__link">{{ $label }}</a>
            @endforeach
        </nav>
    </aside>
</div>
