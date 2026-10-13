@extends('layouts.lawfirm')

@section('title', 'Verify OTP - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <span class="auth-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                        <h1 class="auth-title">Verify OTP</h1>
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

/* Messages */
.auth-body .alert { padding: 12px 14px; border-radius: var(--jb-r-ctl); font-size: 14px; }
.auth-body .alert-danger { background: rgba(255, 112, 112, .08); border-color: rgba(255, 112, 112, .4); color: #ffb4b4; }
.auth-body .alert-success { background: rgba(92, 227, 168, .08); border-color: rgba(92, 227, 168, .35); color: #a9f0cf; }

/* The 4-digit code */
.auth-body #otp { text-align: center; font-size: 24px; font-weight: 600; letter-spacing: .5em; text-indent: .5em; font-variant-numeric: tabular-nums; }
.auth-body .text-danger { color: var(--bs-form-invalid-color) !important; font-size: 13.5px; }

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
