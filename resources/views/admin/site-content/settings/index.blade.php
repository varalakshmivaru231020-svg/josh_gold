@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Site Settings</h1>
    <p class="text-muted mb-0">Logo, contact details and offices for the whole website.</p>
</div>

<ul class="nav nav-tabs mb-4 flex-nowrap overflow-auto">
    @foreach([
        'general' => ['fa-cog', 'General'],
        'contact' => ['fa-address-card', 'Contact'],
        'offices' => ['fa-building', 'Offices'],
    ] as $key => [$icon, $label])
    <li class="nav-item">
        <a class="nav-link text-nowrap {{ $tab === $key ? 'active fw-semibold' : '' }}"
           href="{{ route('admin.site-content.settings', ['tab' => $key]) }}" @if($tab === $key) aria-current="page" @endif>
            <i class="fas {{ $icon }} me-2"></i>{{ $label }}
        </a>
    </li>
    @endforeach
</ul>

@include('admin.site-content.settings._' . $tab)
@endsection
