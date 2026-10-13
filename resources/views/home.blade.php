@extends('layouts.bullion')

@section('title', 'Gold & Silver Rates Today in Bengaluru | JOSHA GOLD AND DIAMOND JEWELLERY')
@section('description', "Today's 24K and 22K gold and 999 silver rates, published every morning and evening, with prices by purity and weight. Message our Bengaluru desk for a quote.")

@php
    use App\Models\MetalRate;
    use App\Models\SiteSetting;
    use Illuminate\Support\Str;

    // ₹ in the Indian grouping (1,48,000)
    $inr = function (float $value): string {
        $n   = (string) abs(round($value));
        $out = strlen($n) > 3
            ? preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($n, 0, -3)) . ',' . substr($n, -3)
            : $n;
        return ($value < 0 ? '−' : '') . '₹' . $out;
    };

    $whatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp')) ?: '919632527961';
    $phone    = trim((string) SiteSetting::get('contact_phone'));
    $email    = trim((string) SiteSetting::get('contact_email'));
    $hours    = trim((string) SiteSetting::get('contact_working_hours'));
    $address  = trim((string) SiteSetting::get('contact_address')) ?: (string) $offices->first(fn ($o) => filled($o->address))?->address;
    $waLink   = fn (string $text) => 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode($text);
    // "Get a quote" opens the contact page with the item filled in
    $quoteLink = fn (string $item = '') => route('contact', $item !== '' ? ['item' => $item] : []);

    if ($rate) {
        $published = $rate->published_at;
        $isStale   = $published->lt(now(MetalRate::TIMEZONE)->subHours(36));
        $quotes = [
            ['24K Gold',   $rate->goldRate('24K', 10), 'per 10 g', route('rates.gold')],
            ['22K Gold',   $rate->goldRate('22K', 10), 'per 10 g', route('rates.gold')],
            ['Silver 999', $rate->silverRate(1000),    'per kg',   route('rates.silver')],
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
@endphp

@section('content')
<div class="jb-store">
    {{-- ─── Rate strip ─────────────────────────────────── --}}
    <div class="jb-ratestrip">
        <div class="jb-wrap jb-ratestrip__row">
            <div class="jb-ratestrip__inner jb-scroll-x">
                @if($rate)
                    <span class="jb-live" data-state="{{ $isStale ? 'stale' : 'live' }}" title="{{ ucfirst($rate->session) }} rate, {{ $published->format('j M Y, g:i A') }}">
                        <span class="jb-live__dot"></span>
                        @if($isStale)
                            Rates as of {{ $published->format('j M, g:i A') }}
                        @else
                            <strong>Live rates</strong>
                        @endif
                    </span>
                    @foreach($quotes as [$label, $value, $unit, $anchor])
                        <a class="jb-ratestrip__item" href="{{ $anchor }}">
                            {{ $label }} <b>{{ $inr($value) }}</b> <span class="jb-ratestrip__unit">/{{ str_replace(['per ', ' '], '', $unit) }}</span>
                        </a>
                    @endforeach
                    @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data</span>@endif
                @else
                    <span class="jb-live" data-state="stale"><span class="jb-live__dot"></span> Today's rates will appear here once published</span>
                @endif
            </div>
            <a class="jb-ratestrip__more" href="{{ route('rates.cities') }}">City rates →</a>
        </div>
    </div>

    {{-- ─── Sub-navigation ─────────────────────────────── --}}
    <nav class="jb-subnav" aria-label="Sections">
        <div class="jb-wrap jb-subnav__inner">
            <div class="jb-scroll-x">
                <a class="jb-pill" href="#categories" aria-current="true">All</a>
                @foreach(\App\Models\Collection::activeCached() as $collectionPill)
                    <a class="jb-pill" href="{{ $collectionPill->url }}">{{ $collectionPill->name }}</a>
                @endforeach
            </div>
            @if($rate)
            <form class="jb-search jb-md-up" role="search" data-search-form>
                <label class="jb-sr" for="jbSearch">Search prices by weight</label>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input id="jbSearch" type="search" placeholder="Search 10 g, 22K, silver bar…" autocomplete="off" data-search>
            </form>
            @endif
            <a class="jb-btn jb-btn--primary jb-btn--sm jb-md-up" href="{{ $quoteLink() }}">Get a quote</a>
        </div>
        @if($rate)
        <div class="jb-subnav__search jb-md-down">
            <form class="jb-search" role="search" data-search-form>
                <label class="jb-sr" for="jbSearchMobile">Search prices by weight</label>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input id="jbSearchMobile" type="search" placeholder="Search 10 g, 22K, silver bar…" autocomplete="off" data-search>
            </form>
        </div>
        @endif
    </nav>

    {{-- ─── Hero ───────────────────────────────────────── --}}
    <section class="jb-hero">
        <div class="jb-wrap jb-hero__grid">
            <div>
                <p class="jb-eyebrow">C V Raman Nagar, Bengaluru · Gold &amp; diamond jewellery</p>
                <h1 class="jb-hero__title">
                    Gold, diamonds &amp; silver
                    <span class="jb-foil jb-hero__line">at today's rate.</span>
                </h1>
                <p class="jb-hero__lead">{{ config('company.name') }} publishes a fresh gold and silver rate every morning and evening. See what any coin, bar or purity is worth right now, then message our desk for jewellery, coins or bars at a firm price.</p>
                <div class="jb-hero__cta">
                    <a class="jb-btn jb-btn--primary jb-btn--lg" href="#by-weight">See prices by weight</a>
                    <a class="jb-btn jb-btn--ghost jb-btn--lg" href="{{ route('rates.gold') }}">Today's gold rate</a>
                </div>

                @if($rate)
                    <div class="jb-quote">
                        @foreach($quotes as [$label, $value, $unit, $anchor])
                            <a class="jb-quote__cell" href="{{ $anchor }}">
                                <span class="jb-quote__label">{{ $label }}</span>
                                <span class="jb-quote__value">{{ $inr($value) }}</span>
                                <span class="jb-quote__unit">{{ $unit }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="jb-quote__foot jb-muted">
                        <span class="jb-live" data-state="{{ $isStale ? 'stale' : 'live' }}"><span class="jb-live__dot"></span> {{ ucfirst($rate->session) }} rate · {{ $published->format('j M Y') }}</span>
                        @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data, not a live market rate</span>@endif
                        <a class="jb-link" href="{{ route('rates.charts') }}">Charts &amp; history →</a>
                    </div>
                @endif
            </div>

            <div class="jb-hero__art">
                @php
                    $collectionUrl = fn (string $slug, string $fallback) => \App\Models\Collection::activeCached()->firstWhere('slug', $slug)?->url ?? $fallback;
                    $heroTiles = [
                        ['gold-necklace.webp',    'Gold jewellery',    'View',       $collectionUrl('gold-jewellery', $quoteLink('Gold jewellery'))],
                        ['gold-bars.webp',        '24K gold bars',     'Live price', $collectionUrl('gold-bars', '#by-weight')],
                        ['diamond-ring.webp',     'Diamond rings',     'View',       $collectionUrl('diamond-jewellery', $quoteLink('Diamond rings'))],
                        ['diamond-necklace.webp', 'Diamond necklaces', 'View',       $collectionUrl('diamond-jewellery', $quoteLink('Diamond necklaces'))],
                    ];
                @endphp
                @foreach($heroTiles as [$photo, $label, $chip, $href])
                    <a class="jb-hero__tile" href="{{ $href }}">
                        <img src="{{ asset('images/site/' . $photo) }}" alt="{{ $label }}" width="450" height="450" @if($loop->index > 1) loading="lazy" @endif>
                        <span class="jb-hero__tile-label"><span>{{ $label }}</span><span class="jb-chip jb-chip--gold">{{ $chip }}</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Trust row ──────────────────────────────────── --}}
    <section class="jb-wrap">
        <div class="jb-trust">
            <div class="jb-trust__item">
                <span class="jb-trust__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg></span>
                <span><p class="jb-trust__title">Bengaluru office</p><p class="jb-trust__text">{{ $hours !== '' ? $hours : 'Visit us in person' }}</p></span>
            </div>
            <div class="jb-trust__item">
                <span class="jb-trust__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                <span><p class="jb-trust__title">Rates twice a day</p><p class="jb-trust__text">Morning and evening sessions</p></span>
            </div>
            <div class="jb-trust__item">
                <span class="jb-trust__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M6 16l3-5 4 3 5-8"/></svg></span>
                <span><p class="jb-trust__title">Every purity priced</p><p class="jb-trust__text">24K–10K gold, 999–800 silver</p></span>
            </div>
            <div class="jb-trust__item">
                <span class="jb-trust__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h18M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18M12 3l9 5H3l9-5Z"/></svg></span>
                <span><p class="jb-trust__title">Listed bank accounts</p><p class="jb-trust__text">Pay only to our published accounts</p></span>
            </div>
        </div>
    </section>

    {{-- ─── Shop by category ─────────────────────────── --}}
    <section class="jb-section" id="categories" style="scroll-margin-top: calc(var(--jb-header-h) + 8px);">
        <div class="jb-wrap">
            <div class="jb-section__head">
                <div>
                    <p class="jb-eyebrow">Collections</p>
                    <h2 class="jb-section__title">Shop by category</h2>
                </div>
            </div>
            @include('partials.collection-cards')
        </div>
    </section>

    {{-- ─── Price by weight ────────────────────────────── --}}
    <section class="jb-section" id="by-weight" style="scroll-margin-top: calc(var(--jb-header-h) + 8px);">
        <div class="jb-wrap">
            <div class="jb-section__head">
                <div>
                    <p class="jb-eyebrow">{{ $rate ? 'Live prices' : 'Prices' }}</p>
                    <h2 class="jb-section__title">Price by weight</h2>
                    <p class="jb-section__sub">Metal value at our latest published rate. Making charges and GST are extra; message us for a full quote.</p>
                </div>
                <a class="jb-link jb-md-up" href="{{ route('live-rates') }}">Full rate table →</a>
            </div>

            @if($rate)
            <div class="jb-shop" data-shop>
                <aside class="jb-filters" aria-label="Filters">
                    <div class="jb-filter-group">
                        <p class="jb-filter-group__title">Metal</p>
                        <div class="jb-filter-list">
                            <button type="button" class="jb-pill" data-filter="metal" data-value="gold" aria-pressed="true">Gold <span class="jb-muted">10</span></button>
                            <button type="button" class="jb-pill" data-filter="metal" data-value="silver" aria-pressed="false">Silver <span class="jb-muted">10</span></button>
                        </div>
                    </div>
                    <div class="jb-filter-group">
                        <p class="jb-filter-group__title">Purity</p>
                        <div class="jb-filter-list">
                            @foreach($goldPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="gold" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $key }} <span class="jb-muted">{{ $fine }}</span></button>
                            @endforeach
                            @foreach($silverPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="silver" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" hidden>{{ MetalRate::SILVER_PURITY[$key]['label'] }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="jb-filter-group">
                        <p class="jb-filter-group__title">Form</p>
                        <div class="jb-filter-list">
                            <button type="button" class="jb-pill" data-filter="form" data-value="all" aria-pressed="true">Coins &amp; bars</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="coin" aria-pressed="false">Coins</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="bar" aria-pressed="false">Bars</button>
                        </div>
                    </div>
                </aside>

                <div>
                    <div class="jb-toolbar">
                        <div class="jb-scroll-x jb-purity-pills">
                            <button type="button" class="jb-pill" data-filter="metal" data-value="gold" aria-pressed="true">Gold</button>
                            <button type="button" class="jb-pill" data-filter="metal" data-value="silver" aria-pressed="false">Silver</button>
                            @foreach($goldPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="gold" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $key }}</button>
                            @endforeach
                            @foreach($silverPurities as $key => $fine)
                                <button type="button" class="jb-pill" data-filter="purity" data-metal="silver" data-value="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" hidden>{{ $key }}</button>
                            @endforeach
                            <button type="button" class="jb-pill" data-filter="form" data-value="all" aria-pressed="true">All</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="coin" aria-pressed="false">Coins</button>
                            <button type="button" class="jb-pill" data-filter="form" data-value="bar" aria-pressed="false">Bars</button>
                        </div>
                        <span data-count>10 weights</span>
                    </div>

                    <div class="jb-grid jb-grid--wide" data-grid>
                        @foreach($pieces as [$metal, $form, $grams])
                            @php
                                $purity = $metal === 'gold' ? '24K' : '999';
                                $fine   = $metal === 'gold' ? $goldPurities['24K'] : '999';
                                $weight = $weightLabel($grams);
                                $title  = ($metal === 'gold' ? 'Gold ' : 'Silver ') . $form;
                            @endphp
                            <article class="jb-card" data-metal="{{ $metal }}" data-form="{{ $form }}" data-grams="{{ $grams }}" @if($metal !== 'gold') hidden @endif>
                                <a class="jb-card__media" href="{{ route('live-rates') . ($metal === 'gold' ? '#gold-rate' : '#silver-rate') }}" tabindex="-1" aria-hidden="true">
                                    <img src="{{ asset('images/site/' . $cardPhotos[$metal . '-' . $form]) }}" alt="" width="480" height="480" loading="lazy">
                                    <span class="jb-card__badge jb-chip jb-chip--gold" data-purity-label>{{ $purity }}</span>
                                    <span class="jb-card__weight">{{ $weight }}</span>
                                </a>
                                <div class="jb-card__body">
                                    <h3 class="jb-card__name">{{ $title }} · {{ $weight }}</h3>
                                    <p class="jb-card__meta"><span data-purity-label>{{ $purity }}</span><span>·</span><span>{{ $form === 'coin' ? 'Coin' : 'Minted bar' }}</span></p>
                                    <p class="jb-card__price" data-price>{{ $inr($rate->priceFor($metal, $purity, $grams)) }}</p>
                                    <p class="jb-card__sub">metal value · excl. GST</p>
                                    <a class="jb-btn jb-btn--primary jb-card__cta" data-enquire href="{{ $quoteLink("{$weight} {$purity} {$metal} {$form}") }}">Get a quote</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
                <div class="jb-receipt"><p class="jb-muted">Prices by weight will appear here as soon as today's rate is published.</p></div>
            @endif
        </div>
    </section>

    {{-- ─── Services ───────────────────────────────────── --}}
    @if($services->isNotEmpty())
    <section class="jb-section" id="services">
        <div class="jb-wrap">
            <div class="jb-section__head">
                <div>
                    <p class="jb-eyebrow">What we do</p>
                    <h2 class="jb-section__title">Our services</h2>
                </div>
                <a class="jb-link" href="{{ route('services.index') }}">All services →</a>
            </div>
            <div class="jb-services">
                @foreach($services->take(3) as $service)
                    <article class="jb-service">
                        <div class="jb-service__img">
                            @if($service->image)<img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">@endif
                            <span class="jb-service__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="jb-service__body">
                            <h3>{{ $service->title }}</h3>
                            @if(filled($service->description))
                                <p>{{ Str::limit(strip_tags($service->description), 150) }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Rates today (summary) ──────────────────────── --}}
    @if($rate)
    <section class="jb-section" id="rates-today">
        <div class="jb-wrap">
            <div class="jb-receipt">
                <div class="jb-section__head">
                    <div>
                        <p class="jb-eyebrow">Reference rates · Bengaluru</p>
                        <h2 class="jb-section__title">Gold and silver rates today</h2>
                    </div>
                    <a class="jb-link jb-md-up" href="{{ route('live-rates') }}">Charts &amp; history →</a>
                </div>
                <p class="jb-muted">
                    Our {{ $rate->session }} rate for {{ $published->format('j F Y') }} puts 24K gold at
                    <strong>{{ $inr($rate->goldRate('24K', 10)) }}</strong> per 10 grams and 22K gold at
                    <strong>{{ $inr($rate->goldRate('22K', 10)) }}</strong> per 10 grams, with 999 silver at
                    <strong>{{ $inr($rate->silverRate(1000)) }}</strong> per kilogram. Lower purities are worked out from the same rate by their metal content.
                    The full table, with ounce and tola prices, is on the <a class="jb-link" href="{{ route('live-rates') }}">live rates</a> page.
                </p>
                <div class="jb-table-wrap">
                    <table class="jb-table">
                        <thead>
                            <tr><th scope="col">Metal</th><th scope="col">1 g</th><th scope="col">10 g</th><th scope="col">100 g</th><th scope="col">1 kg</th></tr>
                        </thead>
                        <tbody>
                            @foreach([['gold', '24K', 'Gold 24K'], ['gold', '22K', 'Gold 22K'], ['gold', '18K', 'Gold 18K'], ['silver', '999', 'Silver 999'], ['silver', '925', 'Silver 925']] as [$metal, $purity, $label])
                                <tr>
                                    <td>{{ $label }}</td>
                                    @foreach([1, 10, 100, 1000] as $g)
                                        <td class="{{ $g === 10 ? 'jb-num-gold' : '' }}">{{ $inr($rate->priceFor($metal, $purity, $g)) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Buying guide ───────────────────────────────── --}}
    <section class="jb-section" id="guide">
        <div class="jb-wrap">
            <p class="jb-eyebrow">Buying guide</p>
            <h2 class="jb-section__title">Before you buy gold or silver</h2>
            <div class="jb-guide">
                <div>
                    <div class="jb-guide__img"><img src="{{ asset('images/site/gold-rings-wide.webp') }}" alt="" loading="lazy"></div>
                    <div class="jb-guide__body">
                    <span class="jb-guide__step">Step 1 · Purity</span>
                    <h3>24K, 22K or 18K?</h3>
                    <p>24K is 99.9% gold and is what coins and bars are made of. 22K (91.6%) is the usual standard for jewellery, and 18K (75%) is harder, so it holds stones well. The price follows the gold content.</p>
                    </div>
                </div>
                <div>
                    <div class="jb-guide__img"><img src="{{ asset('images/site/guide-hallmark.webp') }}" alt="" loading="lazy"></div>
                    <div class="jb-guide__body">
                    <span class="jb-guide__step">Step 2 · Hallmark</span>
                    <h3>Look for the hallmark</h3>
                    <p>Hallmarked gold in India carries the BIS mark, the purity grade and a six-character HUID code. You can check any HUID yourself in the government's BIS CARE app.</p>
                    </div>
                </div>
                <div>
                    <div class="jb-guide__img"><img src="{{ asset('images/site/gold-necklace-wide.webp') }}" alt="" loading="lazy"></div>
                    <div class="jb-guide__body">
                    <span class="jb-guide__step">Step 3 · Price</span>
                    <h3>How the final price is built</h3>
                    <p>Metal value at the day's rate for the weight and purity, plus making charges where they apply, plus 3% GST. The prices on this page are the metal value only.</p>
                    </div>
                </div>
                <div>
                    <div class="jb-guide__img"><img src="{{ asset('images/site/silver-jewellery-wide.webp') }}" alt="" loading="lazy"></div>
                    <div class="jb-guide__body">
                    <span class="jb-guide__step">Step 4 · Silver</span>
                    <h3>Silver, by the kilo</h3>
                    <p>Silver is quoted per kilogram. Bars and coins are usually 999 fine; 925 sterling is used for jewellery and articles. Small pieces carry higher making charges per gram.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── Latest updates (Admin → Updates) ──────────────── --}}
    @php $latestUpdates = \App\Models\Blog::active()->ordered()->take(3)->get(); @endphp
    @if($latestUpdates->isNotEmpty())
    <section class="jb-section jb-section--white" id="updates">
        <div class="jb-wrap">
            <div class="jb-section__head">
                <div>
                    <p class="jb-eyebrow">From our desk</p>
                    <h2 class="jb-section__title">Latest updates</h2>
                </div>
                <a class="jb-link" href="{{ route('updates') }}">All updates →</a>
            </div>
            <div class="jb-news">
                @foreach($latestUpdates as $update)
                    <a class="jb-news__item" href="{{ route('updates.show', $update->slug) }}">
                        <div class="jb-news__img">
                            <img src="{{ $update->image ? asset('storage/' . $update->image) : asset('images/site/gold-bars-2-wide.webp') }}" alt="" loading="lazy">
                        </div>
                        <div class="jb-news__body">
                            <span class="jb-guide__step">{{ $update->created_at?->format('j M Y') }}</span>
                            <h3 style="margin-top: 6px;">{{ $update->title }}</h3>
                            @if(filled($update->description))<p>{{ Str::limit(strip_tags($update->description), 110) }}</p>@endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ─── Contact band ───────────────────────────────── --}}
    <section class="jb-section">
        <div class="jb-wrap">
            <div class="jb-band">
                <div>
                    <p class="jb-eyebrow">Talk to our desk</p>
                    <h2 class="jb-section__title">Get a firm quote before you pay.</h2>
                    <p class="jb-muted" style="margin-top: 10px; max-width: 560px;">Send us the metal, purity and weight you need. We'll confirm today's price and the payment details, and you pay only into the accounts listed on our Bank Details page.</p>
                    <div class="jb-band__actions">
                        <a class="jb-btn jb-btn--primary jb-btn--lg" href="{{ $quoteLink() }}">Send an enquiry</a>
                        <a class="jb-btn jb-btn--wa jb-btn--lg" href="{{ $waLink('Hi, I would like a gold/silver quote.') }}" target="_blank" rel="noopener">WhatsApp us</a>
                        @if($phone !== '')<a class="jb-btn jb-btn--ghost jb-btn--lg" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">Call {{ $phone }}</a>@endif
                        <a class="jb-btn jb-btn--ghost jb-btn--lg" href="{{ route('bank-details') }}">Bank details</a>
                    </div>
                </div>
                <div class="jb-band__card">
                    @if($address !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                        <div><strong>Our office</strong><span>{!! nl2br(e($address)) !!}</span></div>
                    </div>
                    @endif
                    @if($hours !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        <div><strong>Working hours</strong><span>{{ $hours }}</span></div>
                    </div>
                    @endif
                    @if($email !== '')
                    <div class="jb-band__row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <div><strong>Email</strong><a href="mailto:{{ $email }}" style="word-break: break-all;">{{ $email }}</a></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ─── FAQ ────────────────────────────────────────── --}}
    <section class="jb-section" id="faq">
        <div class="jb-wrap">
            <p class="jb-eyebrow">Good to know</p>
            <h2 class="jb-section__title">Common questions</h2>
            <div class="jb-faq">
                <details>
                    <summary>How often are the rates updated?</summary>
                    <p>We publish a morning rate and an evening rate on working days. The time of the latest update is shown in the strip at the top of this page.</p>
                </details>
                <details>
                    <summary>Are the prices on this page final?</summary>
                    <p>No. They are the value of the metal at our published rate. Making charges, where they apply, and 3% GST are added to your final quote.</p>
                </details>
                <details>
                    <summary>Why are 22K and 18K cheaper than 24K?</summary>
                    <p>They contain less gold. We work them out from the 24K rate by gold content: 22K is 22/24 of the 24K price and 18K is 18/24.</p>
                </details>
                <details>
                    <summary>How do I place an order?</summary>
                    <p>Press <a class="jb-link" href="{{ $quoteLink() }}">Get a quote</a> on any item, or send an enquiry from our Contact page, with the metal, purity and weight you want. You can also WhatsApp us{{ $phone !== '' ? ' or call ' . $phone : '' }}. We'll confirm the price and how to pay.</p>
                </details>
                <details>
                    <summary>How do I pay?</summary>
                    <p>By bank transfer to the accounts on our <a class="jb-link" href="{{ route('bank-details') }}">Bank Details</a> page. Never pay into an account that is not listed there.</p>
                </details>
                @if($address !== '')
                <details>
                    <summary>Can I visit you?</summary>
                    <p>Yes. Our office is at {{ preg_replace('/\s*\n\s*/', ' ', $address) }}{{ $hours !== '' ? ' (' . $hours . ')' : '' }}.</p>
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
                document.getElementById('by-weight').scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Links elsewhere on the page that jump to the grid with a metal (and coin/bar) chosen
        var subnavPills = document.querySelectorAll('.jb-subnav .jb-pill');
        document.querySelectorAll('[data-jump-metal]').forEach(function (link) {
            link.addEventListener('click', function () {
                state.metal = link.getAttribute('data-jump-metal');
                state.form = link.getAttribute('data-jump-form') || 'all';
                state.grams = null;
                render();
                subnavPills.forEach(function (pill) {
                    pill.setAttribute('aria-current', String(pill === link));
                });
            });
        });

        render();
    })();
</script>
@endpush
@endif
