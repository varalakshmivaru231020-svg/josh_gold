{{--
    Price table for one metal. Expects: $rate, $metal ('gold'|'silver'),
    $rows ([purity => label]), $weights ([grams => column label]), optional $highlight (grams column in gold)
--}}
@php $highlight = $highlight ?? null; @endphp
<div class="jb-table-wrap">
    <table class="jb-table">
        <thead>
            <tr>
                <th scope="col">{{ $metal === 'gold' ? 'Gold purity' : 'Silver grade' }}</th>
                @foreach($weights as $label)<th scope="col">{{ $label }}</th>@endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $purity => $label)
                <tr>
                    <td>{{ $label }}</td>
                    @foreach($weights as $grams => $colLabel)
                        <td class="{{ $grams == $highlight ? 'jb-num-gold' : '' }}">{{ \App\Support\RateBook::inr($rate->priceFor($metal, $purity, $grams)) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
