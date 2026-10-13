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
/* CMS section: introduction (white-and-gold theme) */
.about-intro-section .about-main-heading { font-family: var(--jb-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--jb-ink); }
.about-intro-section .intro-side-heading { position: relative; padding-bottom: 15px; font-family: var(--jb-serif); font-size: clamp(22px, 3vw, 30px); font-weight: 600; color: var(--jb-ink); }
.about-intro-section .intro-side-heading::after { content: ''; position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: var(--jb-gold-band); border-radius: 2px; }
.about-intro-section .intro-description { color: var(--jb-text) !important; font-size: 1.05rem; line-height: 1.8; }
.about-intro-section .intro-description strong, .about-intro-section .intro-description b { color: var(--jb-ink); }
.about-intro-section .intro-description a { color: var(--jb-gold-strong); }
.about-intro-section .about-intro-img { width: 100%; border: 4px solid #fff; outline: 1px solid var(--jb-line); box-shadow: var(--jb-shadow-lg) !important; transition: transform .5s ease; }
@media (hover: hover) { .about-intro-section .about-intro-img:hover { transform: scale(1.02); } }
.about-intro-section .gold-divider { width: 100px; height: 3px; background: var(--jb-gold-band); border-radius: 2px; }
@media (max-width: 991px) { .about-intro-section .intro-text-content { margin-top: 28px; } }
</style>
