@extends('Layouts.master')
@section('content')
    <!-- Cyberpunk Hero Section -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <p class="cyber-subtitle">Neon Market</p>
                        <h1 class="cyber-title">OUR <span class="cyber-accent">PRODUCTS</span></h1>
                        <div class="cyber-pulse-animation">
                            <div class="pulse-circle"></div>
                            <div class="pulse-circle delay-1"></div>
                            <div class="pulse-circle delay-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cyberpunk Products Section -->
    <div class="cyber-products-section" >
        <div class="container">
            <div class="row ">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-section-title">
                        <h3>HIGH-TECH <span class="cyber-accent">ORGANICS</span></h3>
                        <p class="cyber-section-desc">Premium bio-engineered nutrients for the cyber-enhanced lifestyle</p>
                    </div>
                </div>
            </div>

            <div class="row ">
                @foreach ($products as $item)
                <div class="col-lg-4 col-md-6 text-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="cyber-product-card">
                        <div class="cyber-product-image">
                            <a href="/single-product/{{ $item->id }}">
                                <img src="{{ asset($item->imagepath) }}" alt="{{ $item->name }}" class="cyber-product-img">
                                <div class="cyber-product-overlay">
                                    <div class="cyber-product-badge">NEW</div>
                                </div>
                                <div class="cyber-product-hover-effect"></div>
                            </a>
                        </div>
                        <div class="cyber-product-info">
                            <h3 class="cyber-product-name">{{ $item->name }}</h3>
                            <p class="cyber-product-desc">{{ $item->description }}</p>
                            <div class="cyber-product-actions">
                                <a href="/addproducttocart/{{ $item->id }}" class="cyber-cart-btn">
                                    <span class="cyber-btn-icon"><i class="fas fa-shopping-cart"></i></span>
                                    <span class="cyber-btn-text">ADD TO CART</span>
                                    <span class="cyber-btn-pulse"></span>
                                </a>

                                @if(Auth::check() && (Auth::user() && Auth::user()->role == 'admin' || Auth::user()->role == 'salesman'))
                                <div class="cyber-admin-actions mt-3">
                                    <a href="/removeproduct/{{ $item->id }}" class="cyber-admin-btn delete-btn">
                                        <i class="fas fa-trash"></i> DELETE
                                    </a>
                                    <a href="/editproduct/{{ $item->id }}" class="cyber-admin-btn edit-btn">
                                        <i class="fas fa-cog"></i> EDIT
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Floating Tech Elements -->
    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    @push('styles')
    <style>
        /* Cyberpunk/Futurist Theme Styles */
        :root {
            --cyber-primary: #00f0ff;
            --cyber-secondary: #ff00f0;
            --cyber-dark: #0a0a1a;
            --cyber-light: #e0e0ff;
            --cyber-accent: #00ff88;
            --cyber-danger: #ff003c;
            --cyber-warning: #ffcc00;
            --cyber-bg: rgba(10, 10, 26, 0.8);
            --cyber-border: rgba(0, 240, 255, 0.2);
        }

        body {
            background-color: var(--cyber-dark);
            color: var(--cyber-light);
            font-family: 'Orbitron', 'Rajdhani', sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .cyber-hero-section {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, #0a3a1a 0%, #1a6330 50%, #0a3a1a 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cyber-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,240,255,0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
            opacity: 0.5;
        }

        .cyber-hero-text {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 20px;
        }
        .cyber-products-section .row {
    display: flex;
    flex-wrap: wrap;
}


        .cyber-subtitle {
            color: var(--cyber-primary);
            font-size: 1.2rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.5);
        }

        .cyber-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--cyber-light), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cyber-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .cyber-pulse-animation {
            position: relative;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pulse-circle {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--cyber-accent);
            opacity: 0;
            animation: pulse 3s infinite;
        }

        .pulse-circle.delay-1 {
            animation-delay: 1s;
        }

        .pulse-circle.delay-2 {
            animation-delay: 2s;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(10);
                opacity: 0;
            }
        }

        /* Products Section */
        .cyber-products-section {
            padding: 100px 0;
            position: relative;
        }

        .cyber-section-title {
            margin-bottom: 50px;
        }

        .cyber-section-title h3 {
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--cyber-light);
            margin-bottom: 15px;
        }

        .cyber-section-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .cyber-section-desc {
            color: var(--cyber-light);
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Product Card */
        .cyber-product-card {
            background: var(--cyber-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .cyber-product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 240, 255, 0.05) 0%, rgba(255, 0, 240, 0.05) 100%);
            z-index: -1;
        }

        .cyber-product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 240, 255, 0.2);
        }

        .cyber-product-image {
            position: relative;
            height: 250px;
            margin-bottom: 20px;
            overflow: hidden;
            border-radius: 8px;
        }

        .cyber-product-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: all 0.5s ease;
        }

        .cyber-product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.7) 100%);
        }

        .cyber-product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--cyber-accent);
            color: var(--cyber-dark);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cyber-product-hover-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 240, 255, 0.1) 0%, rgba(255, 0, 240, 0.1) 100%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .cyber-product-card:hover .cyber-product-hover-effect {
            opacity: 1;
        }

        .cyber-product-card:hover .cyber-product-img {
            transform: scale(1.05);
        }

        .cyber-product-info {
            text-align: center;
        }

        .cyber-product-name {
            font-size: 1.4rem;
            color: var(--cyber-light);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cyber-product-desc {
            color: var(--cyber-light);
            opacity: 0.8;
            margin-bottom: 20px;
            font-size: 0.95rem;
            min-height: 60px;
        }

        /* Cart Button */
        .cyber-cart-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            color: var(--cyber-dark);
            border: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .cyber-cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }

        .cyber-btn-icon {
            margin-right: 10px;
        }

        .cyber-btn-text {
            position: relative;
            z-index: 1;
        }

        .cyber-btn-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
            opacity: 0;
            border-radius: 50px;
            animation: pulseBtn 2s infinite;
        }

        @keyframes pulseBtn {
            0% {
                transform: scale(0.95);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        /* Admin Buttons */
        .cyber-admin-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .cyber-admin-btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .cyber-admin-btn i {
            margin-right: 5px;
        }

        .delete-btn {
            background: var(--cyber-danger);
            color: white;
        }

        .delete-btn:hover {
            background: #d1002a;
            transform: translateY(-2px);
        }

        .edit-btn {
            background: var(--cyber-primary);
            color: var(--cyber-dark);
        }

        .edit-btn:hover {
            background: #00d0e0;
            transform: translateY(-2px);
        }

        /* Floating Elements */
        .cyber-floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .cyber-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.2;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: var(--cyber-organic);
            top: -100px;
            left: -100px;
            animation: float 15s infinite ease-in-out;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: var(--cyber-primary);
            bottom: -50px;
            right: -50px;
            animation: float 12s infinite ease-in-out reverse;
        }

        .orb-3 {
            width: 150px;
            height: 150px;
            background: var(--cyber-secondary);
            top: 50%;
            right: 10%;
            animation: float 10s infinite ease-in-out 2s;
        }

        .cyber-circuit-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,240,255,0.03)" stroke-width="1"/></svg>');
            opacity: 0.1;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(20px, 20px);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .cyber-title {
                font-size: 2.8rem;
            }

            .cyber-section-title h3 {
                font-size: 2rem;
            }

            .cyber-product-card {
                padding: 20px;
            }

            .cyber-product-image {
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .cyber-title {
                font-size: 2.2rem;
            }

            .cyber-subtitle {
                font-size: 1rem;
            }

            .cyber-section-title h3 {
                font-size: 1.8rem;
            }

            .cyber-product-name {
                font-size: 1.2rem;
            }

            .cyber-cart-btn {
                padding: 10px 20px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .cyber-hero-section {
                height: 300px;
            }

            .cyber-title {
                font-size: 1.8rem;
            }

            .cyber-section-title h3 {
                font-size: 1.5rem;
            }

            .cyber-product-image {
                height: 180px;
            }

            .cyber-admin-actions {
                flex-direction: column;
                align-items: center;
            }

            .cyber-admin-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation library
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Add hover effect to product cards
            const productCards = document.querySelectorAll('.cyber-product-card');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const img = this.querySelector('.cyber-product-img');
                    if (img) {
                        img.style.transform = 'scale(1.05)';
                    }
                });

                card.addEventListener('mouseleave', function() {
                    const img = this.querySelector('.cyber-product-img');
                    if (img) {
                        img.style.transform = '';
                    }
                });
            });

            // Add pulse animation to products periodically
            setInterval(function() {
                const products = document.querySelectorAll('.cyber-product-card');
                products.forEach((product, index) => {
                    setTimeout(() => {
                        product.style.boxShadow = '0 0 20px rgba(0, 240, 255, 0.3)';
                        setTimeout(() => {
                            product.style.boxShadow = '';
                        }, 1000);
                    }, index * 200);
                });
            }, 8000);
        });
    </script>
    @endpush
@endsection
