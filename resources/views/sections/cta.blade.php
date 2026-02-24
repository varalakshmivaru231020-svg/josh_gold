<section class="cta-section py-5 fade-up">
    <div class="container">
        <div class="cta-container text-center py-5 px-4 shadow-lg">
            <div class="cta-content-inner mx-auto" style="max-width: 800px;">
                <h2 class="cta-main-title mb-4">Need a quality legal assistance?</h2>
                <p class="cta-description mb-5">
                    Schedule a consultation with our expert team today, protect your right.
                </p>
                @php
                    $cards = $section->cards;
                    $btn_text = $cards[0]['btn_text'] ?? 'Book Consultation';
                    $btn_link = $cards[0]['btn_link'] ?? '#';
                @endphp
                <div class="cta-actions">
                    <a href="{{ $btn_link }}" class="btn btn-premium-gold btn-lg text-uppercase fw-bold">
                        {{ $btn_text }} <i class="fas fa-calendar-alt ms-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="cta-decoration-left"><i class="fas fa-balance-scale"></i></div>
            <div class="cta-decoration-right"><i class="fas fa-gavel"></i></div>
        </div>
    </div>
</section>

<style>
.cta-container {
    background: linear-gradient(135deg, #0a1628 0%, #1a2b4b 100%);
    border-radius: 30px;
    position: relative;
    overflow: hidden;
    color: white;
    border: 1px solid rgba(201, 162, 39, 0.2);
}
.cta-main-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--gold-primary);
}
.cta-description {
    font-size: 1.25rem;
    opacity: 0.9;
    letter-spacing: 0.5px;
}
.btn-premium-gold {
    background: var(--gold-primary);
    color: #0a1628;
    border: none;
    padding: 1.2rem 3rem;
    border-radius: 12px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-size: 1rem;
}
.btn-premium-gold:hover {
    background: #e5c05b;
    transform: scale(1.05);
    box-shadow: 0 15px 30px rgba(201, 162, 39, 0.4);
    color: #0a1628;
}
.cta-decoration-left, .cta-decoration-right {
    position: absolute;
    font-size: 12rem;
    opacity: 0.05;
    color: white;
}
.cta-decoration-left {
    bottom: -30px;
    left: -20px;
    transform: rotate(15deg);
}
.cta-decoration-right {
    top: -30px;
    right: -20px;
    transform: rotate(-15deg);
}
@media (max-width: 768px) {
    .cta-main-title {
        font-size: 2.5rem;
    }
    .cta-container {
        padding: 3rem 1rem !important;
    }
}
</style>
