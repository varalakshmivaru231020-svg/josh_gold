@extends('layouts.agent')

@section('title', 'My Profile')

@section('content')
@php
    $distributor = Auth::guard('agent')->user();
@endphp

<div class="container-fluid px-4">
    <h1 class="mt-4">My Profile</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{ route('agent.dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit me-1"></i> Profile Information
                </div>

                <div class="card-body">
                    <form action="{{ route('agent.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $distributor->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $distributor->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <input class="form-control" value="{{ $distributor->mobile }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Referral Code</label>
                                <div class="input-group">
                                    <input class="form-control" value="{{ $distributor->referral_code }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary copy-btn"
                                            data-text="{{ $distributor->referral_code }}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Payment Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Account Holder</label>
                                <input class="form-control"
                                    name="bank_details[account_holder_name]"
                                    value="{{ old('bank_details.account_holder_name', $distributor->bank_details['account_holder_name'] ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Account Number</label>
                                <input class="form-control"
                                    name="bank_details[account_number]"
                                    value="{{ old('bank_details.account_number', $distributor->bank_details['account_number'] ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>IFSC Code</label>
                                <input class="form-control"
                                    name="bank_details[ifsc_code]"
                                    value="{{ old('bank_details.ifsc_code', $distributor->bank_details['ifsc_code'] ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Bank Name</label>
                                <input class="form-control"
                                    name="bank_details[bank_name]"
                                    value="{{ old('bank_details.bank_name', $distributor->bank_details['bank_name'] ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>UPI ID</label>
                            <input class="form-control" name="upi_id"
                                value="{{ old('upi_id', $distributor->upi_id) }}">
                        </div>

                        <button class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="card mb-4 text-center">
                <div class="card-body">
                    <div class="avatar-title rounded-circle bg-light text-primary display-4 mb-2">
                        {{ strtoupper(substr($distributor->name, 0, 1)) }}
                    </div>
                    <h4>{{ $distributor->name }}</h4>
                    <small class="text-muted">Distributor</small>

                    <hr>

                    <div class="row">
                        <div class="col-6">
                            <h6>{{ $distributor->referrals->count() }}</h6>
                            <small>Referrals</small>
                        </div>
                        <div class="col-6">
                            <h6>{{ $distributor->commissions->count() }}</h6>
                            <small>Commissions</small>
                        </div>
                    </div>

                    <hr>

                    <p class="mb-1">Total: ₹{{ number_format($distributor->total_earnings, 2) }}</p>
                    <p class="text-success">Settled: ₹{{ number_format($distributor->settled_amount, 2) }}</p>
                    <p class="text-warning">Pending: ₹{{ number_format($distributor->pending_amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$('.copy-btn').click(function () {
    navigator.clipboard.writeText($(this).data('text'));
});
</script>
@endpush
@endsection
