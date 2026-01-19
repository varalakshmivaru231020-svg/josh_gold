@extends('admin.layouts.app')

@section('title', 'Contact Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Contact Settings</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.contact-settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_address" class="form-label">Address</label>
                        <textarea class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" rows="3">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                        @error('contact_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" placeholder="+91 9876543210">
                        @error('contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" placeholder="info@example.com">
                        @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_whatsapp" class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control @error('contact_whatsapp') is-invalid @enderror" id="contact_whatsapp" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}" placeholder="919876543210">
                        <small class="text-muted">Enter number with country code without + sign (e.g., 919876543210)</small>
                        @error('contact_whatsapp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_working_hours" class="form-label">Working Hours</label>
                        <input type="text" class="form-control @error('contact_working_hours') is-invalid @enderror" id="contact_working_hours" name="contact_working_hours" value="{{ old('contact_working_hours', $settings['contact_working_hours']) }}" placeholder="Mon - Sat: 9:00 AM - 6:00 PM">
                        @error('contact_working_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_map_embed" class="form-label">Google Map Embed Code</label>
                        <textarea class="form-control @error('contact_map_embed') is-invalid @enderror" id="contact_map_embed" name="contact_map_embed" rows="6" placeholder="Paste the Google Maps iframe embed code here">{{ old('contact_map_embed', $settings['contact_map_embed']) }}</textarea>
                        <small class="text-muted">Go to Google Maps, search your location, click "Share" > "Embed a map" and copy the iframe code</small>
                        @error('contact_map_embed')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($settings['contact_map_embed'])
                    <div class="mb-3">
                        <label class="form-label">Map Preview</label>
                        <div class="border rounded" style="height: 200px; overflow: hidden;">
                            {!! $settings['contact_map_embed'] !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.card .border iframe {
    width: 100% !important;
    height: 200px !important;
}
</style>
@endpush
