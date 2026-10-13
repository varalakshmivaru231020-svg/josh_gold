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
/* CMS section: visionary timeline (white-and-gold theme) */
.visionary-section { padding: 60px 0; }
.visionary-section .section-container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 16px; }
@media (min-width: 768px) { .visionary-section .section-container { padding: 0 24px; } }
.visionary-section .section-heading { font-family: var(--jb-serif); font-size: clamp(26px, 3.6vw, 36px); font-weight: 600; color: var(--jb-ink); }
.visionary-section .section-subheading { max-width: 800px; margin-left: auto; margin-right: auto; color: var(--jb-muted) !important; font-size: 1.05rem; }
.visionary-section .section-main-content { color: var(--jb-text); line-height: 1.8; }
.visionary-section .vision-connection-line {
    position: absolute; top: 50%; left: 10%; right: 10%; height: 2px; z-index: 0; transform: translateY(-50%);
    background: linear-gradient(90deg, transparent, var(--jb-line-strong) 20%, var(--jb-line-strong) 80%, transparent);
}
.visionary-section .vision-timeline-wrapper .glass-card { position: relative; z-index: 1; }
.visionary-section .text-gold { color: var(--jb-gold) !important; }
.visionary-section .glass-card {
    padding: 2.2rem; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
    background: linear-gradient(180deg, #fffaf0, #fff 50%); box-shadow: var(--jb-shadow);
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
@media (hover: hover) { .visionary-section .glass-card:hover { transform: translateY(-6px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); } }
.visionary-section .card-icon {
    width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.6rem;
    border-radius: 16px; background: #fbf1da; border: 1px solid #ecd49c; color: var(--jb-gold-strong); font-size: 1.5rem;
    transition: background-color .3s ease, color .3s ease;
}
.visionary-section .glass-card:hover .card-icon { background: var(--jb-gold-band); border-color: #b48b3c; color: #2a1d06; }
.visionary-section .card-title { margin-bottom: 1rem; font-family: var(--jb-serif); font-size: 1.35rem; font-weight: 600; color: var(--jb-ink); }
.visionary-section .card-text { color: var(--jb-text); line-height: 1.65; }
@media (max-width: 575px) { .visionary-section { padding: 40px 0; } .visionary-section .glass-card { padding: 1.6rem; } }
</style>
