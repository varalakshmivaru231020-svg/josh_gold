@extends('layouts.app')

@section('title', 'Reset Password - Distributor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-unlock me-2"></i>
                        Reset Password
                    </h3>
                </div>

                <div class="card-body p-5">

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agent.reset-password.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">New Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Reset Password
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('agent.login') }}">
                                Back to Login
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
