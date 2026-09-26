@extends('admin.layouts.app')

@section('title', 'Add Bank Account')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add Bank Account</h1>
    <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bank Details
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.bank-accounts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.bank-accounts._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Bank Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
