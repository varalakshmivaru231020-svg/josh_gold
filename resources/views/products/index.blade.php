@extends('layouts.lawfirm')

@section('title', 'Authoritative Legal Publications | Bengaluru Law House')

@section('content')
<section class="publications-luxury-page">
    <div class="pub-hero-header">
        {{-- decorative background icon --}}
        <div class="pub-hero-deco" aria-hidden="true"><i class="fas fa-book-journal-whills"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">PUBLICATIONS &amp; BOOKS</div>
            <h1 class="heading-premium text-white">Our Publications</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="pub-subheader text-white opacity-75">Landmark legal guides, celebrated Kannada novels and academic resources authored by Advocate Veerendrababu</p>
        </div>
    </div>

    <div class="container pub-products-container">
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="treatise-premium-card">
                    <div class="treatise-img-box">
                        @if($product->cover_image)
                            <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->name }}" class="treatise-img">
                        @elseif($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="treatise-img">
                        @else
                            <div class="treatise-placeholder">
                                @if($product->book_icon)
                                    <span style="font-size:5rem;">{{ $product->book_icon }}</span>
                                @else
                                    <i class="fas fa-book-journal-whills"></i>
                                @endif
                            </div>
                        @endif
                        <div class="treatise-price-tag">₹{{ number_format($product->price, 0) }}</div>
                    </div>
                    <div class="treatise-body">
                        <div class="treatise-meta-top">{{ $product->category ?? 'Publication' }} @if($product->year) &nbsp;|&nbsp; {{ $product->year }}@endif</div>
                        <h3 class="treatise-name">{{ $product->name }}</h3>
                        <p class="treatise-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100) }}</p>
                        <div class="treatise-meta-list">
                            @if($product->author) <span><i class="fas fa-user-pen me-2"></i>{{ $product->author }}</span> @endif
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="btn-royal-sm mt-3 w-100">Examine Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <div class="empty-state-luxury text-center">
                    <i class="fas fa-book-open fa-4x text-gold mb-4"></i>
                    <h3 class="text-navy">No Publications Available</h3>
                    <p class="text-muted">Our collection is currently being updated for the latest edition year.</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="pagination-premium mt-5" data-aos="fade-up">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── PUBLICATIONS PAGE ── */
    .publications-luxury-page { background: var(--cream, #f9f5ed); min-height: 80vh; padding-bottom: 120px; }

    /* Banner / Hero Header — fully visible, no overlap */
    .pub-hero-header { 
        background: 
            linear-gradient(rgba(8,14,28,0.80) 0%, rgba(10,18,36,0.85) 100%),
            url('/images/publications-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px;
        position: relative;
        overflow: hidden;
        z-index: 0;
    }
    .pub-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.05) 1px, transparent 1px);
        background-size: 55px 55px;
    }
    .pub-hero-header::after { 
        content:''; position:absolute; inset:0; z-index: 0;
        background: radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.22) 0%, transparent 50%); 
    }
    /* Decorative background icon */
    .pub-hero-deco {
        position: absolute; right: 8%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.065);
        pointer-events: none; z-index: 0; line-height: 1;
    }
    .pub-hero-header > * { position: relative; z-index: 1; }
    .pub-hero-header .container { position: relative; z-index: 1; }
    .pub-hero-header .label-gold { display: block; margin-bottom: 16px; }
    .pub-hero-header .heading-premium { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 700; }
    .pub-hero-header .accent-line-gold { width: 60px; height: 3px; background: var(--gold, #c9a84c); margin: 18px auto 22px; border-radius: 2px; }
    .pub-subheader { max-width: 680px; margin: 0 auto; font-family: var(--font-display); font-size: 1.15rem; font-style: italic; position: relative; z-index: 1; opacity: 0.85; }

    /* Products container — sits below the banner with comfortable spacing */
    .pub-products-container { padding-top: 60px; padding-bottom: 60px; }

    /* Cards */
    .treatise-premium-card { background: white; border-radius: 24px; overflow:hidden; border: 1px solid rgba(201,168,76,0.1); transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); height: 100%; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .treatise-premium-card:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.12); border-color: var(--gold, #c9a84c); }
    
    .treatise-img-box { position:relative; background: #eef0f5; height: 300px; display: flex; align-items: center; justify-content: center; padding: 30px; overflow: hidden; }
    .treatise-img { max-height: 100%; max-width: 100%; border-radius: 4px; box-shadow: 15px 15px 40px rgba(0,0,0,0.2); transition: all 0.5s ease; object-fit: cover; }
    .treatise-premium-card:hover .treatise-img { transform: scale(1.06) perspective(1000px) rotateY(-6deg); }
    
    .treatise-placeholder { font-size: 5rem; color: var(--navy-light, #2a4080); opacity: 0.25; display: flex; align-items: center; justify-content: center; }
    .treatise-price-tag { position:absolute; top:18px; right:18px; background:linear-gradient(135deg, var(--gold, #c9a84c), var(--gold-dark, #b8913a)); color:var(--navy, #0c1428); padding:6px 16px; border-radius:50px; font-weight:800; font-size:1rem; box-shadow: 0 5px 20px rgba(201,162,39,0.35); z-index: 2; }

    .treatise-body { padding: 30px; flex: 1; display:flex; flex-direction:column; }
    .treatise-meta-top { font-size: 0.75rem; color: var(--gold-dark, #b8913a); letter-spacing: 3px; text-transform:uppercase; margin-bottom: 16px; font-weight:700; }
    .treatise-name { font-family: var(--font-display); font-size: 1.4rem; color: var(--navy, #0c1428); font-weight: 700; margin-bottom: 12px; line-height: 1.3; }
    .treatise-excerpt { color: #666; font-size: 0.95rem; line-height: 1.7; margin-bottom: 20px; flex: 1; }
    .treatise-meta-list { font-size: 0.9rem; color: #888; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.06); }
    .treatise-meta-list i { color: var(--gold, #c9a84c); }

    .btn-royal-sm { 
        display:inline-flex; align-items:center; justify-content:center;
        background: var(--navy, #0c1428); color: white;
        padding: 13px 22px; border-radius: 10px;
        text-decoration:none; font-weight:700; transition: all 0.3s;
        border: none; letter-spacing: 1px; text-transform: uppercase; font-size: 0.82rem;
    }
    .btn-royal-sm:hover { background: var(--gold, #c9a84c); color: var(--navy, #0c1428); transform: scale(1.02); }

    .empty-state-luxury { background: white; padding: 80px 40px; border-radius: 30px; border: 2px dashed rgba(201,168,76,0.3); }
    .pagination-premium .pagination { justify-content: center; gap: 10px; }
    .pagination-premium .page-link { background: white; border: 1px solid rgba(201,168,76,0.2); color: var(--navy, #0c1428); border-radius: 10px; padding: 10px 20px; font-weight: 600; }
    .pagination-premium .page-item.active .page-link { background: var(--gold, #c9a84c); border-color: var(--gold, #c9a84c); color: var(--navy, #0c1428); }
</style>
@endpush
