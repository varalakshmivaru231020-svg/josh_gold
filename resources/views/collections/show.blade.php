{{--
    One collection page (/collections/{slug}), managed in Admin → Collections.
    Bullion: prices by weight at the latest published rate, with purity pills.
    Jewellery: intro, gallery and today's gold rate, with an enquiry button.
    Expects: $collection, $rate (latest MetalRate or null), $others (the other active collections).
    Styles: public/css/collections.css (plus rate-pages.css for the "last published" line).
--}}
@extends('layouts.lawfirm')

@php
    use App\Models\Collection;
    use App\Support\RateBook;

    $c         = $collection;
    $isBullion = $c->isBullion();
    $metal     = (string) $c->metal;
    $metalName = Collection::METALS[$metal] ?? ucfirst($metal);
    $purities  = $c->purity_list;
    if (! $purities) {
        $purities = $isBullion
            ? [$metal === 'silver' ? '999' : '24K']
            : (Collection::DEFAULT_JEWELLERY_PURITIES[$metal] ?? ['22K', '18K']);
    }
    $purityText = fn (string $p) => $p . (($note = Collection::purityNote($metal, $p)) !== '' ? ' (' . $note . ')' : '');

    $weights   = $isBullion ? $c->weight_list : [];
    $form      = (string) $c->form;
    $formName  = strtolower(Collection::FORMS[$form] ?? $form);
    $pieceName = $metalName . ' ' . $formName;                 // "Gold coin"

    // Paragraphs of the longer text (blank lines separate them)
    $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', trim((string) $c->body)))));

    // Bullion: the lowest metal value on the page (smallest weight at the lowest listed purity)
    $fromPrice = null;
    if ($isBullion && $rate && $weights) {
        $fromPurity = end($purities);
        $fromWeight = collect($weights)->sortBy('grams')->first();
        $fromPrice  = [$rate->priceFor($metal, $fromPurity, $fromWeight['grams']), $fromWeight, $fromPurity];
    }

    $sizesText = '';
    if ($weights) {
        $sorted    = collect($weights)->sortBy('grams')->values();
        $sizesText = $sorted->count() > 1
            ? $sorted->first()['label'] . ' to ' . $sorted->last()['label']
            : $sorted->first()['label'];
    }

    $title = $isBullion
        ? $c->name . ' price today by weight | ' . config('company.name')
        : $c->name . ' | ' . config('company.name');
    $description = trim((string) ($c->intro ?: $c->tagline));
@endphp

@section('title', $title)
@if($description !== '')
@section('meta_description', \Illuminate\Support\Str::limit($description, 160))
@endif

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => $c->eyebrow,
    'title'   => $c->name,
    'sub'     => $c->intro ?: $c->tagline,
    'crumbs'  => [[$c->name]],
])

<div class="jb-col">
    {{-- ─── Hero band: photo + summary ─────────────────── --}}
    <section class="jb-section jb-colhero-sec">
        <div class="jb-wrap">
            <div class="jb-colhero">
                <figure class="jb-colhero__media">
                    <img src="{{ $c->image_url }}" alt="{{ $c->name }}" width="1000" height="750" fetchpriority="high">
                    <figcaption class="jb-colhero__chips">
                        @foreach($purities as $p)
                            <span class="jb-chip jb-chip--glass">{{ $p }}</span>
                        @endforeach
                        @if($isBullion && count($weights))
                            <span class="jb-chip jb-chip--glass">{{ count($weights) }} {{ count($weights) === 1 ? 'size' : 'sizes' }}</span>
                        @endif
                    </figcaption>
                </figure>

                <div class="jb-panel jb-panel--glow jb-colhero__panel">
                    @if($isBullion)
                        <p class="jb-eyebrow">At a glance</p>
                        @if($fromPrice)
                            <p class="jb-colhero__from">From</p>
                            <p class="jb-colhero__price">{{ RateBook::inr($fromPrice[0]) }}</p>
                            <p class="jb-colhero__unit">{{ $fromPrice[1]['name'] ?? $fromPrice[1]['label'] }} · {{ $fromPrice[2] }} · metal value, excl. GST</p>
                        @else
                            <h2 class="jb-colhero__heading">{{ $c->tagline ?: $c->name }}</h2>
                        @endif

                        <dl class="jb-facts">
                            <div><dt>Metal</dt><dd>{{ $metalName }}</dd></div>
                            <div><dt>Purity</dt><dd>{{ implode(', ', array_map($purityText, $purities)) }}</dd></div>
                            @if($sizesText !== '')
                                <div><dt>Sizes</dt><dd>{{ $sizesText }} <span class="jb-muted">· {{ count($weights) }} {{ count($weights) === 1 ? 'weight' : 'weights' }}</span></dd></div>
                            @endif
                            <div><dt>Rate</dt><dd>{{ $rate ? ucfirst($rate->session) . ' rate, ' . $rate->published_at->format('j M Y') : 'Not published yet' }}</dd></div>
                        </dl>

                        <div class="jb-colhero__cta">
                            @if($rate && $weights)
                                <a class="jb-btn jb-btn--primary jb-btn--lg" href="#prices">See prices by weight</a>
                            @endif
                            <a class="jb-btn jb-btn--ghost jb-btn--lg" href="{{ route('contact', ['item' => $c->name]) }}">Ask for a quote</a>
                        </div>
                    @else
                        <p class="jb-eyebrow">Today's {{ strtolower($metalName) }} rate</p>
                        @if($rate)
                            <table class="jb-ratepanel">
                                <thead>
                                    <tr><th scope="col">Purity</th><th scope="col">Per gram</th><th scope="col">Per 10 g</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($purities as $p)
                                        <tr>
                                            <th scope="row">{{ $p }} <span>{{ Collection::purityNote($metal, $p) }}</span></th>
                                            <td>{{ RateBook::inr($rate->priceFor($metal, $p, 1)) }}</td>
                                            <td><b>{{ RateBook::inr($rate->priceFor($metal, $p, 10)) }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p class="jb-colhero__note">
                                {{ ucfirst($rate->session) }} rate, {{ $rate->published_at->format('j M Y, g:i A') }} IST.
                                @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data</span>@endif
                                Metal value only; making charges, stones and GST are extra.
                            </p>
                        @else
                            <p class="jb-colhero__note">Today's rate will appear here as soon as it is published.</p>
                        @endif

                        <div class="jb-colhero__cta">
                            <a class="jb-btn jb-btn--primary jb-btn--lg" href="{{ route('contact', ['item' => $c->name]) }}">Enquire about {{ $c->name }}</a>
                            <a class="jb-btn jb-btn--ghost jb-btn--lg" href="{{ route($metal === 'silver' ? 'rates.silver' : 'rates.gold') }}">{{ $metalName }} rate today</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($isBullion)
        {{-- ─── Prices by weight ───────────────────────── --}}
        <section class="jb-section" id="prices">
            <div class="jb-wrap">
                <div class="jb-section__head jb-colprices__head">
                    <div>
                        <p class="jb-eyebrow">{{ $rate ? 'Live prices' : 'Prices' }}</p>
                        <h2 class="jb-section__title">{{ $c->name }} by weight</h2>
                        <p class="jb-section__sub">Metal value at our latest published rate. Making charges and GST are extra; ask our desk for a full quote.</p>
                    </div>
                    @if($rate && count($purities) > 1 && $weights)
                        <div class="jb-colprices__pills" role="group" aria-label="Purity">
                            @foreach($purities as $p)
                                <button type="button" class="jb-pill" data-purity="{{ $p }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $p }} @if(($note = Collection::purityNote($metal, $p)) !== '')<span class="jb-muted">{{ $note }}</span>@endif
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($rate && $weights)
                    @include('rates._meta')

                    <div class="jb-grid jb-colgrid jb-colgrid--{{ min(count($weights), 9) }}" data-col-prices>
                        @foreach($weights as $w)
                            @php
                                $prices = [];
                                $quotes = [];
                                foreach ($purities as $p) {
                                    $prices[$p] = RateBook::inr($rate->priceFor($metal, $p, $w['grams']));
                                    $quotes[$p] = route('contact', ['item' => trim("{$w['label']}" . ($w['name'] ? " ({$w['name']})" : '') . " {$p} {$metal} {$form}")]);
                                }
                                $first = $purities[0];
                            @endphp
                            <article class="jb-card" data-prices='@json($prices)' data-quotes='@json($quotes)'>
                                <div class="jb-card__media">
                                    <img src="{{ $c->image_url }}" alt="" width="480" height="480" loading="lazy">
                                    <span class="jb-card__badge jb-chip jb-chip--gold" data-purity-label>{{ $first }}</span>
                                    <span class="jb-card__weight">{{ $w['name'] ?? $w['label'] }}</span>
                                </div>
                                <div class="jb-card__body">
                                    <h3 class="jb-card__name">{{ $pieceName }} · {{ $w['name'] ?? $w['label'] }}</h3>
                                    <p class="jb-card__meta"><span data-purity-label>{{ $first }}</span><span>·</span><span>{{ $w['name'] ? $w['label'] : ucfirst($formName) }}</span></p>
                                    <p class="jb-card__price" data-price>{{ $prices[$first] }}</p>
                                    <p class="jb-card__sub">metal value · excl. GST</p>
                                    <a class="jb-btn jb-btn--primary jb-card__cta" data-quote href="{{ $quotes[$first] }}">Get a quote</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="jb-receipt jb-colempty">
                        <p class="jb-muted">
                            @if(! $weights)
                                Sizes for {{ strtolower($c->name) }} are not listed yet. Ask our desk for the weights available and a price.
                            @else
                                Prices for {{ strtolower($c->name) }} will appear here as soon as today's rate is published.
                            @endif
                        </p>
                        <a class="jb-btn jb-btn--primary" href="{{ route('contact', ['item' => $c->name]) }}">Ask for a quote</a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ─── About (longer text, optional) ─────────────── --}}
    @if($paragraphs)
        <section class="jb-section">
            <div class="jb-wrap">
                <div class="jb-colabout">
                    <div>
                        <p class="jb-eyebrow">About the collection</p>
                        <h2 class="jb-section__title">{{ $c->name }}</h2>
                    </div>
                    <div class="jb-colabout__text">
                        @foreach($paragraphs as $para)
                            <p>{!! nl2br(e($para)) !!}</p>
                        @endforeach
                        @unless($isBullion)
                            <a class="jb-link" href="{{ route('contact', ['item' => $c->name]) }}">Enquire about {{ $c->name }} →</a>
                        @endunless
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ─── Gallery (jewellery) ───────────────────────── --}}
    @php $gallery = $isBullion ? [] : $c->gallery_urls; @endphp
    @if($gallery)
        <section class="jb-section">
            <div class="jb-wrap">
                <div class="jb-section__head">
                    <div>
                        <p class="jb-eyebrow">Gallery</p>
                        <h2 class="jb-section__title">{{ $c->name }} ideas</h2>
                        <p class="jb-section__sub">Photos are for reference. Share a photo of any design you like when you enquire.</p>
                    </div>
                </div>
                <div class="jb-gallery jb-gallery--{{ min(count($gallery), 4) }}">
                    @foreach($gallery as $src)
                        <figure class="jb-gallery__item">
                            <img src="{{ $src }}" alt="{{ $c->name }}, photo {{ $loop->iteration }}" loading="lazy">
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ─── FAQ ────────────────────────────────────────── --}}
    <section class="jb-section">
        <div class="jb-wrap">
            <p class="jb-eyebrow">Good to know</p>
            <h2 class="jb-section__title">{{ $c->name }} questions</h2>
            <div class="jb-faq">
                @if($isBullion)
                    @php
                        // "What does 24K mean?" / "What do 24K and 22K mean?", from the purities on this page
                        $purityQuestion = count($purities) > 1
                            ? 'What do ' . implode(', ', array_slice($purities, 0, -1)) . ' and ' . end($purities) . ' mean?'
                            : 'What does ' . $purities[0] . ($metal === 'silver' ? ' silver' : '') . ' mean?';
                    @endphp
                    @if($metal === 'silver')
                        <details>
                            <summary>{{ $purityQuestion }}</summary>
                            <p>Silver purity is given in parts per thousand. 999 (fine) silver is 99.9% pure; 925 (sterling) silver is 92.5% silver, with the rest usually copper for strength. Lower purities are priced from the 999 rate by their silver content.</p>
                        </details>
                    @else
                        <details>
                            <summary>{{ $purityQuestion }}</summary>
                            <p>Karat measures how much of the metal is gold. 24K is 99.9% pure gold (999); 22K is 91.6% gold (916), with other metals added for strength. Lower purities are priced from the 24K rate by their gold content.</p>
                        </details>
                    @endif
                    <details>
                        <summary>Is the price shown the final price?</summary>
                        <p>No. It is the value of the {{ $metal }} only, at our latest published rate. A {{ $formName }} can carry a minting or making charge that depends on the piece, so ask our desk for the full price before you pay.</p>
                    </details>
                    <details>
                        <summary>Is GST charged on {{ strtolower($c->name) }}?</summary>
                        <p>Yes. GST of 3% applies to {{ $metal }} {{ $formName }}s in India and is added to the metal value shown here.</p>
                    </details>
                @else
                    <details>
                        <summary>What is a hallmark and HUID?</summary>
                        <p>A hallmark is the BIS purity mark on gold jewellery, for example 22K916 or 18K750. Each hallmarked piece also carries a six-character HUID code that identifies it, which you can look up in the BIS CARE app. Ask to see the HUID on any piece before you buy.</p>
                    </details>
                    <details>
                        <summary>How are making charges worked out?</summary>
                        <p>Making charges pay for the work that goes into a design. They are quoted either per gram or as a percentage of the gold value and vary from piece to piece, so ask for them as a separate line in your quote.</p>
                    </details>
                    <details>
                        <summary>Is the rate on this page the jewellery price?</summary>
                        <p>No. It is the value of the {{ $metal }} only. A jewellery price adds making charges, stones where there are any, and 3% GST.</p>
                    </details>
                @endif
            </div>
        </div>
    </section>

    {{-- ─── Other collections ─────────────────────────── --}}
    @if($others->isNotEmpty())
        <section class="jb-section">
            <div class="jb-wrap">
                <div class="jb-section__head">
                    <div>
                        <p class="jb-eyebrow">Collections</p>
                        <h2 class="jb-section__title">Other collections</h2>
                    </div>
                </div>
                @include('partials.collection-cards', ['items' => $others, 'variant' => 'strip'])
            </div>
        </section>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
@pushOnce('styles', 'collections-css')
<link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
@endPushOnce

@if($isBullion && $rate && count($purities) > 1 && $weights)
@push('scripts')
<script>
    // Purity pills: swap each card's price, purity label and quote link (all worked out on the server)
    (function () {
        var grid = document.querySelector('[data-col-prices]');
        var pills = Array.prototype.slice.call(document.querySelectorAll('.jb-colprices__pills [data-purity]'));
        if (!grid || !pills.length) return;

        var cards = Array.prototype.slice.call(grid.querySelectorAll('.jb-card')).map(function (card) {
            return {
                el: card,
                prices: JSON.parse(card.getAttribute('data-prices')),
                quotes: JSON.parse(card.getAttribute('data-quotes'))
            };
        });

        function show(purity) {
            pills.forEach(function (pill) {
                pill.setAttribute('aria-pressed', String(pill.getAttribute('data-purity') === purity));
            });
            cards.forEach(function (card) {
                card.el.querySelector('[data-price]').textContent = card.prices[purity];
                card.el.querySelector('[data-quote]').href = card.quotes[purity];
                card.el.querySelectorAll('[data-purity-label]').forEach(function (el) { el.textContent = purity; });
            });
        }

        pills.forEach(function (pill) {
            pill.addEventListener('click', function () { show(pill.getAttribute('data-purity')); });
        });
    })();
</script>
@endpush
@endif
