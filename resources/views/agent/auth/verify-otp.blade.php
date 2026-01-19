@extends('layouts.app')

@section('title', 'Verify OTP - Agent')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-key me-2"></i>Verify OTP</h3>
                </div>

                <div class="card-body p-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('agent.verify-otp.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label" for="otp">Enter OTP</label>
                            <input type="text" name="otp" id="otp" class="form-control" maxlength="4" required autofocus autocomplete="off" pattern="\d{4}" title="Enter 4 digit OTP">
                            @error('otp')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Verify</button>

                        <div class="text-center mt-4">
                            <a href="{{ route('agent.resend-otp') }}">Resend OTP</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
