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
/* CMS section: call to action (white-and-gold theme) */
.cta-section .cta-container {
    position: relative; overflow: hidden; border-radius: 22px; border: 1px solid var(--jb-line-strong);
    background: radial-gradient(600px 260px at 100% 0%, rgba(214, 178, 95, .28), transparent 65%),
        radial-gradient(500px 240px at 0% 100%, rgba(214, 178, 95, .16), transparent 65%), linear-gradient(180deg, #fffaf0, #fbf1da);
    box-shadow: var(--jb-shadow-lg) !important; color: var(--jb-text);
}
.cta-section .cta-content-inner { position: relative; z-index: 1; }
.cta-section .cta-main-title { font-family: var(--jb-serif); font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 600; line-height: 1.2; color: var(--jb-ink); }
.cta-section .cta-description { color: var(--jb-text); font-size: 1.1rem; line-height: 1.65; }
.cta-section .btn-premium-gold {
    padding: 1rem 2.4rem; border: 1px solid #b48b3c; border-radius: var(--jb-r-btn);
    background: var(--jb-gold-band); color: #2a1d06; font-size: .95rem; letter-spacing: .06em;
    box-shadow: 0 10px 24px -14px rgba(138, 101, 22, .8), inset 0 1px 0 rgba(255, 255, 255, .45);
    transition: filter .15s, transform .15s;
}
.cta-section .btn-premium-gold:hover { filter: brightness(1.06); transform: translateY(-1px); color: #2a1d06; }
.cta-section .cta-decoration-left, .cta-section .cta-decoration-right { position: absolute; font-size: 12rem; color: var(--jb-gold); opacity: .08; }
.cta-section .cta-decoration-left { bottom: -30px; left: -20px; transform: rotate(15deg); }
.cta-section .cta-decoration-right { top: -30px; right: -20px; transform: rotate(-15deg); }
@media (max-width: 768px) {
    .cta-section .cta-container { padding: 3rem 1rem !important; }
    .cta-section .cta-decoration-left, .cta-section .cta-decoration-right { font-size: 7rem; }
}
</style>
