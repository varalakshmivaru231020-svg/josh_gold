<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Glide.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <link rel="icon" type="image/jpg" href="{{ asset('blr.jpg') }}">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <img src="{{ asset('blr.jpg') }}" height="60" style="padding-right:10px;">
                Bengaluru Law House Publications
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="https://news.blrlawhouse.com">News</a>
                    </li>

                    @auth('agent')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::guard('agent')->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('agent.dashboard') }}">
                                        Distributor Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('agent.profile') }}">
                                        Distributor Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('agent.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- Login/Register options hidden per user request --}}
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">

                <div class="col-md-4">
                    <h5>Bengaluru Law House Publications</h5>
                    <p>
                        Bengaluru Law House Publications is one of the leading providers of legal books
                        and academic resources in India. We specialize in delivering reliable, updated,
                        and authentic law publications to students, advocates, law firms, and institutions.
                    </p>
                </div>

                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50">Products</a></li>
                        <li><a href="/about-us" class="text-white-50">About Us</a></li>
                        <li><a href="/contact-us" class="text-white-50">Contact Us</a></li>
                        <li><a href="/privacy-policy" class="text-white-50">Privacy Policy</a></li>
                        <li><a href="/terms-conditions" class="text-white-50">Terms & Conditions</a></li>
                        <li><a href="/shipping-policy" class="text-white-50">Shipping Policy</a></li>
                        <li><a href="/refund-return-policy" class="text-white-50">Refund & Return Policy</a></li>
                        <li><a href="/disclamer" class="text-white-50">Disclaimer</a></li>
                        {{-- <li>
                            <a href="{{ route('agent.login') }}" class="text-white-50">
                                Distributor Login
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-phone me-2"></i> +91 96325 27961</p>
                    <p><i class="fas fa-envelope me-2"></i> bengalurulawhouse@gmail.com</p>
                </div>

            </div>

            <hr class="bg-light">

            <div class="text-center">
                <p>Copyright © 2025. All rights reserved by Supremeone Software.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @stack('scripts')
</body>
</html>
