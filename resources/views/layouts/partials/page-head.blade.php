{{--
    Heading band for inner pages, in the home-page style.
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Payments',                 // small gold label (optional)
        'title'   => 'Bank details',             // page title
        'accent'  => null,                       // optional second part of the title, shown in gold foil
        'sub'     => 'Use the accounts below…',  // optional intro line
        'crumbs'  => [['Bank details']],         // trail after Home: [label] or [label, url]
        'center'  => false,                      // centre the text
    ])
--}}
@php
    $crumbs = $crumbs ?? [[$title]];
@endphp
<section class="jb-pagehead{{ ! empty($center) ? ' jb-pagehead--center' : '' }}">
    <div class="jb-wrap">
        <nav class="jb-pagehead__crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            @foreach($crumbs as $crumb)
                <span aria-hidden="true">/</span>
                @if(isset($crumb[1]))
                    <a href="{{ $crumb[1] }}">{{ $crumb[0] }}</a>
                @else
                    <span @if($loop->last) aria-current="page" @endif>{{ $crumb[0] }}</span>
                @endif
            @endforeach
        </nav>
        @if(! empty($eyebrow))<p class="jb-eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="jb-pagehead__title">{{ $title }}@if(! empty($accent)) <span class="jb-foil">{{ $accent }}</span>@endif</h1>
        @if(! empty($sub))<p class="jb-pagehead__sub">{{ $sub }}</p>@endif
    </div>
</section>
