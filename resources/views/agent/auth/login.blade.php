@extends('layouts.lawfirm')

@section('title', 'Distributor Login - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <h3 class="mb-0">
                            <i class="fas fa-user-tie me-2"></i>
                            Distributor Login
                        </h3>
                    </div>

                    <div class="auth-body">
                    <form method="POST" action="{{ route('agent.login') }}">
                        @csrf
                        
                        {{-- Mobile Number --}}
                        <div class="mb-4">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input
                                    type="text"
                                    id="mobile"
                                    name="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}"
                                    required
                                    autofocus
                                >
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                >
                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                        </div>

                        {{-- Links --}}
                        <div class="text-center mt-4">
                            <a href="{{ route('agent.forgot-password') }}" class="text-muted">
                                Forgot Password?
                            </a>
                            <span class="mx-2">|</span>
                            <a href="{{ route('agent.register') }}" class="text-primary">
                                New Distributor? Register
                            </a>
                        </div>
                    </form>
                    </div>

                    <div class="auth-footer">
                        <small>Distributor Login – Restricted Access</small>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('products.index') }}" class="back-link">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Store
                    </a>
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

.auth-body .btn-outline-secondary {
    border-color: rgba(201, 162, 39, 0.3);
    color: var(--navy-deep);
}

.auth-body a {
    color: var(--gold-dark);
}

.auth-body a:hover {
    color: var(--gold-primary);
}

.auth-footer {
    background: rgba(26, 35, 50, 0.03);
    padding: 1rem 2rem;
    text-align: center;
    color: var(--text-muted);
}

.back-link {
    color: var(--navy-deep);
    text-decoration: none;
}

.back-link:hover {
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile number formatting (10 digits)
    var mobileInput = document.getElementById('mobile');
    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // Password visibility toggle
    var toggleBtn = document.querySelector('.password-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            var input = document.getElementById('password');
            var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
});
</script>
@endpush
