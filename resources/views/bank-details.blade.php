@extends('layouts.lawfirm')

@section('title', 'Bank Details | Josha Infinity Inc Service')

@section('content')
<section class="bank-page">
    <div class="bank-hero">
        <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
            <span class="bank-eyebrow">PAYMENTS</span>
            <h1 class="bank-heading">Bank Details</h1>
            <div class="bank-separator mx-auto"></div>
            <p class="bank-sub">Use the account details below for bank transfers and UPI payments.</p>
        </div>
    </div>

    <div class="container bank-body">
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Bank Details</span>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($accounts as $index => $account)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ min($index * 80, 400) }}">
                <div class="bank-card h-100">
                    <div class="bank-card-head">
                        <i class="fas fa-building-columns"></i>
                        <h3>{{ $account->bank_name }}</h3>
                    </div>

                    <dl class="bank-rows">
                        <div class="bank-row">
                            <dt>Account Holder</dt>
                            <dd>{{ $account->account_holder }}</dd>
                        </div>
                        <div class="bank-row">
                            <dt>Account Number</dt>
                            <dd>
                                <span>{{ $account->account_number }}</span>
                                <button type="button" class="bank-copy" data-copy="{{ $account->account_number }}" title="Copy account number"><i class="far fa-copy"></i></button>
                            </dd>
                        </div>
                        <div class="bank-row">
                            <dt>IFSC Code</dt>
                            <dd>
                                <span>{{ $account->ifsc_code }}</span>
                                <button type="button" class="bank-copy" data-copy="{{ $account->ifsc_code }}" title="Copy IFSC code"><i class="far fa-copy"></i></button>
                            </dd>
                        </div>
                        @if($account->branch)
                        <div class="bank-row">
                            <dt>Branch</dt>
                            <dd>{{ $account->branch }}</dd>
                        </div>
                        @endif
                        @if($account->account_type)
                        <div class="bank-row">
                            <dt>Account Type</dt>
                            <dd>{{ $account->account_type }}</dd>
                        </div>
                        @endif
                        @if($account->upi_id)
                        <div class="bank-row">
                            <dt>UPI ID</dt>
                            <dd>
                                <span>{{ $account->upi_id }}</span>
                                <button type="button" class="bank-copy" data-copy="{{ $account->upi_id }}" title="Copy UPI ID"><i class="far fa-copy"></i></button>
                            </dd>
                        </div>
                        @endif
                    </dl>

                    @if($account->qr_image)
                    <div class="bank-qr">
                        <img src="{{ asset('storage/' . $account->qr_image) }}" alt="Payment QR for {{ $account->bank_name }}" loading="lazy">
                        <small>Scan to pay</small>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <i class="fas fa-building-columns fa-3x text-gold mb-4 d-block opacity-50"></i>
                <h3 class="text-navy">Bank Details Coming Soon</h3>
                <p class="text-muted">Please contact us for payment details.</p>
                <a href="{{ route('contact') }}" class="btn btn-outline-navy rounded-pill px-4 py-2 mt-2">Contact Us</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .bank-page { background: var(--cream); min-height: 80vh; padding-bottom: 100px; }
    .bank-hero {
        background: var(--navy); padding: 150px 0 90px; position: relative; overflow: hidden;
    }
    .bank-hero::before {
        content: ''; position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    .bank-eyebrow {
        display: block; font-size: 0.75rem; letter-spacing: 4px; font-weight: 800;
        color: var(--gold); text-transform: uppercase; margin-bottom: 16px;
    }
    .bank-heading {
        font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.2rem);
        color: #fff; font-weight: 700; margin-bottom: 20px;
    }
    .bank-separator { width: 60px; height: 3px; background: var(--gold); margin-bottom: 24px; border-radius: 2px; }
    .bank-sub { max-width: 620px; margin: 0 auto; color: rgba(255,255,255,0.75); font-size: 1.05rem; line-height: 1.7; }
    .bank-body { padding-top: 60px; }
    .breadcrumb-luxury { display: flex; align-items: center; gap: 12px; font-size: .85rem; letter-spacing: 1px; text-transform: uppercase; font-weight: 600; }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration: none; transition: color .3s; }
    .breadcrumb-luxury a:hover { color: var(--navy); }
    .breadcrumb-luxury .sep { opacity: .3; }

    .bank-card {
        background: #fff; border: 1px solid #e8e8e8; border-radius: 16px; padding: 32px 30px;
        position: relative; overflow: hidden; transition: all 0.35s ease;
    }
    .bank-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--gold), transparent);
    }
    .bank-card:hover { box-shadow: 0 20px 50px rgba(0,0,0,0.09); border-color: rgba(201,168,76,0.4); }
    .bank-card-head { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
    .bank-card-head i { font-size: 1.6rem; color: var(--gold-dark); }
    .bank-card-head h3 {
        font-family: var(--font-display); font-size: 1.45rem; font-weight: 700;
        color: var(--navy); margin: 0;
    }
    .bank-rows { margin: 0; }
    .bank-row {
        display: flex; justify-content: space-between; align-items: center; gap: 16px;
        padding: 12px 0; border-bottom: 1px dashed #e6e1d4;
    }
    .bank-row:last-child { border-bottom: 0; }
    .bank-row dt { font-size: 0.78rem; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); font-weight: 600; margin: 0; }
    .bank-row dd { margin: 0; font-weight: 600; color: var(--navy); text-align: right; word-break: break-word; }
    .bank-copy {
        background: none; border: 0; color: var(--gold-dark); margin-left: 8px; padding: 2px 6px;
        border-radius: 6px; cursor: pointer; transition: background 0.2s;
    }
    .bank-copy:hover { background: rgba(201,168,76,0.15); }
    .bank-copy.copied { color: #198754; }
    .bank-qr { text-align: center; margin-top: 22px; padding-top: 22px; border-top: 1px dashed #e6e1d4; }
    .bank-qr img { max-width: 180px; width: 100%; border-radius: 10px; border: 1px solid #e8e8e8; padding: 6px; background: #fff; }
    .bank-qr small { display: block; margin-top: 8px; color: var(--text-muted); }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.bank-copy').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy');
            var done = function () {
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(function () {
                    btn.classList.remove('copied');
                    btn.innerHTML = '<i class="far fa-copy"></i>';
                }, 1500);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done);
            } else {
                var ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                done();
            }
        });
    });
</script>
@endpush
