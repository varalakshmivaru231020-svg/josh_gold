<section class="team-section py-5 fade-up">
    <div class="container text-center">
        @if($section->heading)
            <h2 class="section-heading mb-2">{{ $section->heading }}</h2>
        @endif
        @if($section->subheading)
            <p class="section-subheading text-muted mb-5">{{ $section->subheading }}</p>
        @endif

        <div class="row g-4">
            @if($section->cards)
                @foreach($section->cards as $member)
                    <div class="col-md-3">
                        <div class="team-card h-100">
                            <div class="member-image mb-3">
                                <img src="{{ $member['image'] ?? 'https://via.placeholder.com/300x300' }}" alt="{{ $member['name'] ?? '' }}" class="img-fluid rounded-circle">
                            </div>
                            <h4 class="member-name">{{ $member['name'] ?? '' }}</h4>
                            <p class="member-designation text-gold">{{ $member['designation'] ?? '' }}</p>
                            @if(isset($member['bio']))
                                <p class="member-bio small text-muted">{{ $member['bio'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<style>
.team-card {
    background: white;
    padding: 2.5rem 1.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}
.team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}
.member-image img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 4px solid var(--cream-primary);
}
.member-name {
    color: var(--navy-deep);
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.text-gold {
    color: var(--gold-primary);
    font-weight: 600;
}
.member-bio {
    line-height: 1.5;
}
</style>
