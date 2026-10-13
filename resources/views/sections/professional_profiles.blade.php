<section class="professional-profiles py-5 fade-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="profile-main-header text-center mb-5">
                    @if($section->subheading)
                        <p class="section-label-gold mb-2 text-uppercase letter-spacing-2">{{ $section->subheading }}</p>
                    @endif
                    @if($section->heading)
                        <h2 class="profile-hero-title mb-4">{{ $section->heading }}</h2>
                    @endif
                    <div class="gold-divider mx-auto mb-5" style="width: 100px; height: 3px; background: var(--jb-gold-band);"></div>
                    @if($section->content)
                        <div class="profile-intro-text lead text-muted mb-5 max-width-800 mx-auto">
                            {!! $section->content !!}
                        </div>
                    @endif
                </div>

                <div class="profile-list">
                    @if($section->cards)
                        @foreach($section->cards as $profile)
                            <div class="profile-item d-flex align-items-start mb-5 pb-4">
                                <div class="profile-circular-icon me-4 flex-shrink-0">
                                    @if(isset($profile['image']))
                                        <img src="{{ asset('storage/' . $profile['image']) }}" alt="{{ $profile['type'] ?? '' }}" class="w-100 h-100 rounded-circle object-fit-cover">
                                    @else
                                        <i class="{{ $profile['icon'] ?? 'fas fa-user-tie' }}"></i>
                                    @endif
                                </div>
                                <div class="profile-item-content">
                                    <h3 class="profile-item-title mb-2">{{ $profile['type'] ?? $profile['name'] }}</h3>
                                    <div class="profile-item-desc text-muted">
                                        {{ $profile['description'] ?? '' }}
                                        @if(isset($profile['achievements']) && is_array($profile['achievements']))
                                            <div class="mt-2">
                                                @foreach($profile['achievements'] as $achievement)
                                                    <span class="d-block mb-1"><i class="fas fa-check-circle text-gold me-2 tiny-icon"></i>{{ $achievement }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* CMS section: profiles (white-and-gold theme) */
.professional-profiles .section-label-gold { color: var(--jb-gold-strong); font-size: 11px; font-weight: 700; letter-spacing: .2em; }
.professional-profiles .profile-hero-title { font-family: var(--jb-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--jb-ink); line-height: 1.15; }
.professional-profiles .gold-divider { background: var(--jb-gold-band) !important; border-radius: 2px; }
.professional-profiles .profile-intro-text { color: var(--jb-text) !important; font-size: 1.05rem; line-height: 1.8; }
.professional-profiles .profile-item { padding: 22px; margin-bottom: 16px !important; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: #fff; box-shadow: var(--jb-shadow); }
.professional-profiles .profile-circular-icon {
    width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; overflow: hidden;
    border-radius: 50%; background: var(--jb-gold-band); color: #2a1d06; font-size: 1.4rem;
    border: 3px solid #fff; box-shadow: 0 0 0 1px var(--jb-line-strong), var(--jb-shadow);
}
.professional-profiles .profile-item-title { font-family: var(--jb-serif); font-size: 1.35rem; font-weight: 600; color: var(--jb-ink); }
.professional-profiles .profile-item-desc { color: var(--jb-text) !important; font-size: 1rem; line-height: 1.7; }
.professional-profiles .text-gold { color: var(--jb-gold) !important; }
.professional-profiles .tiny-icon { font-size: .8rem; }
@media (max-width: 768px) {
    .professional-profiles .profile-item { flex-direction: column; padding: 18px; }
    .professional-profiles .profile-circular-icon { margin-bottom: 1rem; }
}
</style>
