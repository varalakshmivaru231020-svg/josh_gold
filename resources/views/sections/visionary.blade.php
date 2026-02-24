<section class="visionary-section py-5 fade-up">
    <div class="section-container">
        @if($section->heading)
            <h2 class="section-heading text-center mb-2">
                {{ $section->heading }}
            </h2>
        @endif

        @if($section->subheading)
            <p class="section-subheading text-center text-muted mb-5">
                {{ $section->subheading }}
            </p>
        @endif

        @if($section->content)
            <div class="section-main-content mb-5">
                {!! $section->content !!}
            </div>
        @endif

        @if($section->cards)
            <div class="vision-timeline-wrapper position-relative py-4">
                {{-- Decorative Line --}}
                <div class="vision-connection-line d-none d-md-block"></div>
                
                <div class="row g-4 align-items-stretch">
                    @foreach($section->cards as $index => $card)
                        <div class="col-md-4 mb-4">
                            <div class="glass-card h-100 fade-up" style="animation-delay: {{ $index * 0.1 }}s">
                                @if(isset($card['icon']))
                                    <div class="card-icon">
                                        <i class="{{ $card['icon'] }}"></i>
                                    </div>
                                @endif
                                <h4 class="card-title">{{ $card['title'] ?? '' }}</h4>
                                <p class="card-text">{{ $card['content'] ?? '' }}</p>
                                
                                {{-- Flow Arrow for mobile --}}
                                @if(!$loop->last)
                                    <div class="vision-mobile-arrow d-md-none text-center mt-3">
                                        <i class="fas fa-chevron-down text-gold"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.visionary-section {
    padding: 60px 0;
}
.section-heading {
    color: var(--navy-deep);
    font-size: 2.5rem;
    font-weight: 700;
}
.section-subheading {
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    font-size: 1.1rem;
}
.vision-connection-line {
    position: absolute;
    top: 50%;
    left: 10%;
    right: 10%;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(201, 162, 39, 0.3) 20%, rgba(201, 162, 39, 0.3) 80%, transparent);
    z-index: 0;
    transform: translateY(-50%);
}
.vision-timeline-wrapper .glass-card {
    position: relative;
    z-index: 1;
}
.text-gold { color: var(--gold-primary); }
.glass-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 24px;
    padding: 2.5rem;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
}
.glass-card:hover {
    transform: translateY(-10px) scale(1.02);
    background: white;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
    border-color: var(--gold-primary);
}
.card-icon {
    width: 65px;
    height: 65px;
    background: var(--navy-primary);
    color: var(--gold-primary);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}
.glass-card:hover .card-icon {
    background: var(--gold-primary);
    color: white;
    transform: rotate(360deg);
}
.card-title {
    color: var(--navy-primary);
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
}
.card-text {
    color: var(--text-muted);
    line-height: 1.6;
}
</style>
