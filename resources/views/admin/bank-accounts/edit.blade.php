@extends('admin.layouts.app')

@section('title', 'Edit Bank Account')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Bank Account</h1>
    <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bank Details
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.bank-accounts.update', $account) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.bank-accounts._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Bank Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
