<section class="vision-mission-section py-5 fade-up">
    <div class="container">
        @if($section->heading)
            <div class="text-center mb-5">
                <h2 class="section-heading">{{ $section->heading }}</h2>
                @if($section->subheading)
                    <p class="vm-sub">{{ $section->subheading }}</p>
                @endif
            </div>
        @endif
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="vision-card p-5 h-100 shadow-lg">
                    <div class="icon-box mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="title">Our Vision</h3>
                    <div class="content">
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
                    <h3 class="title">Our Mission</h3>
                    <div class="content">
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
/* CMS section: vision and mission (white-and-gold theme: a gold-tinted band) */
.vision-mission-section {
    border-top: 1px solid var(--jb-line); border-bottom: 1px solid var(--jb-line);
    background: radial-gradient(800px 340px at 88% 0%, rgba(214, 178, 95, .22), transparent 60%),
        radial-gradient(600px 300px at 0% 100%, rgba(214, 178, 95, .14), transparent 60%), linear-gradient(180deg, #fffaf0, #fbf1da);
}
.vision-mission-section .section-heading { margin-bottom: 1rem; font-family: var(--jb-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--jb-ink); }
.vision-mission-section .vm-sub { color: var(--jb-muted); }
.vision-mission-section .vision-card, .vision-mission-section .mission-card {
    border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: #fff;
    box-shadow: var(--jb-shadow) !important; transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
@media (hover: hover) {
    .vision-mission-section .vision-card:hover, .vision-mission-section .mission-card:hover { transform: translateY(-6px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg) !important; }
}
.vision-mission-section .icon-box {
    width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;
    border-radius: 16px; background: var(--jb-gold-band); color: #2a1d06; font-size: 1.4rem;
    box-shadow: 0 10px 24px -14px rgba(138, 101, 22, .8);
}
.vision-mission-section .title { margin-bottom: 1.2rem; font-family: var(--jb-serif); font-size: 1.6rem; font-weight: 600; color: var(--jb-ink); }
.vision-mission-section .content { color: var(--jb-text); font-size: 1.05rem; line-height: 1.8; }
.vision-mission-section .content strong, .vision-mission-section .content b { color: var(--jb-ink); }
@media (max-width: 575px) { .vision-mission-section .vision-card, .vision-mission-section .mission-card { padding: 1.75rem !important; } }
</style>
