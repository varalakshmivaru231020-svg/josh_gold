@extends('layouts.lawfirm')

@section('title', 'Adv. Veerendrababu Nanjegowda | Bengaluru Law House')

@section('content')

<!-- ── HERO SECTION ── -->
<section class="hero-premium" id="home">
    <div class="hero-bg"></div>
    <div class="hero-grid-overlay"></div>
    <div class="hero-inner">
        <!-- Hero Text Content -->
        <div class="hero-text" data-aos="fade-right">
            <div class="hero-badge-lux">
                <span class="dot"></span>
                ADVOCATE &bull; AUTHOR &bull; FILMMAKER &bull; JOURNALIST
            </div>
            
            <h1 class="hero-name-alt">
                <span class="name-prefix">Adv.</span> 
                <span class="name-main">Veerendrababu Nanjegowda</span>
                <span class="kannada-subtitle">ವೀರೇಂದ್ರಬಾಬು ನಂಜೇಗೌಡ</span>
            </h1>

            <p class="hero-quals-alt">MBA &bull; LLB &bull; PHD (PURSUING)</p>

            <div class="hero-quote-block">
                <p>"A relentless guardian of justice, a storyteller of truth, and a voice that echoes across courtrooms and culture alike."</p>
            </div>
            
            <div class="hero-pills-alt">
                <span class="pill-tag">SUPREME COURT OF INDIA</span>
                <span class="pill-tag">HIGH COURTS</span>
                <span class="pill-tag">NGT</span>
                <span class="pill-tag">ADR SPECIALIST</span>
                <span class="pill-tag">15+ PUBLICATIONS</span>
            </div>

            <div class="hero-cta-group">
                <a href="{{ url('/contact-us') }}" class="btn-hero-solid">
                    <i class="fas fa-balance-scale icon-left"></i> Consult Now
                </a>
                <a href="{{ route('services.index') }}" class="btn-hero-outline">
                    View Practice Areas &rarr;
                </a>
            </div>
        </div>

        <!-- Hero Visual Content -->
        <div class="hero-visual" data-aos="fade-left" data-aos-delay="200">
            <div class="hero-visual-combo">
                <div class="hero-portrait-frame-lux">
                    <div class="bracket-tl"></div>
                    <div class="bracket-tr"></div>
                    <div class="bracket-bl"></div>
                    <div class="bracket-br"></div>
                    
                    <div class="portrait-display">
                        @if(isset($about) && $about->hero_image)
                            <img src="{{ asset('storage/' . $about->hero_image) }}" alt="Adv. Veerendrababu Nanjegowda">
                        @else
                            <img src="{{ asset('blr.jpg') }}" alt="Adv. Veerendrababu Nanjegowda">
                        @endif
                        
                        <div class="display-label">
                            <h4>Veerendrababu Nanjegowda</h4>
                            <p>MBA &bull; LLB &bull; PhD (Pursuing)</p>
                            <span class="role-tag">Advocate &bull; Author &bull; Filmmaker &bull; Journalist</span>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Stats Overlay -->
                <div class="hero-stats-row">
                    <div class="stat-card-lux" data-aos="zoom-in" data-aos-delay="400">
                        <span class="stat-val">3+</span>
                        <span class="stat-lbl">COURT TIERS</span>
                    </div>
                    <div class="stat-card-lux" data-aos="zoom-in" data-aos-delay="500">
                        <span class="stat-val">15+</span>
                        <span class="stat-lbl">BOOKS</span>
                    </div>
                    <div class="stat-card-lux" data-aos="zoom-in" data-aos-delay="600">
                        <span class="stat-val">2</span>
                        <span class="stat-lbl">OFFICES</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── OFFICIAL DIGITAL IDENTITY CARD ── -->
<section class="identity-card-section" id="identity-card">
    <div class="container" data-aos="fade-up">
        <div class="premium-id-card">
            <div class="row g-0">
                <!-- PHOTO COLUMN -->
                <div class="col-lg-5 id-photo-col">
                    <div class="id-frame-accents">
                        <div class="frame-edge edge-tl"></div>
                        <div class="frame-edge edge-bl"></div>
                    </div>
                    
                    @if(isset($about) && $about->id_card_image)
                        <img src="{{ asset('storage/' . $about->id_card_image) }}" alt="Adv. Veerendrababu Nanjegowda" class="id-main-image">
                    @elseif(isset($about) && $about->image)
                        <img src="{{ asset('storage/' . $about->image) }}" alt="Adv. Veerendrababu Nanjegowda" class="id-main-image">
                    @else
                        <img src="{{ asset('blr.jpg') }}" alt="Adv. Veerendrababu Nanjegowda" class="id-main-image">
                    @endif
                    
                    <div class="id-photo-overlay-text">
                        <h2 class="photo-name">Veerendrababu Nanjegowda</h2>
                        <p class="photo-designation">Advocate, Senior Journalist, Author, Film Maker</p>
                    </div>
                    <div class="photo-gradient-guard"></div>
                </div>

                <!-- CONTENT COLUMN -->
                <div class="col-lg-7 id-content-col">
                    <!-- ID Header -->
                    <div class="id-content-header">
                        <div class="id-brand-meta">
                            <div class="id-seal-wrapper">
                                @php
                                    $siteLogo = \App\Models\SiteSetting::get('site_logo');
                                @endphp
                                @if($siteLogo)
                                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Seal">
                                @else
                                    <div class="id-seal-fallback">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 65%; height: 65%;">
                                            <path d="M12 3L4 7V11C4 16.5 7.5 21.5 12 23C16.5 21.5 20 16.5 20 11V7L12 3Z" stroke="#0c1428" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="4" stroke="#0c1428" stroke-width="1.5"/>
                                            <line x1="12" y1="8" x2="12" y2="16" stroke="#0c1428" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="9" y1="12" x2="15" y2="12" stroke="#0c1428" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="id-brand-titles">
                                <h1 class="main-id-name">Veerendrababu Nanjegowda</h1>
                                <p class="main-id-quals">MBA · LLB · PhD (Pursuing)</p>
                            </div>
                        </div>
                        <div class="id-header-line"></div>
                        <div class="id-courts-jurisdiction">
                            NGT · High Courts · Supreme Court of India
                        </div>
                    </div>

                    <!-- ID Body Grid -->
                    <div class="id-content-body">
                        <div class="row g-0 h-100">
                            <!-- Left: Basic Info -->
                            <div class="col-md-7 contact-info-panel">
                                <!-- Phone -->
                                <div class="id-info-block">
                                    <label class="id-label">PHONE</label>
                                    <div class="id-data-row">
                                        <i class="fas fa-diamond id-bullet"></i>
                                        <a href="tel:+918041502373">+91 80 4150 2373</a>
                                    </div>
                                    <div class="id-data-row">
                                        <i class="fas fa-diamond id-bullet"></i>
                                        <a href="tel:+919632527961">+91 96325 27961</a>
                                    </div>
                                </div>
                                
                                <div class="id-block-divider">
                                    <div class="divider-dot"></div>
                                </div>

                                <!-- Email -->
                                <div class="id-info-block">
                                    <label class="id-label">EMAIL</label>
                                    <div class="id-data-row">
                                        <i class="fas fa-diamond id-bullet"></i>
                                        <a href="mailto:bengalurulawhouse@gmail.com">bengalurulawhouse@gmail.com</a>
                                    </div>
                                </div>

                                <div class="id-block-divider">
                                    <div class="divider-dot"></div>
                                </div>

                                <!-- Website -->
                                <div class="id-info-block">
                                    <label class="id-label">WEBSITE</label>
                                    <div class="id-data-row">
                                        <i class="fas fa-diamond id-bullet"></i>
                                        <a href="http://www.blrlawhouse.com" target="_blank">www.blrlawhouse.com</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Office Addresses -->
                            <div class="col-md-5 office-info-panel">
                                <!-- Delhi Office -->
                                <div class="id-info-block mb-4">
                                    <label class="id-label">NEW DELHI OFFICE</label>
                                    <p class="id-address">
                                        Lawyer's Chambers,<br>
                                        Supreme Court of India,<br>
                                        New Delhi – 110001
                                    </p>
                                </div>

                                <div class="divider-dot-vertical"></div>

                                <!-- Bengaluru Office -->
                                <div class="id-info-block">
                                    <label class="id-label">BENGALURU OFFICE</label>
                                    <p class="id-address">
                                        #1652/6, 20th Cross, 8th Main,<br>
                                        CQAL Layout, C-Block,<br>
                                        Sahakaranagar,<br>
                                        Bengaluru – 560092
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Glow background effect -->
            <div class="id-glow-effect"></div>
        </div>
    </div>
</section>

<!-- ── ABOUT / BIOGRAPHY ── -->
<section class="section-premium section-cream" id="about" style="position: relative; z-index: 5; margin-top: -2px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="about-visual pe-lg-5">
                    @if(isset($about) && $about->image)
                        <img src="{{ asset('storage/' . $about->image) }}" alt="Biography" class="img-fluid rounded-4 shadow-lg border border-gold border-4">
                    @elseif($about?->image)
                        <img src="{{ asset('storage/' . $about->image) }}" alt="Biography" class="img-fluid rounded-4 shadow-lg">
                    @else
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=500" alt="Biography" class="img-fluid rounded-4 shadow-lg">
                    @endif
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="about-content">
                    <span class="label-gold">Biography</span>
                    <h2 class="title-premium text-navy">{{ $about?->title ?? 'Bridging the Gap Between Law & Literature' }}</h2>
                    <div class="gold-separator"></div>
                    <div class="lead fw-normal text-muted mb-4 rich-text-summary">
                        {!! $about?->content ?? 'Adv. Veerendrababu Nanjegowda is a distinguished legal luminary with multifaceted expertise spanning Law, Business, Journalism, and Cinema.' !!}
                    </div>
                    

                    {{-- Quote Block: dark navy background, styled like reference design --}}
                    <div class="about-quote-block">
                        <span class="about-quote-mark">&ldquo;</span>
                        <p class="about-quote-text">Justice is not a privilege for the few — it is the birthright of every citizen. My practice is a promise to make it accessible, fair, and meaningful.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── PRACTICE AREAS ── -->
<section class="practice-areas-section" id="practice">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="pas-eyebrow">AREAS OF PRACTICE</span>
            <h2 class="pas-heading">Practicing at the Pinnacles of India's Judiciary</h2>
            <div class="pas-separator mx-auto"></div>
            <p class="pas-subtext">Appearing before the <span class="text-decoration-underline" style="color: var(--gold-dark);">National Green Tribunal</span>, <span class="text-decoration-underline" style="color: var(--gold-dark);">High Courts</span> and the <span class="text-decoration-underline" style="color: var(--gold-dark);">Supreme Court of India</span>, Advocate Veerendrababu brings unmatched expertise across a wide spectrum of legal domains.</p>
        </div>

        <div class="pas-grid">
            @forelse($services as $index => $service)
            <div class="pas-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                <div class="pas-card-icon">
                    @if($service->icon && str_starts_with($service->icon, 'fa'))
                        <i class="{{ $service->icon }}"></i>
                    @else
                        <span class="pas-emoji">{{ $service->icon ?? '⚖️' }}</span>
                    @endif
                </div>
                <h3 class="pas-card-title">{{ $service->title }}</h3>
                <p class="pas-card-text">{{ $service->description }}</p>
            </div>
            @empty
            {{-- Fallback static cards - EXACTLY as per request --}}
            <div class="pas-card" data-aos="fade-up" data-aos-delay="0">
                <div class="pas-card-icon"><i class="fas fa-balance-scale" style="color: #c9a84c;"></i></div>
                <h3 class="pas-card-title">Alternative Dispute Resolution</h3>
                <p class="pas-card-text">Specialist in ADR for civil disputes — mediation, arbitration, conciliation & negotiation.</p>
            </div>
            <div class="pas-card" data-aos="fade-up" data-aos-delay="80">
                <div class="pas-card-icon"><i class="fas fa-university" style="color: #4a5568;"></i></div>
                <h3 class="pas-card-title">Banking & Finance Law</h3>
                <p class="pas-card-text">Expert counsel in complex banking issues, financial litigation & regulatory matters.</p>
            </div>
            <div class="pas-card" data-aos="fade-up" data-aos-delay="160">
                <div class="pas-card-icon"><i class="fas fa-lock" style="color: #e4b439;"></i></div>
                <h3 class="pas-card-title">Criminal Law</h3>
                <p class="pas-card-text">Strategic and compassionate representation across criminal proceedings at all levels.</p>
            </div>
            <div class="pas-card" data-aos="fade-up" data-aos-delay="240">
                <div class="pas-card-icon"><i class="fas fa-leaf" style="color: #48bb78; opacity: 0.8;"></i></div>
                <h3 class="pas-card-title">Environmental Law</h3>
                <p class="pas-card-text">Practising before the National Green Tribunal on environmental and ecological disputes.</p>
            </div>
            <div class="pas-card" data-aos="fade-up" data-aos-delay="320">
                <div class="pas-card-icon"><i class="fas fa-landmark" style="color: #2d3748;"></i></div>
                <h3 class="pas-card-title">Supreme Court Practice</h3>
                <p class="pas-card-text">Representing clients in matters of national importance before India's apex court.</p>
            </div>
            <div class="pas-card" data-aos="fade-up" data-aos-delay="400">
                <div class="pas-card-icon"><i class="fas fa-edit" style="color: #f56565; opacity: 0.8;"></i></div>
                <h3 class="pas-card-title">Civil Disputes</h3>
                <p class="pas-card-text">Comprehensive civil litigation covering property, contracts, family & commercial matters.</p>
            </div>
            @endforelse
        </div>

        {{-- Quote Banner --}}
        <div class="pas-quote-banner" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-quote-left pas-quote-mark"></i>
            <p class="pas-quote-text">"The best legal victory is one that heals, not one that merely wins. ADR is not a compromise — it is wisdom in action."</p>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('services.index') }}" class="btn-outline-navy">
                View All Practice Areas <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

@if(isset($featuredBook))
<section class="section-premium section-navy" id="featured-book" style="background: #0c1428; position: relative; overflow: hidden; padding-bottom: 80px;">
    <div class="hero-grid-overlay opacity-10"></div>
    <div class="container" data-aos="zoom-in">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center">
                <div class="fb-mockup">
                    @if($featuredBook->image)
                        <img src="{{ asset('storage/' . $featuredBook->image) }}" alt="{{ $featuredBook->book_name }}" class="fb-img">
                    @else
                        <img src="https://via.placeholder.com/400x550" alt="Book Cover" class="fb-img">
                    @endif
                    <div class="fb-glow"></div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <span class="label-gold">{{ $featuredBook->title ?? 'Featured Publication' }}</span>
                <h2 class="fb-title mb-2">{{ $featuredBook->book_name ?? "Thanks Appa" }}</h2>
                <div class="fb-divider mb-4"></div>
                
                <div class="fb-desc mb-4">
                    {!! $featuredBook->description ?? "A deeply emotional Kannada novel — a son's tribute to a father's wisdom, discipline, and unconditional love. Blending business insights with heartfelt storytelling, this work shares timeless life lessons wrapped in the warmth of Appa's voice." !!}
                </div>

                <div class="fb-price-box mb-4">
                    <span class="fb-price-label">Price: </span>
                    <span class="fb-price-gold">₹{{ number_format($featuredBook->price ?? 499.00, 2) }}</span>
                </div>

                <a href="{{ $featuredBook->product_id ? route('products.show', $featuredBook->product_id) : route('products.index') }}" class="btn-purchase-lux">
                    <i class="fas fa-shopping-cart me-2"></i> {{ $featuredBook->button_text ?? 'PURCHASE NOW' }}
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── PUBLICATIONS & BOOKS ── -->
<section class="pub-section" id="books">
    <div class="container">
        <div class="pub-header" data-aos="fade-up">
            <span class="pub-eyebrow">PUBLICATIONS &amp; BOOKS</span>
            <h2 class="pub-heading">A Prolific Author — Law, Literature &amp; Life</h2>
            <div class="pub-separator"></div>
            <p class="pub-subtext">From landmark legal guides to heartfelt novels — Advocate Veerendrababu is a celebrated Kannada author whose 15+ works have touched thousands of readers, students, and legal professionals across Karnataka and beyond.</p>
        </div>

        @php
            $homeProducts = \App\Models\Product::active()->take(4)->get();
        @endphp

        <div class="pub-cards-grid">
            @forelse($homeProducts as $index => $product)
            <div class="pub-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                @if($product->year)
                <div class="pub-badge">{{ $product->year }}</div>
                @endif
                <div class="pub-cover">
                    <div class="pub-icon-bg">
                        <span class="pub-emoji">{{ $product->book_icon ?? '📚' }}</span>
                    </div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">{{ $product->name }}</h3>
                    </div>
                </div>
                @if($product->category)
                <div class="pub-category-tag">{{ strtoupper($product->category) }}</div>
                @endif
                <div class="pub-card-body">
                    <p class="pub-desc">{{ Str::limit(strip_tags($product->description), 180) }}</p>
                    {{-- 
                    <div class="mt-4">
                        <a href="{{ route('products.show', $product) }}" class="btn-royal-sm">
                            View Details <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div> 
                    --}}
                </div>
            </div>
            @empty
            {{-- Fallback cards as per request --}}
            <div class="pub-card" data-aos="fade-up" data-aos-delay="0">
                <div class="pub-badge">2018</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">🌾</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Swayam Krushi</h3>
                    </div>
                </div>
                <div class="pub-category-tag">KANNADA LITERATURE</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A landmark Kannada work on self-sustaining agriculture, championing the dignity, wisdom, and resilience of farmers. Beloved across rural Karnataka, this impactful book was later adapted into a celebrated documentary.</p>
                </div>
            </div>
            <div class="pub-card" data-aos="fade-up" data-aos-delay="100">
                <div class="pub-badge">2026</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">💛</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Thanks Appa</h3>
                    </div>
                </div>
                <div class="pub-category-tag">KANNADA NOVEL</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A deeply emotional Kannada novel — a son’s tribute to a father’s wisdom, discipline, and unconditional love. Blending business insights with heartfelt storytelling, this work is a must-read for the new generation.</p>
                </div>
            </div>
            <div class="pub-card" data-aos="fade-up" data-aos-delay="200">
                <div class="pub-badge">Legal</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">⚖️</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">State v/s Abhiram</h3>
                    </div>
                </div>
                <div class="pub-category-tag">LEGAL PUBLICATION</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A significant legal publication examining criminal justice through the lens of a landmark case. Written with the precision of an experienced advocate and the clarity of a storyteller.</p>
                </div>
            </div>
            <div class="pub-card" data-aos="fade-up" data-aos-delay="300">
                <div class="pub-badge">12-Volume</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">📚</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Dainandina Jeevanakkagi Kanunu</h3>
                    </div>
                </div>
                <div class="pub-category-tag">12-VOLUME LEGAL</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A monumental 12-volume legal guide series in Kannada — making the law accessible to every citizen in their own language. An invaluable resource for homes, professionals, and students.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Publication Gallery Photos --}}
        @php
            $pubPhotos = isset($galleryPhotos) ? $galleryPhotos->take(4) : collect([]);
        @endphp
        @if($pubPhotos->count() > 0)
        <div class="pub-gallery-row" data-aos="fade-up" data-aos-delay="100">
            @foreach($pubPhotos as $photo)
            <div class="pub-gallery-item">
                <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title ?? 'Publication Event' }}" loading="lazy">
                @if($photo->caption || $photo->title)
                <div class="pub-gallery-overlay">
                    <p class="pub-gallery-caption">{{ $photo->caption ?? $photo->title }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
        

    </div>
</section>


{{-- STATS SECTION HIDDEN --}}
{{-- @if(isset($stats) && $stats->count() > 0)
<section class="section-premium section-navy">
    ... counters ...
</section>
@endif --}}

<!-- ── BEYOND THE BAR SECTION ── -->
@php
    $bbEyebrow  = \App\Models\SiteSetting::get('bb_eyebrow', 'BEYOND THE BAR');
    $bbTitle    = \App\Models\SiteSetting::get('bb_title', 'Filmmaker, Journalist & Cultural Visionary');
    $bbImgTop   = \App\Models\SiteSetting::get('bb_image_top');
    $bbImgLeft  = \App\Models\SiteSetting::get('bb_image_left');
    $bbImgRight = \App\Models\SiteSetting::get('bb_image_right');
    
    $bbItem1T   = \App\Models\SiteSetting::get('bb_item1_title', 'Filmmaker, Director & Lead Actor');
    $bbItem1D   = \App\Models\SiteSetting::get('bb_item1_desc', 'Produced, directed and acted in the Kannada film Swayam Krushi — transforming stories of farmers and the soil into powerful cinema that honours Karnataka\'s rural heritage.');
    $bbItem1I   = \App\Models\SiteSetting::get('bb_item1_icon', 'fas fa-clapperboard');
    
    $bbItem2T   = \App\Models\SiteSetting::get('bb_item2_title', 'Senior Journalist & Chief Editor');
    $bbItem2D   = \App\Models\SiteSetting::get('bb_item2_desc', 'Served as Chief Editor of Mussanje (Kannada evening daily) and editorial head of V News 24 (Kannada TV channel) — a career built on truth, accountability and fearless reporting.');
    $bbItem2I   = \App\Models\SiteSetting::get('bb_item2_icon', 'fas fa-newspaper');
    
    $bbItem3T   = \App\Models\SiteSetting::get('bb_item3_title', 'Prolific Kannada Author — 15+ Works');
    $bbItem3D   = \App\Models\SiteSetting::get('bb_item3_desc', 'From Swayam Krushi to Thanks Appa to State vs Abhiram and the 12-volume Dainandina Jeevanakkagi Kanunu series — his literary output is a gift to Karnataka\'s language and legal awareness.');
    $bbItem3I   = \App\Models\SiteSetting::get('bb_item3_icon', 'fas fa-feather-pointed');
@endphp
<section class="beyond-bar-section" id="beyond-the-bar">
    <div class="container">
        <div class="beyond-bar-heading-block" data-aos="fade-up">
            <span class="bb-eyebrow">{{ $bbEyebrow }}</span>
            <h2 class="bb-title">{{ $bbTitle }}</h2>
            <div class="bb-accent-line"></div>
        </div>

        <div class="beyond-bar-grid">
            <!-- Left Side: Timeline List -->
            <div class="bb-timeline-col" data-aos="fade-right">
                <div class="bb-timeline">
                    <!-- Item 1: Filmmaker -->
                    <div class="bb-item">
                        <div class="bb-icon-box">
                            <i class="{{ $bbItem1I }}"></i>
                        </div>
                        <div class="bb-content">
                            <h4 class="bb-item-title">{{ $bbItem1T }}</h4>
                            <p class="bb-item-desc">{!! nl2br(e($bbItem1D)) !!}</p>
                        </div>
                    </div>
                    <!-- Item 2: Journalist -->
                    <div class="bb-item">
                        <div class="bb-icon-box">
                            <i class="{{ $bbItem2I }}"></i>
                        </div>
                        <div class="bb-content">
                            <h4 class="bb-item-title">{{ $bbItem2T }}</h4>
                            <p class="bb-item-desc">{!! nl2br(e($bbItem2D)) !!}</p>
                        </div>
                    </div>
                    <!-- Item 3: Author -->
                    <div class="bb-item">
                        <div class="bb-icon-box">
                            <i class="{{ $bbItem3I }}"></i>
                        </div>
                        <div class="bb-content">
                            <h4 class="bb-item-title">{{ $bbItem3T }}</h4>
                            <p class="bb-item-desc">{!! nl2br(e($bbItem3D)) !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Dynamic Gallery Grid -->
            <div class="bb-gallery-col" data-aos="fade-left">
                <div class="bb-gallery-grid">
                    <div class="bb-gallery-top">
                        <img src="{{ $bbImgTop ? asset('storage/' . $bbImgTop) : 'https://images.unsplash.com/photo-1542204113-e93847e202af?auto=format&fit=crop&q=80&w=1000' }}"
                             alt="Filmmaking Craft" class="bb-img">
                    </div>
                    <div class="bb-gallery-bottom">
                        <div class="bb-img-wrap">
                            <img src="{{ $bbImgLeft ? asset('storage/' . $bbImgLeft) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&q=80&w=600' }}"
                                 alt="Journalism" class="bb-img">
                        </div>
                        <div class="bb-img-wrap">
                            <img src="{{ $bbImgRight ? asset('storage/' . $bbImgRight) : 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&q=80&w=600' }}"
                                 alt="Authoring" class="bb-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── OFFICES ── -->
<section class="offices-section" id="offices">
    <div class="container">
        {{-- Header centered --}}
        <div class="offices-header text-center mx-auto" data-aos="fade-up">
            <span class="pas-eyebrow" style="color:var(--gold-dark);">OUR OFFICES</span>
            <h2 class="offices-heading">Two Offices. One Commitment to Justice.</h2>
            <div class="pas-separator mx-auto" style="margin:14px auto 20px;"></div>
            <p class="offices-subtext mx-auto">Strategically located in <strong>Bengaluru</strong> and at the <strong style="text-decoration:underline; color:var(--gold-dark);">Supreme Court of India in New Delhi</strong>,<br>enabling seamless legal service at state and national apex level.</p>
        </div>

        {{-- Photo Grid --}}
        <div class="offices-img-grid" data-aos="fade-up" data-aos-delay="100">
            @forelse($offices as $index => $office)
                @if($office->image)
                <div class="offices-img-item {{ $index === 0 ? 'offices-img-tall' : '' }}">
                    <img src="{{ asset('storage/' . $office->image) }}" alt="{{ $office->title }}" loading="lazy">
                </div>
                @endif
            @empty
                {{-- no images yet --}}
            @endforelse
        </div>

        {{-- Info Cards --}}
        <div class="offices-cards-grid" data-aos="fade-up" data-aos-delay="200">
            @forelse($offices as $index => $office)
                @php
                    $isBlr = str_contains(strtolower($office->title), 'bengaluru');
                    $isDelhi = str_contains(strtolower($office->title), 'delhi');
                    
                    // Force exact colors from reference
                    $cCol = $isDelhi ? '#8b1a24' : ($isBlr ? '#1a2e5e' : ($office->color ?? '#1a2e5e'));
                    
                    $hIcon = $isBlr ? 'fa-solid fa-city' : ($isDelhi ? 'fa-solid fa-landmark' : 'fa-solid fa-building');
                    $hIconClass = $isBlr ? 'ocl-header-icon-blue' : 'ocl-header-icon-white';
                @endphp
                <div class="office-card-lux">
                    <div class="ocl-header" style="background: {{ $cCol }};">
                        <div class="ocl-icon-box">
                            <i class="{{ $hIcon }} {{ $hIconClass }}"></i>
                        </div>
                        <div class="ocl-header-text">
                            <h3 class="ocl-title">{{ $office->title }}</h3>
                            @if($office->subtitle)
                                <p class="ocl-subtitle">{{ strtoupper($office->subtitle) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="ocl-body">
                        @if($office->address)
                            <div class="ocl-row">
                                <i class="fas fa-map-marker-alt ocl-icon ocl-pin"></i>
                                <span>{{ $office->address }}</span>
                            </div>
                        @endif
                        @if($office->phone)
                            <div class="ocl-row">
                                <i class="fas fa-phone-alt ocl-icon ocl-phone"></i>
                                <a href="tel:{{ str_replace(['+', ' ', '(', ')', '-'], '', $office->phone) }}" class="ocl-link">
                                    {{ $office->phone }}
                                </a>
                            </div>
                        @endif
                        @if($office->mobile)
                            <div class="ocl-row">
                                <i class="fas fa-mobile-alt ocl-icon ocl-mobile"></i>
                                <a href="tel:{{ str_replace(['+', ' ', '(', ')', '-'], '', $office->mobile) }}" class="ocl-link">
                                    {{ $office->mobile }}
                                </a>
                            </div>
                        @endif
                        @if($office->email)
                            <div class="ocl-row">
                                <i class="fas fa-envelope ocl-icon ocl-email"></i>
                                <a href="mailto:{{ $office->email }}" class="ocl-link">
                                    {{ $office->email }}
                                </a>
                            </div>
                        @endif
                        @if($office->website)
                            <div class="ocl-row">
                                <i class="fas fa-globe ocl-icon ocl-web"></i>
                                <a href="http://{{ str_replace(['http://', 'https://'], '', $office->website) }}" target="_blank" class="ocl-link">
                                    {{ $office->website }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Fallback Cards matched to screenshot --}}
                <div class="office-card-lux">
                    <div class="ocl-header" style="background: #1a2e5e;">
                        <div class="ocl-icon-box"><i class="fa-solid fa-city ocl-header-icon-blue"></i></div>
                        <div class="ocl-header-text">
                            <h3 class="ocl-title">Bengaluru Office</h3>
                            <p class="ocl-subtitle">BENGALURU LAW HOUSE • KARNATAKA • HIGH COURTS • NGT</p>
                        </div>
                    </div>
                    <div class="ocl-body">
                        <div class="ocl-row"><i class="fas fa-map-marker-alt ocl-icon ocl-pin"></i><span>#1652/6, 20th Cross, 8th Main, CQAL Layout, C-Block, Sahakaranagar, Bengaluru – 560092</span></div>
                        <div class="ocl-row">
                            <i class="fas fa-phone-alt ocl-icon ocl-phone"></i>
                            <a href="tel:+918041502373" class="ocl-link">+91 80 4150 2373</a>
                        </div>
                        <div class="ocl-row">
                            <i class="fas fa-mobile-alt ocl-icon ocl-mobile"></i>
                            <a href="tel:+919632527961" class="ocl-link">+91 96325 27961</a>
                        </div>
                        <div class="ocl-row">
                            <i class="fas fa-envelope ocl-icon ocl-email"></i>
                            <a href="mailto:bengalurulawhouse@gmail.com" class="ocl-link">bengalurulawhouse@gmail.com</a>
                        </div>
                        <div class="ocl-row">
                            <i class="fas fa-globe ocl-icon ocl-web"></i>
                            <a href="https://www.blrlawhouse.com" target="_blank" class="ocl-link">www.blrlawhouse.com</a>
                        </div>
                    </div>
                </div>
                <div class="office-card-lux">
                    <div class="ocl-header" style="background: #8b1a24;">
                        <div class="ocl-icon-box"><i class="fa-solid fa-landmark ocl-header-icon-white"></i></div>
                        <div class="ocl-header-text">
                            <h3 class="ocl-title">New Delhi Office</h3>
                            <p class="ocl-subtitle">SUPREME COURT OF INDIA • NGT NEW DELHI</p>
                        </div>
                    </div>
                    <div class="ocl-body">
                        <div class="ocl-row"><i class="fas fa-map-marker-alt ocl-icon ocl-pin"></i><span>Lawyer's Chambers, Supreme Court of India, New Delhi – 110001</span></div>
                        <div class="ocl-row">
                            <i class="fas fa-mobile-alt ocl-icon ocl-mobile"></i>
                            <a href="tel:+919632527961" class="ocl-link">+91 96325 27961</a>
                        </div>
                        <div class="ocl-row">
                            <i class="fas fa-envelope ocl-icon ocl-email"></i>
                            <a href="mailto:bengalurulawhouse@gmail.com" class="ocl-link">bengalurulawhouse@gmail.com</a>
                        </div>
                        <div class="ocl-row">
                            <i class="fas fa-globe ocl-icon ocl-web"></i>
                            <a href="https://www.blrlawhouse.com" target="_blank" class="ocl-link">www.blrlawhouse.com</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── PHOTO GALLERY ── -->
@if(isset($galleryPhotos) && $galleryPhotos->count() > 0)
<section class="photo-gallery-section" id="gallery-photos">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="pas-eyebrow">PHOTO GALLERY</span>
            <h2 class="pas-heading" style="color: var(--navy);">Moments Across Courtrooms, Culture &amp; Community</h2>
            <div class="pas-separator mx-auto"></div>
        </div>

        <div class="pg-mosaic" data-aos="fade-up" data-aos-delay="100">
            @foreach($galleryPhotos as $index => $photo)
            <div class="pg-item {{ $index === 0 ? 'pg-tall' : '' }}" data-aos="zoom-in" data-aos-delay="{{ min($index * 60, 400) }}">
                <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title ?? 'Gallery Photo' }}" loading="lazy">
                @if($photo->caption || $photo->title)
                <div class="pg-overlay">
                    <p class="pg-caption">{{ $photo->caption ?? $photo->title }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


@endsection

@push('styles')
<style>
    /* ── PRACTICE AREAS SECTION ── */
    .practice-areas-section {
        background: #ffffff;
        padding: 100px 0 80px;
    }
    .pas-eyebrow {
        display: block; font-size: 0.75rem; letter-spacing: 4px; font-weight: 800;
        color: var(--gold-dark, #b8913a); text-transform: uppercase; margin-bottom: 16px;
    }
    .pas-heading {
        font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3rem);
        color: var(--navy, #0c1428); font-weight: 700; margin-bottom: 14px; line-height: 1.2;
    }
    .pas-separator {
        width: 60px; height: 3px; background: var(--gold, #c9a84c);
        margin: 0 auto 24px; border-radius: 2px;
    }
    .pas-subtext {
        max-width: 660px; margin: 0 auto; color: #555;
        font-size: 1.05rem; line-height: 1.75;
    }

    /* Card Grid */
    .pas-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 20px;
    }
    .pas-card {
        background: #ffffff;
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        padding: 40px 30px 40px;
        transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .pas-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: var(--gold, #c9a84c);
        opacity: 1; transition: transform 0.35s;
    }
    .pas-card:hover {
        box-shadow: 0 20px 60px rgba(0,0,0,0.10);
        border-color: rgba(201,168,76,0.35);
        transform: translateY(-6px);
    }
    .pas-card:hover::before { transform: scaleX(1.1); }

    .pas-card-icon {
        margin-bottom: 22px;
        line-height: 1;
        display: flex;
        justify-content: center;
    }
    .pas-emoji {
        font-size: 2.5rem;
        display: block;
    }
    .pas-card-icon i {
        font-size: 2.2rem;
        color: var(--gold-dark, #b8913a);
    }
    .pas-card-title {
        font-family: var(--font-display); font-size: 1.08rem;
        color: var(--navy, #0c1428); font-weight: 700; margin-bottom: 12px; line-height: 1.3;
    }
    .pas-card-text {
        font-size: 0.9rem; color: #666; line-height: 1.7; margin: 0;
    }

    /* Quote Banner */
    .pas-quote-banner {
        background: var(--navy, #0c1428);
        border-radius: 16px;
        padding: 40px 60px;
        margin-top: 50px;
        display: flex;
        align-items: center;
        gap: 28px;
        position: relative;
        overflow: hidden;
    }
    .pas-quote-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(201,168,76,0.06) 0%, transparent 60%);
    }
    .pas-quote-mark {
        font-size: 2rem; color: var(--gold, #c9a84c); opacity: 0.6; flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .pas-quote-text {
        font-family: var(--font-display); font-size: 1.08rem; font-style: italic;
        color: rgba(255,255,255,0.88); margin: 0; line-height: 1.65;
        position: relative; z-index: 1;
    }

    @media (max-width: 991px) {
        .pas-grid { grid-template-columns: repeat(2, 1fr); }
        .pas-quote-banner { padding: 30px 35px; }
    }
    @media (max-width: 576px) {
        .pas-grid { grid-template-columns: 1fr; }
        .pas-quote-banner { flex-direction: column; text-align: center; padding: 28px 24px; }
    }

    /* ── OFFICES SECTION ── */
    .offices-section {
        background: #ffffff;
        padding: 100px 0 100px;
    }
    .offices-header {
        max-width: 800px;
        margin-bottom: 60px;
    }
    .offices-heading {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3rem);
        color: var(--navy);
        font-weight: 700;
        margin: 10px 0 0;
        line-height: 1.2;
    }
    .offices-subtext {
        color: #555;
        font-size: 1.05rem;
        line-height: 1.8;
        margin: 0;
        max-width: 700px;
    }

    /* Photo grid */
    .offices-img-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 40px;
    }
    .offices-img-item {
        border-radius: 12px;
        overflow: hidden;
        min-height: 280px;
    }
    .offices-img-tall { min-height: 420px; }
    .offices-img-item img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform 0.5s ease;
    }
    .offices-img-item:hover img { transform: scale(1.04); }

    /* ── OFFICE CARDS LUX (MATCH SCREENSHOT) ── */
    .offices-cards-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .office-card-lux {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 45px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .office-card-lux:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.12);
    }
    .ocl-header {
        padding: 25px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: #ffffff;
    }
    .ocl-icon-box {
        width: 52px;
        height: 52px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        backdrop-filter: blur(8px);
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
    }
    .ocl-header-text {
        display: flex;
        flex-direction: column;
    }
    .ocl-title {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }
    .ocl-subtitle {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.8;
        margin-top: 5px;
    }
    .ocl-body {
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .ocl-row {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        color: #444;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .ocl-icon {
        width: 18px;
        font-size: 1rem;
        margin-top: 3px;
        text-align: center;
        flex-shrink: 0;
    }
    
    /* Icon Colors from Reference - EXACT SHADES */
    .ocl-pin { color: #ff4d4d; }    /* Vibrant Red/Pink */
    .ocl-phone { color: #8b1a24; }  /* Deep Maroon */
    .ocl-mobile { color: #8b1a24; } /* Deep Maroon */
    .ocl-email { color: #6f42c1; }  /* Purple */
    .ocl-web { color: #007bff; }    /* Bright Blue */

    .ocl-header-icon-white { color: #ffffff !important; }
    .ocl-header-icon-blue { color: #60c0ff !important; } /* Electric Blue for Bengaluru */

    .ocl-link {
        color: inherit;
        text-decoration: none;
        transition: 0.3s;
    }
    .ocl-link:hover {
        color: var(--gold-dark);
    }

    @media (max-width: 991px) {
        .offices-cards-grid { gap: 20px; }
    }
    @media (max-width: 768px) {
        .offices-cards-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .offices-img-grid { grid-template-columns: 1fr; }
        .offices-img-tall { min-height: 260px; }
        .offices-cards-grid { grid-template-columns: 1fr; }
    }

    /* ── PHOTO GALLERY SECTION ── */
    .photo-gallery-section {
        background: var(--navy, #0c1428);
        padding: 100px 0 90px;
    }
    .photo-gallery-section .pas-heading { color: white !important; }
    .photo-gallery-section .pas-eyebrow { color: var(--gold) !important; }

    .pg-mosaic {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: 220px;
        gap: 14px;
    }
    .pg-item {
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        cursor: pointer;
    }
    .pg-tall { grid-row: span 2; }

    .pg-item img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.55s cubic-bezier(0.2, 0.8, 0.2, 1);
        display: block;
    }
    .pg-item:hover img { transform: scale(1.08); }

    .pg-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 55%);
        display: flex; align-items: flex-end;
        padding: 18px 16px;
        opacity: 0; transition: opacity 0.35s;
    }
    .pg-item:hover .pg-overlay { opacity: 1; }
    .pg-caption {
        color: white; font-size: 0.85rem; font-weight: 600;
        margin: 0; line-height: 1.3; letter-spacing: 0.5px;
    }

    @media (max-width: 991px) {
        .pg-mosaic { grid-template-columns: repeat(3, 1fr); grid-auto-rows: 180px; }
    }
    @media (max-width: 600px) {
        .pg-mosaic { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 150px; }
        .pg-tall { grid-row: span 1; }
    }

    /* ── FOLLOW ONLINE SECTION ── */
    .follow-online-section {
        background: #ffffff;
        padding: 100px 0;
    }
    .follow-eyebrow {
        display: block; font-size: 0.78rem; letter-spacing: 4px;
        color: var(--gold-dark); font-weight: 800; text-transform: uppercase; margin-bottom: 15px;
    }
    .follow-heading {
        font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3rem);
        color: var(--navy); font-weight: 700; margin-bottom: 15px;
    }
    .follow-separator {
        width: 60px; height: 3px; background: var(--gold); margin: 0 auto 20px; border-radius: 2px;
    }
    .follow-subtext {
        max-width: 600px; margin: 0 auto; color: #666; font-size: 1.05rem; line-height: 1.7;
    }
    .follow-cards-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
        max-width: 950px; margin: 0 auto;
    }
    .follow-card {
        display: flex; align-items: center; gap: 22px;
        padding: 28px 30px; border-radius: 18px;
        text-decoration: none; transition: all 0.35s cubic-bezier(0.2,0.8,0.2,1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.18);
    }
    .follow-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 25px 55px rgba(0,0,0,0.3);
    }
    .follow-card-icon {
        width: 52px; height: 52px; background: rgba(255,255,255,0.2);
        border-radius: 14px; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: white; flex-shrink: 0;
    }
    .follow-card-info { display: flex; flex-direction: column; gap: 4px; }
    .follow-card-platform {
        font-size: 0.68rem; letter-spacing: 2.5px; font-weight: 800;
        color: rgba(255,255,255,0.7); text-transform: uppercase;
    }
    .follow-card-handle {
        font-size: 1rem; font-weight: 700; color: white;
        font-family: var(--font-display); line-height: 1.3;
    }

    /* ── CONTACT DARK SECTION ── */
    .home-contact-dark {
        background: var(--navy-deep, #0a0f1e);
        padding: 100px 0 80px;
    }
    .hcd-heading {
        font-family: var(--font-display); font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: white; font-weight: 700; margin-bottom: 15px;
    }
    .hcd-subtext { max-width: 650px; margin: 0 auto; color: rgba(255,255,255,0.55); line-height: 1.7; }
    .hcd-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
        max-width: 1100px; margin: 0 auto;
    }
    .hcd-card {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 35px 30px; text-align: center;
        transition: all 0.35s;
    }
    .hcd-card:hover {
        background: rgba(201,168,76,0.08); border-color: rgba(201,168,76,0.3);
        transform: translateY(-8px);
    }
    .hcd-icon {
        display: block; font-size: 2rem; margin-bottom: 15px; color: rgba(255,255,255,0.4);
    }
    .hcd-label {
        display: block; font-size: 0.68rem; letter-spacing: 2.5px; color: rgba(255,255,255,0.45);
        font-weight: 800; text-transform: uppercase; margin-bottom: 12px;
    }
    .hcd-text { font-size: 0.95rem; color: rgba(255,255,255,0.75); line-height: 1.7; margin: 0; }

    @media (max-width: 768px) {
        .follow-cards-grid { grid-template-columns: 1fr; }
        .hcd-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .hcd-grid { grid-template-columns: 1fr; }
    }

    .hero-premium {
      min-height: 100vh;
      background: #0c1428;
      display: flex; align-items: center;
      position: relative; overflow: hidden;
      padding-top: 85px;
      width: 100%;
    }
    .hero-bg {
      position: absolute; inset: 0;
      background: radial-gradient(circle at 70% 30%, rgba(201,168,76,0.08) 0%, transparent 60%),
                  radial-gradient(circle at 20% 70%, rgba(30,47,88,0.5) 0%, transparent 50%),
                  #0c1428;
    }
    .hero-grid-overlay {
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(30,47,88,0.3) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(30,47,88,0.3) 1px, transparent 1px);
      background-size: 50px 50px;
      opacity: 0.6;
    }
    .hero-inner {
      position: relative; z-index: 5;
      max-width: 1400px; margin: 0 auto; padding: 60px 40px;
      display: grid; grid-template-columns: 1.25fr 1fr; gap: 80px; align-items: center;
    }

    /* Left Column Content */
    .hero-badge-lux {
      display: inline-flex; align-items: center; gap: 10px;
      border: 1px solid rgba(201,168,76,0.5); border-radius: 50px;
      padding: 8px 25px; margin-bottom: 35px;
      color: rgba(255,255,255,0.7); font-size: 0.72rem;
      letter-spacing: 3px; font-weight: 700;
      background: rgba(201,168,76,0.05);
    }
    .hero-badge-lux .dot { width: 6px; height: 6px; background: #c9a84c; border-radius: 50%; box-shadow: 0 0 10px #c9a84c; }

    .hero-name-alt {
      font-family: var(--font-display);
      font-size: clamp(2.8rem, 5vw, 4.8rem);
      font-weight: 700; line-height: 1.05; margin-bottom: 12px;
    }
    .hero-name-alt .name-prefix { color: #f8f8f8; display: block; margin-bottom: 5px; }
    .hero-name-alt .name-main { color: #c9a84c; display: block; }
    .hero-name-alt .kannada-subtitle {
      display: block; font-size: 1.8rem; color: #8b6914; 
      margin-top: 10px; font-weight: 400; font-style: italic;
    }

    .hero-quals-alt {
      font-size: 0.85rem; color: rgba(255,255,255,0.4);
      letter-spacing: 3px; font-weight: 700; margin-bottom: 40px;
    }

    .hero-quote-block {
      border-left: 3px solid #c9a84c;
      padding: 5px 0 5px 30px; margin-bottom: 45px;
      max-width: 580px;
    }
    .hero-quote-block p {
      color: rgba(255,255,255,0.85); font-family: var(--font-display);
      font-size: 1.6rem; line-height: 1.6; font-style: italic; margin: 0;
    }

    .hero-pills-alt { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 50px; }
    .pill-tag {
      font-size: 0.68rem; padding: 8px 18px; border-radius: 50px;
      border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.6);
      background: rgba(255,255,255,0.03); letter-spacing: 1.5px; font-weight: 700;
    }

    .hero-cta-group { display: flex; gap: 20px; align-items: center; }
    .btn-hero-solid {
      background: #c9a84c; color: #0c1428; border: none;
      padding: 16px 35px; border-radius: 8px; font-weight: 800;
      display: inline-flex; align-items: center; gap: 12px;
      font-size: 0.95rem; text-decoration: none; transition: 0.3s;
      box-shadow: 0 10px 25px rgba(201,168,76,0.3);
    }
    .btn-hero-solid:hover { background: #e8c878; transform: translateY(-3px); }
    .btn-hero-outline {
      background: transparent; border: 1px solid rgba(201,168,76,0.5);
      color: #c9a84c; padding: 16px 35px; border-radius: 8px;
      font-weight: 700; text-decoration: none; transition: 0.3s;
      font-size: 0.95rem;
    }
    .btn-hero-outline:hover { background: rgba(201,168,76,0.1); color: white; }

    /* Right Column Visuals */
    .hero-portrait-frame-lux {
      position: relative; padding: 25px;
      background: radial-gradient(circle at center, rgba(10,31,20,0.8), #080d1a);
      border-radius: 20px; border: 1px solid rgba(201,168,76,0.1);
      box-shadow: 0 50px 100px rgba(0,0,0,0.8), 0 0 40px rgba(10,31,20,0.4);
    }
    .hero-portrait-frame-lux [class^="bracket-"] {
      position: absolute; width: 35px; height: 35px;
      border: 2px solid #5d6d45; pointer-events: none; opacity: 0.7;
    }
    .bracket-tl { top: 15px; left: 15px; border-right: 0; border-bottom: 0; }
    .bracket-tr { top: 15px; right: 15px; border-left: 0; border-bottom: 0; }
    .bracket-bl { bottom: 15px; left: 15px; border-right: 0; border-top: 0; }
    .bracket-br { bottom: 15px; right: 15px; border-left: 0; border-top: 0; }

    .portrait-display {
      position: relative; border-radius: 12px; overflow: hidden;
      aspect-ratio: 4/5; border: 1px solid rgba(255,255,255,0.05);
    }
    .portrait-display img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.9); transition: 0.6s; }
    .portrait-display:hover img { transform: scale(1.05); filter: brightness(1.1); }
    
    .display-label {
      position: absolute; bottom: 0; left: 0; right: 0;
      padding: 40px 25px 25px; text-align: center;
      background: linear-gradient(transparent, rgba(10,31,20,0.95));
    }
    .display-label h4 { 
      color: white; font-family: var(--font-display); font-size: 1.6rem;
      font-weight: 700; margin-bottom: 5px; 
    }
    .display-label p { color: rgba(255,255,255,0.6); font-size: 0.75rem; margin-bottom: 12px; font-weight: 700; }
    .display-label .role-tag { 
      font-size: 0.62rem; color: #c9a84c; text-transform: uppercase;
      letter-spacing: 1px; font-weight: 800; opacity: 0.9;
    }

    /* Bottom Stats Row */
    .hero-stats-row {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 30px;
    }
    .stat-card-lux {
      background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;
      padding: 20px 10px; text-align: center; transition: 0.4s;
    }
    .stat-card-lux:hover { background: rgba(201,168,76,0.1); border-color: #c9a84c; transform: translateY(-5px); }
    .stat-val { display: block; font-size: 1.8rem; font-weight: 700; color: #c9a84c; font-family: var(--font-display); margin-bottom: 5px; }
    .stat-lbl { display: block; font-size: 0.6rem; font-weight: 800; color: rgba(255,255,255,0.5); letter-spacing: 2px; }

    @media (max-width: 1100px) {
      .hero-premium { padding-top: 70px; min-height: auto; }
      .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 40px; padding: 30px 20px 60px; }
      .hero-text { order: 1; display: flex; flex-direction: column; align-items: center; }
      .hero-visual { order: 2; margin-bottom: 0; }
      .hero-quote-block { border-left: 0; border-top: 2px solid #c9a84c; padding: 20px 0 0; margin-left: auto; margin-right: auto; text-align: center; margin-bottom: 35px; }
      .hero-pills-alt { justify-content: center; margin-bottom: 40px; }
      .hero-badge-lux { margin-bottom: 25px; }
      .hero-cta-group { flex-direction: column; width: 100%; max-width: 320px; gap: 15px; }
      .btn-hero-solid, .btn-hero-outline { width: 100%; justify-content: center; padding: 14px 25px; }
      .hero-portrait-frame-lux { max-width: 360px; margin: 0 auto; padding: 20px; }
    }
    @media (max-width: 576px) {
      .hero-premium { padding-top: 65px; }
      .hero-inner { padding: 20px 15px 50px; }
      .hero-name-alt { font-size: 2.2rem; }
      .hero-name-alt .kannada-subtitle { font-size: 1.2rem; }
      .hero-quote-block p { font-size: 1.2rem; }
      .portrait-display { aspect-ratio: 1/1; }
      .hero-stats-row { gap: 10px; margin-top: 20px; }
      .stat-card-lux { padding: 15px 5px; }
      .stat-val { font-size: 1.5rem; }
    }

    /* ── HERO STATS BAR ── */
    .hero-stats-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: rgba(12,20,40,0.85); backdrop-filter: blur(15px);
        border-top: 1px solid rgba(201,168,76,0.2);
        padding: 25px 0; z-index: 5;
    }
    .h-stat-item { display: flex; align-items: flex-end; gap: 10px; }
    .h-stat-label { color: var(--gold); font-family: var(--font-display); font-size: 1.1rem; letter-spacing: 1px; font-weight: 600; }
    .h-stat-num { color: white; font-size: 1.8rem; font-weight: 800; line-height: 1; font-family: var(--font-display); }
    .h-stat-text { color: rgba(255,255,255,0.5); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 2px; }

    /* ── DIGITAL IDENTITY CARD FIXES ── */
    :root {
        --gold-premium: #c9a84c;
    }
    .text-gold-premium { color: var(--gold-premium); }
    .text-gold-label { 
        color: var(--gold-premium); 
        font-size: 0.7rem; 
        letter-spacing: 2px; 
        font-weight: 700;
        margin-bottom: 12px;
    }
    .italic { font-style: italic; }
    /* ── REFINED DIGITAL IDENTITY CARD ── */
    .identity-card-section {
        background: #0a0f1e;
        padding: 60px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .premium-id-card {
        background: #0c1428;
        border: 1px solid rgba(201, 168, 76, 0.3);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
        z-index: 2;
        box-shadow: 0 50px 120px rgba(0, 0, 0, 0.8);
        max-width: 1120px;
        margin: 0 auto;
    }
    
    /* Photo Section */
    .id-photo-col {
        position: relative;
        min-height: 480px; /* Reduced height from 540px */
        background: #000;
        border-right: 1px solid rgba(201, 168, 76, 0.35);
    }
    .id-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.95;
    }
    .id-frame-accents .frame-edge {
        position: absolute;
        width: 45px;
        height: 45px;
        border: 1px solid rgba(201, 168, 76, 0.5);
        z-index: 10;
    }
    .edge-tl { top: 20px; left: 20px; border-right: none; border-bottom: none; }
    .edge-bl { bottom: 20px; left: 20px; border-top: none; border-right: none; }
    
    .id-photo-overlay-text {
        position: absolute;
        bottom: 40px;
        left: 40px;
        z-index: 15;
    }
    .photo-name {
        color: white;
        font-family: var(--font-display);
        font-size: 2.6rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 8px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.9);
    }
    .photo-designation {
        color: var(--gold);
        font-size: 1rem;
        font-style: italic;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.9);
        letter-spacing: 0.5px;
        opacity: 0.95;
    }
    .photo-gradient-guard {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(12, 20, 40, 0.98) 0%, transparent 45%);
        z-index: 12;
    }

    /* Content Section */
    .id-content-col {
        padding: 40px 60px; /* Reduced vertical padding from 50px */
        background: #0c1428;
    }
    .id-brand-meta {
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 20px;
    }
    .id-seal-wrapper {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--gold);
        box-shadow: 0 0 25px rgba(201, 168, 76, 0.3);
    }
    .id-seal-wrapper img { width: 90%; height: 90%; object-fit: contain; }
    .id-seal-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #c9a84c, #8b6914);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: #0c1428;
    }
    
    .id-brand-titles .main-id-name {
        color: white;
        font-family: var(--font-display);
        font-size: 2.4rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .id-brand-titles .main-id-quals {
        color: var(--gold);
        font-size: 1.2rem;
        font-style: italic;
        margin: 5px 0 0;
        opacity: 0.9;
    }
    .id-header-line {
        height: 1px;
        background: rgba(201, 168, 76, 0.3);
        margin: 20px 0;
    }
    .id-courts-jurisdiction {
        color: var(--gold);
        font-size: 0.85rem;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 30px; /* Reduced margin from 45px */
    }

    /* Info Blocks */
    .id-info-block {
        margin-bottom: 25px; /* Reduced margin */
    }
    .id-label {
        display: block;
        color: var(--gold);
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 15px; /* Reduced margin */
        opacity: 0.95;
    }
    .id-data-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .id-bullet {
        color: var(--gold);
        font-size: 0.5rem;
        opacity: 0.8;
    }
    .id-data-row a {
        color: white;
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 400;
        transition: 0.3s;
    }
    .id-data-row a:hover { color: var(--gold); }
    
    .id-block-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        margin: 20px 0; /* Reduced margin */
        position: relative;
    }
    .divider-dot {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 6px;
        height: 6px;
        background: var(--gold);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--gold);
    }

    .id-address {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.05rem;
        line-height: 1.5;
        margin: 0;
    }

    .contact-info-panel {
        padding-right: 40px;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    .office-info-panel {
        padding-left: 40px;
        position: relative;
    }
    .divider-dot-vertical {
        position: absolute;
        left: -3px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: var(--gold);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--gold);
    }

    .id-glow-effect {
        position: absolute;
        bottom: -5%;
        right: -5%;
        width: 30%;
        height: 30%;
        background: radial-gradient(circle, rgba(201, 168, 76, 0.1) 0%, transparent 70%);
        z-index: 1;
        filter: blur(80px);
        pointer-events: none;
    }

    @media (max-width: 1200px) {
        .premium-id-card { max-width: 920px; }
        .photo-name { font-size: 2rem; }
        .main-id-name { font-size: 1.9rem; }
        .id-data-row a { font-size: 1.15rem; }
    }

    @media (max-width: 991px) {
        .identity-card-section { padding: 40px 15px; }
        .id-content-col { padding: 30px 20px; }
        .id-photo-col { min-height: 400px; border-right: none; border-bottom: 1px solid rgba(201, 168, 76, 0.3); }
        .id-photo-overlay-text { bottom: 25px; left: 25px; right: 25px; }
        .photo-name { font-size: 1.8rem; line-height: 1.2; }
        .photo-designation { font-size: 0.85rem; }
        .id-brand-meta { gap: 15px; flex-direction: column; text-align: center; }
        .id-brand-titles .main-id-name { font-size: 1.8rem; text-align: center; }
        .main-id-quals { text-align: center; font-size: 1rem; }
        .id-courts-jurisdiction { text-align: center; font-size: 0.75rem; margin-bottom: 25px; }
        .contact-info-panel { padding-right: 0; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 25px; }
        .office-info-panel { padding-left: 0; padding-top: 25px; }
        .divider-dot-vertical { display: none; }
    }
    
    @media (max-width: 576px) {
        .id-photo-col { min-height: 340px; }
        .photo-name { font-size: 1.5rem; }
        .id-brand-titles .main-id-name { font-size: 1.5rem; }
        .id-data-row a { font-size: 1rem; }
        .id-seal-wrapper { width: 75px; height: 75px; }
        .id-label { letter-spacing: 2px; margin-bottom: 10px; }
        .id-address { font-size: 0.9rem; }
    }
    .hero-grid-overlay {
        position: absolute; inset: 0;
        background-image: 
            linear-gradient(rgba(201,168,76,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201,168,76,0.05) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    @media (max-width: 991px) {
        .id-card-photo-side { min-height: 400px; border-right: none; border-bottom: 1px solid rgba(201,168,76,0.3); }
        .display-name { font-size: 1.6rem; }
    }

    /* ── SECTIONS ── */
    .section-premium { padding: 100px 40px; position: relative; }
    @media (max-width: 991px) {
        .about-visual { margin-bottom: 40px; text-align: center; }
        .about-content { text-align: center; }
        .gold-separator { margin: 25px auto; }
        .about-quote-block { padding: 30px; margin-top: 30px; }
        .about-quote-text { font-size: 1.1rem; }
    }
    @media (max-width: 768px) {
        .section-premium { padding: 60px 20px; }
        .title-premium { font-size: 2rem; }
        .gold-separator { margin: 20px 0; }
        .gold-separator.center { margin: 20px auto; }
    }

    /* ── ABOUT QUOTE BLOCK ── */
    .about-quote-block {
        position: relative;
        background: var(--navy, #0c1428);
        border-radius: 12px;
        padding: 32px 36px 32px 44px;
        margin-top: 28px;
        border-left: 4px solid var(--gold, #c9a84c);
        box-shadow: 0 12px 40px rgba(12,20,40,0.18);
        overflow: hidden;
    }
    .about-quote-block::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 50%, rgba(201,168,76,0.06) 0%, transparent 70%);
        pointer-events: none;
    }
    .about-quote-mark {
        font-family: var(--font-display, Georgia, serif);
        font-size: 5rem;
        line-height: 0.6;
        color: var(--gold, #c9a84c);
        opacity: 0.6;
        display: block;
        margin-bottom: 8px;
    }
    .about-quote-text {
        font-family: var(--font-display, Georgia, serif);
        font-size: 1.12rem;
        font-style: italic;
        line-height: 1.75;
        color: rgba(255,255,255,0.88);
        margin: 0;
    }

    /* ── PRACTICE AREAS ── */
    .practice-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:30px; }
    .practice-card-dark {
      background:rgba(255,255,255,0.03); border-radius:24px;
      border: 1px solid rgba(201,168,76,0.15);
      padding:40px; transition:all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
      position:relative; overflow:hidden;
    }
    .practice-card-dark:hover { transform:translateY(-10px); background: rgba(255,255,255,0.06); border-color: var(--gold); }
    .practice-icon { font-size:2.8rem; margin-bottom:25px; display:block; }
    .practice-card-dark h3 { font-family:var(--font-display); font-size:1.5rem; color:var(--white); margin-bottom:15px; font-weight:700; }
    .practice-card-dark p { font-size:1rem; color:rgba(255,255,255,0.6); line-height:1.7; }

    .bg-navy-light { background: var(--navy-mid); }

    /* ── FEATURED BOOK FIXES ── */
    .fb-mockup { position: relative; display: inline-block; padding: 20px; }
    .fb-img { 
        max-width: 100%; height: auto; border-radius: 4px 15px 15px 4px; 
        box-shadow: 20px 40px 80px rgba(0,0,0,0.6);
        position: relative; z-index: 2;
        transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .fb-mockup:hover .fb-img { transform: rotateY(-10deg) scale(1.02); }
    .fb-glow {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 100%; height: 100%; background: radial-gradient(circle, rgba(201,168,76,0.18) 0%, transparent 75%);
        z-index: 1; filter: blur(40px);
    }
    .fb-title { font-family: var(--font-display); font-size: 3.5rem; color: #ffffff; font-weight: 700; margin: 0; }
    .fb-divider { width: 80px; height: 3px; background: var(--gold); border-radius: 2px; }
    .fb-desc { font-size: 1.15rem; line-height: 1.8; color: rgba(255,255,255,0.85) !important; max-width: 550px; }
    .fb-price-box { display: flex; align-items: baseline; gap: 10px; }
    .fb-price-label { font-size: 1.2rem; color: rgba(255,255,255,0.5); font-weight: 500; }
    .fb-price-gold { font-family: var(--font-display); font-size: 2.2rem; color: var(--gold); font-weight: 700; }
    
    .btn-purchase-lux {
        background: #1a2e5e; color: white; border: none; padding: 18px 45px; border-radius: 8px;
        font-weight: 700; text-decoration: none; display: inline-flex; align-items: center;
        letter-spacing: 1px; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-purchase-lux:hover { background: #233b76; color: white; transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.5); }


    /* ── PUBLICATIONS & BOOKS SECTION ── */
    .pub-section {
        background: var(--cream, #f9f5ed);
        padding: 100px 0 80px;
        position: relative;
    }
    .pub-header {
        text-align: center;
        margin-bottom: 60px;
    }
    .pub-eyebrow {
        display: block;
        font-size: 0.72rem;
        letter-spacing: 5px;
        font-weight: 800;
        color: var(--gold-dark, #b8913a);
        text-transform: uppercase;
        margin-bottom: 14px;
    }
    .pub-heading {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3rem);
        color: var(--navy, #0c1428);
        font-weight: 700;
        margin-bottom: 16px;
        line-height: 1.2;
    }
    .pub-separator {
        width: 60px; height: 3px;
        background: var(--gold, #c9a84c);
        margin: 0 auto 20px;
        border-radius: 2px;
    }
    .pub-subtext {
        max-width: 700px;
        margin: 0 auto;
        color: #555;
        font-size: 1.05rem;
        line-height: 1.75;
    }

    /* Cards Grid */
    .pub-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 50px;
    }
    .pub-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(0,0,0,0.07);
        border: 1px solid rgba(201,168,76,0.12);
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .pub-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.12);
        border-color: rgba(201,168,76,0.35);
    }

    /* Year Badge */
    .pub-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: var(--gold, #c9a84c);
        color: var(--navy, #0c1428);
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        padding: 4px 10px;
        border-radius: 50px;
        text-transform: uppercase;
        z-index: 2;
    }

    .pub-book-meta-top {
        position: relative;
        z-index: 1;
        width: 100%;
        text-align: center;
    }
    .pub-cover {
        background: linear-gradient(160deg, var(--navy, #0c1428), #1e3060);
        padding: 40px 24px 30px;
        text-align: center;
        position: relative;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .pub-cover-img {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.6;
    }
    .pub-cover-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(12,20,40,0.85) 30%, rgba(12,20,40,0.3) 100%);
    }
    .pub-icon-bg {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .pub-emoji {
        font-size: 3.5rem;
        display: block;
        line-height: 1;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 4px 15px rgba(0,0,0,0.5));
    }
    .pub-book-title {
        font-family: var(--font-display);
        font-size: 1.05rem;
        color: #fff;
        font-weight: 700;
        line-height: 1.35;
        margin: 0;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    /* Category tag */
    .pub-category-tag {
        background: rgba(201,168,76,0.1);
        border-bottom: 1px solid rgba(201,168,76,0.15);
        color: var(--gold-dark, #b8913a);
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        padding: 8px 18px;
        text-align: center;
    }

    /* Card body */
    .pub-card-body {
        padding: 22px 22px 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .pub-desc {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.7;
        flex: 1;
        margin-bottom: 16px;
    }
    .pub-read-more {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--navy, #0c1428);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: color 0.3s;
        align-self: flex-start;
    }
    .pub-read-more:hover { color: var(--gold-dark, #b8913a); }

    /* Publication Gallery Row */
    .pub-gallery-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 10px;
    }
    .pub-gallery-item {
        border-radius: 14px;
        overflow: hidden;
        height: 220px;
        position: relative;
        cursor: pointer;
    }
    .pub-gallery-item img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.55s cubic-bezier(0.2, 0.8, 0.2, 1);
        display: block;
    }
    .pub-gallery-item:hover img { transform: scale(1.08); }
    .pub-gallery-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 55%);
        display: flex; align-items: flex-end;
        padding: 15px 14px;
        opacity: 0; transition: opacity 0.35s;
    }
    .pub-gallery-item:hover .pub-gallery-overlay { opacity: 1; }
    .pub-gallery-caption {
        color: white; font-size: 0.82rem; font-weight: 600;
        margin: 0; line-height: 1.3; letter-spacing: 0.3px;
    }

    @media (max-width: 1100px) {
        .pub-cards-grid { grid-template-columns: repeat(2, 1fr); }
        .pub-gallery-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .pub-cards-grid { grid-template-columns: 1fr; }
        .pub-gallery-row { grid-template-columns: 1fr 1fr; }
        .pub-gallery-item { height: 160px; }
    }

    /* ── GALLERY (old kept for compatibility) ── */
    .gallery-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; }
    .gallery-item { border-radius:15px; overflow:hidden; height: 250px; position: relative; cursor: pointer; }
    .gallery-item img { width:100%; height:100%; object-fit:cover; transition: transform 0.6s; }
    .gallery-item:hover img { transform: scale(1.1); }
    .gallery-item.wide { grid-column: span 2; }
    .item-overlay { position: absolute; inset: 0; background: rgba(12,20,40,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
    .gallery-item:hover .item-overlay { opacity: 1; }


    /* ── CONNECT ── */
    .connect-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:20px; }
    .social-card-premium {
      display:flex; align-items:center; gap:20px;
      padding:25px; border-radius:20px; text-decoration:none;
      transition:all 0.3s;
    }
    .social-card-premium.sc-web-lux { background: var(--navy-mid); border: 1px solid rgba(201,168,76,0.3); }
    .social-card-premium:hover { transform:translateY(-8px); filter:brightness(1.15); }

    .blog-card-lux { background:white; border-radius: 20px; overflow:hidden; border: 1px solid rgba(0,0,0,0.05); transition: 0.4s; }
    .blog-card-lux:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); border-color: var(--gold); }
    .blog-img-wrap { height: 200px; overflow:hidden; }
    .blog-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .blog-card-lux:hover .blog-img-wrap img { transform: scale(1.1); }

    .contact-box-lux { background: var(--navy-mid); border: 1px solid var(--gold); }
    .form-luxury-dark { 
        width: 100%; padding: 15px 20px; background: rgba(255,255,255,0.05); 
        border: 1px solid rgba(201,168,76,0.3); border-radius: 12px; color: white;
        transition: 0.3s;
    }
    .form-luxury-dark:focus { outline: none; border-color: var(--gold); background: rgba(255,255,255,0.1); }
    .select-lux { appearance: none; cursor: pointer; }

    /* Custom Buttons */
    .btn-primary-gold {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: var(--navy);
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(201,168,76,0.2);
        border: none;
    }
    .btn-primary-gold:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(201,168,76,0.3); color: var(--navy); }
    .btn-outline-navy {
        border: 2px solid var(--navy);
        color: var(--navy);
        padding: 13px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: all 0.3s;
    }
    .btn-outline-navy:hover { background: var(--navy); color: var(--white); transform: translateY(-3px); }

    .rich-text-summary { line-height: 1.8; }

    /* ── BEYOND THE BAR SECTION ── */
    .beyond-bar-section {
        background: #f6f1e9;
        padding: 100px 0;
        position: relative;
    }
    .beyond-bar-heading-block {
        text-align: center;
        margin-bottom: 70px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    .bb-eyebrow {
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        font-size: 13px;
        color: #b08a3a;
        font-weight: 800;
        margin-bottom: 14px;
    }
    .bb-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: clamp(2rem, 5vw, 3.2rem);
        color: #121826;
        line-height: 1.1;
        margin-bottom: 22px;
    }
    .bb-accent-line {
        width: 45px;
        height: 3px;
        background: #b08a3a;
        margin: 0 auto;
        border-radius: 2px;
    }
    .beyond-bar-grid {
        display: grid;
        grid-template-columns: 44% 1fr;
        gap: 60px;
        align-items: flex-start;
    }

    /* Timeline Styling */
    .bb-timeline {
        position: relative;
        padding-top: 10px;
    }
    .bb-timeline::before {
        content: '';
        position: absolute;
        left: 21px;
        top: 30px;
        bottom: 50px;
        width: 2px;
        background: rgba(176,138,58,0.2);
    }
    .bb-item {
        display: flex;
        gap: 25px;
        margin-bottom: 45px;
        position: relative;
    }
    .bb-icon-box {
        width: 44px;
        height: 44px;
        background: #b08a3a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
        z-index: 2;
        box-shadow: 0 5px 15px rgba(176,138,58,0.3);
    }
    .bb-item-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #121826;
        margin-bottom: 8px;
    }
    .bb-item-desc {
        font-size: 0.92rem;
        line-height: 1.7;
        color: #5c6472;
        max-width: 420px;
    }

    /* Gallery Styling */
    .bb-gallery-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .bb-gallery-top .bb-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 18px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transition: 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .bb-gallery-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .bb-gallery-bottom .bb-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 18px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        transition: 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .bb-img:hover {
        transform: scale(1.02);
        box-shadow: 0 30px 60px rgba(0,0,0,0.15);
    }

    @media (max-width: 1024px) {
        .beyond-bar-grid { grid-template-columns: 1fr; gap: 50px; }
        .bb-item-desc { max-width: 100%; }
        .bb-gallery-top .bb-img { height: 350px; }
    }
    @media (max-width: 768px) {
        .beyond-bar-section { padding: 80px 20px; }
        .bb-gallery-bottom { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Local script simplified; header logic is handled in the main layout
</script>
@endpush
