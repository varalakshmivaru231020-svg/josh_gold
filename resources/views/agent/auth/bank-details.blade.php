@extends('layouts.app')

@section('title', 'Bank Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0"><i class="fas fa-university me-2"></i>Payment Information</h4>
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Please provide your bank details or UPI ID for receiving commissions and settlements.
                    </div>
                    
                    <form method="POST" action="{{ route('Distributor.bank-details') }}">
                        @csrf
                        
                        <h5 class="mb-3">Bank Account Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" 
                                       id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}">
                                @error('account_holder_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" inputmode="numeric" pattern="\d+" class="form-control @error('account_number') is-invalid @enderror" 
                                       id="account_number" name="account_number" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ifsc_code" class="form-label">IFSC Code</label>
                                <input type="text" maxlength="11" style="text-transform: uppercase;" pattern="[A-Z]{4}0[A-Z0-9]{6}" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                       id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}">
                                @error('ifsc_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                       id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3">OR</h5>
                        
                        <div class="mb-4">
                            <label for="upi_id" class="form-label">UPI ID</label>
                            <input type="email" class="form-control @error('upi_id') is-invalid @enderror" 
                                   id="upi_id" name="upi_id" value="{{ old('upi_id') }}" 
                                   placeholder="example@upi" aria-describedby="upiHelp">
                            @error('upi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="upiHelp" class="text-muted">Enter your UPI ID (e.g., mobilenumber@upi)</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Please ensure the information is accurate. You can update it later from your profile.
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Save & Continue
                            </button>
                            <a href="{{ route('Distributor.dashboard') }}" class="btn btn-outline-secondary">
                                Skip for now
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
