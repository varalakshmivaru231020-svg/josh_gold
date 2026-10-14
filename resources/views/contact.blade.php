@extends('layouts.lawfirm')

@section('title', 'Contact Us | JOSHA GOLD AND DIAMOND JEWELLERY')

@php
    // Everything here comes from admin (Contact Settings) and is optional: blank = not shown
    $address  = trim((string) ($contact['address'] ?? ''));
    $phone    = trim((string) ($contact['phone'] ?? ''));
    $email    = trim((string) ($contact['email'] ?? ''));
    $waNumber = preg_replace('/\D+/', '', (string) ($contact['whatsapp'] ?? ''));
    $hours    = trim((string) ($contact['working_hours'] ?? ''));
    $mapEmbed = trim((string) ($contact['map_embed'] ?? ''));
    $addrLines = array_values(array_filter(array_map(fn ($line) => trim($line, " 	,"), preg_split('/\R/', $address))));
    $mapQuery  = implode(', ', $addrLines);   // shown as text
    // A leading door/floor/building line makes Google guess between similarly named places,
    // so the map searches by the locality lines (all but the first) when there are 3 or more
    $mapSearch = count($addrLines) >= 3 ? implode(', ', array_slice($addrLines, 1)) : $mapQuery;

    // The map loads only with the visitor's consent (data-consent-embed), so it needs a plain URL:
    // the src of the embed code pasted in admin, a URL pasted there, or Google Maps for the address
    $mapSrc = '';
    if ($mapEmbed !== '') {
        if (preg_match('/\bsrc\s*=\s*["\']([^"\']+)["\']/i', $mapEmbed, $m)) {
            $mapSrc = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5);
        } elseif (preg_match('#^https://\S+$#i', $mapEmbed)) {
            $mapSrc = $mapEmbed;
        }
    }
    if (! preg_match('#^https://#i', $mapSrc)) {
        $mapSrc = $mapQuery !== '' ? 'https://www.google.com/maps?q=' . urlencode($mapSearch) . '&output=embed' : '';
    }

    // Error messages are linked to their fields for screen readers
    $errorRef = fn (string $field) => $errors->has($field) ? 'aria-invalid="true" aria-describedby="contact-' . $field . '-error"' : '';

    // "Get a quote" links pass the item, e.g. ?item=10 g 24K gold coin
    $quoteItem = \Illuminate\Support\Str::limit(trim(strip_tags((string) request('item'))), 120, '');
@endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Get in touch',
    'title'   => 'Contact',
    'accent'  => 'Us',
    'sub'     => "Questions about gold & silver rates or our services? We'd love to hear from you.",
    'crumbs'  => [['Contact Us']],
])

<section class="contact-luxury-page">

    {{-- Content --}}
    <div class="jb-wrap contact-content-container">
        <div class="row g-4 g-lg-5">

            {{-- Contact Form --}}
            <div class="col-lg-7" data-aos="fade-up">
                <div class="contact-card-premium">
                    <h3 class="card-heading-navy mb-4">{{ $quoteItem !== '' ? 'Request a quote' : 'Send us a Message' }}</h3>
                    @if($quoteItem !== '')
                        <p class="jb-muted mb-4">You're asking about <strong class="text-navy">{{ $quoteItem }}</strong>. Add your details and we'll reply with today's price.</p>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" role="status">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i> {{ session('success') }}
                        </div>
                    @endif

                    <p class="contact-required-note">Fields marked <span class="text-danger" aria-hidden="true">*</span><span class="visually-hidden">with an asterisk</span> are required.</p>

                    <form action="{{ route('contact.submit') }}" method="POST" aria-describedby="contact-privacy-note">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="contact-name" class="label-gold-sm">Full Name <span class="text-danger" aria-hidden="true">*</span></label>
                                <input type="text" id="contact-name" name="name" class="form-luxury @error('name') is-invalid @enderror" required autocomplete="name" placeholder="Your full name" value="{{ old('name') }}" {!! $errorRef('name') !!}>
                                @error('name') <div class="invalid-feedback" id="contact-name-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="contact-email" class="label-gold-sm">Email Address <span class="text-danger" aria-hidden="true">*</span></label>
                                <input type="email" id="contact-email" name="email" class="form-luxury @error('email') is-invalid @enderror" required autocomplete="email" placeholder="your@email.com" value="{{ old('email') }}" {!! $errorRef('email') !!}>
                                @error('email') <div class="invalid-feedback" id="contact-email-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="contact-phone" class="label-gold-sm">Phone Number <span class="label-optional">(optional)</span></label>
                                <input type="tel" id="contact-phone" name="phone" class="form-luxury @error('phone') is-invalid @enderror" autocomplete="tel" maxlength="20" placeholder="+91 XXXXX XXXXX" value="{{ old('phone') }}" {!! $errorRef('phone') !!}>
                                @error('phone') <div class="invalid-feedback" id="contact-phone-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="contact-subject" class="label-gold-sm">Subject <span class="text-danger" aria-hidden="true">*</span></label>
                                <input type="text" id="contact-subject" name="subject" class="form-luxury @error('subject') is-invalid @enderror" required placeholder="How can we help?" value="{{ old('subject', $quoteItem !== '' ? 'Quote request: ' . $quoteItem : '') }}" {!! $errorRef('subject') !!}>
                                @error('subject') <div class="invalid-feedback" id="contact-subject-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="contact-message" class="label-gold-sm">Message <span class="text-danger" aria-hidden="true">*</span></label>
                            <textarea id="contact-message" name="message" class="form-luxury @error('message') is-invalid @enderror" rows="5" required maxlength="2000" placeholder="Write your message here..." {!! $errorRef('message') !!}>{{ old('message', $quoteItem !== '' ? "Please send me a quote for: {$quoteItem}." : '') }}</textarea>
                            @error('message') <div class="invalid-feedback" id="contact-message-error">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn-gold-action w-100 py-3">
                            <i class="fas fa-paper-plane me-2" aria-hidden="true"></i> Send Message
                        </button>
                        <p class="contact-privacy-note" id="contact-privacy-note">
                            <i class="fas fa-lock me-1" aria-hidden="true"></i>
                            We ask for these details only so we can reply to your enquiry. See our <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>.
                            We never ask for card numbers or passwords.
                        </p>
                    </form>
                </div>
            </div>

            {{-- Contact Information Sidebar --}}
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="80">
                <div class="contact-sidebar-luxury">
                    <h3 class="sidebar-heading mb-4">Contact Information</h3>

                    @if($address !== '')
                    {{-- Address --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Address</span>
                            <p class="cib-text">{!! nl2br(e($address)) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($phone !== '')
                    {{-- Phone --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Phone</span>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="cib-link">{{ $phone }}</a>
                        </div>
                    </div>
                    @endif

                    @if($email !== '')
                    {{-- Email --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-envelope"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Email</span>
                            <a href="mailto:{{ $email }}" class="cib-link">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    @if($waNumber !== '')
                    {{-- WhatsApp --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon cib-whatsapp"><i class="fab fa-whatsapp"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">WhatsApp</span>
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="cib-link cib-wa-link">
                                Chat with us <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7em;"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($hours !== '')
                    {{-- Working Hours --}}
                    <div class="contact-info-block">
                        <div class="cib-icon"><i class="fas fa-clock"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Working Hours</span>
                            <p class="cib-text">{!! nl2br(e($hours)) !!}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Map: the embed pasted in admin, or Google Maps for the address; hidden if neither is set --}}
        @if($mapSrc !== '')
        <div class="map-section mt-5" data-aos="fade-up">
            <div class="map-head">
                <div>
                    <span class="label-gold-sm">FIND US</span>
                    <h3 class="map-title">Visit Our Office</h3>
                    @if($mapQuery !== '')
                    <p class="map-address"><i class="fas fa-map-marker-alt me-2"></i>{{ $mapQuery }}</p>
                    @endif
                </div>
                @if($mapQuery !== '')
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($mapSearch) }}" target="_blank" rel="noopener" class="btn-gold-action map-directions">
                    <i class="fas fa-directions me-2"></i> Get Directions
                </a>
                @endif
            </div>

            {{-- Loads only after the visitor allows embedded content or clicks "Load map" (public/js/consent.js) --}}
            <div class="jb-embed map-embed" data-consent-embed data-embed-src="{{ $mapSrc }}" data-embed-title="Map of our office" data-embed-height="360">
                <div class="jb-embed__placeholder">
                    <span class="jb-embed__icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                    <p class="jb-embed__title">Map of our office</p>
                    <p>This map is provided by Google, which may set cookies.</p>
                    <button type="button" class="jb-btn jb-btn--primary jb-btn--sm" data-embed-load>Load map</button>
                    <a href="{{ url('/cookie-policy') }}" class="map-embed__link">Cookie policy</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── CONTACT PAGE (white and gold theme) ── */
    .contact-luxury-page { padding-bottom: 56px; }
    .contact-content-container { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .contact-content-container { padding-top: 48px; } }

    /* Form card */
    .contact-card-premium {
        position: relative; height: 100%; padding: 36px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, #fffaf0, #fff 40%); box-shadow: var(--jb-shadow-lg);
    }
    .contact-card-premium::before {
        content: ''; position: absolute; top: -1px; left: 18%; right: 18%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--jb-gold), transparent);
    }
    .card-heading-navy { font-family: var(--jb-serif); color: var(--jb-ink); font-size: 24px; font-weight: 600; }

    .label-gold-sm {
        display: block; margin-bottom: 8px;
        color: var(--jb-gold-strong); font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    }
    .form-luxury {
        width: 100%; padding: 12px 16px;
        background: #fff; border: 1px solid var(--jb-line-strong); border-radius: var(--jb-r-ctl);
        color: var(--jb-ink); font: 15px/1.5 var(--jb-sans);
        transition: border-color .15s, background-color .15s, box-shadow .15s;
    }
    .form-luxury::placeholder { color: #a99d86; }
    .form-luxury:focus {
        outline: none; background: #fff;
        border-color: var(--jb-gold); box-shadow: 0 0 0 .2rem rgba(201, 163, 92, .2);
    }
    .form-luxury.is-invalid { border-color: var(--jb-down); }
    .label-optional { color: var(--jb-muted); font-weight: 600; letter-spacing: .08em; text-transform: none; }
    .contact-required-note { margin: -8px 0 20px; color: var(--jb-muted); font-size: 13.5px; }
    .contact-card-premium .invalid-feedback { color: var(--jb-down); font-size: 13.5px; font-weight: 600; }
    .contact-privacy-note { margin: 14px 0 0; color: var(--jb-muted); font-size: 13.5px; line-height: 1.6; text-align: center; }
    .contact-privacy-note i { color: var(--jb-gold-strong); }
    .contact-privacy-note a { color: var(--jb-gold-strong); text-decoration: underline; text-underline-offset: 3px; }

    .btn-gold-action {
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--jb-gold-band); color: #2a1d06; border: 1px solid #b48b3c;
        border-radius: var(--jb-r-btn); font: 700 14.5px/1 var(--jb-sans); letter-spacing: .01em;
        box-shadow: 0 10px 24px -14px rgba(138, 101, 22, .8), inset 0 1px 0 rgba(255, 255, 255, .45);
        transition: filter .15s, transform .15s;
    }
    .btn-gold-action:hover { filter: brightness(1.06); color: #2a1d06; }
    .btn-gold-action:active { transform: translateY(1px); }

    /* Sidebar */
    .contact-sidebar-luxury {
        position: relative; height: 100%; padding: 36px; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: radial-gradient(420px 220px at 100% 0%, rgba(214, 178, 95, .26), transparent 65%), linear-gradient(180deg, #fffaf0, #fbf1da); box-shadow: var(--jb-shadow-lg);
        color: var(--jb-text);
    }
    .sidebar-heading { font-family: var(--jb-serif); font-size: 22px; font-weight: 600; color: var(--jb-ink); }

    .contact-info-block {
        display: flex; align-items: flex-start; gap: 16px;
        padding-bottom: 20px; border-bottom: 1px solid var(--jb-line);
    }
    .contact-info-block:last-child { border-bottom: none; padding-bottom: 0; }

    .cib-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #fff; border: 1px solid #ecd49c; border-radius: 12px;
        color: var(--jb-gold-strong); font-size: 1.05rem; box-shadow: var(--jb-shadow);
        transition: background-color .2s, color .2s, border-color .2s;
    }
    .contact-info-block:hover .cib-icon { background: var(--jb-gold-band); border-color: #b48b3c; color: #2a1d06; }
    .cib-whatsapp { color: #1fa855; }
    .contact-info-block:hover .cib-whatsapp { background: var(--jb-whatsapp); border-color: var(--jb-whatsapp); color: #fff; }

    .cib-label {
        display: block; margin-bottom: 4px;
        color: var(--jb-muted); font-size: 10.5px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
    }
    .cib-text { margin: 0; color: var(--jb-text); font-size: 14.5px; line-height: 1.7; }
    .cib-link { color: var(--jb-gold-strong); text-decoration: none; font-size: 15px; font-weight: 600; word-break: break-word; transition: color .15s; }
    .cib-link:hover { color: var(--jb-gold-deep); }
    .cib-wa-link { color: #138a43; }
    .cib-wa-link:hover { color: #0b6b31; }

    /* Map */
    .map-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .map-title { font-family: var(--jb-serif); font-size: 26px; font-weight: 600; color: var(--jb-ink); margin: .2rem 0 .4rem; }
    .map-address { margin: 0; color: var(--jb-muted); max-width: 640px; }
    .map-address i { color: var(--jb-gold-strong); }
    .map-directions { width: auto !important; min-height: 46px; padding: 0 20px; text-decoration: none; white-space: nowrap; }
    /* The map card itself is .jb-embed (styled in public/css/consent.css) */
    .map-embed { min-height: 360px; box-shadow: var(--jb-shadow-lg); }
    .map-embed__link { color: var(--jb-gold-strong); font-size: 13px; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .map-embed__link:hover { color: var(--jb-gold-deep); }

    /* Responsive */
    @media (max-width: 991px) {
        .contact-card-premium, .contact-sidebar-luxury { padding: 28px; }
        .card-heading-navy { font-size: 22px; }
    }
    @media (max-width: 576px) {
        .map-title { font-size: 22px; }
        .contact-card-premium, .contact-sidebar-luxury { padding: 22px; }
    }
</style>
@endpush
