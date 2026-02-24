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
                    <div class="gold-divider mx-auto mb-5" style="width: 100px; height: 3px; background: var(--gold-primary);"></div>
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
.section-label-gold {
    color: var(--gold-primary);
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: 2px;
}
.profile-hero-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3.5rem;
    font-weight: 700;
    color: #0a1628;
    line-height: 1.1;
}
.profile-intro-text {
    line-height: 1.8;
    font-size: 1.1rem;
}
.profile-circular-icon {
    width: 60px;
    height: 60px;
    background: var(--gold-primary);
    color: #0a1628;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 10px 20px rgba(201, 162, 39, 0.2);
}
.profile-item-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: #0a1628;
}
.profile-item-desc {
    font-size: 1.05rem;
    line-height: 1.7;
}
.profile-item {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.profile-item:last-child {
    border-bottom: none;
}
.tiny-icon {
    font-size: 0.8rem;
}
@media (max-width: 768px) {
    .profile-hero-title {
        font-size: 2.5rem;
    }
    .profile-item {
        flex-direction: column;
    }
    .profile-circular-icon {
        margin-bottom: 1.5rem;
    }
}
</style>
