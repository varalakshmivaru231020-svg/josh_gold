@extends('layouts.lawfirm')

@section('title', 'Data request | JOSHA GOLD AND DIAMOND JEWELLERY')
@section('meta_description', 'Ask JOSHA GOLD AND DIAMOND JEWELLERY to show, correct or delete the personal data we hold about you, or to withdraw your consent.')

@php
    // aria-describedby for a field: its hint (if any) plus its error (when there is one)
    $describedBy = function (string $field, ?string $hint = null) use ($errors) {
        $ids = array_filter([$hint, $errors->has($field) ? "pr-{$field}-error" : null]);
        return $ids ? 'aria-describedby="' . implode(' ', $ids) . '"' : '';
    };
@endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Privacy',
    'title'   => 'Data',
    'accent'  => 'request',
    'sub'     => 'Ask us to show, correct or delete the personal data we hold about you, or to withdraw your consent.',
    'crumbs'  => [['Privacy Policy', url('/privacy-policy')], ['Data request']],
])

<section class="pr-page">
    <div class="jb-wrap pr-body">
        <div class="row g-4 g-lg-5">

            {{-- Request form --}}
            <div class="col-lg-7">
                <div class="pr-card" id="request-form">
                    <h2 class="pr-card__title">Make a request</h2>
                    <p class="pr-muted">Fields marked <span class="pr-req" aria-hidden="true">*</span><span class="visually-hidden">with an asterisk</span> are required.</p>

                    @if(session('success'))
                        <div class="pr-alert pr-alert--success" role="status" tabindex="-1" id="pr-status">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="pr-alert pr-alert--error" role="alert" tabindex="-1" id="pr-status">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            <div>
                                <strong>Please check the form:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('privacy.request.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pr-field">
                                <label for="pr-name" class="pr-label">Full name <span class="pr-req" aria-hidden="true">*</span></label>
                                <input type="text" id="pr-name" name="name" class="pr-input @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required maxlength="255" autocomplete="name"
                                       @error('name') aria-invalid="true" @enderror {!! $describedBy('name') !!}>
                                @error('name')<p class="pr-error" id="pr-name-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 pr-field">
                                <label for="pr-email" class="pr-label">Email address <span class="pr-req" aria-hidden="true">*</span></label>
                                <input type="email" id="pr-email" name="email" class="pr-input @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                                       @error('email') aria-invalid="true" @enderror {!! $describedBy('email', 'pr-email-hint') !!}>
                                <p class="pr-hint" id="pr-email-hint">We will reply to this address.</p>
                                @error('email')<p class="pr-error" id="pr-email-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 pr-field">
                                <label for="pr-phone" class="pr-label">Phone number <span class="pr-optional">(optional)</span></label>
                                <input type="tel" id="pr-phone" name="phone" class="pr-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" maxlength="20" autocomplete="tel"
                                       @error('phone') aria-invalid="true" @enderror {!! $describedBy('phone') !!}>
                                @error('phone')<p class="pr-error" id="pr-phone-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 pr-field">
                                <label for="pr-request_type" class="pr-label">What would you like us to do? <span class="pr-req" aria-hidden="true">*</span></label>
                                <select id="pr-request_type" name="request_type" class="pr-input pr-select @error('request_type') is-invalid @enderror" required
                                        @error('request_type') aria-invalid="true" @enderror {!! $describedBy('request_type') !!}>
                                    <option value="" @selected(old('request_type') === null || old('request_type') === '')>Choose a request</option>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('request_type') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('request_type')<p class="pr-error" id="pr-request_type-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="pr-field">
                            <label for="pr-details" class="pr-label">Details <span class="pr-optional">(optional)</span></label>
                            <textarea id="pr-details" name="details" rows="5" maxlength="2000" class="pr-input @error('details') is-invalid @enderror"
                                      @error('details') aria-invalid="true" @enderror {!! $describedBy('details', 'pr-details-hint') !!}>{{ old('details') }}</textarea>
                            <p class="pr-hint" id="pr-details-hint">For example, what needs correcting, or roughly when you contacted us. Please do not include card numbers or passwords.</p>
                            @error('details')<p class="pr-error" id="pr-details-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="pr-field">
                            <div class="pr-check @error('confirm') is-invalid @enderror">
                                <input type="checkbox" id="pr-confirm" name="confirm" value="1" required @checked(old('confirm'))
                                       @error('confirm') aria-invalid="true" @enderror {!! $describedBy('confirm') !!}>
                                <label for="pr-confirm">I confirm I am the person this request is about, or authorised to act for them. <span class="pr-req" aria-hidden="true">*</span></label>
                            </div>
                            @error('confirm')<p class="pr-error" id="pr-confirm-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="jb-btn jb-btn--primary jb-btn--lg pr-submit">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Send request
                        </button>
                        <p class="pr-hint pr-after">We use these details only to deal with your request. See our <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>.</p>
                    </form>
                </div>
            </div>

            {{-- Rights and what happens next --}}
            <div class="col-lg-5">
                <aside class="pr-side" aria-labelledby="pr-rights-title">
                    <h2 class="pr-side__title" id="pr-rights-title">Your rights</h2>
                    <p>As provided under India's Digital Personal Data Protection Act, 2023 and applicable law, you can ask us to:</p>
                    <dl class="pr-rights">
                        <div><dt>Access</dt><dd>tell you what personal data we hold about you and how we use it</dd></div>
                        <div><dt>Correct</dt><dd>correct, complete or update it</dd></div>
                        <div><dt>Delete</dt><dd>erase it, unless the law requires us to keep it</dd></div>
                        <div><dt>Withdraw consent</dt><dd>stop using data you agreed to share</dd></div>
                        <div><dt>Other</dt><dd>for example a complaint (grievance) or nominating someone to act for you</dd></div>
                    </dl>

                    <h3 class="pr-side__sub">What happens next</h3>
                    <ol class="pr-steps">
                        <li>Your request goes straight to our team.</li>
                        <li>We may ask you to verify your identity, so that we never show or change someone else's data.</li>
                        <li>We reply by email within <mark class="tbc">[To confirm: response time]</mark>.</li>
                    </ol>

                    @if($email !== '' || $phone !== '')
                        <h3 class="pr-side__sub">Prefer to email or call?</h3>
                        <ul class="pr-contact">
                            @if($email !== '')<li><i class="fas fa-envelope" aria-hidden="true"></i> <a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                            @if($phone !== '')<li><i class="fas fa-phone-alt" aria-hidden="true"></i> <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></li>@endif
                        </ul>
                    @endif

                    <p class="pr-side__foot">Read more in our <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> and <a href="{{ url('/cookie-policy') }}">Cookie Policy</a>.</p>
                </aside>
            </div>

        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pr-page { padding-bottom: 56px; }
    .pr-body { padding-top: 32px; }
    @media (min-width: 768px) { .pr-body { padding-top: 48px; } }

    .pr-card, .pr-side {
        position: relative; height: 100%; padding: 36px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow-lg);
    }
    .pr-card { background: linear-gradient(180deg, #fffaf0, #fff 40%); }
    .pr-card::before {
        content: ''; position: absolute; top: -1px; left: 18%; right: 18%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--jb-gold), transparent);
    }
    .pr-side {
        background: radial-gradient(420px 220px at 100% 0%, rgba(214, 178, 95, .22), transparent 65%), linear-gradient(180deg, #fffaf0, #fbf1da);
        color: var(--jb-text); font-size: 15px; line-height: 1.7;
    }
    .pr-card__title, .pr-side__title { margin: 0 0 6px; font-family: var(--jb-serif); font-size: 24px; font-weight: 600; color: var(--jb-ink); }
    .pr-side__title { margin-bottom: 12px; }
    .pr-side__sub { margin: 24px 0 10px; font-family: var(--jb-serif); font-size: 18px; font-weight: 600; color: var(--jb-ink); }
    .pr-muted { margin: 0 0 22px; color: var(--jb-muted); font-size: 14px; }

    /* Fields */
    .pr-field { margin-bottom: 20px; }
    .pr-label { display: block; margin-bottom: 7px; color: var(--jb-ink); font-size: 14px; font-weight: 700; }
    .pr-req { color: var(--jb-down); }
    .pr-optional { color: var(--jb-muted); font-weight: 400; }
    .pr-input {
        display: block; width: 100%; padding: 12px 16px;
        background: #fff; border: 1px solid var(--jb-line-strong); border-radius: var(--jb-r-ctl);
        color: var(--jb-ink); font: 15px/1.5 var(--jb-sans);
        transition: border-color .15s, box-shadow .15s;
    }
    .pr-select {
        appearance: none; -webkit-appearance: none; padding-right: 40px; cursor: pointer;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' fill='none' stroke='%238a6516' stroke-width='1.8'/%3E%3C/svg%3E") no-repeat right 16px center;
    }
    .pr-input:focus { outline: none; border-color: var(--jb-gold); box-shadow: 0 0 0 .2rem rgba(201, 163, 92, .25); }
    .pr-input.is-invalid { border-color: var(--jb-down); }
    .pr-hint { margin: 6px 0 0; color: var(--jb-muted); font-size: 13px; line-height: 1.5; }
    .pr-hint a, .pr-side a { color: var(--jb-gold-strong); text-decoration: underline; text-underline-offset: 3px; }
    .pr-error { margin: 6px 0 0; color: var(--jb-down); font-size: 13.5px; font-weight: 600; }

    .pr-check {
        display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: #fff;
    }
    .pr-check.is-invalid { border-color: var(--jb-down); }
    .pr-check input { flex: none; width: 20px; height: 20px; margin-top: 2px; accent-color: var(--jb-gold-strong); cursor: pointer; }
    .pr-check label { color: var(--jb-ink); font-size: 14.5px; line-height: 1.55; cursor: pointer; }

    .pr-submit { width: 100%; }
    .pr-after { margin-top: 12px; text-align: center; }

    /* Messages */
    .pr-alert { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 22px; padding: 14px 16px; border-radius: var(--jb-r-ctl); font-size: 14.5px; line-height: 1.55; }
    .pr-alert i { margin-top: 3px; }
    .pr-alert ul { margin: 6px 0 0; padding-left: 18px; }
    .pr-alert--success { background: #e9f6ef; border: 1px solid #a7d9bd; color: #0d5c37; }
    .pr-alert--error { background: #fdeeec; border: 1px solid #f0b4ac; color: #8e2318; }
    .pr-alert:focus { outline: 2px solid var(--jb-gold); outline-offset: 2px; }

    /* Rights list */
    .pr-rights { margin: 0; }
    .pr-rights > div { padding: 10px 0; border-bottom: 1px solid var(--jb-line); }
    .pr-rights > div:last-child { border-bottom: 0; }
    .pr-rights dt { color: var(--jb-ink); font-weight: 700; }
    .pr-rights dd { margin: 0; }
    .pr-steps { margin: 0; padding-left: 22px; }
    .pr-steps li { margin-bottom: 8px; }
    .pr-steps li::marker { color: var(--jb-gold-strong); font-weight: 700; }
    .pr-contact { margin: 0; padding: 0; list-style: none; }
    .pr-contact li { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; overflow-wrap: anywhere; }
    .pr-contact i { width: 18px; color: var(--jb-gold-strong); }
    .pr-side__foot { margin: 22px 0 0; padding-top: 16px; border-top: 1px solid var(--jb-line); font-size: 14px; }

    /* Details still to be confirmed by the owner */
    .tbc {
        padding: 1px 6px; border: 1px dashed #c98a00; border-radius: 6px;
        background: #ffe8a3; color: #5a3a00; font-weight: 600;
        -webkit-box-decoration-break: clone; box-decoration-break: clone;
    }

    @media (max-width: 991px) { .pr-card, .pr-side { padding: 28px; } }
    @media (max-width: 575px) {
        .pr-card, .pr-side { padding: 22px 18px; }
        .pr-card__title, .pr-side__title { font-size: 22px; }
    }
</style>
@endpush

@push('scripts')
<script>
    // After sending, move focus to the result so screen readers and keyboard users land on it
    (function () {
        var status = document.getElementById('pr-status');
        if (!status) return;
        status.scrollIntoView({ block: 'center' });
        status.focus({ preventScroll: true });
    })();
</script>
@endpush
