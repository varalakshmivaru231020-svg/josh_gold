@extends('layouts.app')

@section('title', 'Forgot Password - Distributor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Forgot Password
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

                    <form method="POST" action="{{ route('agent.forgot-password.sendOtp') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Registered Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input
                                    type="text"
                                    name="mobile"
                                    class="form-control"
                                    maxlength="10"
                                    required
                                >
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Send OTP
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
