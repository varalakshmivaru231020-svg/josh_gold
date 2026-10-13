@extends('layouts.lawfirm')

@section('title', 'Bank Details - Bengaluru Law House')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="auth-card">
                    <div class="auth-header bank-header">
                        <span class="auth-icon" aria-hidden="true"><i class="fas fa-university"></i></span>
                        <h1 class="auth-title">Payment Information</h1>
                    </div>
                    <div class="auth-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Please provide your bank details or UPI ID for receiving commissions and settlements.
                    </div>

                    <form method="POST" action="{{ route('agent.bank-details') }}">
                        @csrf

                        <h5 class="mb-3">Bank Account Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror"
                                       id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}">
                                @error('account_holder_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" inputmode="numeric" pattern="\d+" class="form-control @error('account_number') is-invalid @enderror"
                                       id="account_number" name="account_number" value="{{ old('account_number') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ifsc_code" class="form-label">IFSC Code</label>
                                <input type="text" maxlength="11" style="text-transform: uppercase;" pattern="[A-Z]{4}0[A-Z0-9]{6}" class="form-control @error('ifsc_code') is-invalid @enderror"
                                       id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}">
                                @error('ifsc_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                       id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">OR</h5>

                        <div class="mb-4">
                            <label for="upi_id" class="form-label">UPI ID</label>
                            <input type="email" class="form-control @error('upi_id') is-invalid @enderror"
                                   id="upi_id" name="upi_id" value="{{ old('upi_id') }}"
                                   placeholder="example@upi" aria-describedby="upiHelp">
                            @error('upi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="upiHelp" class="text-muted">Enter your UPI ID (e.g., mobilenumber@upi)</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Please ensure the information is accurate. You can update it later from your profile.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save & Continue
                            </button>
                            <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary">
                                Skip for now
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


/* Messages */
.auth-body .alert { padding: 12px 14px; border-radius: var(--jb-r-ctl); font-size: 14px; }
.auth-body .alert-danger { background: rgba(255, 112, 112, .08); border-color: rgba(255, 112, 112, .4); color: #ffb4b4; }
.auth-body .alert-success { background: rgba(92, 227, 168, .08); border-color: rgba(92, 227, 168, .35); color: #a9f0cf; }
.auth-body .alert-info { background: rgba(255, 255, 255, .03); border-color: var(--jb-line); color: var(--jb-text); }
.auth-body .alert-info i { color: var(--jb-gold-light); }
.auth-body .alert-warning { background: rgba(255, 170, 60, .08); border-color: rgba(255, 170, 60, .35); color: #ffd59a; }

/* Section headings and divider */
.auth-body h5 { padding-bottom: 10px; border-bottom: 1px solid var(--jb-line); color: var(--jb-white); font: 600 17px/1.3 var(--jb-serif); }
.auth-body hr { height: 1px; border: 0; opacity: 1; background: linear-gradient(90deg, transparent, var(--jb-line-strong), transparent); }
.auth-body #upiHelp { display: block; margin-top: 6px; font-size: 12.5px; }

/* "Skip for now" */
.auth-body .d-grid .btn-outline-secondary { min-height: 46px; font-size: 15px; display: inline-flex; align-items: center; justify-content: center; --bs-btn-border-color: var(--jb-line-strong); }

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
