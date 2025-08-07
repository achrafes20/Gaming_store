@extends('Layouts.master')
@section('content')
<!-- Cyber Product Detail Section -->
<div class="cyber-product-detail-section">
    <div class="container">
        <!-- Cyber Product Header -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="cyber-section-header" data-aos="fade-up">
                    <div class="cyber-glitch" data-text="TECH SPECS">TECH SPECS</div>
                    <h2><span class="cyber-accent">{{ $product->name }}</span> DETAILS</h2>
                </div>
            </div>
        </div>

        <!-- Cyber Product Main Content -->
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-6">
                @if($product->ProductPhotos->count() > 1)
                <div class="cyber-product-gallery" data-aos="fade-right">
                    <div class="cyber-main-image">
                        <img src="{{ asset($product->imagepath) }}" alt="{{ $product->name }}" class="cyber-hologram">
                        <div class="cyber-image-glow"></div>
                    </div>

                    <div class="cyber-thumbnails">
                        @foreach ($product->ProductPhotos as $item)
                        <div class="cyber-thumbnail">
                            <img src="{{ asset($item->imagepath) }}" alt="{{ $product->name }} variation">
                            <div class="cyber-thumbnail-overlay"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="cyber-single-image" data-aos="fade-right">
                    <img src="{{ asset($product->imagepath) }}" alt="{{ $product->name }}" class="cyber-hologram">
                    <div class="cyber-image-glow"></div>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="cyber-product-info" data-aos="fade-left">
                    <div class="cyber-product-header">
                        <h3 class="cyber-product-title">{{ $product->name }}</h3>
                        <div class="cyber-product-price">
                            <span class="cyber-currency">$</span>{{ number_format($product->price, 2) }}
                        </div>
                    </div>

                    <div class="cyber-product-description">
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="cyber-product-meta">
                        <div class="cyber-meta-item">
                            <i class="fas fa-microchip"></i>
                            <span><strong>Category:</strong> {{ $product->Category->name }}</span>
                        </div>
                        <div class="cyber-meta-item">
                            <i class="fas fa-cube"></i>
                            <span><strong>Stock:</strong> {{ $product->quantity }} available</span>
                        </div>
                    </div>

                    <div class="cyber-product-actions">
                        <div class="cyber-quantity-selector">
                            <button class="cyber-qty-btn minus">-</button>
                            <input type="number" value="1" min="1" max="{{ $product->quantity }}">
                            <button class="cyber-qty-btn plus">+</button>
                        </div>

                        <a href="/addproducttocart/{{ $product->id }}" class="cyber-cart-btn">
                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                            <div class="cyber-btn-glow"></div>
                        </a>
                    </div>

                    <div class="cyber-tech-specs">
                        <h4><i class="fas fa-info-circle"></i> TECHNICAL SPECIFICATIONS</h4>
                        <ul>
                            <li><span>Model:</span> {{ $product->name }}</li>
                            <li><span>Dimensions:</span> Standard</li>
                            <li><span>Weight:</span> Lightweight</li>
                            <li><span>Warranty:</span> 2 Years</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cyber Related Products -->
<div class="cyber-related-products">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="cyber-section-header" data-aos="fade-up">
                    <div class="cyber-glitch" data-text="YOU MAY ALSO LIKE">YOU MAY ALSO LIKE</div>
                    <h2>RELATED <span class="cyber-accent">TECH</span></h2>
                </div>
            </div>
        </div>

        <div class="cyber-related-grid">
            @foreach ($relatedProducts as $item)
            <div class="cyber-related-card" data-aos="fade-up">
                <div class="cyber-related-image">
                    <a href="/single-product/{{ $item->id }}">
                        <img src="{{ asset($item->imagepath) }}" alt="{{ $item->name }}">
                        <div class="cyber-related-overlay">
                            <div class="cyber-quick-view">QUICK VIEW</div>
                        </div>
                    </a>
                </div>
                <div class="cyber-related-info">
                    <h3>{{ $item->name }}</h3>
                    <p>{{ Str::limit($item->description, 60) }}</p>
                    <div class="cyber-related-price">${{ number_format($item->price, 2) }}</div>
                    <a href="/addproducttocart/{{ $item->id }}" class="cyber-related-cart-btn">
                        <i class="fas fa-shopping-cart"></i> ADD TO CART
                    </a>
                </div>
                <div class="cyber-product-glow"></div>
            </div>
            @endforeach
        </div>
    </div>
</div>

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

    /* Cyber Product Detail Section */
    .cyber-product-detail-section {
        padding: 100px 0;
        background: var(--cyber-darker);
        position: relative;
    }

    /* Cyber Section Header */
    .cyber-section-header {
        margin-bottom: 60px;
    }

    .cyber-glitch {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
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

    .cyber-section-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: var(--cyber-light);
        margin-bottom: 15px;
    }

    .cyber-accent {
        color: var(--cyber-primary);
    }

    /* Cyber Product Gallery */
    .cyber-product-gallery {
        position: relative;
    }

    .cyber-main-image {
        position: relative;
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(0, 240, 255, 0.2);
    }

    .cyber-main-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .cyber-hologram {
        filter: drop-shadow(0 0 20px rgba(0, 240, 255, 0.3));
        transition: all 0.5s ease;
    }

    .cyber-main-image:hover .cyber-hologram {
        filter: drop-shadow(0 0 30px rgba(0, 240, 255, 0.5));
    }

    .cyber-image-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .cyber-thumbnails {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .cyber-thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 5px;
        overflow: hidden;
        border: 1px solid rgba(0, 240, 255, 0.2);
        cursor: pointer;
        position: relative;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .cyber-thumbnail:hover {
        border-color: var(--cyber-primary);
        transform: translateY(-5px);
    }

    .cyber-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cyber-thumbnail-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .cyber-thumbnail:hover .cyber-thumbnail-overlay {
        background: rgba(0, 0, 0, 0.1);
    }

    /* Cyber Single Image */
    .cyber-single-image {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(0, 240, 255, 0.2);
    }

    .cyber-single-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Cyber Product Info */
    .cyber-product-info {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.2);
        border-radius: 10px;
        padding: 30px;
        height: 100%;
    }

    .cyber-product-header {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px dashed rgba(0, 240, 255, 0.3);
    }

    .cyber-product-title {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .cyber-product-price {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-primary);
        font-size: 1.8rem;
        font-weight: bold;
    }

    .cyber-currency {
        font-size: 1rem;
        margin-right: 2px;
    }

    .cyber-product-description {
        color: rgba(224, 224, 255, 0.8);
        margin-bottom: 30px;
        line-height: 1.7;
    }

    .cyber-product-meta {
        margin-bottom: 30px;
    }

    .cyber-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: rgba(224, 224, 255, 0.8);
    }

    .cyber-meta-item i {
        color: var(--cyber-primary);
        width: 20px;
        text-align: center;
    }

    .cyber-product-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .cyber-quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid rgba(0, 240, 255, 0.3);
        border-radius: 5px;
        overflow: hidden;
    }

    .cyber-qty-btn {
        width: 40px;
        height: 40px;
        background: rgba(0, 240, 255, 0.1);
        color: var(--cyber-light);
        border: none;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cyber-qty-btn:hover {
        background: rgba(0, 240, 255, 0.3);
    }

    .cyber-quantity-selector input {
        width: 60px;
        height: 40px;
        text-align: center;
        background: transparent;
        border: none;
        color: var(--cyber-light);
        font-size: 1rem;
        -moz-appearance: textfield;
    }

    .cyber-quantity-selector input::-webkit-outer-spin-button,
    .cyber-quantity-selector input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cyber-cart-btn {
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 0 20px;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
        color: var(--cyber-dark);
        border: none;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 40px;
    }

    .cyber-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
    }

    .cyber-btn-glow {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.5s ease;
    }

    .cyber-cart-btn:hover .cyber-btn-glow {
        left: 100%;
    }

    .cyber-tech-specs {
        background: rgba(0, 240, 255, 0.05);
        border: 1px solid rgba(0, 240, 255, 0.1);
        border-radius: 5px;
        padding: 20px;
    }

    .cyber-tech-specs h4 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cyber-tech-specs ul {
        list-style: none;
    }

    .cyber-tech-specs li {
        margin-bottom: 8px;
        color: rgba(224, 224, 255, 0.8);
        display: flex;
    }

    .cyber-tech-specs span {
        font-weight: bold;
        min-width: 100px;
        display: inline-block;
    }

    /* Cyber Related Products */
    .cyber-related-products {
        padding: 80px 0;
        background: var(--cyber-dark);
        position: relative;
    }

    .cyber-related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .cyber-related-card {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .cyber-related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
        border-color: var(--cyber-primary);
    }

    .cyber-related-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .cyber-related-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: all 0.5s ease;
    }

    .cyber-related-card:hover .cyber-related-image img {
        transform: scale(1.05);
    }

    .cyber-related-overlay {
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

    .cyber-related-card:hover .cyber-related-overlay {
        opacity: 1;
    }

    .cyber-quick-view {
        background: var(--cyber-primary);
        color: var(--cyber-dark);
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    .cyber-related-info {
        padding: 20px;
    }

    .cyber-related-info h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .cyber-related-info p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .cyber-related-price {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-primary);
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .cyber-related-cart-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        background: rgba(0, 240, 255, 0.1);
        color: var(--cyber-light);
        border: 1px solid var(--cyber-primary);
        border-radius: 5px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cyber-related-cart-btn:hover {
        background: var(--cyber-primary);
        color: var(--cyber-dark);
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

    .cyber-related-card:hover .cyber-product-glow {
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

    /* Responsive Styles */
    @media (max-width: 992px) {
        .cyber-product-title {
            font-size: 1.5rem;
        }

        .cyber-product-price {
            font-size: 1.5rem;
        }

        .cyber-related-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cyber-section-header h2 {
            font-size: 2rem;
        }

        .cyber-product-actions {
            flex-direction: column;
        }

        .cyber-cart-btn {
            width: 100%;
        }

        .cyber-tech-specs li {
            flex-direction: column;
        }

        .cyber-tech-specs span {
            margin-bottom: 5px;
        }
    }

    @media (max-width: 576px) {
        .cyber-section-header h2 {
            font-size: 1.8rem;
        }

        .cyber-product-title {
            font-size: 1.3rem;
        }

        .cyber-product-description {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize product gallery slider if multiple images
        @if($product->ProductPhotos->count() > 1)
        $('.cyber-product-gallery').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.cyber-thumbnails'
        });

        $('.cyber-thumbnails').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.cyber-product-gallery',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            arrows: false
        });
        @endif

        // Quantity selector functionality
        $('.cyber-qty-btn.plus').click(function() {
            var input = $(this).siblings('input');
            var value = parseInt(input.val());
            input.val(value + 1).trigger('change');
        });

        $('.cyber-qty-btn.minus').click(function() {
            var input = $(this).siblings('input');
            var value = parseInt(input.val());
            if (value > 1) {
                input.val(value - 1).trigger('change');
            }
        });

        // Product image hover effect
        $('.cyber-hologram').hover(
            function() {
                $(this).css('filter', 'drop-shadow(0 0 30px rgba(0, 240, 255, 0.5))');
            },
            function() {
                $(this).css('filter', 'drop-shadow(0 0 20px rgba(0, 240, 255, 0.3))');
            }
        );

        // Related product hover effect
        $('.cyber-related-card').hover(
            function() {
                $(this).find('.cyber-product-glow').css('opacity', '1');
                $(this).find('img').css('transform', 'scale(1.05)');
            },
            function() {
                $(this).find('.cyber-product-glow').css('opacity', '0');
                $(this).find('img').css('transform', 'scale(1)');
            }
        );
    });
</script>
@endpush
@endsection
