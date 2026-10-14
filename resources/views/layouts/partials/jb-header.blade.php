{{--
    Site header, used by layouts/bullion and layouts/lawfirm:
      1. market ticker: our latest published rates, the change since the previous published rate, and when they were published;
      2. sticky header: logo in the centre, menu split either side, with Shop and Gold rate dropdowns;
      3. mobile drawer (phones and tablets).
    Styles: public/css/bullion-theme.css · Scroll state: public/js/site.js
--}}
@php
    use App\Models\Collection;
    use App\Models\FooterLink;
    use App\Models\MetalRate;
    use App\Models\SiteSetting;
    use App\Support\RateBook;

    $jbLogo = SiteSetting::get('site_logo');
    $jbLogo = $jbLogo ? versioned_asset('storage/' . $jbLogo) : versioned_asset('images/brand/josha-logo-header.webp');

    $jbHours    = trim((string) SiteSetting::get('contact_working_hours'));
    $jbPhone    = trim((string) SiteSetting::get('contact_phone'));

    $jbCityUrl = function (string $key) {
        [$state, $city] = explode('/', $key);
        return route('rates.city', ['state' => $state, 'city' => $city]);
    };
    $jbOnCity = fn (string $key) => request()->routeIs('rates.city')
        && request()->route('state') . '/' . request()->route('city') === $key;

    // Ticker: the latest published rate and the one before it (only our own published figures)
    $jbRate = MetalRate::currentRate();
    $jbPrev = $jbRate?->getPreviousRate();
    $jbTicker = [];
    if ($jbRate) {
        $jbGoldChange   = RateBook::sessionChange($jbRate, $jbPrev, 'gold');
        $jbSilverChange = RateBook::sessionChange($jbRate, $jbPrev, 'silver');
        $jbTicker = [
            ['Gold 24K',   $jbRate->goldRate('24K', 10), '10 g', $jbGoldChange,   route('rates.gold')],
            ['Gold 22K',   $jbRate->goldRate('22K', 10), '10 g', $jbGoldChange,   route('rates.gold')],
            ['Gold 18K',   $jbRate->goldRate('18K', 10), '10 g', $jbGoldChange,   route('rates.gold')],
            ['Silver 999', $jbRate->silverRate(1000),    '1 kg', $jbSilverChange, route('rates.silver')],
        ];
        $jbStale = $jbRate->published_at->lt(now(MetalRate::TIMEZONE)->subHours(36));
    }

    // Menu either side of the logo: [label, url, is current page, dropdown key]
    $jbLeft = [
        ['Shop',        route('home') . '#categories', request()->routeIs('home', 'collections.*'), 'shop'],
        ['Gold rate',   route('rates.gold'),   request()->routeIs('rates.gold', 'rates.cities', 'rates.city', 'live-rates'), 'gold'],
        ['Silver rate', route('rates.silver'), request()->routeIs('rates.silver'), null],
        ['Charts',      route('rates.charts'), request()->routeIs('rates.charts'), null],
        ['Gold Pulse',  route('rates.pulse'),  request()->routeIs('rates.pulse'), null],
    ];
    $jbRight = [
        ['About us',   route('about-us'),       request()->routeIs('about-us'), null],
        ['Services',   route('services.index'), request()->routeIs('services.index'), null],
        ['Updates',    route('updates'),        request()->routeIs('updates', 'updates.*'), null],
        ['Contact us', route('contact'),        request()->routeIs('contact'), null],
    ];
    $jbCollections = Collection::activeCached();
    $jbMenuCities  = RateBook::menuCities();
    $jbAllCities   = RateBook::cities();

    // Drawer "Company" and "Notices & letters" follow the footer columns (Admin → Footer)
    $jbFooter = FooterLink::activeByColumnCached();
    $jbLink = fn (array $l) => [$l['label'], url($l['url']), url($l['url']) === url()->current()];
    $jbCompany = array_map($jbLink, $jbFooter['company'] ?? []);
    $jbNotices = array_map($jbLink, $jbFooter['notices'] ?? []);
    $jbCaret = '<svg class="jb-lux__caret" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true" focusable="false"><path d="m2 3.5 3 3 3-3"/></svg>';
@endphp

<a class="jb-skip" href="#main-content">Skip to content</a>

{{-- ─── Market ticker ─── --}}
<div class="jb-ticker" role="region" aria-label="Latest published rates">
    <div class="jb-wrap jb-ticker__row">
        @if($jbRate)
            <div class="jb-scroll-x jb-ticker__items">
                @foreach($jbTicker as [$label, $value, $unit, $change, $url])
                    @if(! $loop->first)<span class="jb-ticker__sep" aria-hidden="true"></span>@endif
                    <a class="jb-ticker__item" href="{{ $url }}">
                        <span class="jb-ticker__label">{{ $label }}</span>
                        <span class="jb-ticker__value">{{ RateBook::inr($value) }}</span>
                        <span class="jb-ticker__unit">/ {{ $unit }}</span>
                        @if($change !== null && abs($change) >= 0.005)
                            <span class="jb-change jb-change--{{ $change > 0 ? 'up' : 'down' }}">
                                <span aria-hidden="true">{{ $change > 0 ? '▲' : '▼' }}</span>
                                <span class="jb-sr">{{ $change > 0 ? 'up' : 'down' }}</span>{{ number_format(abs($change), 2) }}%<span class="jb-sr"> since our previous published rate</span>
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="jb-ticker__meta">
                @if($jbRate->is_sample)<span class="jb-chip jb-chip--warn">Sample data</span>@endif
                <span class="jb-live" data-state="{{ $jbStale ? 'stale' : 'live' }}">
                    <span class="jb-live__dot" aria-hidden="true"></span>
                    Updated <time datetime="{{ $jbRate->published_at->toIso8601String() }}">{{ $jbRate->published_at->format('j M Y, g:i A') }}</time>
                </span>
                <a href="{{ route('live-rates') }}">All rates →</a>
            </div>
        @else
            <span class="jb-live" data-state="stale"><span class="jb-live__dot" aria-hidden="true"></span> Today's rates will appear here once published</span>
        @endif
    </div>
</div>

{{-- ─── Header ─── --}}
<header class="jb-lux" data-header>
    <div class="jb-lux__inner">
        <nav class="jb-lux__nav jb-lux__nav--left" aria-label="Main">
            @foreach($jbLeft as [$label, $url, $active, $menu])
                <div class="jb-lux__item">
                    <a href="{{ $url }}" class="jb-lux__link" @if($active) aria-current="page" @endif>{{ $label }}@if($menu){!! $jbCaret !!}@endif</a>
                    @if($menu === 'shop' && $jbCollections->isNotEmpty())
                        <div class="jb-lux__menu">
                            @foreach($jbCollections as $jbCol)
                                <a href="{{ $jbCol->url }}" @if(request()->is('collections/' . $jbCol->slug)) aria-current="page" @endif>{{ $jbCol->name }}</a>
                            @endforeach
                            <hr>
                            <a href="{{ route('home') }}#by-weight">Prices by weight <small>live</small></a>
                        </div>
                    @elseif($menu === 'gold')
                        <div class="jb-lux__menu">
                            <a href="{{ route('rates.gold') }}" @if(request()->routeIs('rates.gold')) aria-current="page" @endif>Gold rate today</a>
                            <a href="{{ route('live-rates') }}" @if(request()->routeIs('live-rates')) aria-current="page" @endif>Full rate table</a>
                            <a href="{{ route('rates.cities') }}" @if(request()->routeIs('rates.cities')) aria-current="page" @endif>Rates by city</a>
                            @if(count($jbMenuCities))
                                <p class="jb-lux__menu-label">Cities</p>
                                @foreach($jbMenuCities as $key => [$name])
                                    <a href="{{ $jbCityUrl($key) }}" @if($jbOnCity($key)) aria-current="page" @endif>{{ $name }}</a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <a class="jb-brand" href="{{ route('home') }}">
            <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="136" height="112" fetchpriority="high">
        </a>

        <nav class="jb-lux__nav jb-lux__nav--right" aria-label="Company">
            @foreach($jbRight as [$label, $url, $active])
                <div class="jb-lux__item">
                    <a href="{{ $url }}" class="jb-lux__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
                </div>
            @endforeach
        </nav>

        <button type="button" class="jb-menu-btn jb-menu-btn--open" id="jbMenuOpen" aria-controls="jbDrawer" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 8h16M4 16h16"/></svg>
        </button>
    </div>
</header>

<div class="jb-drawer" id="jbDrawer" aria-hidden="true">
    <div class="jb-drawer__backdrop" data-drawer-close></div>
    <aside class="jb-drawer__panel" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="jb-drawer__head">
            <a href="{{ route('home') }}">
                <img class="jb-brand__logo" src="{{ $jbLogo }}" alt="{{ config('company.name') }}, home" width="78" height="64">
            </a>
            <button type="button" class="jb-menu-btn" data-drawer-close aria-label="Close menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <nav class="jb-drawer__nav" aria-label="Mobile">
            <a href="{{ route('home') }}#by-weight" class="jb-drawer__link jb-drawer__link--primary">Prices by weight</a>
            @foreach(array_merge(array_slice($jbLeft, 1), $jbRight) as [$label, $url, $active])
                <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ route('live-rates') }}" class="jb-drawer__link" @if(request()->routeIs('live-rates')) aria-current="page" @endif>Full rate table</a>
            <a href="{{ route('bank-details') }}" class="jb-drawer__link" @if(request()->routeIs('bank-details')) aria-current="page" @endif>Bank details</a>

            @if($jbCollections->isNotEmpty())
                <p class="jb-drawer__label">Shop</p>
                @foreach($jbCollections as $jbCol)
                    <a href="{{ $jbCol->url }}" class="jb-drawer__link" @if(request()->is('collections/' . $jbCol->slug)) aria-current="page" @endif>{{ $jbCol->name }}</a>
                @endforeach
            @endif

            @if(count($jbAllCities))
                <p class="jb-drawer__label">Rates by city</p>
                @foreach($jbAllCities as $key => [$name])
                    <a href="{{ $jbCityUrl($key) }}" class="jb-drawer__link" @if($jbOnCity($key)) aria-current="page" @endif>{{ $name }}</a>
                @endforeach
            @endif

            @if(count($jbCompany))
                <p class="jb-drawer__label">{{ FooterLink::heading('company') }}</p>
                @foreach($jbCompany as [$label, $url, $active])
                    <a href="{{ $url }}" class="jb-drawer__link" @if($active) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            @endif

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
