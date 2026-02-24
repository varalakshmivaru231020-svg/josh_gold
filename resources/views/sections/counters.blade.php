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
.achievement-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    padding: 3rem 2rem;
    border-radius: 24px;
    border: 1px solid rgba(201, 162, 39, 0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}
.achievement-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--gold-primary);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}
.achievement-card:hover::before {
    transform: scaleX(1);
}
.achievement-card:hover {
    transform: translateY(-15px);
    background: white;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}
.achievement-icon-wrapper {
    font-size: 2.8rem;
    color: var(--gold-primary);
    transition: all 0.4s ease;
}
.achievement-card:hover .achievement-icon-wrapper {
    transform: scale(1.2);
    color: var(--navy-primary);
}
.achievement-number-box {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-bottom: 0.5rem;
}
.achievement-number {
    font-size: 3rem;
    font-weight: 800;
    color: var(--navy-primary);
    font-family: 'Inter', sans-serif;
}
.plus-sign {
    font-size: 2rem;
    color: var(--gold-primary);
    font-weight: 700;
}
.achievement-title {
    font-size: 1.1rem;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.section-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3.5rem;
    font-weight: 700;
    color: #0a1628;
}
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
