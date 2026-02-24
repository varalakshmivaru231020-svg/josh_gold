@extends('layouts.lawfirm')

@section('title', $page->title . ' | Bengaluru Law House')

@section('content')
<section class="cms-luxury-page">
    <div class="cms-hero-header" @if($page->banner_image) style="background-image: linear-gradient(rgba(12,20,40,0.85), rgba(12,20,40,0.85)), url('{{ asset('storage/' . $page->banner_image) }}');" @endif>
        <div class="container text-center" data-aos="fade-up">
            <nav class="cms-breadcrumb mb-4">
                <a href="{{ url('/') }}">Home</a>
                <span class="sep">/</span>
                <span class="curr">{{ $page->title }}</span>
            </nav>
            <h1 class="heading-premium text-white">{{ $page->banner_title ?? $page->title }}</h1>
            <div class="accent-line-gold mx-auto"></div>
        </div>
    </div>

    <div class="container py-5 mt-n5">
        @php
            $hasContent = $page->content && trim(strip_tags($page->content)) !== '';
        @endphp
        
        @if($hasContent)
        <div class="cms-content-card" data-aos="fade-up">
            <div class="rich-text-legal">
                {!! $page->content !!}
            </div>
        </div>
        @endif

        {{-- Dynamic Sections handled by Admin CMS --}}
        <div class="dynamic-sections-wrap mt-5">
            @foreach($page->sections->where('is_active', true)->sortBy('sort_order') as $section)
                <div class="dynamic-section-item" data-aos="fade-up">
                    @includeIf('sections.' . $section->section_type, ['section' => $section])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .cms-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    
    .cms-hero-header { 
        background: var(--navy); 
        padding: 180px 0 140px; 
        position:relative; 
        overflow:hidden; 
        background-size: cover; 
        background-position: center; 
    }
    .cms-hero-header::before {
        content: ''; position: absolute; inset: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.04) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    .cms-hero-header::after { 
        content:''; position:absolute; inset:0; 
        background: radial-gradient(circle at 50% 120%, rgba(201,168,76,0.15) 0%, transparent 60%); 
    }
    
    .cms-breadcrumb { display:flex; align-items:center; justify-content:center; gap:10px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; z-index:2; position:relative; font-weight: 700; }
    .cms-breadcrumb a { color: var(--gold); text-decoration:none; transition: all 0.3s; }
    .cms-breadcrumb a:hover { color: white; }
    .cms-breadcrumb .sep { color: rgba(255,255,255,0.3); }
    .cms-breadcrumb .curr { color: white; opacity:0.8; }

    .cms-content-card { background: white; padding: 80px; border-radius: 40px; box-shadow: 0 40px 100px rgba(0,0,0,0.06); border: 1px solid rgba(201,168,76,0.1); position:relative; }
    .cms-content-card::before { content:''; position:absolute; top:0; left:50px; right:50px; height:5px; background:linear-gradient(90deg, var(--gold), var(--gold-dark)); border-radius:0 0 10px 10px; }

    .rich-text-legal { font-size: 1.15rem; line-height: 1.9; color: var(--text-muted); }
    .rich-text-legal h2 { font-family: var(--font-display); color: var(--navy); font-size: 2.5rem; margin-top: 50px; margin-bottom: 25px; font-weight: 700; border-bottom: 1px solid var(--cream-dark); padding-bottom: 20px; }
    .rich-text-legal p { margin-bottom: 30px; }
    .rich-text-legal ul { margin-bottom: 30px; padding-left: 25px; }
    .rich-text-legal li { margin-bottom: 15px; position:relative; list-style: none; }
    .rich-text-legal li::before { content: '◈'; position:absolute; left:-35px; color: var(--gold-dark); font-size: 0.9rem; }

    .mt-n5 { margin-top: -100px !important; }

    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; z-index: 2; position: relative; }

    @media (max-width: 991px) {
        .cms-content-card { padding: 40px; }
        .rich-text-legal h2 { font-size: 2rem; }
    }
</style>
@endpush
