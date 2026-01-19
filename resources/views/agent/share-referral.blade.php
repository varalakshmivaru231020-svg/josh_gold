@extends('layouts.Distributor')

@section('title', 'Share Referral Code')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Share Referral Code</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('Distributor.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Share</li>
    </ol>
    
    <div class="row">
        <!-- Referral Code Card -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <i class="fas fa-qrcode me-1"></i>
                    Your Referral Code
                </div>
                <div class="card-body text-center py-5">
                    <div class="display-1 mb-4 font-monospace">{{ $Distributor->referral_code }}</div>
                    
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <button class="btn btn-primary btn-lg copy-btn" data-text="{{ $Distributor->referral_code }}">
                            <i class="fas fa-copy me-2"></i>Copy Code
                        </button>
                        <button class="btn btn-success btn-lg" id="generateQrBtn">
                            <i class="fas fa-qrcode me-2"></i>Generate QR Code
                        </button>
                    </div>
                    
                    <div id="qrcode" class="mt-4 d-none"></div>
                </div>
            </div>
            
            <!-- Share Links -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-share-alt me-1"></i>
                    Quick Share
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="https://wa.me/?text={{ urlencode('Use my referral code ' . $Distributor->referral_code . ' to get 10% discount on all products! Shop now: ' . url('/products')) }}" 
                               target="_blank" class="btn btn-success w-100">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/products?ref=' . $Distributor->referral_code)) }}" 
                               target="_blank" class="btn btn-primary w-100">
                                <i class="fab fa-facebook me-2"></i>Facebook
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Get 10% discount using referral code: ' . $Distributor->referral_code . ' Shop now: ' . url('/products?ref=' . $Distributor->referral_code)) }}" 
                               target="_blank" class="btn btn-info w-100">
                                <i class="fab fa-twitter me-2"></i>Twitter
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="mailto:?subject=Get 10% Discount&body=Use my referral code {{ $Distributor->referral_code }} to get 10% discount on all products!%0D%0A%0D%0AShop now: {{ url('/products?ref=' . $Distributor->referral_code) }}" 
                               class="btn btn-danger w-100">
                                <i class="fas fa-envelope me-2"></i>Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Referral Link & Instructions -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-link me-1"></i>
                    Your Referral Link
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="referralLink" 
                               value="{{ $shareUrl }}" readonly>
                        <button class="btn btn-outline-primary copy-link-btn" type="button">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Share this link directly. When someone clicks and registers, they'll automatically be connected to your referral.
                    </div>
                    
                    <div class="mt-4">
                        <h6>Link Statistics</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="display-6 text-primary">{{ $Distributor->referrals->count() }}</div>
                                <small class="text-muted">Total Referrals</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 text-success">{{ $Distributor->commissions->where('status', 'settled')->count() }}</div>
                                <small class="text-muted">Settled Commissions</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 text-warning">{{ $Distributor->commissions->where('status', 'pending')->count() }}</div>
                                <small class="text-muted">Pending Commissions</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sharing Tips -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-lightbulb me-1"></i>
                    Sharing Tips for Maximum Results
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-comment-dots text-primary me-2"></i>Personal Message</h6>
                            </div>
                            <p class="mb-1">Send personalized messages to friends and family explaining the benefits.</p>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-users text-success me-2"></i>Social Media Groups</h6>
                            </div>
                            <p class="mb-1">Share in relevant Facebook groups, WhatsApp groups, and online communities.</p>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-video text-info me-2"></i>Create Content</h6>
                            </div>
                            <p class="mb-1">Create posts, stories, or videos about the products you love.</p>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-email text-warning me-2"></i>Email Signature</h6>
                            </div>
                            <p class="mb-1">Add your referral link to your email signature.</p>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-blog text-danger me-2"></i>Blog or Website</h6>
                            </div>
                            <p class="mb-1">If you have a blog or website, create content about the products.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Commission Calculator -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calculator me-1"></i>
                    Commission Calculator
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="orderAmount" class="form-label">Enter Order Amount (₹)</label>
                        <input type="number" class="form-control" id="orderAmount" 
                               placeholder="e.g., 1000" min="1">
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <span>Customer Discount (10%):</span>
                            <strong id="customerDiscount">₹0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Final Price:</span>
                            <strong id="finalPrice">₹0.00</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span>Your Commission (5%):</span>
                            <strong class="text-success" id="yourCommission">₹0.00</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
#qrcode {
    width: 200px;
    height: 200px;
    margin: 0 auto;
    border: 2px solid #dee2e6;
    padding: 10px;
    border-radius: 8px;
}

#qrcode img {
    width: 100%;
    height: 100%;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
$(document).ready(function() {
    // Copy referral code
    $('.copy-btn').click(function() {
        const text = $(this).data('text');
        navigator.clipboard.writeText(text).then(() => {
            const original = $(this).html();
            $(this).html('<i class="fas fa-check me-2"></i>Copied!');
            setTimeout(() => {
                $(this).html(original);
            }, 2000);
        });
    });
    
    // Copy referral link
    $('.copy-link-btn').click(function() {
        const link = $('#referralLink').val();
        navigator.clipboard.writeText(link).then(() => {
            $(this).html('<i class="fas fa-check"></i>');
            setTimeout(() => {
                $(this).html('<i class="fas fa-copy"></i>');
            }, 2000);
        });
    });
    
    // Generate QR Code
    $('#generateQrBtn').click(function() {
        const qrcodeDiv = $('#qrcode');
        if (qrcodeDiv.hasClass('d-none')) {
            qrcodeDiv.removeClass('d-none');
            qrcodeDiv.empty();
            
            QRCode.toCanvas(qrcodeDiv[0], '{{ $shareUrl }}', function(error) {
                if (error) console.error(error);
            });
            
            $(this).html('<i class="fas fa-sync-alt me-2"></i>Regenerate QR');
        } else {
            qrcodeDiv.empty();
            QRCode.toCanvas(qrcodeDiv[0], '{{ $shareUrl }}', function(error) {
                if (error) console.error(error);
            });
        }
    });
    
    // Commission Calculator
    $('#orderAmount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;
        const discount = amount * 0.10;
        const finalPrice = amount - discount;
        const commission = finalPrice * 0.05;
        
        $('#customerDiscount').text('₹' + discount.toFixed(2));
        $('#finalPrice').text('₹' + finalPrice.toFixed(2));
        $('#yourCommission').text('₹' + commission.toFixed(2));
    });
});
</script>
@endpush
@endsection