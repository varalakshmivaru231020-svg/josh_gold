{{--
    Site header: masthead (office details left, logo centre, contact icons right) and the sticky gold
    menu bar, plus the mobile drawer. Used by layouts/bullion and layouts/lawfirm.
    Styles: public/css/bullion-theme.css
--}}
@php
    use App\Models\FooterLink;
    use App\Models\SiteSetting;
    use App\Support\RateBook;

    $jbLogo = SiteSetting::get('site_logo');
    $jbLogo = $jbLogo ? asset('storage/' . $jbLogo) : asset('images/brand/josha-logo-header.webp');

    $jbHours    = trim((string) SiteSetting::get('contact_working_hours'));
    $jbPhone    = trim((string) SiteSetting::get('contact_phone'));
    $jbEmail    = trim((string) SiteSetting::get('contact_email'));
    $jbWhatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp')) ?: '918035565332';

    $jbCityUrl = function (string $key) {
        [$state, $city] = explode('/', $key);
        return route('rates.city', ['state' => $state, 'city' => $city]);
    };
    $jbOnCity = fn (string $key) => request()->routeIs('rates.city')
        && request()->route('state') . '/' . request()->route('city') === $key;

    // Gold menu bar: [label, url, is current page]
    $jbMain = [
        ['Shop',        route('home'),         request()->routeIs('home')],
        ['Gold rate',   route('rates.gold'),   request()->routeIs('rates.gold')],
        ['Silver rate', route('rates.silver'), request()->routeIs('rates.silver')],
        ['Charts',      route('rates.charts'), request()->routeIs('rates.charts')],
        ['Gold Pulse',  route('rates.pulse'),  request()->routeIs('rates.pulse')],
        ['Updates',     route('updates'),      request()->routeIs('updates', 'updates.*')],
    ];
    $jbCities = [];   // cities marked "Show in menu" in Admin → Cities
    foreach (RateBook::menuCities() as $key => [$name]) {
        $jbCities[] = [$name, $jbCityUrl($key), $jbOnCity($key)];
    }
    $jbContact = ['Contact us', route('contact'), request()->routeIs('contact')];

    // Drawer "Company" and "Notices & letters" follow the footer columns (Admin → Footer)
    $jbFooter = FooterLink::activeByColumnCached();
    $jbLink = fn (array $l) => [$l['label'], url($l['url']), url($l['url']) === url()->current()];
    $jbCompany = array_map($jbLink, $jbFooter['company'] ?? []);
    if (! collect($jbCompany)->contains(fn ($l) => $l[1] === route('contact'))) {
        $jbCompany[] = $jbContact;
    }
    $jbNotices = array_map($jbLink, $jbFooter['notices'] ?? []);
@endphp

<a class="jb-skip" href="#main-content">Skip to content</a>

<header class="jb-masthead">
    <div class="jb-masthead__inner">
        <div class="jb-masthead__info">
            @if($jbHours !== '')<p>Office timing : <b>{{ $jbHours }}</b></p>@endif
            @if($jbPhone !== '')<p>Call us : <a href="tel:{{ preg_replace('/[^\d+]/', '', $jbPhone) }}"><b>{{ $jbPhone }}</b></a></p>@endif
            @if($jbEmail !== '')<p>Email : <a href="mailto:{{ $jbEmail }}"><b>{{ $jbEmail }}</b></a></p>@endif
        </div>

        <a class="jb-brand" href="{{ route('home') }}">
            <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="121" height="100">
        </a>

        <div class="jb-masthead__actions">
            <a class="jb-roundlink jb-roundlink--wa" href="https://wa.me/{{ $jbWhatsapp }}" target="_blank" rel="noopener">
                <span class="jb-roundlink__icon"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91A9.85 9.85 0 0 0 12.04 2Zm5.8 14.06c-.24.68-1.42 1.31-1.96 1.36-.5.05-.97.23-3.27-.68-2.77-1.09-4.52-3.92-4.66-4.1-.13-.18-1.11-1.48-1.11-2.83s.7-2 .96-2.28c.25-.27.55-.34.73-.34l.53.01c.17 0 .4-.06.62.48.24.56.8 1.94.87 2.08.07.14.11.3.02.48-.09.18-.14.3-.27.46l-.41.48c-.14.14-.28.29-.12.56.16.27.71 1.17 1.52 1.9 1.05.93 1.93 1.22 2.2 1.36.27.14.43.11.59-.07.16-.18.68-.79.86-1.06.18-.27.36-.23.61-.14.25.09 1.6.75 1.87.89.27.14.46.2.52.32.07.11.07.66-.17 1.33Z"/></svg></span>
                WhatsApp
            </a>
            @if($jbPhone !== '')
            <a class="jb-roundlink" href="tel:{{ preg_replace('/[^\d+]/', '', $jbPhone) }}">
                <span class="jb-roundlink__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 1.1 4.2 2 2 0 0 1 3.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L7 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2Z"/></svg></span>
                Call us
            </a>
            @endif
        </div>

        <button type="button" class="jb-menu-btn jb-menu-btn--open" id="jbMenuOpen" aria-controls="jbDrawer" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>

<nav class="jb-navbar" aria-label="Main">
    <div class="jb-navbar__inner">
        @foreach($jbMain as [$label, $url, $active])
            <a href="{{ $url }}" class="jb-navbar__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
        @if(count($jbCities))
            <span class="jb-navbar__sep" aria-hidden="true"></span>
            @foreach($jbCities as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-navbar__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        @endif
        <span class="jb-navbar__sep" aria-hidden="true"></span>
        <a href="{{ $jbContact[1] }}" class="jb-navbar__link" @if($jbContact[2]) aria-current="page" @endif>{{ $jbContact[0] }}</a>
    </div>
</nav>

<div class="jb-drawer" id="jbDrawer" aria-hidden="true">
    <div class="jb-drawer__backdrop" data-drawer-close></div>
    <aside class="jb-drawer__panel" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="jb-drawer__head">
            <a href="{{ route('home') }}">
                <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="65" height="54">
            </a>
            <button type="button" class="jb-menu-btn" data-drawer-close aria-label="Close menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <nav class="jb-drawer__nav" aria-label="Mobile">
            <a href="{{ route('home') }}" class="jb-drawer__link jb-drawer__link--primary">Shop gold &amp; silver</a>
            @foreach(array_slice($jbMain, 1) as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ route('rates.cities') }}" class="jb-drawer__link" @if(request()->routeIs('rates.cities')) aria-current="page" @endif>Rates by city</a>
            <a href="{{ route('live-rates') }}" class="jb-drawer__link" @if(request()->routeIs('live-rates')) aria-current="page" @endif>Full rate table</a>

            @php $jbAllCities = RateBook::cities(); @endphp
            @if(count($jbAllCities))
                <p class="jb-drawer__label">Cities</p>
                @foreach($jbAllCities as $key => [$name])
                    <a href="{{ $jbCityUrl($key) }}" class="jb-drawer__link" @if($jbOnCity($key)) aria-current="page" @endif>{{ $name }}</a>
                @endforeach
            @endif

            <p class="jb-drawer__label">Company</p>
            @foreach($jbCompany as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach

            @if(count($jbNotices))
                <p class="jb-drawer__label">{{ FooterLink::heading('notices') }}</p>
                @foreach($jbNotices as [$label, $url, $active])
                    <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            @endif

            @if($jbHours !== '' || $jbPhone !== '')
                <div class="jb-drawer__contact">
                    @if($jbHours !== '')<div>Office timing: <b>{{ $jbHours }}</b></div>@endif
                    @if($jbPhone !== '')<div>Call: <a href="tel:{{ preg_replace('/[^\d+]/', '', $jbPhone) }}"><b>{{ $jbPhone }}</b></a></div>@endif
                </div>
            @endif
        </nav>
    </aside>
</div>
