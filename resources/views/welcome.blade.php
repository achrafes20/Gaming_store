@extends("Layouts.master")
@section('content')
<!-- Cyberpunk Hero Slider -->
<div class="cyber-slider">
    <!-- Slide 1 -->
    <div class="cyber-slide" style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">
        <div class="cyber-slide-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="cyber-hero-content">
                        <div class="cyber-glitch" data-text="NEW GENERATION">NEW GENERATION</div>
                        <h1 class="cyber-title">ULTIMATE TECH <span class="cyber-accent">COLLECTION</span></h1>
                        <p class="cyber-subtitle">Experience the future of technology today with our cutting-edge devices</p>
                        <div class="cyber-buttons">
                            <a href="/categories" class="cyber-btn-primary">
                                <span>EXPLORE PRODUCTS</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="/contact" class="cyber-btn-secondary">
                                <span>CONTACT US</span>
                                <i class="fas fa-comment-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="cyber-slide-image">
                        <img src="{{ asset('assets/img/cyber-product-1.png') }}" alt="Tech Product" class="cyber-hologram">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="cyber-slide" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
        <div class="cyber-slide-overlay"></div>
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cyber-hero-content">
                        <div class="cyber-glitch" data-text="INNOVATION">INNOVATION</div>
                        <h1 class="cyber-title">PREMIUM <span class="cyber-accent">ELECTRONICS</span></h1>
                        <p class="cyber-subtitle">Discover the latest tech innovations with our 100% authentic collection</p>
                        <div class="cyber-buttons justify-content-center">
                            <a href="/categories" class="cyber-btn-primary">
                                <span>SHOP NOW</span>
                                <i class="fas fa-shopping-bag"></i>
                            </a>
                            <a href="/contact" class="cyber-btn-secondary">
                                <span>GET SUPPORT</span>
                                <i class="fas fa-headset"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="cyber-slide" style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 50%, #2c3e50 100%);">
        <div class="cyber-slide-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 offset-lg-5 text-right">
                    <div class="cyber-hero-content">
                        <div class="cyber-glitch" data-text="EXCLUSIVE">EXCLUSIVE</div>
                        <h1 class="cyber-title">MEGA <span class="cyber-accent">DISCOUNTS</span></h1>
                        <p class="cyber-subtitle">Limited time offers on our most advanced tech products</p>
                        <div class="cyber-buttons justify-content-end">
                            <a href="/categories" class="cyber-btn-primary">
                                <span>VIEW DEALS</span>
                                <i class="fas fa-tag"></i>
                            </a>
                            <a href="/contact" class="cyber-btn-secondary">
                                <span>LEARN MORE</span>
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cyberpunk Hero Slider -->

<!-- Cyber Features Section -->
<div class="cyber-features-section">
    <div class="container">
        <div class="cyber-features-grid">
            <div class="cyber-feature-card" data-aos="fade-up">
                <div class="cyber-feature-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="cyber-feature-content">
                    <h3>LIGHTNING DELIVERY</h3>
                    <p>Same-day shipping on all orders</p>
                </div>
                <div class="cyber-feature-pulse"></div>
            </div>

            <div class="cyber-feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="cyber-feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="cyber-feature-content">
                    <h3>24/7 TECH SUPPORT</h3>
                    <p>Expert assistance around the clock</p>
                </div>
                <div class="cyber-feature-pulse"></div>
            </div>

            <div class="cyber-feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="cyber-feature-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div class="cyber-feature-content">
                    <h3>HASSLE-FREE RETURNS</h3>
                    <p>30-day money back guarantee</p>
                </div>
                <div class="cyber-feature-pulse"></div>
            </div>
        </div>
    </div>
</div>
<!-- End Cyber Features Section -->

<!-- Cyber Products Section -->
<div class="cyber-products-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="cyber-section-header" data-aos="fade-up">
                    <div class="cyber-section-glitch" data-text="OUR COLLECTION">OUR COLLECTION</div>
                    <h2>FEATURED <span class="cyber-accent">CATEGORIES</span></h2>
                    <p>Explore our cutting-edge technology categories and discover the future today</p>
                </div>
            </div>
        </div>

        <div class="cyber-products-grid">
            @foreach ($categories as $item)
            <div class="cyber-product-card" data-aos="fade-up">
                <div class="cyber-product-image">
                    <a href="/product/{{$item->id}}">
                        <img src="{{url($item->imagepath)}}" alt="{{$item->name}}" class="cyber-product-hover">
                        <div class="cyber-product-overlay">
                            <div class="cyber-product-badge">EXPLORE</div>
                        </div>
                    </a>
                </div>
                <div class="cyber-product-info">
                    <h3>{{ $item->name }}</h3>
                    <p>{{ Str::limit($item->description, 100) }}</p>

                    @if(Auth::check() && (Auth::user() && Auth::user()->role == 'admin' || Auth::user()->role == 'salesman'))
                    <a href="/removecategory/{{ $item->id }}" class="cyber-delete-btn">
                        <i class="fas fa-trash"></i> DELETE CATEGORY
                    </a>
                    @endif
                </div>
                <div class="cyber-product-glow"></div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- End Cyber Products Section -->

@push('styles')
<style>
    /* Cyberpunk Theme Variables */
    :root {
        --cyber-primary: #00f0ff;
        --cyber-secondary: #ff00f0;
        --cyber-accent: #00ff88;
        --cyber-dark: #0a0a1a;
        --cyber-darker: #050510;
        --cyber-light: #e0e0ff;
        --cyber-card-bg: rgba(20, 20, 40, 0.8);
    }

    /* Cyber Slider */
    .cyber-slider {
        position: relative;
        height: 100vh;
        max-height: 800px;
        overflow: hidden;
    }

    .cyber-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        opacity: 0;
        transition: opacity 1s ease;
        z-index: 1;
    }

    .cyber-slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,240,255,0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
        opacity: 0.3;
    }

    .cyber-slide.active {
        opacity: 1;
        z-index: 2;
    }

    .cyber-hero-content {
        position: relative;
        z-index: 3;
        padding: 40px;
        background: rgba(10, 10, 26, 0.5);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        border: 1px solid rgba(0, 240, 255, 0.2);
    }

    .cyber-glitch {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.2rem;
        color: var(--cyber-primary);
        text-transform: uppercase;
        letter-spacing: 3px;
        position: relative;
        margin-bottom: 15px;
    }

    .cyber-glitch::before, .cyber-glitch::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .cyber-glitch::before {
        left: 2px;
        text-shadow: -2px 0 var(--cyber-secondary);
        animation: glitch-anim-1 2s infinite linear alternate-reverse;
    }

    .cyber-glitch::after {
        left: -2px;
        text-shadow: -2px 0 var(--cyber-accent);
        animation: glitch-anim-2 2s infinite linear alternate-reverse;
    }

    .cyber-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: bold;
        color: var(--cyber-light);
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .cyber-accent {
        color: var(--cyber-primary);
    }

    .cyber-subtitle {
        font-size: 1.2rem;
        color: rgba(224, 224, 255, 0.8);
        margin-bottom: 30px;
    }

    .cyber-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .cyber-btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
        color: var(--cyber-dark);
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .cyber-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.4);
    }

    .cyber-btn-primary i {
        margin-left: 10px;
        transition: transform 0.3s ease;
    }

    .cyber-btn-primary:hover i {
        transform: translateX(5px);
    }

    .cyber-btn-secondary {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        background: transparent;
        color: var(--cyber-light);
        border: 1px solid var(--cyber-primary);
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .cyber-btn-secondary:hover {
        background: rgba(0, 240, 255, 0.1);
        transform: translateY(-3px);
    }

    .cyber-slide-image {
        position: relative;
        animation: float 6s ease-in-out infinite;
    }

    .cyber-hologram {
        max-width: 100%;
        filter: drop-shadow(0 0 20px rgba(0, 240, 255, 0.5));
    }

    /* Cyber Features Section */
    .cyber-features-section {
        padding: 80px 0;
        background: var(--cyber-dark);
        position: relative;
        z-index: 2;
    }

    .cyber-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .cyber-feature-card {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.1);
        border-radius: 10px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cyber-feature-card:hover {
        transform: translateY(-10px);
        border-color: var(--cyber-primary);
    }

    .cyber-feature-icon {
        width: 80px;
        height: 80px;
        background: rgba(0, 240, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--cyber-primary);
        margin-bottom: 20px;
        border: 1px solid var(--cyber-primary);
    }

    .cyber-feature-content h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    .cyber-feature-content p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 0.9rem;
    }

    .cyber-feature-pulse {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 10px;
        background: radial-gradient(circle, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.5s ease;
    }

    .cyber-feature-card:hover .cyber-feature-pulse {
        opacity: 1;
        width: 110%;
        height: 110%;
    }

    /* Cyber Products Section */
    .cyber-products-section {
        padding: 100px 0;
        background: var(--cyber-darker);
        position: relative;
    }

    .cyber-section-header {
        margin-bottom: 60px;
    }

    .cyber-section-glitch {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        color: var(--cyber-primary);
        text-transform: uppercase;
        letter-spacing: 3px;
        position: relative;
        margin-bottom: 15px;
    }

    .cyber-section-glitch::before, .cyber-section-glitch::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .cyber-section-glitch::before {
        left: 2px;
        text-shadow: -2px 0 var(--cyber-secondary);
        animation: glitch-anim-1 2s infinite linear alternate-reverse;
    }

    .cyber-section-glitch::after {
        left: -2px;
        text-shadow: -2px 0 var(--cyber-accent);
        animation: glitch-anim-2 2s infinite linear alternate-reverse;
    }

    .cyber-section-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: var(--cyber-light);
        margin-bottom: 15px;
    }

    .cyber-section-header p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .cyber-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .cyber-product-card {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .cyber-product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
    }

    .cyber-product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .cyber-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: all 0.5s ease;
    }

    .cyber-product-hover:hover {
        transform: scale(1.05);
    }

    .cyber-product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .cyber-product-card:hover .cyber-product-overlay {
        opacity: 1;
    }

    .cyber-product-badge {
        background: var(--cyber-primary);
        color: var(--cyber-dark);
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    .cyber-product-info {
        padding: 20px;
    }

    .cyber-product-info h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    .cyber-product-info p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .cyber-delete-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        background: rgba(255, 0, 60, 0.1);
        color: var(--cyber-light);
        border: 1px solid var(--cyber-error);
        border-radius: 5px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cyber-delete-btn i {
        margin-right: 8px;
    }

    .cyber-delete-btn:hover {
        background: var(--cyber-error);
        color: var(--cyber-light);
    }

    .cyber-product-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .cyber-product-card:hover .cyber-product-glow {
        opacity: 1;
    }

    /* Animations */
    @keyframes glitch-anim-1 {
        0% { clip: rect(32px, 9999px, 76px, 0); }
        20% { clip: rect(8px, 9999px, 44px, 0); }
        40% { clip: rect(50px, 9999px, 99px, 0); }
        60% { clip: rect(42px, 9999px, 76px, 0); }
        80% { clip: rect(62px, 9999px, 52px, 0); }
        100% { clip: rect(34px, 9999px, 77px, 0); }
    }

    @keyframes glitch-anim-2 {
        0% { clip: rect(76px, 9999px, 36px, 0); }
        20% { clip: rect(54px, 9999px, 66px, 0); }
        40% { clip: rect(39px, 9999px, 35px, 0); }
        60% { clip: rect(45px, 9999px, 40px, 0); }
        80% { clip: rect(60px, 9999px, 72px, 0); }
        100% { clip: rect(31px, 9999px, 15px, 0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .cyber-title {
            font-size: 2.5rem;
        }

        .cyber-slide {
            padding-top: 100px;
            padding-bottom: 100px;
        }

        .cyber-hero-content {
            padding: 30px;
        }
    }

    @media (max-width: 768px) {
        .cyber-title {
            font-size: 2rem;
        }

        .cyber-subtitle {
            font-size: 1rem;
        }

        .cyber-buttons {
            flex-direction: column;
        }

        .cyber-btn-primary, .cyber-btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .cyber-features-grid {
            grid-template-columns: 1fr;
        }

        .cyber-products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 576px) {
        .cyber-title {
            font-size: 1.8rem;
        }

        .cyber-section-header h2 {
            font-size: 2rem;
        }

        .cyber-hero-content {
            padding: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Hero slider functionality
        const slides = document.querySelectorAll('.cyber-slide');
        let currentSlide = 0;

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Start with first slide
        showSlide(0);

        // Auto slide change
        setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);

        // Product card hover effect
        const productCards = document.querySelectorAll('.cyber-product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = 'scale(1.05)';
                }
            });

            card.addEventListener('mouseleave', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = 'scale(1)';
                }
            });
        });

        // Floating animation for slide images
        gsap.to('.cyber-hologram', {
            y: 20,
            duration: 3,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });
    });
</script>
@endpush
@endsection
