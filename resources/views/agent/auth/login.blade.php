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
    text-align: center;
    border-bottom: 2px solid var(--gold);
}

.auth-header h3 {
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

.auth-body .btn-outline-secondary {
    border-color: rgba(12, 20, 40, 0.1);
    color: var(--navy);
}

.auth-body a {
    color: var(--gold-dark);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.auth-body a:hover {
    color: var(--gold);
}

.auth-footer {
    background: var(--navy);
    padding: 1rem 2rem;
    text-align: center;
    color: rgba(255,255,255,0.5);
    font-size: 0.8rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.back-link {
    color: var(--navy);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.back-link:hover {
    color: var(--gold);
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
