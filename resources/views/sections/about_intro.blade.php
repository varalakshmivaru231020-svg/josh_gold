<section class="about-intro-section py-5 fade-up">
    <div class="container">
        <!-- Main Middle Heading -->
        <div class="text-center mb-5">
            <h2 class="about-main-heading">{{ $section->heading }}</h2>
            <div class="gold-divider mx-auto mt-3"></div>
        </div>

        <div class="row align-items-center mt-5">
            <!-- Left Side: Image -->
            <div class="col-lg-5">
                <div class="intro-image-wrapper pe-lg-4">
                    @php
                        $aboutMain = \App\Models\AboutSection::first();
                        $introImage = isset($section->cards[0]['image']) 
                            ? asset('storage/' . $section->cards[0]['image']) 
                            : ($aboutMain && $aboutMain->image ? asset('storage/' . $aboutMain->image) : asset('about_intro_main.jpg'));
                        
                        $displaySubheading = $section->subheading ?: ($aboutMain->subtitle ?? 'Introduction');
                        $displayContent = $section->content ?: ($aboutMain->content ?? '');
                    @endphp
                    <img src="{{ $introImage }}" alt="About Us" class="img-fluid rounded-4 shadow-lg about-intro-img">
                    <div class="image-accent-border"></div>
                </div>
            </div>

            <!-- Right Side: Introduction -->
            <div class="col-lg-6">
                <div class="intro-text-content ps-lg-4">
                    <h3 class="intro-side-heading mb-4">{{ $displaySubheading }}</h3>
                    <div class="intro-description text-muted">
                        {!! $displayContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-main-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3rem;
    font-weight: 700;
    color: #0a1628;
}
.intro-side-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.25rem;
    font-weight: 700;
    color: #0a1628;
    position: relative;
    padding-bottom: 15px;
}
.intro-side-heading::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--gold-primary);
}
.intro-description {
    font-size: 1.15rem;
    line-height: 1.8;
}
.intro-img {
    border: 1px solid rgba(201, 162, 39, 0.2);
    transition: transform 0.5s ease;
}
.intro-img:hover {
    transform: scale(1.02);
}
.gold-divider {
    width: 100px;
    height: 4px;
    background: var(--gold-primary);
}
</style>
