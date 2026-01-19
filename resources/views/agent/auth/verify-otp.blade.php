@extends('layouts.lawfirm')

@section('title', 'Verify OTP - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <h3 class="mb-0"><i class="fas fa-key me-2"></i>Verify OTP</h3>
                    </div>

                    <div class="auth-body">
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
</section>
@endsection

@push('styles')
<style>
.auth-section {
    margin-top: 80px;
    min-height: calc(100vh - 80px);
    background: var(--cream-primary);
    padding: 40px 0;
}

.auth-card {
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow-medium);
    overflow: hidden;
}

.auth-header {
    background: var(--navy-deep);
    color: var(--cream-primary);
    padding: 1.5rem 2rem;
    text-align: center;
    border-bottom: 3px solid var(--gold-primary);
}

.auth-header h3 {
    color: var(--cream-primary);
    font-family: var(--font-heading);
}

.auth-body {
    padding: 2.5rem;
}

.auth-body .form-label {
    color: var(--navy-deep);
    font-weight: 500;
}

.auth-body .form-control {
    border: 2px solid rgba(201, 162, 39, 0.2);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    text-align: center;
    font-size: 1.5rem;
    letter-spacing: 0.5rem;
}

.auth-body .form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.15);
}

.auth-body .btn-primary {
    background: var(--gold-primary);
    border-color: var(--gold-primary);
    color: var(--navy-deep);
    font-weight: 600;
}

.auth-body .btn-primary:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.auth-body a {
    color: var(--gold-dark);
}

.auth-body a:hover {
    color: var(--gold-primary);
}

@media (max-width: 767px) {
    .auth-section {
        margin-top: 70px;
        padding: 20px 0;
    }

    .auth-body {
        padding: 1.5rem;
    }
}
</style>
@endpush
