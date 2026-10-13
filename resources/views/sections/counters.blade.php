<section class="counters-section py-5 fade-up">
    <div class="container text-center">
        @if($section->heading)
            <h2 class="section-heading mb-2">{{ $section->heading }}</h2>
        @endif
        @if($section->subheading)
            <p class="section-subheading text-muted mb-5">{{ $section->subheading }}</p>
        @endif

        <div class="row g-4 justify-content-center">
            @php
                $stats = \App\Models\Stat::where('is_active', true)->ordered()->get();
            @endphp
            @if($stats->count() > 0)
                @foreach($stats as $index => $stat)
                    <div class="col-6 col-md-4 col-lg">
                        <div class="achievement-card fade-up" style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="achievement-icon-wrapper mb-3">
                                <i class="{{ $stat->icon ?? 'fas fa-chart-line' }}"></i>
                            </div>
                            <div class="achievement-number-box">
                                <h3 class="achievement-number fw-bold" data-target="{{ $stat->count ?? 0 }}">0</h3>
                                <span class="plus-sign">{{ $stat->suffix ?? '+' }}</span>
                            </div>
                            <p class="achievement-title">{{ $stat->title ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                @if($section->cards)
                    @foreach($section->cards as $index => $card)
                        <div class="col-6 col-md-4 col-lg">
                            <div class="achievement-card fade-up" style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="achievement-icon-wrapper mb-3">
                                    <i class="{{ $card['icon'] ?? 'fas fa-chart-line' }}"></i>
                                </div>
                                <div class="achievement-number-box">
                                    <h3 class="achievement-number fw-bold" data-target="{{ $card['count'] ?? 0 }}">0</h3>
                                    <span class="plus-sign">+</span>
                                </div>
                                <p class="achievement-title">{{ $card['title'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</section>

<style>
/* CMS section: counters (white-and-gold theme) */
.counters-section .section-heading { font-family: var(--jb-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--jb-ink); }
.counters-section .section-subheading { color: var(--jb-muted) !important; }
.counters-section .achievement-card {
    position: relative; overflow: hidden; height: 100%; padding: 2.4rem 1.4rem; text-align: center;
    background: linear-gradient(180deg, #fffaf0, #fff 55%); border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
    box-shadow: var(--jb-shadow); transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
.counters-section .achievement-card::before {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
    background: var(--jb-gold-band); transform: scaleX(0); transition: transform .4s ease;
}
@media (hover: hover) {
    .counters-section .achievement-card:hover { transform: translateY(-6px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
    .counters-section .achievement-card:hover::before { transform: scaleX(1); }
}
.counters-section .achievement-icon-wrapper { font-size: 2.2rem; color: var(--jb-gold); transition: color .3s ease; }
.counters-section .achievement-card:hover .achievement-icon-wrapper { color: var(--jb-gold-strong); }
.counters-section .achievement-number-box { display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: .5rem; }
.counters-section .achievement-number { margin: 0; font-family: var(--jb-serif); font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 600; color: var(--jb-ink); font-variant-numeric: tabular-nums; }
.counters-section .plus-sign { font-family: var(--jb-serif); font-size: 1.8rem; font-weight: 600; color: var(--jb-gold-strong); }
.counters-section .achievement-title { margin: 0; color: var(--jb-muted); font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
@media (max-width: 575px) { .counters-section .achievement-card { padding: 1.8rem 1rem; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.achievement-number');
    const speed = 200;

    const animate = (counter) => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const inc = target / speed;

        if (count < target) {
            counter.innerText = Math.ceil(count + inc);
            setTimeout(() => animate(counter), 1);
        } else {
            counter.innerText = target;
        }
    };

    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animate(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => observer.observe(counter));
});
</script>
