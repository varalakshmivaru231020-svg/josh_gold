@extends('admin.layouts.app')

@section('title', 'Add City')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add City</h1>
    <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Cities
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.cities.store') }}" method="POST">
            @csrf
            @include('admin.cities._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save City
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
