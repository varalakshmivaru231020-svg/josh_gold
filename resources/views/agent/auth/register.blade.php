@extends('layouts.lawfirm')

@section('title', 'Distributor Registration - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="auth-card">
                    <div class="auth-header">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Distributor Registration
                        </h4>
                    </div>

                    <div class="auth-body">
                    <form id="registrationForm" method="POST" action="{{ route('agent.register') }}">
                        @csrf

                        {{-- Name & Mobile --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mobile" class="form-label">Mobile Number *</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input
                                        type="text"
                                        id="mobile"
                                        name="mobile"
                                        maxlength="10"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        value="{{ old('mobile') }}"
                                        required
                                    >
                                    <button type="button" class="btn btn-outline-primary" id="sendOtpBtn">
                                        Send OTP
                                    </button>
                                </div>
                                @error('mobile')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email & OTP --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address (Optional)</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="otp" class="form-label">OTP *</label>
                                <input
                                    type="text"
                                    id="otp"
                                    name="otp"
                                    maxlength="4"
                                    class="form-control @error('otp') is-invalid @enderror"
                                    required
                                >
                                @error('otp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Passwords --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the
                                <a href="{{ url('/terms-conditions') }}">Terms and Conditions</a>
                                and
                                <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Register as Distributor
                            </button>

                            <a href="{{ route('agent.login') }}" class="btn btn-outline-primary">
                                Already have an account? Login
                            </a>
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
    border-bottom: 3px solid var(--gold-primary);
}

.auth-header h4 {
    color: var(--cream-primary);
    font-family: var(--font-heading);
}

.auth-body {
    padding: 2rem;
}

.auth-body .form-label {
    color: var(--navy-deep);
    font-weight: 500;
}

.auth-body .form-control {
    border: 2px solid rgba(201, 162, 39, 0.2);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.auth-body .form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.15);
}

.auth-body .input-group-text {
    background: var(--cream-primary);
    border: 2px solid rgba(201, 162, 39, 0.2);
    border-right: none;
    color: var(--navy-deep);
}

.auth-body .input-group .form-control {
    border-left: none;
}

.auth-body .input-group .btn-outline-primary {
    background: var(--gold-primary);
    border-color: var(--gold-primary);
    color: var(--navy-deep);
    font-weight: 600;
}

.auth-body .input-group .btn-outline-primary:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.auth-body .btn-primary {
    background: var(--gold-primary);
    border-color: var(--gold-primary);
    color: var(--navy-deep);
    font-weight: 600;
    padding: 0.75rem 1.5rem;
}

.auth-body .btn-primary:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.auth-body .btn-outline-primary {
    border-color: var(--gold-primary);
    color: var(--navy-deep);
}

.auth-body .btn-outline-primary:hover {
    background: var(--gold-primary);
    color: var(--navy-deep);
}

.auth-body a {
    color: var(--gold-dark);
}

.auth-body a:hover {
    color: var(--gold-primary);
}

.auth-body .form-check-input:checked {
    background-color: var(--gold-primary);
    border-color: var(--gold-primary);
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

@push('scripts')
<script>
document.getElementById('sendOtpBtn').addEventListener('click', function () {

    const mobile = document.getElementById('mobile').value;
    const btn = this;

    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10-digit mobile number');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';

    fetch('{{ route("agent.send.otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mobile })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('OTP sent successfully');
            startCountdown(btn);
        } else {
            alert(data.message || 'Failed to send OTP');
            resetBtn(btn);
        }
    })
    .catch(() => {
        alert('Failed to send OTP. Try again.');
        resetBtn(btn);
    });
});

function startCountdown(btn) {
    let timeLeft = 60;
    const timer = setInterval(() => {
        btn.innerText = `Resend in ${timeLeft--}s`;
        if (timeLeft < 0) {
            clearInterval(timer);
            resetBtn(btn);
        }
    }, 1000);
}

function resetBtn(btn) {
    btn.disabled = false;
    btn.innerText = 'Send OTP';
}

// Mobile auto-format
document.getElementById('mobile').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
});
</script>
@endpush
