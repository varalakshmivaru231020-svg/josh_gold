<section class="vision-mission-section py-5 fade-up" style="background: #0a1628;">
    <div class="container">
        @if($section->heading)
            <div class="text-center mb-5">
                <h2 class="section-heading text-white">{{ $section->heading }}</h2>
                @if($section->subheading)
                    <p class="text-white-50">{{ $section->subheading }}</p>
                @endif
            </div>
        @endif
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="vision-card p-5 h-100 shadow-lg">
                    <div class="icon-box mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="title text-white">Our Vision</h3>
                    <div class="content text-white-50">
                        @if(is_array($section->cards) && isset($section->cards[0]['vision']))
                            {!! $section->cards[0]['vision'] !!}
                        @else
                            {{ $section->content }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mission-card p-5 h-100 shadow-lg">
                    <div class="icon-box mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="title text-white">Our Mission</h3>
                    <div class="content text-white-50">
                        @if(is_array($section->cards) && isset($section->cards[0]['mission']))
                            {!! $section->cards[0]['mission'] !!}
                        @else
                            {{ $section->content }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.vision-card, .mission-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}
.vision-card:hover, .mission-card:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-10px);
    border-color: var(--gold-primary);
}
.icon-box {
    width: 65px;
    height: 65px;
    background: var(--gold-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #0a1628;
}
.title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-family: 'Cormorant Garamond', serif;
}
.content {
    font-size: 1.15rem;
    line-height: 1.8;
}
.section-heading {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 700;
    font-size: 3rem;
    margin-bottom: 1rem;
}
</style>
