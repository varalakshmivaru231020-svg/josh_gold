{{--
    City cards linking to the city rate pages. Expects: $rate, $cities, $metal ('gold'|'silver'), optional $except (city key)
--}}
@php use App\Support\RateBook; @endphp
<div class="jb-citygrid">
    @foreach($cities as $key => [$name, $state])
        @continue(isset($except) && $except === $key)
        @php [$stateSlug, $citySlug] = explode('/', $key); @endphp
        <a class="jb-citycard" href="{{ route('rates.city', ['state' => $stateSlug, 'city' => $citySlug]) }}">
            <span class="jb-citycard__name">{{ $name }}</span>
            <span class="jb-citycard__state">{{ $state }}</span>
            @if($metal === 'gold')
                <span class="jb-citycard__row"><span>24K · 10 g</span><b>{{ RateBook::inr($rate->goldRate('24K', 10)) }}</b></span>
                <span class="jb-citycard__row"><span>22K · 10 g</span><b>{{ RateBook::inr($rate->goldRate('22K', 10)) }}</b></span>
            @else
                <span class="jb-citycard__row"><span>999 · 1 kg</span><b>{{ RateBook::inr($rate->silverRate(1000)) }}</b></span>
                <span class="jb-citycard__row"><span>999 · 10 g</span><b>{{ RateBook::inr($rate->silverRate(10)) }}</b></span>
            @endif
        </a>
    @endforeach
</div>
<p class="jb-muted jb-note">We publish one reference rate for every city. Local jewellers add their own premium, making charges and GST.</p>
