@extends('layouts.bullion')

@section('title', 'Gold, Diamond & Silver in Bengaluru · Rates Today | JOSHA GOLD AND DIAMOND JEWELLERY')
@section('description', "Today's 24K, 22K and 18K gold and 999 silver rates, published every morning and evening, with prices by weight for coins, bars and biscuits. Message our Bengaluru desk for a firm quote.")

@php
    use App\Models\MetalRate;
    use App\Models\SiteSetting;
    use App\Support\RateBook;
    use Illuminate\Support\Str;

    $inr = fn (float $value): string => RateBook::inr($value);

    $whatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp')) ?: '918035565332';
    $phone    = trim((string) SiteSetting::get('contact_phone'));
    $email    = trim((string) SiteSetting::get('contact_email'));
    $hours    = trim((string) SiteSetting::get('contact_working_hours'));
    $address  = trim((string) SiteSetting::get('contact_address')) ?: (string) $offices->first(fn ($o) => filled($o->address))?->address;
    $waLink   = fn (string $text) => 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode($text);
    // "Get a quote" opens the contact page with the item filled in
    $quoteLink = fn (string $item = '') => route('contact', $item !== '' ? ['item' => $item] : []);
    $collectionUrl = fn (string $slug, string $fallback) => \App\Models\Collection::activeCached()->firstWhere('slug', $slug)?->url ?? $fallback;

    if ($rate) {
        $published = $rate->published_at;
        $isStale   = $published->lt(now(MetalRate::TIMEZONE)->subHours(36));
        $prev      = $rate->getPreviousRate();
        $goldChange   = RateBook::sessionChange($rate, $prev, 'gold');
        $silverChange = RateBook::sessionChange($rate, $prev, 'silver');
        $quotes = [
            ['24K Gold',   $rate->goldRate('24K', 10), 'per 10 g', $goldChange,   route('rates.gold')],
            ['22K Gold',   $rate->goldRate('22K', 10), 'per 10 g', $goldChange,   route('rates.gold')],
            ['Silver 999', $rate->silverRate(1000),    'per kg',   $silverChange, route('rates.silver')],
        ];
    }

    // Price-by-weight catalogue: [metal, form, grams]
    $pieces = [
        ['gold', 'coin', 0.5], ['gold', 'coin', 1], ['gold', 'coin', 2], ['gold', 'coin', 5], ['gold', 'coin', 8], ['gold', 'coin', 10],
        ['gold', 'bar', 10], ['gold', 'bar', 20], ['gold', 'bar', 50], ['gold', 'bar', 100],
        ['silver', 'coin', 5], ['silver', 'coin', 10], ['silver', 'coin', 20], ['silver', 'coin', 50], ['silver', 'coin', 100],
        ['silver', 'bar', 100], ['silver', 'bar', 250], ['silver', 'bar', 500], ['silver', 'bar', 1000], ['silver', 'bar', 5000],
    ];
    // 0.5 → "0.5 g", 10 → "10 g", 1000 → "1 kg"
    $weightLabel = fn (float $g) => $g >= 1000
        ? rtrim(rtrim(number_format($g / 1000, 3, '.', ''), '0'), '.') . ' kg'
        : rtrim(rtrim(number_format($g, 3, '.', ''), '0'), '.') . ' g';

    $cardPhotos = [
        'gold-coin'   => 'gold-coins-card.webp',
        'gold-bar'    => 'gold-bars-card.webp',
        'silver-coin' => 'silver-coins-card.webp',
        'silver-bar'  => 'silver-bar-card.webp',
    ];

    $goldPurities   = ['24K' => '999.9', '22K' => '916', '18K' => '750'];
    $silverPurities = ['999' => '999', '925' => '925'];

    // Reference table rows: [metal, purity, label, fineness note, swatch colour]
    $tableRows = [
        'Gold' => [
            ['gold', '24K', '24K', '99.9% gold', '#d4a73c'],
            ['gold', '22K', '22K', '91.6% gold', '#c99a3e'],
            ['gold', '18K', '18K', '75% gold',   '#b98c4a'],
        ],
        'Silver' => [
            ['silver', '999', '999 fine', '99.9% silver',   '#d9dcdf'],
            ['silver', '925', '925 sterling', '92.5% silver', '#c3c7cb'],
        ],
    ];
@endphp

@push('head')
<link rel="preload" as="image" href="{{ asset('images/site/hero-bullion.webp') }}" imagesrcset="{{ asset('images/site/hero-bullion-1200.webp') }} 1200w, {{ asset('images/site/hero-bullion.webp') }} 2400w" imagesizes="100vw" fetchpriority="high">
<script type="application/ld+json">{!! json_encode(array_filter([
    '@context'  => 'https://schema.org',
    '@type'     => 'JewelryStore',
    'name'      => config('company.name'),
    'url'       => route('home'),
    'logo'      => asset('images/brand/josha-logo.png'),
    'image'     => asset('images/site/hero-bullion-1200.webp'),
    'telephone' => $phone !== '' ? $phone : null,
    'email'     => $email !== '' ? $email : null,
    'address'   => $address !== '' ? [
        '@type' => 'PostalAddress',
        'streetAddress' => preg_replace('/\s*\n\s*/', ', ', $address),
        'addressLocality' => 'Bengaluru',
        'addressRegion' => 'Karnataka',
        'addressCountry' => 'IN',
    ] : null,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
<div class="jb-store">
    {{-- ─── Hero ───────────────────────────────────────── --}}
    <section class="jb-hero" aria-labelledby="hero-title">
        <div class="jb-hero__bg" data-parallax="0.12">
            <img src="{{ asset('images/site/hero-bullion.webp') }}"
                 srcset="{{ asset('images/site/hero-bullion-1200.webp') }} 1200w, {{ asset('images/site/hero-bullion.webp') }} 2400w" sizes="100vw"
                 alt="Gold bullion bars and coins under warm studio light" width="2400" height="1350" fetchpriority="high">
        </div>
        <div class="jb-dust" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>

        <div class="jb-wrap jb-hero__grid">
            <div class="jb-hero__copy">
                <p class="jb-eyebrow">Bengaluru · Gold, diamonds &amp; silver</p>
                <h1 class="jb-hero__title" id="hero-title">Precious Metals. <em>Timeless Trust.</em></h1>
                <p class="jb-hero__lead">Discover gold, diamonds and silver with transparent pricing, exceptional craftsmanship and a commitment to quality.</p>
                <div class="jb-hero__cta">
                    <a class="jb-btn jb-btn--primary jb-btn--lg" href="{{ route('rates.gold') }}">View live gold rates</a>
                    <a class="jb-btn jb-btn--light jb-btn--lg" href="#categories">Explore collections</a>
                </div>

                @if($rate)
                    <div class="jb-quote">
                        @foreach($quotes as [$label, $value, $unit, $change, $anchor])
                            <a class="jb-quote__cell" href="{{ $anchor }}">
                                <span class="jb-quote__label">{{ $label }}</span>
                                <span class="jb-quote__value">{{ $inr($value) }}</span>
                                <span class="jb-quote__unit">{{ $unit }}
                                    @if($change !== null && abs($change) >= 0.005)
                                        <span class="jb-change jb-change--{{ $change > 0 ? 'up' : 'down' }}"><span aria-hidden="true">{{ $change > 0 ? '▲' : '▼' }}</span><span class="jb-sr">{{ $change > 0 ? 'up' : 'down' }}</span>{{ number_format(abs($change), 2) }}%<span class="jb-sr"> since our previous rate</span></span>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                    <div class="jb-quote__foot">
                        <span class="jb-live" data-state="{{ $isStale ? 'stale' : 'live' }}"><span class="jb-live__dot" aria-hidden="true"></span> {{ ucfirst($rate->session) }} rate · {{ $published->format('j M Y, g:i A') }}</span>
                        @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data, not a live market rate</span>@endif
                        <a class="jb-link" href="{{ route('rates.charts') }}">Charts &amp; history →</a>
                    </div>
                @endif
            </div>

            <div class="jb-hero__art">
                <span class="jb-hero__ring" aria-hidden="true"></span>
                <a class="jb-hero__frame jb-hero__frame--a" href="{{ $collectionUrl('gold-jewellery', route('contact')) }}" data-parallax="-0.05" aria-label="Explore Gold jewellery collection">
                    <img src="{{ asset('images/site/hero-necklace.webp') }}" alt="Gold jewellery" width="720" height="900" fetchpriority="high">
                    <span class="jb-hero__caption">Gold jewellery <span class="jb-hero__caption-arrow" aria-hidden="true">→</span></span>
                </a>
                <a class="jb-hero__frame jb-hero__frame--b" href="{{ $collectionUrl('diamond-jewellery', route('contact')) }}" data-parallax="0.04" aria-label="Explore Diamonds collection">
                    <img src="{{ asset('images/site/hero-diamond.webp') }}" alt="Diamonds" width="720" height="720">
                    <span class="jb-hero__caption">Diamonds <span class="jb-hero__caption-arrow" aria-hidden="true">→</span></span>
                </a>
            </div>
        </div>
    </section>

    {{-- ─── Trust band ─────────────────────────────────── --}}
    <section class="jb-trust" aria-label="Why buy from us">
        @php
            $trust = [
                ['Transparent pricing', 'Rates published every morning and evening, with the metal value shown by weight.', '<path d="M4 19h16M6 15l3.5-4 3.5 2.5L19 6"/><path d="M15 6h4v4"/>'],
                ['Purity, clearly stated', 'Every purity priced: 24K to 10K gold, 999 to 800 silver.', '<path d="M6 3h12l3 6-9 12L3 9l3-6Z"/><path d="M3 9h18M9 3l3 18 3-18"/>'],
                ['Wholesale available', 'Bullion and wholesale trade on enquiry, at the day\'s rate.', '<path d="M3 20h18M5 20l2-6h10l2 6M8 14l1.5-5h5L16 14"/>'],
                ['Personal assistance', 'Speak to our Bengaluru desk by phone or WhatsApp.', '<path d="M20 15.5v3a2 2 0 0 1-2.2 2A17 17 0 0 1 3.5 6.2 2 2 0 0 1 5.5 4h3l1.5 4-2 1.5a12 12 0 0 0 5.5 5.5l1.5-2 4 1.5Z"/>'],
                ['Secure enquiries', 'A firm quote first; pay only into our listed bank accounts.', '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>'],
            ];
        @endphp
        @foreach($trust as [$title, $text, $icon])
            <div class="jb-trust__item" data-reveal style="--jb-delay: {{ $loop->index * 0.06 }}s">
                <span class="jb-trust__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $icon !!}</svg></span>
                <span><p class="jb-trust__title">{{ $title }}</p><p class="jb-trust__text">{{ $text }}</p></span>
            </div>
        @endforeach
    </section>

    {{-- ─── Shop by category ─────────────────────────── --}}
    <section class="jb-section" id="categories" style="scroll-margin-top: var(--jb-header-h);">
        <div class="jb-wrap">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">Collections</p>
                    <h2 class="jb-section__title">Shop by <em>category</em></h2>
                </div>
                <p class="jb-section__sub" style="max-width: 440px;">Coins, bars and biscuits priced at today's rate, and jewellery made to order. Every piece is quoted before you pay.</p>
            </div>
            @include('partials.collection-cards')
        </div>
    </section>

    {{-- ─── Price by weight ────────────────────────────── --}}
    <section class="jb-section jb-section--white" id="by-weight" style="scroll-margin-top: var(--jb-header-h);">
        <div class="jb-wrap">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">{{ $rate ? 'Live prices' : 'Prices' }}</p>
                    <h2 class="jb-section__title">Price <em>by weight</em></h2>
                    <p class="jb-section__sub">Metal value at our latest published rate. Making charges, where they apply, and 3% GST are added to your quote.</p>
                </div>
                <a class="jb-link" href="{{ route('live-rates') }}">Full rate table →</a>
            </div>

            @if($rate)
            <div class="jb-shop" data-shop>
                <div class="jb-controls" data-reveal>
                    <div class="jb-control" role="group" aria-labelledby="ctl-metal">
                        <span class="jb-control__label" id="ctl-metal">Metal</span>
                        <div class="jb-scroll-x">
                            <button type="button" class="jb-pill" data-filter="metal" data-value="gold" aria-pressed="true">Gold</button>
                            <button type="button" class="jb-pill" data-filter="metal" data-value="silver" aria-pressed="false">Silver</button>
                        </div>
                    </div>
                    <div class="jb-control" role="group" aria-labelledby="ctl-purity">
                        <span class="jb-control__label" id="ctl-purity">Purity</span>
                        <div class="jb-scroll-x">
                            @foreach($goldPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="gold" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $key }} <span class="jb-muted">{{ $fine }}</span></button>
                            @endforeach
                            @foreach($silverPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="silver" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" hidden>{{ MetalRate::SILVER_PURITY[$key]['label'] }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="jb-control" role="group" aria-labelledby="ctl-form">
                        <span class="jb-control__label" id="ctl-form">Format</span>
                        <div class="jb-scroll-x">
                            <button type="button" class="jb-pill" data-filter="form" data-value="all" aria-pressed="true">All</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="coin" aria-pressed="false">Coins</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="bar" aria-pressed="false">Bars</button>
                        </div>
                    </div>
                    <form class="jb-search" role="search" data-search-form>
                        <label class="jb-sr" for="jbSearch">Search prices by weight</label>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input id="jbSearch" type="search" placeholder="Search 10 g, 22K, silver bar…" autocomplete="off" data-search>
                    </form>
                </div>

                <div class="jb-toolbar">
                    <span data-count aria-live="polite">10 weights</span>
                    <span>Prices in ₹ · metal value only</span>
                </div>

                <div class="jb-grid jb-grid--wide" data-grid>
                    @foreach($pieces as [$metal, $form, $grams])
                        @php
                            $purity = $metal === 'gold' ? '24K' : '999';
                            $weight = $weightLabel($grams);
                            $title  = ($metal === 'gold' ? 'Gold ' : 'Silver ') . $form;
                        @endphp
                        <article class="jb-card" data-metal="{{ $metal }}" data-form="{{ $form }}" data-grams="{{ $grams }}" @if($metal !== 'gold') hidden @endif>
                            <a class="jb-card__media" href="{{ route('live-rates') . ($metal === 'gold' ? '#gold-rate' : '#silver-rate') }}" tabindex="-1" aria-hidden="true">
                                <img src="{{ asset('images/site/' . $cardPhotos[$metal . '-' . $form]) }}" alt="" width="400" height="400" loading="lazy" decoding="async">
                                <span class="jb-card__badge jb-chip jb-chip--gold" data-purity-label>{{ $purity }}</span>
                                <span class="jb-card__weight">{{ $weight }}</span>
                            </a>
                            <div class="jb-card__body">
                                <h3 class="jb-card__name">{{ $title }} · {{ $weight }}</h3>
                                <p class="jb-card__meta"><span data-purity-label>{{ $purity }}</span><span aria-hidden="true">·</span><span>{{ $form === 'coin' ? 'Coin' : 'Minted bar' }}</span></p>
                                <p class="jb-card__price" data-price>{{ $inr($rate->priceFor($metal, $purity, $grams)) }}</p>
                                <p class="jb-card__sub">Metal value · making &amp; GST extra</p>
                                <a class="jb-btn jb-btn--primary jb-card__cta" data-enquire href="{{ $quoteLink("{$weight} {$purity} {$metal} {$form}") }}">Get a quote</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
            @else
                <div class="jb-receipt"><p class="jb-muted">Prices by weight will appear here as soon as today's rate is published.</p></div>
            @endif
        </div>
    </section>

    {{-- ─── Services (Admin → Services) ────────────────── --}}
    @if($services->isNotEmpty())
    <section class="jb-section jb-dark" id="services">
        <div class="jb-wrap">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">What we do</p>
                    <h2 class="jb-section__title">Our <em>services</em></h2>
                </div>
                <a class="jb-link" href="{{ route('services.index') }}">All services →</a>
            </div>
            <div class="jb-services">
                @foreach($services->take(3) as $service)
                    <article class="jb-service" data-reveal style="--jb-delay: {{ $loop->index * 0.1 }}s">
                        <div class="jb-service__img">
                            @if($service->image)<img src="{{ asset('storage/' . $service->image) }}" alt="" width="800" height="550" loading="lazy" decoding="async">@endif
                            <span class="jb-service__num" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="jb-service__body">
                            <h3>{{ $service->title }}</h3>
                            @if(filled($service->description))
                                <p>{{ Str::limit(strip_tags($service->description), 160) }}</p>
                            @endif
                            <p class="jb-service__cta"><a href="{{ $quoteLink($service->title) }}">Enquire<span class="jb-sr"> about {{ $service->title }}</span></a></p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Reference rates ────────────────────────────── --}}
    @if($rate)
    <section class="jb-section jb-section--ivory" id="rates-today">
        <div class="jb-wrap jb-rates">
            <div class="jb-rates__aside" data-reveal>
                <p class="jb-eyebrow">Reference rates · Bengaluru</p>
                <h2 class="jb-section__title">Gold &amp; silver <em>rates today</em></h2>
                <p style="margin-top: 20px;">
                    Our {{ $rate->session }} rate puts 24K gold at <strong>{{ $inr($rate->goldRate('24K', 10)) }}</strong> per 10 grams
                    and 999 silver at <strong>{{ $inr($rate->silverRate(1000)) }}</strong> per kilogram.
                </p>
                <p>Lower purities are worked out from the same rate by their metal content. Ounce and tola prices are on the full table.</p>
                <div class="jb-rates__stamp">
                    <span class="jb-live" data-state="{{ $isStale ? 'stale' : 'live' }}"><span class="jb-live__dot" aria-hidden="true"></span> Last updated <b><time datetime="{{ $published->toIso8601String() }}">{{ $published->format('j M Y, g:i A') }}</time></b></span>
                    @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data</span>@endif
                </div>
                <p style="margin-top: 20px;"><a class="jb-link" href="{{ route('live-rates') }}">Full rate table →</a></p>
            </div>
            <div class="jb-table-wrap" data-reveal style="--jb-delay: .1s" tabindex="0" role="region" aria-label="Rates by purity and weight">
                <table class="jb-table">
                    <caption>Metal value in ₹, excluding making charges and 3% GST.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Purity</th>
                            <th scope="col" class="jb-table__unit">1 g</th>
                            <th scope="col" class="jb-table__unit">10 g</th>
                            <th scope="col" class="jb-table__unit">100 g</th>
                            <th scope="col" class="jb-table__unit">1 kg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableRows as $group => $rows)
                            <tr class="jb-table__group"><th scope="rowgroup" colspan="5">{{ $group }}</th></tr>
                            @foreach($rows as [$metal, $purity, $label, $note, $swatch])
                                <tr>
                                    <th scope="row"><span class="jb-purity"><span class="jb-purity__dot" style="background: {{ $swatch }};" aria-hidden="true"></span>{{ $label }} <small>{{ $note }}</small></span></th>
                                    @foreach([1, 10, 100, 1000] as $g)
                                        <td class="{{ $g === 10 ? 'jb-num-gold' : '' }}">{{ $inr($rate->priceFor($metal, $purity, $g)) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Buying guide ───────────────────────────────── --}}
    <section class="jb-section" id="guide">
        <div class="jb-wrap">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">Buying guide</p>
                    <h2 class="jb-section__title">Before you buy <em>gold or silver</em></h2>
                </div>
            </div>
            @php
                $guide = [
                    ['gold-rings-wide.webp', 'Purity', 'Understanding gold purity',
                        '24K is 99.9% gold and is what coins and bars are made of. 22K (91.6%) is the usual standard for jewellery, and 18K (75%) is harder, so it holds stones well. The price follows the gold content.',
                        route('rates.gold'), 'Rates by purity'],
                    ['guide-jewellery.webp', 'Jewellery', 'How to choose gold jewellery',
                        'Look for the BIS hallmark: the BIS logo, the purity grade and a six-character HUID code you can check in the BIS CARE app. Ask for the gold weight and the making charges separately.',
                        $collectionUrl('gold-jewellery', route('contact')), 'Gold jewellery'],
                    ['guide-bullion.webp', 'Bullion', 'Coins, bars and biscuits',
                        'Coins and bars are 24K and are priced at the metal value for their weight, plus making charges and 3% GST. Prices move with the market every day; past prices are no guide to future returns.',
                        route('home') . '#by-weight', 'Prices by weight'],
                    ['guide-silver.webp', 'Silver', 'How to evaluate silver',
                        'Silver is quoted per kilogram. Bars and coins are usually 999 fine; 925 sterling is used for jewellery and articles. Small pieces carry higher making charges per gram.',
                        route('rates.silver'), 'Silver rate today'],
                ];
            @endphp
            <div class="jb-guide">
                @foreach($guide as [$img, $step, $heading, $text, $href, $more])
                    <a class="jb-guide__card" href="{{ $href }}" data-reveal style="--jb-delay: {{ $loop->index * 0.08 }}s">
                        <div class="jb-guide__img"><img src="{{ asset('images/site/' . $img) }}" alt="" width="600" height="413" loading="lazy" decoding="async"></div>
                        <div class="jb-guide__body">
                            <span class="jb-guide__step">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ $step }}</span>
                            <h3>{{ $heading }}</h3>
                            <p>{{ $text }}</p>
                            <span class="jb-guide__more">{{ $more }} →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Latest updates (Admin → Updates) ──────────────── --}}
    @php $latestUpdates = \App\Models\Blog::active()->ordered()->take(3)->get(); @endphp
    @if($latestUpdates->isNotEmpty())
    <section class="jb-section jb-section--white" id="updates">
        <div class="jb-wrap">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">From our desk</p>
                    <h2 class="jb-section__title">Latest <em>updates</em></h2>
                </div>
                <a class="jb-link" href="{{ route('updates') }}">All updates →</a>
            </div>
            <div class="jb-news">
                @foreach($latestUpdates as $update)
                    <a class="jb-news__item" href="{{ route('updates.show', $update->slug) }}" data-reveal style="--jb-delay: {{ $loop->index * 0.08 }}s">
                        <div class="jb-news__img">
                            <img src="{{ $update->image ? asset('storage/' . $update->image) : asset('images/site/gold-bars-2-wide.webp') }}" alt="" width="600" height="400" loading="lazy" decoding="async">
                        </div>
                        <div class="jb-news__body">
                            @if($update->created_at)<time class="jb-news__date" datetime="{{ $update->created_at->toDateString() }}">{{ $update->created_at->format('j M Y') }}</time>@endif
                            <h3>{{ $update->title }}</h3>
                            @if(filled($update->description))<p>{{ Str::limit(strip_tags($update->description), 120) }}</p>@endif
                            <span class="jb-news__more">Read more →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Quotation and contact ──────────────────────── --}}
    <section class="jb-section" id="quote">
        <div class="jb-wrap">
            <div class="jb-band" data-reveal>
                <div>
                    <p class="jb-eyebrow">Talk to our desk</p>
                    <h2 class="jb-section__title">Request a <em>firm quotation</em></h2>
                    <p class="jb-section__sub">Tell us the metal, purity and weight you need, or the piece of jewellery you have in mind. We'll confirm today's price and the payment details, and you pay only into the accounts listed on our Bank Details page.</p>
                    <div class="jb-band__actions">
                        <a class="jb-btn jb-btn--dark jb-btn--lg" href="{{ $quoteLink() }}">Send an enquiry</a>
                        <a class="jb-btn jb-btn--wa jb-btn--lg" href="{{ $waLink('Hi, I would like a gold/silver quote.') }}" target="_blank" rel="noopener">WhatsApp us<span class="jb-sr"> (opens WhatsApp)</span></a>
                        @if($phone !== '')<a class="jb-btn jb-btn--ghost jb-btn--lg" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">Call {{ $phone }}</a>@endif
                    </div>
                    <p class="jb-band__links"><a class="jb-link" href="{{ route('bank-details') }}">Bank details →</a><a class="jb-link" href="{{ route('privacy.request') }}">Your data &amp; privacy →</a></p>
                </div>
                <div class="jb-band__card">
                    @if($address !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                        <div><strong>Our office</strong><span>{!! nl2br(e($address)) !!}</span></div>
                    </div>
                    @endif
                    @if($phone !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 15.5v3a2 2 0 0 1-2.2 2A17 17 0 0 1 3.5 6.2 2 2 0 0 1 5.5 4h3l1.5 4-2 1.5a12 12 0 0 0 5.5 5.5l1.5-2 4 1.5Z"/></svg>
                        <div><strong>Call or WhatsApp</strong><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></div>
                    </div>
                    @endif
                    @if($email !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <div><strong>Email</strong><a href="mailto:{{ $email }}" style="word-break: break-all;">{{ $email }}</a></div>
                    </div>
                    @endif
                    @if($hours !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        <div><strong>Working hours</strong><span>{{ $hours }}</span></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ─── FAQ ────────────────────────────────────────── --}}
    <section class="jb-section jb-section--white" id="faq">
        <div class="jb-wrap jb-faq-layout">
            <div class="jb-section__head" data-reveal>
                <div>
                    <p class="jb-eyebrow">Good to know</p>
                    <h2 class="jb-section__title">Common <em>questions</em></h2>
                    <p class="jb-section__sub">Anything else? <a class="jb-link" href="{{ route('contact') }}">Ask our desk →</a></p>
                </div>
            </div>
            <div class="jb-faq" data-reveal style="--jb-delay: .1s">
                <details>
                    <summary>How often are the rates updated?</summary>
                    <div class="jb-faq__body"><p>We publish a morning rate and an evening rate on working days. The time of the latest update is shown in the bar at the top of every page.</p></div>
                </details>
                <details>
                    <summary>Are the prices on this page final?</summary>
                    <div class="jb-faq__body"><p>No. They are the value of the metal at our published rate. Making charges, where they apply, and 3% GST are added to your final quote.</p></div>
                </details>
                <details>
                    <summary>Why are 22K and 18K cheaper than 24K?</summary>
                    <div class="jb-faq__body"><p>They contain less gold. We work them out from the 24K rate by gold content: 22K is 22/24 of the 24K price and 18K is 18/24.</p></div>
                </details>
                <details>
                    <summary>What purity is your silver?</summary>
                    <div class="jb-faq__body"><p>Silver bars and coins are usually 999 fine (99.9% silver). 925 sterling (92.5%) is used for jewellery and articles. The rate for each purity is on our <a class="jb-link" href="{{ route('rates.silver') }}">silver rate</a> page.</p></div>
                </details>
                <details>
                    <summary>How do I request a quotation?</summary>
                    <div class="jb-faq__body"><p>Press <a class="jb-link" href="{{ $quoteLink() }}">Get a quote</a> on any item, or send an enquiry from our Contact page, with the metal, purity and weight you want. You can also WhatsApp us{{ $phone !== '' ? ' or call ' . $phone : '' }}. We'll confirm the price and how to pay.</p></div>
                </details>
                <details>
                    <summary>How do I pay?</summary>
                    <div class="jb-faq__body"><p>By bank transfer to the accounts on our <a class="jb-link" href="{{ route('bank-details') }}">Bank Details</a> page. Never pay into an account that is not listed there.</p></div>
                </details>
                @if($address !== '')
                <details>
                    <summary>Can I visit you?</summary>
                    <div class="jb-faq__body"><p>Yes. Our office is at {{ preg_replace('/\s*\n\s*/', ' ', $address) }}{{ $hours !== '' ? ' (' . $hours . ')' : '' }}.</p></div>
                </details>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@if($rate)
@push('scripts')
<script>
    (function () {
        var shop = document.querySelector('[data-shop]');
        if (!shop) return;

        var RATE = {
            gold: {{ (float) $rate->gold_999_rate }},
            silver: {{ (float) $rate->silver_999_rate }},
            gold_purity: @json(MetalRate::PURITY),
            silver_purity: @json(collect(MetalRate::SILVER_PURITY)->map(fn ($p) => $p['multiplier'])),
        };
        var QUOTE = @json(route('contact')) + '?item=';
        var state = { metal: 'gold', purity: { gold: '24K', silver: '999' }, form: 'all', grams: null };
        var cards = Array.prototype.slice.call(shop.querySelectorAll('[data-grid] .jb-card'));
        var count = shop.querySelector('[data-count]');

        function inr(v) {
            return '₹' + Math.round(v).toLocaleString('en-IN');
        }
        function weightLabel(g) {
            return g >= 1000 ? (g / 1000) + ' kg' : g + ' g';
        }

        function render() {
            var purity = state.purity[state.metal];
            var mult = state.metal === 'gold' ? RATE.gold_purity[purity] : RATE.silver_purity[purity];
            var shown = 0;

            cards.forEach(function (card) {
                var metal = card.getAttribute('data-metal');
                var form = card.getAttribute('data-form');
                var visible = metal === state.metal && (state.form === 'all' || state.form === form)
                    && (state.grams === null || Math.abs(Number(card.getAttribute('data-grams')) - state.grams) < 0.001);
                card.hidden = !visible;
                if (!visible) return;
                shown++;

                var grams = Number(card.getAttribute('data-grams'));
                var price = Math.round(RATE[metal] * mult * grams * 100) / 100;
                card.querySelector('[data-price]').textContent = inr(price);
                card.querySelectorAll('[data-purity-label]').forEach(function (el) { el.textContent = purity; });
                card.querySelector('[data-enquire]').href = QUOTE + encodeURIComponent(
                    weightLabel(grams) + ' ' + purity + ' ' + metal + ' ' + form
                );
            });

            count.textContent = shown ? shown + (shown === 1 ? ' weight' : ' weights') : 'No match. Try another weight.';

            shop.querySelectorAll('[data-filter]').forEach(function (btn) {
                var f = btn.getAttribute('data-filter');
                var v = btn.getAttribute('data-value');
                if (f === 'purity') {
                    btn.hidden = btn.getAttribute('data-metal') !== state.metal;
                    btn.setAttribute('aria-pressed', String(v === purity));
                } else {
                    btn.setAttribute('aria-pressed', String(state[f] === v));
                }
            });
        }

        shop.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-filter]');
            if (!btn) return;
            var f = btn.getAttribute('data-filter');
            var v = btn.getAttribute('data-value');
            if (f === 'purity') state.purity[state.metal] = v; else state[f] = v;
            render();
        });

        // Search box: "10 g", "22k", "silver bar", "1 kg"… sets the matching filters
        function applySearch(q) {
            q = q.toLowerCase();
            state.form = /\bbars?\b/.test(q) ? 'bar' : (/\bcoins?\b/.test(q) ? 'coin' : 'all');
            if (/\bsilver\b|\b(999|925)\b/.test(q) && !/\bgold\b/.test(q)) state.metal = 'silver';
            else if (/\bgold\b|\b(24|22|18)\s*k\b/.test(q)) state.metal = 'gold';
            var k = q.match(/\b(24|22|18)\s*k\b/);
            if (k) state.purity.gold = k[1] + 'K';
            var s = q.match(/\b(999|925)\b/);
            if (s) state.purity.silver = s[1];
            var w = q.replace(/\b(24|22|18)\s*k\b|\b(999|925)\b/g, '').match(/(\d+(?:\.\d+)?)\s*(kg|kilo|g|gm|gram|grams)?\b/);
            state.grams = w ? Number(w[1]) * (/^k/.test(w[2] || '') ? 1000 : 1) : null;
            render();
        }
        document.querySelectorAll('[data-search-form]').forEach(function (form) {
            var input = form.querySelector('[data-search]');
            input.addEventListener('input', function () { applySearch(input.value); });
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                applySearch(input.value);
            });
        });

        render();
    })();
</script>
@endpush
@endif
