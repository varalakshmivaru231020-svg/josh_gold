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
    margin-top: 85px;
    min-height: calc(100vh - 85px);
    background: var(--cream);
    padding: 60px 0;
    display: flex;
    align-items: center;
}

.auth-card {
    background: var(--white);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    border: 1px solid rgba(201, 168, 76, 0.1);
}

.auth-header {
    background: var(--navy);
    color: var(--gold);
    padding: 2.5rem 2rem;
    border-bottom: 2px solid var(--gold);
}

.auth-header h4 {
    color: var(--gold);
    font-family: var(--font-display);
    font-weight: 700;
    letter-spacing: 1px;
}

.auth-body {
    padding: 3rem;
}

.auth-body .form-label {
    color: var(--navy);
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.auth-body .form-control {
    border: 1.5px solid rgba(12, 20, 40, 0.1);
    border-radius: 10px;
    padding: 0.9rem 1.2rem;
    font-family: var(--font-body);
    font-size: 1rem;
    background: var(--cream);
    transition: all 0.3s;
}

.auth-body .form-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 4px rgba(201, 168, 76, 0.1);
    background: var(--white);
}

.auth-body .input-group-text {
    background: var(--cream-dark);
    border: 1.5px solid rgba(12, 20, 40, 0.1);
    border-right: none;
    color: var(--navy);
    font-weight: 600;
}

.auth-body .input-group .form-control {
    border-left: none;
}

.auth-body .input-group .btn-outline-primary {
    background: var(--gold);
    border: 1.5px solid var(--gold);
    color: var(--navy);
    font-weight: 700;
    padding: 0 20px;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.auth-body .input-group .btn-outline-primary:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.auth-body .btn-primary {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border: none;
    color: var(--navy);
    font-weight: 700;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 10px 20px rgba(201, 168, 76, 0.2);
    transition: all 0.3s;
}

.auth-body .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(201, 168, 76, 0.3);
    filter: brightness(1.1);
}

.auth-body .btn-outline-primary {
    border: 2px solid var(--gold);
    color: var(--navy);
    font-weight: 700;
    border-radius: 10px;
    padding: 0.8rem;
    transition: all 0.3s;
}

.auth-body .btn-outline-primary:hover {
    background: var(--gold);
    color: var(--navy);
    transform: translateY(-2px);
}

.auth-body a {
    color: var(--gold-dark);
    font-weight: 600;
    text-decoration: none;
}

.auth-body a:hover {
    color: var(--gold);
    text-decoration: underline;
}

.auth-body .form-check-input:checked {
    background-color: var(--gold);
    border-color: var(--gold);
}

@media (max-width: 767px) {
    .auth-section {
        margin-top: 70px;
        padding: 40px 15px;
    }

    .auth-body {
        padding: 2.5rem 1.5rem;
    }

    .auth-header {
        padding: 2rem 1.5rem;
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
