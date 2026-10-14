{{--
    Site header: logo in the centre with the menu split either side (sticky), plus the mobile drawer. Used by layouts/bullion and layouts/lawfirm.
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

<header class="jb-lux">
    <div class="jb-lux__inner">
        <nav class="jb-lux__nav jb-lux__nav--left" aria-label="Main">
            @foreach($jbMain as [$label, $url, $active])
                @continue($loop->index > 4)
                <a href="{{ $url }}" class="jb-lux__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>

        <a class="jb-brand" href="{{ route('home') }}">
            <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="121" height="100">
        </a>

        <nav class="jb-lux__nav jb-lux__nav--right" aria-label="More">
            @foreach(array_slice($jbMain, 5) as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-lux__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            @foreach($jbCities as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-lux__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ $jbContact[1] }}" class="jb-lux__link" @if($jbContact[2]) aria-current="page" @endif>{{ $jbContact[0] }}</a>
        </nav>

        <button type="button" class="jb-menu-btn jb-menu-btn--open" id="jbMenuOpen" aria-controls="jbDrawer" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>

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
