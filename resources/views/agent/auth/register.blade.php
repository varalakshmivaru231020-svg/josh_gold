@extends('layouts.lawfirm')

@section('title', 'Distributor Registration - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="auth-card">
                    <div class="auth-header">
                        <span class="auth-icon" aria-hidden="true"><i class="fas fa-user-plus"></i></span>
                        <h1 class="auth-title">Distributor Registration</h1>
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
/* Centred dark card on the black page, with a soft gold glow behind it */
.auth-section {
    display: flex;
    align-items: center;
    min-height: calc(100vh - var(--jb-header-h));
    padding: 48px 0 64px;
    background:
        radial-gradient(640px 460px at 50% 40%, rgba(201, 163, 92, .17), transparent 70%),
        radial-gradient(700px 420px at 100% 0%, rgba(141, 107, 34, .08), transparent 65%),
        var(--jb-ground);
}
.auth-section > .container { width: 100%; }

.auth-card {
    overflow: hidden;
    border: 1px solid var(--jb-line);
    border-radius: var(--jb-r-card);
    background: linear-gradient(180deg, rgba(201, 163, 92, .07), transparent 140px), var(--jb-panel);
    box-shadow: 0 28px 60px -32px rgba(0, 0, 0, .9);
}

.auth-header { position: relative; padding: 32px 28px 22px; text-align: center; }
.auth-header::after {
    content: ""; position: absolute; left: 14%; right: 14%; bottom: 0; height: 1px;
    background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent);
}
.auth-icon {
    width: 46px; height: 46px; margin: 0 auto 14px; display: grid; place-items: center;
    border: 1px solid var(--jb-line); border-radius: 13px; background: #0a0a0b;
    color: var(--jb-gold-light); font-size: 18px;
}
.auth-title { margin: 0; color: var(--jb-white); font: 600 clamp(24px, 3vw, 28px)/1.2 var(--jb-serif); letter-spacing: -.01em; }

.auth-body { padding: 26px 32px 30px; }
.auth-body .form-label { margin-bottom: 6px; color: var(--jb-text); font-size: 13px; font-weight: 600; letter-spacing: .02em; }
.auth-body .form-control { min-height: 48px; padding: .7rem 1rem; font-size: 15px; background-color: var(--jb-ground); border-color: var(--jb-line-strong); color: var(--jb-white); }
.auth-body .form-control:focus { background-color: #000; border-color: rgba(234, 215, 166, .6); box-shadow: 0 0 0 .2rem rgba(234, 215, 166, .14); }
.auth-body .form-control.is-invalid { border-color: var(--bs-form-invalid-border-color); }
.auth-body .form-control.is-invalid:focus { box-shadow: 0 0 0 .2rem rgba(255, 112, 112, .18); }
.auth-body .input-group-text { min-width: 56px; justify-content: center; background: var(--jb-panel-2); border-color: var(--jb-line-strong); color: var(--jb-gold-light); font-weight: 600; }
.auth-body .input-group > .btn { border-radius: var(--jb-r-ctl); }
.auth-body .btn-primary { min-height: 50px; font-size: 15px; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); }

.auth-body a:not(.btn) { color: var(--jb-gold-light); font-weight: 600; text-decoration: none; }
.auth-body a:not(.btn):hover { color: var(--jb-white); text-decoration: underline; text-underline-offset: 3px; }

/* Send OTP, inside the mobile field */
.auth-body #sendOtpBtn {
    min-width: 112px; padding: 0 14px; font-size: 13px; white-space: nowrap;
    --bs-btn-bg: rgba(201, 163, 92, .14); --bs-btn-color: var(--jb-gold-light); --bs-btn-border-color: var(--jb-line-strong);
    --bs-btn-hover-bg: rgba(201, 163, 92, .26); --bs-btn-hover-color: #fff; --bs-btn-hover-border-color: rgba(234, 215, 166, .5);
    --bs-btn-active-bg: rgba(201, 163, 92, .3); --bs-btn-active-color: #fff; --bs-btn-active-border-color: rgba(234, 215, 166, .5);
    --bs-btn-disabled-bg: rgba(201, 163, 92, .1); --bs-btn-disabled-color: var(--jb-gold-light); --bs-btn-disabled-border-color: var(--jb-line-strong); --bs-btn-disabled-opacity: .8;
}

/* Terms checkbox */
.auth-body .form-check-input { width: 1.15em; height: 1.15em; margin-top: .2em; background-color: var(--jb-ground); border-color: #6b5f48; }
.auth-body .form-check-input:focus { border-color: rgba(234, 215, 166, .6); box-shadow: 0 0 0 .2rem rgba(234, 215, 166, .14); }
.auth-body .form-check-input:checked { --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23111' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e"); }
.auth-body .form-check-label { color: var(--jb-text); font-size: 14px; line-height: 1.55; }

/* "Already have an account? Login" */
.auth-body .d-grid .btn-outline-primary { min-height: 46px; font-size: 15px; display: inline-flex; align-items: center; justify-content: center; --bs-btn-border-color: var(--jb-line-strong); }

@media (max-width: 575.98px) {
    .auth-section {
        align-items: flex-start; padding: 28px 0 44px;
        background: radial-gradient(420px 380px at 50% 170px, rgba(201, 163, 92, .15), transparent 70%), var(--jb-ground);
    }
    .auth-header { padding: 26px 20px 18px; }
    .auth-body { padding: 22px 20px 24px; }
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
