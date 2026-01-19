@extends('layouts.lawfirm')

@section('title', $page->title . ' - Bengaluru Law House')

@section('content')
    <!-- Page Banner -->
    <section class="page-banner" @if($page->banner_image) style="background-image: url('{{ asset('storage/' . $page->banner_image) }}');" @endif>
        <div class="banner-overlay"></div>
        <div class="banner-pattern">
            <i class="fas fa-balance-scale"></i>
        </div>
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title fade-up">{{ $page->banner_title ?? $page->title }}</h1>
                <div class="banner-divider fade-up delay-1"></div>
                <nav class="banner-breadcrumb fade-up delay-2">
                    <a href="{{ url('/') }}">Home</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">{{ $page->title }}</span>
                </nav>
            </div>
        </div>
        <!-- Animated Shapes -->
        <div class="banner-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </section>

    <!-- Page Content -->
    <section class="page-content-section">
        <div class="container">
            <div class="content-wrapper fade-up">
                <div class="page-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page Banner */
    .page-banner {
        position: relative;
        min-height: 400px;
        background: linear-gradient(135deg, var(--navy-deep) 0%, #0f1c2e 100%);
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 80px;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(26, 35, 50, 0.92) 0%, rgba(15, 28, 46, 0.88) 100%);
        z-index: 1;
    }

    .banner-pattern {
        position: absolute;
        top: 50%;
        right: 10%;
        transform: translateY(-50%);
        z-index: 2;
        opacity: 0.03;
    }

    .banner-pattern i {
        font-size: 350px;
        color: var(--gold-primary);
    }

    .banner-content {
        position: relative;
        z-index: 3;
        text-align: center;
        padding: 60px 0;
    }

    .banner-title {
        font-family: var(--font-heading);
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--cream-primary);
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: -0.02em;
    }

    .banner-divider {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
        margin: 0 auto 1.5rem;
        border-radius: 2px;
        position: relative;
    }

    .banner-divider::before,
    .banner-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--gold-primary);
        border-radius: 50%;
    }

    .banner-divider::before {
        left: -15px;
    }

    .banner-divider::after {
        right: -15px;
    }

    .banner-breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 0.95rem;
    }

    .banner-breadcrumb a {
        color: var(--gold-primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .banner-breadcrumb a:hover {
        color: var(--gold-light);
    }

    .banner-breadcrumb .separator {
        color: rgba(250, 248, 243, 0.4);
        font-size: 0.7rem;
    }

    .banner-breadcrumb .current {
        color: rgba(250, 248, 243, 0.7);
    }

    /* Animated Shapes */
    .banner-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 2;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border: 2px solid rgba(201, 162, 39, 0.1);
        border-radius: 50%;
    }

    .shape-1 {
        width: 200px;
        height: 200px;
        top: 10%;
        left: -50px;
        animation: floatShape 8s ease-in-out infinite;
    }

    .shape-2 {
        width: 150px;
        height: 150px;
        bottom: 20%;
        right: 5%;
        animation: floatShape 10s ease-in-out infinite reverse;
    }

    .shape-3 {
        width: 100px;
        height: 100px;
        top: 30%;
        left: 15%;
        animation: floatShape 6s ease-in-out infinite;
        animation-delay: 1s;
    }

    @keyframes floatShape {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-30px) rotate(180deg);
        }
    }

    /* Page Content Section */
    .page-content-section {
        padding: 80px 0;
        background: var(--cream-primary);
    }

    .content-wrapper {
        max-width: 900px;
        margin: 0 auto;
        background: var(--white);
        padding: 3rem;
        border-radius: 16px;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .content-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-light), var(--gold-primary));
        border-radius: 16px 16px 0 0;
    }

    .page-content {
        font-size: 1.05rem;
        line-height: 1.9;
        color: var(--text-primary);
    }

    .page-content h1,
    .page-content h2,
    .page-content h3,
    .page-content h4,
    .page-content h5,
    .page-content h6 {
        font-family: var(--font-heading);
        color: var(--navy-deep);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .page-content h2 {
        font-size: 1.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(201, 162, 39, 0.2);
    }

    .page-content h3 {
        font-size: 1.4rem;
        color: var(--navy-primary);
    }

    .page-content p {
        margin-bottom: 1.25rem;
    }

    .page-content ul,
    .page-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .page-content li {
        margin-bottom: 0.5rem;
    }

    .page-content a {
        color: var(--gold-dark);
        text-decoration: none;
        border-bottom: 1px solid rgba(201, 162, 39, 0.3);
        transition: all 0.3s ease;
    }

    .page-content a:hover {
        color: var(--navy-primary);
        border-bottom-color: var(--navy-primary);
    }

    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .page-content blockquote {
        background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(201, 162, 39, 0.05));
        border-left: 4px solid var(--gold-primary);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: var(--navy-primary);
    }

    .page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .page-content table th {
        background: var(--navy-deep);
        color: var(--cream-primary);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .page-content table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .page-content table tr:nth-child(even) {
        background: rgba(201, 162, 39, 0.03);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .page-banner {
            min-height: 350px;
            margin-top: 70px;
        }

        .banner-title {
            font-size: 2.5rem;
        }

        .banner-pattern i {
            font-size: 250px;
        }
    }

    @media (max-width: 767px) {
        .page-banner {
            min-height: 300px;
        }

        .banner-title {
            font-size: 2rem;
        }

        .content-wrapper {
            padding: 2rem 1.5rem;
        }

        .banner-pattern {
            display: none;
        }
    }
</style>
@endpush
