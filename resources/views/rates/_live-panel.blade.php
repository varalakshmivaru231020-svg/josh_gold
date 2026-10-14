{{--
    Rates panel beside the live charts (/charts and /charts/{slug}), styled like allindiabullion.com:
    each price sits in a blue box when it last went up and a red box when it went down.
      - Spot products: live international prices (public/js/live-spot.js polls /live-rates/spot); a box blinks on every move.
      - Gold and silver products: our published rate (Admin → Live Rates), coloured by the change since our previous rate.
    Expects $rate (latest MetalRate or null).
--}}
@php
    use App\Support\RateBook;

    $spot = \Illuminate\Support\Facades\Cache::get('spot.prices.last', []);
    $spotFmt = fn ($v, $f) => $v === null ? '—' : match ($f) {
        'usd2' => number_format($v, 2), 'usd3' => number_format($v, 3), 'fx' => number_format($v, 2), default => RateBook::inr($v),
    };
    $spotTiles = [
        ['GLD $',        'xau_usd',       'usd2n'],
        ['SLR $',        'xag_usd',       'usd3n'],
        ['$ vs ₹',       'usd_inr',       'fxn'],
        ['GLD ₹ · 10 g', 'gold_inr_10g',  'inrn'],
        ['SLR ₹ · kg',   'silver_inr_kg', 'inrn'],
    ];

    $gst = 1.03;
    $groups = [];
    if ($rate) {
        $prev = $rate->getPreviousRate();
        $dir = function (string $metal) use ($rate, $prev) {
            $c = RateBook::sessionChange($rate, $prev, $metal);
            return $c === null || abs($c) < 0.005 ? '' : ($c > 0 ? 'is-up' : 'is-down');
        };
        $g = $rate->gold_999_rate * 10;
        $s = $rate->silver_999_rate * 1000;
        $g22 = $rate->goldRate('22K', 10);
        $groups = [
            'Gold products · 10 g' => [$dir('gold'), [
                ['Retail 995', $g * .995], ['995 with GST', $g * .995 * $gst],
                ['Retail 999', $g],        ['999 with GST', $g * $gst],
                ['22K (916)',  $g22],      ['22K with GST', $g22 * $gst],
            ]],
            'Silver products · kg' => [$dir('silver'), [
                ['Retail 999', $s], ['999 with GST', $s * $gst],
            ]],
        ];
    }
    $digits = fn (float $v) => ltrim(RateBook::inr($v), '₹');
@endphp
<aside class="lc-rates lp" aria-label="Rates">
    <h2 class="lc-rates__title">Spot products</h2>
    <div class="lp-tiles lp-tiles--3">
        @foreach($spotTiles as [$label, $key, $fmt])
            <div class="lp-tile" data-spot-item>
                <span class="lp-tile__label">{{ $label }}</span>
                <span class="lp-tile__value" data-spot="{{ $key }}" data-format="{{ $fmt }}" data-value="{{ $spot[$key] ?? '' }}">{{ $spotFmt($spot[$key] ?? null, rtrim($fmt, 'n')) }}</span>
            </div>
        @endforeach
    </div>

    @if($rate)
        @foreach($groups as $title => [$dirClass, $tiles])
            <h2 class="lc-rates__title">{{ $title }}</h2>
            <div class="lp-tiles">
                @foreach($tiles as [$label, $value])
                    <div class="lp-tile {{ $dirClass }}">
                        <span class="lp-tile__label">{{ $label }}</span>
                        <span class="lp-tile__value">{{ $digits($value) }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
        <p class="lc-note">
            <span class="lp-key lp-key--up"></span> up <span class="lp-key lp-key--down"></span> down.
            Spot products are live international prices (spot in ₹ is before import duty and GST).
            Gold and silver products are our rates, published {{ $rate->published_at->format('d M Y, g:i A') }}@if($rate->is_sample)<span class="lc-sample">Sample data</span>@endif;
            “with GST” adds 3%, making charges extra. <a href="{{ route('contact') }}">Ask for a quote</a>.
        </p>
    @else
        <p class="lc-note">Our gold and silver rates will appear here once they are published.</p>
    @endif
</aside>
