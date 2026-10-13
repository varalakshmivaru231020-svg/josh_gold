@extends('layouts.lawfirm')

@section('title', 'Sitemap | ' . config('company.name'))
@section('meta_description', 'Every page on the ' . config('company.name') . ' website, in one place.')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Sitemap',
    'title'   => 'Every page,',
    'accent'  => 'in one place',
    'sub'     => 'Rates, city pages, guides and company information. Search engines can use our sitemap.xml.',
    'crumbs'  => [['Sitemap']],
])

<section class="jb-section">
    <div class="jb-wrap">
        @include('partials.sitemap-links')
        <p class="jb-muted mt-4 mb-0" style="font-size: 13px;">For search engines: <a class="jb-link" href="{{ route('sitemap.xml') }}">sitemap.xml</a></p>
    </div>
</section>
@endsection
