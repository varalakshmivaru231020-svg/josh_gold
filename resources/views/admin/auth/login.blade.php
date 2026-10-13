@extends('layouts.lawfirm')

@section('title', 'Admin Login')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <span class="auth-icon" aria-hidden="true"><i class="fas fa-lock"></i></span>
                        <h1 class="auth-title">Admin Login</h1>
                    </div>
                    <div class="auth-body">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group @error('email') has-validation @enderror">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group @error('password') has-validation @enderror">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword" aria-label="Show or hide password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <div class="auth-links mt-4">
                            <a href="#" class="auth-links__quiet">Forgot Password?</a>
                        </div>
                    </form>
                    </div>

                    <div class="auth-footer">
                        <small>Restricted Access - Admin Only</small>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('products.index') }}" class="back-link">
                        <i class="fas fa-arrow-left me-1"></i> Back to Store
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
});
</script>
@endpush
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
.auth-body .password-toggle {
    min-width: 52px;
    --bs-btn-bg: var(--jb-ground); --bs-btn-color: var(--jb-muted); --bs-btn-border-color: var(--jb-line-strong);
    --bs-btn-hover-bg: var(--jb-panel-2); --bs-btn-hover-color: var(--jb-gold-light); --bs-btn-hover-border-color: var(--jb-line-strong);
    --bs-btn-active-bg: var(--jb-panel-2); --bs-btn-active-color: var(--jb-gold-light); --bs-btn-active-border-color: var(--jb-line-strong);
}
.auth-body .btn-primary { min-height: 50px; font-size: 15px; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); }

.auth-body a:not(.btn) { color: var(--jb-gold-light); font-weight: 600; text-decoration: none; }
.auth-body a:not(.btn):hover { color: var(--jb-white); text-decoration: underline; text-underline-offset: 3px; }
.auth-links { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 6px 12px; font-size: 14px; }
.auth-links > span { color: var(--jb-line-strong); }
.auth-body a.auth-links__quiet { color: var(--jb-muted); }

.auth-footer {
    padding: 14px 24px; border-top: 1px solid var(--jb-line-soft); background: rgba(0, 0, 0, .35);
    color: var(--jb-muted); text-align: center; font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
}
.auth-footer small { font-size: inherit; }

.back-link { color: var(--jb-muted); font-size: 14px; font-weight: 600; text-decoration: none; }
.back-link:hover { color: var(--jb-gold-light); }

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
