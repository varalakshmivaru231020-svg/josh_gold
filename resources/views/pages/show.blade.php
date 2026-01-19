@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container py-5">
    <h1>{{ $page->title }}</h1>
    <hr>

    {{-- If content contains safe HTML --}}
    <div class="page-content">
        {!! $page->content !!}
    </div>
</div>
@endsection
