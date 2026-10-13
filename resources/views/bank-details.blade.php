@extends('layouts.lawfirm')

@section('title', 'Bank Details | Josha Gold and Diamond Jewellery')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Payments',
    'title'   => 'Bank',
    'accent'  => 'Details',
    'sub'     => 'Use the account details below for bank transfers and UPI payments.',
    'crumbs'  => [['Bank Details']],
])

<section class="bank-page">
    <div class="jb-wrap bank-body">
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
    .bank-page { padding-bottom: 56px; }
    .bank-body { padding-top: 32px; }
    @media (min-width: 768px) { .bank-body { padding-top: 48px; } }

    .bank-card {
        position: relative; overflow: hidden; padding: 28px 26px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, #fffaf0, #fff 40%); box-shadow: var(--jb-shadow);
        transition: border-color .2s, box-shadow .2s;
    }
    .bank-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: var(--jb-gold-band);
    }
    .bank-card:hover { border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
    .bank-card-head { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
    .bank-card-head i {
        width: 44px; height: 44px; flex: none; display: grid; place-items: center;
        border: 1px solid #ecd49c; border-radius: 12px; background: #fbf1da;
        color: var(--jb-gold-strong); font-size: 1.1rem;
    }
    .bank-card-head h3 { margin: 0; font-family: var(--jb-serif); font-size: 21px; font-weight: 600; color: var(--jb-ink); }
    .bank-rows { margin: 0; }
    .bank-row {
        display: flex; justify-content: space-between; align-items: center; gap: 16px;
        padding: 12px 0; border-bottom: 1px dashed var(--jb-line);
    }
    .bank-row:last-child { border-bottom: 0; }
    .bank-row dt { margin: 0; color: var(--jb-muted); font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .bank-row dd { margin: 0; color: var(--jb-ink); font-weight: 600; text-align: right; word-break: break-word; font-variant-numeric: tabular-nums; }
    .bank-copy {
        margin-left: 8px; padding: 2px 6px; border: 0; border-radius: 6px;
        background: none; color: var(--jb-gold-strong); cursor: pointer; transition: background-color .2s, color .2s;
    }
    .bank-copy:hover { background: #fbf1da; color: var(--jb-gold-deep); }
    .bank-copy.copied { color: var(--jb-up); }
    .bank-qr { margin-top: 22px; padding-top: 22px; border-top: 1px dashed var(--jb-line); text-align: center; }
    /* QR codes need a light background to scan */
    .bank-qr img { width: 100%; max-width: 180px; padding: 8px; border: 1px solid var(--jb-line); border-radius: 12px; background: #fff; box-shadow: var(--jb-shadow); }
    .bank-qr small { display: block; margin-top: 8px; color: var(--jb-muted); }
    @media (max-width: 420px) {
        .bank-card { padding: 22px 18px; }
        .bank-row { align-items: flex-start; gap: 10px; }
    }
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
