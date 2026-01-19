@extends('layouts.app')

@section('title', 'Distributor Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Distributor Login
                    </h3>
                </div>

                <div class="card-body p-5">
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

                <div class="card-footer text-center bg-light py-3">
                    <small class="text-muted">
                        Distributor Login – Restricted Access
                    </small>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Store
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

    // Mobile number formatting (10 digits)
    $('#mobile').on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    // Password visibility toggle
    $('.password-toggle').on('click', function () {
        const input = $('#password');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

});
</script>
@endpush
@endsection
