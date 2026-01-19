@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Profile Settings</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-cog me-1"></i>
                    Profile Information
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3">Change Password</h5>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Admin Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-shield me-1"></i>
                    Admin Information
                </div>
                <div class="card-body text-center">
                    <div class="avatar-xl mb-3">
                        <div class="avatar-title bg-light text-primary rounded-circle display-4">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $admin->name }}</h4>
                    <p class="text-muted">{{ $admin->email }}</p>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="mb-0">{{ \App\Models\Order::count() }}</h6>
                            <small class="text-muted">Total Orders</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ \App\Models\Agent::count() }}</h6>
                            <small class="text-muted">Total Agents</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Tips -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-1"></i>
                    Security Tips
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use a strong, unique password
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Never share your login credentials
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Log out when not using the system
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Keep your contact information updated
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Report any suspicious activity
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Password visibility toggle
    $('.password-toggle').click(function() {
        const input = $(this).parent().find('input');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endpush
@endsection