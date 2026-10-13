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
/* CMS section: team (white-and-gold theme) */
.team-section .section-heading { font-family: var(--jb-serif); font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--jb-ink); }
.team-section .section-subheading { color: var(--jb-muted) !important; }
.team-section .team-card {
    padding: 2.2rem 1.4rem; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
    background: linear-gradient(180deg, #fffaf0, #fff 50%); box-shadow: var(--jb-shadow); transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
@media (hover: hover) { .team-section .team-card:hover { transform: translateY(-6px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); } }
.team-section .member-image img { width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 0 0 1px var(--jb-line-strong), var(--jb-shadow); background: var(--jb-panel-3); }
.team-section .member-name { margin-bottom: .25rem; font-family: var(--jb-serif); font-weight: 600; color: var(--jb-ink); }
.team-section .text-gold { color: var(--jb-gold-strong) !important; font-weight: 600; }
.team-section .member-bio { color: var(--jb-muted) !important; line-height: 1.5; }
</style>
