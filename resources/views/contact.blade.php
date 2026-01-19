@extends('layouts.lawfirm')

@section('title', 'Contact Us - Bengaluru Law House')

@section('content')
<section class="contact-page-section">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="page-header text-center mb-5">
            <p class="section-label fade-up">Get In Touch</p>
            <h1 class="page-title fade-up delay-1">Contact Us</h1>
            <div class="section-divider mx-auto fade-up delay-2"></div>
            <p class="page-description fade-up delay-3">
                Have questions about our publications? We'd love to hear from you.
            </p>
        </div>

        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="contact-form-card fade-up">
                    <h4 class="form-card-title">
                        <i class="fas fa-paper-plane me-2"></i>Send us a Message
                    </h4>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary-law">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="contact-info-card fade-up delay-1">
                    <h4 class="info-card-title">
                        <i class="fas fa-info-circle me-2"></i>Contact Information
                    </h4>

                    @if($contact['address'])
                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h6>Address</h6>
                            <p>{!! nl2br(e($contact['address'])) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($contact['phone'])
                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h6>Phone</h6>
                            <p><a href="tel:{{ $contact['phone'] }}">{{ $contact['phone'] }}</a></p>
                        </div>
                    </div>
                    @endif

                    @if($contact['email'])
                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h6>Email</h6>
                            <p><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></p>
                        </div>
                    </div>
                    @endif

                    @if($contact['whatsapp'])
                    <div class="contact-info-item">
                        <div class="info-icon whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="info-content">
                            <h6>WhatsApp</h6>
                            <p><a href="https://wa.me/{{ $contact['whatsapp'] }}" target="_blank">Chat with us</a></p>
                        </div>
                    </div>
                    @endif

                    @if($contact['working_hours'])
                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h6>Working Hours</h6>
                            <p>{{ $contact['working_hours'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Quick Contact Buttons -->
                <div class="quick-contact-buttons fade-up delay-2">
                    @if($contact['phone'])
                    <a href="tel:{{ $contact['phone'] }}" class="quick-btn call-btn">
                        <i class="fas fa-phone-alt"></i>
                        <span>Call Now</span>
                    </a>
                    @endif
                    @if($contact['whatsapp'])
                    <a href="https://wa.me/{{ $contact['whatsapp'] }}" target="_blank" class="quick-btn whatsapp-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                    @endif
                    @if($contact['email'])
                    <a href="mailto:{{ $contact['email'] }}" class="quick-btn email-btn">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Google Map -->
        @if($contact['map_embed'])
        <div class="map-section mt-5 fade-up">
            <h4 class="map-title text-center mb-4">
                <i class="fas fa-map-marked-alt me-2"></i>Find Us On Map
            </h4>
            <div class="map-container">
                {!! $contact['map_embed'] !!}
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.contact-page-section {
    margin-top: 80px;
    min-height: calc(100vh - 80px);
    background: var(--cream-primary);
    padding: 60px 0;
}

.page-header {
    margin-bottom: 3rem;
}

.page-title {
    font-family: var(--font-heading);
    font-size: 2.5rem;
    color: var(--navy-deep);
    margin-bottom: 1rem;
}

.page-description {
    max-width: 600px;
    margin: 1rem auto 0;
    color: var(--text-muted);
}

/* Contact Form Card */
.contact-form-card {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: var(--shadow-soft);
    height: 100%;
}

.form-card-title {
    font-family: var(--font-heading);
    font-size: 1.3rem;
    color: var(--navy-deep);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--gold-primary);
}

.contact-form-card .form-label {
    color: var(--navy-deep);
    font-weight: 500;
}

.contact-form-card .form-control {
    border: 2px solid rgba(201, 162, 39, 0.2);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.contact-form-card .form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.15);
}

/* Contact Info Card */
.contact-info-card {
    background: var(--navy-deep);
    border-radius: 12px;
    padding: 2rem;
    color: var(--cream-primary);
}

.info-card-title {
    font-family: var(--font-heading);
    font-size: 1.3rem;
    color: var(--cream-primary);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--gold-primary);
}

.contact-info-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.contact-info-item:last-child {
    margin-bottom: 0;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: var(--gold-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon i {
    color: var(--navy-deep);
    font-size: 1.25rem;
}

.info-icon.whatsapp {
    background: #25d366;
}

.info-icon.whatsapp i {
    color: white;
}

.info-content h6 {
    color: var(--gold-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-content p {
    margin: 0;
    color: rgba(250, 248, 243, 0.9);
}

.info-content a {
    color: rgba(250, 248, 243, 0.9);
    text-decoration: none;
}

.info-content a:hover {
    color: var(--gold-primary);
}

/* Quick Contact Buttons */
.quick-contact-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.quick-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 12px;
    text-decoration: none;
    transition: var(--transition-fast);
}

.quick-btn i {
    font-size: 1.5rem;
}

.quick-btn span {
    font-size: 0.85rem;
    font-weight: 600;
}

.call-btn {
    background: var(--navy-deep);
    color: var(--cream-primary);
}

.call-btn:hover {
    background: var(--navy-light);
    color: var(--cream-primary);
    transform: translateY(-3px);
}

.whatsapp-btn {
    background: #25d366;
    color: white;
}

.whatsapp-btn:hover {
    background: #128C7E;
    color: white;
    transform: translateY(-3px);
}

.email-btn {
    background: var(--gold-primary);
    color: var(--navy-deep);
}

.email-btn:hover {
    background: var(--gold-dark);
    color: var(--navy-deep);
    transform: translateY(-3px);
}

/* Map Section */
.map-title {
    font-family: var(--font-heading);
    color: var(--navy-deep);
}

.map-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
    height: 400px;
}

.map-container iframe {
    width: 100% !important;
    height: 100% !important;
    border: none;
}

/* Alert Styling */
.alert-success {
    background: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #155724;
    border-radius: 8px;
}

@media (max-width: 991px) {
    .contact-info-card {
        margin-top: 2rem;
    }
}

@media (max-width: 767px) {
    .contact-page-section {
        margin-top: 70px;
        padding: 40px 0;
    }

    .page-title {
        font-size: 2rem;
    }

    .contact-form-card,
    .contact-info-card {
        padding: 1.5rem;
    }

    .quick-contact-buttons {
        flex-wrap: wrap;
    }

    .quick-btn {
        flex: 1 1 calc(50% - 0.5rem);
    }

    .map-container {
        height: 300px;
    }
}
</style>
@endpush
