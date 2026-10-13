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
                    <h3 class="card-heading-navy mb-4">Send us a Message</h3>

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="label-gold-sm">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-luxury @error('name') is-invalid @enderror" required placeholder="Your full name" value="{{ old('name') }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="label-gold-sm">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-luxury @error('email') is-invalid @enderror" required placeholder="your@email.com" value="{{ old('email') }}">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="label-gold-sm">Phone Number</label>
                                <input type="tel" name="phone" class="form-luxury @error('phone') is-invalid @enderror" placeholder="+91 XXXXX XXXXX" value="{{ old('phone') }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="label-gold-sm">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-luxury @error('subject') is-invalid @enderror" required placeholder="How can we help?" value="{{ old('subject') }}">
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-gold-sm">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-luxury @error('message') is-invalid @enderror" rows="5" required placeholder="Write your message here...">{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn-gold-action w-100 py-3">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
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
        @if($mapEmbed !== '' || $mapQuery !== '')
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

            <div class="map-premium-card">
                <div class="map-inner">
                    @if($mapEmbed !== '')
                        {!! $mapEmbed !!}
                    @else
                        <iframe src="https://www.google.com/maps?q={{ urlencode($mapSearch) }}&output=embed"
                                title="Map showing {{ $mapQuery }}" loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── CONTACT PAGE (dark bullion theme) ── */
    .contact-luxury-page { padding-bottom: 56px; }
    .contact-content-container { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .contact-content-container { padding-top: 48px; } }

    /* Form card */
    .contact-card-premium {
        position: relative; height: 100%; padding: 36px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, rgba(201, 163, 92, .05), transparent 50%), var(--jb-panel);
    }
    .contact-card-premium::before {
        content: ''; position: absolute; top: -1px; left: 18%; right: 18%; height: 1px;
        background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent);
    }
    .card-heading-navy { font-family: var(--jb-serif); color: var(--jb-white); font-size: 24px; font-weight: 600; }

    .label-gold-sm {
        display: block; margin-bottom: 8px;
        color: var(--jb-gold); font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    }
    .form-luxury {
        width: 100%; padding: 12px 16px;
        background: var(--jb-black); border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl);
        color: var(--jb-white); font: 15px/1.5 var(--jb-sans);
        transition: border-color .15s, background-color .15s, box-shadow .15s;
    }
    .form-luxury::placeholder { color: #7d7d7d; }
    .form-luxury:focus {
        outline: none; background: var(--jb-panel-2);
        border-color: rgba(234, 215, 166, .55); box-shadow: 0 0 0 .2rem rgba(234, 215, 166, .15);
    }
    .form-luxury.is-invalid { border-color: var(--jb-down); }

    .btn-gold-action {
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--jb-champagne); color: #111; border: 1px solid var(--jb-champagne);
        border-radius: var(--jb-r-btn); font: 700 14.5px/1 var(--jb-sans); letter-spacing: .01em;
        box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45);
        transition: background-color .15s, transform .15s;
    }
    .btn-gold-action:hover { background: #fff8ea; color: #111; }
    .btn-gold-action:active { background: var(--jb-champagne-down); transform: translateY(1px); }

    /* Sidebar */
    .contact-sidebar-luxury {
        position: relative; height: 100%; padding: 36px; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: radial-gradient(420px 220px at 100% 0%, rgba(201, 163, 92, .12), transparent 65%), linear-gradient(180deg, #111114, #0a0a0b);
        color: var(--jb-text);
    }
    .sidebar-heading { font-family: var(--jb-serif); font-size: 22px; font-weight: 600; color: var(--jb-white); }

    .contact-info-block {
        display: flex; align-items: flex-start; gap: 16px;
        padding-bottom: 20px; border-bottom: 1px solid var(--jb-line-soft);
    }
    .contact-info-block:last-child { border-bottom: none; padding-bottom: 0; }

    .cib-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #0a0a0b; border: 1px solid var(--jb-line); border-radius: 12px;
        color: var(--jb-gold-light); font-size: 1.05rem;
        transition: background-color .2s, color .2s, border-color .2s;
    }
    .contact-info-block:hover .cib-icon { background: var(--jb-champagne); border-color: var(--jb-champagne); color: #111; }
    .cib-whatsapp { color: var(--jb-whatsapp); }
    .contact-info-block:hover .cib-whatsapp { background: var(--jb-whatsapp); border-color: var(--jb-whatsapp); color: #062b14; }

    .cib-label {
        display: block; margin-bottom: 4px;
        color: var(--jb-muted); font-size: 10.5px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
    }
    .cib-text { margin: 0; color: var(--jb-text); font-size: 14.5px; line-height: 1.7; }
    .cib-link { color: var(--jb-gold-light); text-decoration: none; font-size: 15px; font-weight: 600; word-break: break-word; transition: color .15s; }
    .cib-link:hover { color: var(--jb-white); }
    .cib-wa-link { color: var(--jb-whatsapp); }
    .cib-wa-link:hover { color: #7fffb0; }

    /* Map */
    .map-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .map-title { font-family: var(--jb-serif); font-size: 26px; font-weight: 600; color: var(--jb-white); margin: .2rem 0 .4rem; }
    .map-address { margin: 0; color: var(--jb-muted); max-width: 640px; }
    .map-address i { color: var(--jb-gold); }
    .map-directions { width: auto !important; min-height: 46px; padding: 0 20px; text-decoration: none; white-space: nowrap; }
    .map-premium-card {
        height: 400px; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel);
    }
    .map-inner { width: 100%; height: 100%; position: relative; }
    .map-inner iframe { width: 100% !important; height: 100% !important; border: none; }
    /* Google's embed has no dark style: invert it so the map sits in the dark page */
    .map-inner iframe[src*="google.com/maps"] { filter: invert(.9) hue-rotate(180deg) brightness(.95) contrast(.9); }

    /* Responsive */
    @media (max-width: 991px) {
        .contact-card-premium, .contact-sidebar-luxury { padding: 28px; }
        .card-heading-navy { font-size: 22px; }
    }
    @media (max-width: 576px) {
        .map-title { font-size: 22px; }
        .map-premium-card { height: 320px; }
        .contact-card-premium, .contact-sidebar-luxury { padding: 22px; }
    }
</style>
@endpush
