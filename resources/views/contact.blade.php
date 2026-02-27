@extends('layouts.lawfirm')

@section('title', 'Contact Us | Bengaluru Law House')

@section('content')
<section class="contact-luxury-page">

    {{-- Hero Banner --}}
    <div class="contact-hero-header">
        {{-- decorative background icon --}}
        <div class="contact-hero-deco" aria-hidden="true"><i class="fas fa-balance-scale"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">GET IN TOUCH</div>
            <h1 class="heading-premium text-white">Contact Us</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="contact-subheader text-white mt-3">Have questions about our publications? We'd love to hear from you.</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="container contact-content-container">
        <div class="row g-5">

            {{-- Contact Form --}}
            <div class="col-lg-7" data-aos="fade-right">
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
            <div class="col-lg-5" data-aos="fade-left">
                <div class="contact-sidebar-luxury">
                    <h3 class="sidebar-heading mb-4">Contact Information</h3>

                    {{-- Address --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Address</span>
                            <p class="cib-text">1652/6, 20th Cross, 8th Main Road,<br>C Block, CQAL Layout, Sahakar Nagar,<br>Bengaluru, Karnataka – 560092.</p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Phone</span>
                            <a href="tel:+919632527961" class="cib-link">+91 96325 27961</a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon"><i class="fas fa-envelope"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Email</span>
                            <a href="mailto:bengalurulawhouse@gmail.com" class="cib-link">bengalurulawhouse@gmail.com</a>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="contact-info-block mb-4">
                        <div class="cib-icon cib-whatsapp"><i class="fab fa-whatsapp"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">WhatsApp</span>
                            <a href="https://wa.me/919632527961" target="_blank" class="cib-link cib-wa-link">
                                Chat with us <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7em;"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="contact-info-block">
                        <div class="cib-icon"><i class="fas fa-clock"></i></div>
                        <div class="cib-content">
                            <span class="cib-label">Working Hours</span>
                            <p class="cib-text">Mon – Sat: 9:00 AM – 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Map --}}
        @if(isset($contact['map_embed']) && $contact['map_embed'])
        <div class="map-premium-card mt-5" data-aos="fade-up">
            <div class="map-inner">
                 {!! $contact['map_embed'] !!}
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── CONTACT PAGE ── */
    .contact-luxury-page { background: var(--cream, #f8f5ef); min-height: 80vh; padding-bottom: 120px; }

    /* Hero Banner */
    .contact-hero-header {
        background:
            linear-gradient(rgba(8,14,28,0.80) 0%, rgba(10,18,36,0.85) 100%),
            url('/images/contact-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px;
        position: relative;
        overflow: hidden;
        z-index: 0;
    }
    /* Grid texture */
    .contact-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image:
            linear-gradient(rgba(201, 168, 76, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.05) 1px, transparent 1px);
        background-size: 55px 55px;
    }
    /* Bottom warm glow */
    .contact-hero-header::after {
        content:''; position: absolute; inset:0; z-index: 0;
        background: radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.22) 0%, transparent 50%);
    }
    /* Decorative large scale icon */
    .contact-hero-deco {
        position: absolute; right: 8%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.08);
        pointer-events: none; z-index: 0; line-height: 1;
        transition: none;
    }
    .contact-hero-header .container { position: relative; z-index: 1; }
    .contact-hero-header .label-gold { display: block; margin-bottom: 14px; }
    .contact-hero-header .heading-premium { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 700; }
    .contact-hero-header .accent-line-gold { width: 60px; height: 3px; background: var(--gold, #c9a84c); margin: 18px auto 0; border-radius: 2px; }
    .contact-subheader { max-width: 600px; margin: 0 auto; font-family: var(--font-display); font-size: 1.15rem; font-style: italic; opacity: 0.85; }

    /* Content Container - NO negative margin, sits below banner properly */
    .contact-content-container { padding-top: 60px; padding-bottom: 40px; }

    /* Form Card */
    .contact-card-premium {
        background: white; padding: 50px; border-radius: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.06);
        border: 1px solid rgba(201,168,76,0.12);
        position: relative;
    }
    .contact-card-premium::before {
        content:''; position:absolute; top:0; left:40px; right:40px; height:4px;
        background: linear-gradient(90deg, var(--gold, #c9a84c), var(--gold-dark, #8b6914));
        border-radius: 0 0 8px 8px;
    }

    .card-heading-navy {
        font-family: var(--font-display); color: var(--navy, #0c1428);
        font-size: 1.8rem; font-weight: 700;
    }

    .label-gold-sm {
        display:block; color: var(--gold-dark, #8b6914);
        font-size: 0.78rem; text-transform:uppercase; letter-spacing: 2px;
        font-weight: 700; margin-bottom: 10px;
    }
    .form-luxury {
        width: 100%; padding: 14px 20px;
        background: var(--cream, #f8f5ef); border: 1px solid rgba(201,168,76,0.12);
        border-radius: 10px; transition: all 0.3s; font-size: 0.95rem;
        color: var(--text, #2a2a2a);
    }
    .form-luxury:focus {
        outline: none; border-color: var(--gold, #c9a84c);
        background: white; box-shadow: 0 4px 16px rgba(201,168,76,0.12);
    }

    .btn-gold-action {
        background: linear-gradient(135deg, var(--gold, #c9a84c), var(--gold-dark, #8b6914));
        color: var(--navy, #0c1428); border:none;
        transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        border-radius: 10px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;
    }
    .btn-gold-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(201,168,76,0.35);
    }

    /* Sidebar */
    .contact-sidebar-luxury {
        background: var(--navy, #0c1428); padding: 50px;
        border-radius: 30px; color: white; height: 100%;
        border: 1px solid rgba(201,168,76,0.25);
        position: relative; overflow: hidden;
    }
    .contact-sidebar-luxury::after {
        content: ''; position: absolute; bottom: -40px; right: -40px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .sidebar-heading {
        font-family: var(--font-display);
        font-size: 1.6rem; font-weight: 700; color: var(--gold, #c9a84c);
    }

    .contact-info-block {
        display: flex; align-items: flex-start; gap: 18px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .contact-info-block:last-child { border-bottom: none; padding-bottom: 0; }

    .cib-icon {
        width: 48px; height: 48px; flex-shrink: 0;
        background: rgba(201,168,76,0.12); border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold, #c9a84c); font-size: 1.15rem;
        transition: all 0.3s;
    }
    .contact-info-block:hover .cib-icon {
        background: var(--gold, #c9a84c); color: var(--navy, #0c1428);
        transform: scale(1.08);
    }
    .cib-whatsapp { background: rgba(37, 211, 102, 0.12); color: #25d366; }
    .contact-info-block:hover .cib-whatsapp { background: #25d366; color: white; }

    .cib-label {
        display: block; font-size: 0.7rem; text-transform: uppercase;
        letter-spacing: 1.5px; color: rgba(255,255,255,0.45);
        font-weight: 700; margin-bottom: 4px;
    }
    .cib-text {
        font-size: 0.95rem; line-height: 1.7; margin: 0;
        color: rgba(255,255,255,0.85);
    }
    .cib-link {
        color: var(--gold-light, #e8c878); text-decoration: none;
        font-size: 1rem; font-weight: 600; transition: 0.3s;
        font-family: var(--font-display);
    }
    .cib-link:hover { color: #fff; }
    .cib-wa-link { color: #25d366; }
    .cib-wa-link:hover { color: #7fffb0; }

    /* Map */
    .map-premium-card {
        border-radius: 24px; overflow: hidden;
        border: 1px solid rgba(201,168,76,0.2);
        height: 400px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.1);
    }
    .map-inner { width: 100%; height: 100%; position: relative; }
    .map-inner iframe { width: 100% !important; height: 100% !important; border: none; }

    /* Responsive */
    @media (max-width: 991px) {
        .contact-card-premium, .contact-sidebar-luxury { padding: 35px; border-radius: 24px; }
        .card-heading-navy { font-size: 1.5rem; }
    }
    @media (max-width: 576px) {
        .contact-content-container { padding-top: 40px; }
        .contact-card-premium, .contact-sidebar-luxury { padding: 28px; }
    }
</style>
@endpush
