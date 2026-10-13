@extends('layouts.lawfirm')

@section('title', 'Knowledge — gold, silver and jewellery explained | ' . config('company.name'))
@section('meta_description', 'Plain-language guides to gold purity, hallmarks and HUID, how jewellery prices are built, silver grades and bullion weights.')

{{-- $articles: active KnowledgeArticle rows in order (Admin → Knowledge) --}}

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Knowledge',
    'title'   => 'Gold, silver and jewellery,',
    'accent'  => 'explained.',
    'sub'     => 'Short, plain-language guides to purity, hallmarks, pricing and weights, so you know exactly what you are paying for.',
    'crumbs'  => [['Knowledge']],
])

<section class="jb-section">
    <div class="jb-wrap">
        @if($articles->isNotEmpty())
            <div class="jb-kgrid">
                @foreach($articles as $article)
                    <article class="jb-kcard">
                        <span class="jb-chip jb-chip--gold">{{ $article->tag }}</span>
                        <h2>{{ $article->title }}</h2>
                        @if(filled($article->intro))<p>{{ $article->intro }}</p>@endif
                        @if($points = $article->pointList())
                            <ul>
                                @foreach($points as $point)<li>{{ $point }}</li>@endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="jb-panel"><p class="jb-muted mb-0">Our guides will appear here soon.</p></div>
        @endif

        <div class="jb-receipt" style="margin-top: 22px;">
            <p class="jb-eyebrow">Still have a question?</p>
            <h2 class="jb-section__title" style="margin-bottom: 12px;">Ask our desk</h2>
            <div class="d-flex flex-wrap gap-2">
                <a class="jb-btn jb-btn--primary" href="{{ route('rates.gold') }}">Today's gold rate</a>
                <a class="jb-btn jb-btn--ghost" href="{{ route('news') }}">News &amp; updates</a>
                <a class="jb-btn jb-btn--ghost" href="{{ route('contact') }}">Contact us</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
