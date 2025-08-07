@extends('Layouts.master')
@section('content')
    <!-- Hero Section Futuriste -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <p class="cyber-subtitle">Tech Shopping Cart</p>
                        <h1 class="cyber-title">YOUR DEVICES COLLECTION</h1>
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

    <!-- Cart Section Futuriste -->
    <div class="cyber-cart-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="cyber-cart-header">
                        <h2><i class="fas fa-shopping-basket"></i> Your Selected Tech</h2>
                        <div class="cyber-cart-count">{{ count($cartProducts) }} ITEMS</div>
                    </div>

                    <div class="cyber-cart-items">
                        @foreach ($cartProducts as $item)
                            <div class="cyber-cart-item" data-aos="fade-up">
                                <div class="cyber-cart-item-image">
                                    <img src="{{ asset($item->product->imagepath) }}" alt="{{ $item->product->name }}"
                                        class="cyber-hover-glow">
                                    <div class="cyber-item-actions">

                                        <a href="/deletecartitem/{{ $item->id }}" class="cyber-delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>

                                    </div>
                                </div>
                                <div class="cyber-cart-item-details">
                                    <h3>
                                        <a href="/single-product/{{ $item->product->id }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <div class="cyber-item-specs">
                                        <div class="cyber-spec">
                                            <i class="fas fa-microchip"></i>
                                            <span>High Performance</span>
                                        </div>
                                        <div class="cyber-spec">
                                            <i class="fas fa-bolt"></i>
                                            <span>Fast Delivery</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cyber-cart-item-price">
                                    <div class="cyber-price">${{ number_format($item->product->price, 2) }}</div>
                                </div>
                                <div class="cyber-cart-item-quantity">
                                    <div class="cyber-quantity-selector">


                                        <span class="cyber-qty-value">{{ $item->quantity }}</span>
                                
                                    </div>
                                </div>
                                <div class="cyber-cart-item-total">
                                    <div class="cyber-total-price">
                                        ${{ number_format($item->quantity * $item->product->price, 2) }}</div>
                                </div>
                            </div>
                        @endforeach

                        @if (count($cartProducts) == 0)
                            <div class="cyber-empty-cart">
                                <div class="cyber-empty-icon">
                                    <i class="fas fa-shopping-basket"></i>
                                </div>
                                <h3>Your Tech Cart is Empty</h3>
                                <p>Explore our latest electronic devices and add some tech magic to your cart!</p>
                                <a href="/products" class="cyber-explore-btn">
                                    <span>Explore Products</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cyber-cart-summary">
                        <h3 class="cyber-summary-title"><i class="fas fa-receipt"></i> ORDER SUMMARY</h3>

                        <div class="cyber-summary-details">
                            <div class="cyber-summary-row">
                                <span>Subtotal ({{ count($cartProducts) }} items)</span>
                                <span>${{ number_format($cartProducts->sum(function ($item) {return $item->product->price * $item->quantity;}),2) }}</span>
                            </div>
                            <div class="cyber-summary-row">
                                <span>Shipping</span>
                                <span class="cyber-free">FREE</span>
                            </div>
                            <div class="cyber-summary-row">
                                <span>Discount</span>
                                <span style="color: red">- {{ session('discount', 0) }} Dh</span>
                            </div>
                            <div class="cyber-total-row">
                                <span>Total</span>
                                <span
                                    class="cyber-grand-total">${{ number_format(
                                        $cartProducts->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) - session('discount', 0),
                                        2,
                                    ) }}</span>
                            </div>
                        </div>

                        <div class="cyber-promo-section">
                            <div class="cyber-promo-input">
                                <form method="POST" action="{{ route('coupon.apply') }}">
                                    @csrf
                                    <input type="text" name="code" placeholder="Enter your coupon code">
                                    <button type="submit" class="cyber-promo-btn">Apply</button>
                                </form>

                            </div>
                            @if (session('discount'))
                                <p style="color: rgb(92, 253, 6)">Coupon applied: -{{ session('discount') }} DH</p>
                            @endif
                        </div>

                        <div class="cyber-checkout-btns">
                            <a href="/Completeorder" class="cyber-checkout-btn">
                                <span>PROCEED TO CHECKOUT</span>
                                <i class="fas fa-lock"></i>
                            </a>
                            <a href="/previousorder" class="cyber-previous-orders-btn">
                                <span>VIEW PREVIOUS ORDERS</span>
                                <i class="fas fa-history"></i>
                            </a>
                        </div>

                        <div class="cyber-security-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure Checkout • 256-bit SSL Encryption</span>
                        </div>
                    </div>


                </div>
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

    <!-- Mini Cart Preview (fixed on scroll) -->
    <div class="cyber-mini-cart">
        <div class="cyber-mini-cart-icon">
            <i class="fas fa-shopping-basket"></i>
            <span class="cyber-mini-cart-count">{{ count($cartProducts) }}</span>
        </div>
        <div class="cyber-mini-cart-total">
            ${{ number_format($cartProducts->sum(function ($item) {return $item->product->price * $item->quantity;}),2) }}
        </div>
        <a href="/Completeorder" class="cyber-mini-checkout-btn">CHECKOUT</a>
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
                --cyber-card-bg: rgba(20, 20, 40, 0.7);
            }

            /* Hero Section */
            .cyber-hero-section {
                position: relative;
                height: 300px;
                background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
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

            .cyber-subtitle {
                color: var(--cyber-primary);
                font-size: 1.2rem;
                letter-spacing: 3px;
                text-transform: uppercase;
                margin-bottom: 15px;
                text-shadow: 0 0 10px rgba(0, 240, 255, 0.5);
            }

            .cyber-title {
                font-size: 2.8rem;
                font-weight: 700;
                background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 30px;
                text-transform: uppercase;
                letter-spacing: 2px;
            }

            .cyber-pulse-animation {
                position: relative;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .pulse-circle {
                position: absolute;
                width: 15px;
                height: 15px;
                border-radius: 50%;
                background-color: var(--cyber-primary);
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
                    transform: scale(8);
                    opacity: 0;
                }
            }

            /* Cart Section */
            .cyber-cart-section {
                padding: 60px 0;
                position: relative;
                background-color: var(--cyber-dark);
            }

            .cyber-cart-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
                padding-bottom: 15px;
                border-bottom: 1px solid rgba(0, 240, 255, 0.2);
            }

            .cyber-cart-header h2 {
                color: var(--cyber-light);
                font-size: 1.5rem;
                margin: 0;
                display: flex;
                align-items: center;
            }

            .cyber-cart-header h2 i {
                margin-right: 10px;
                color: var(--cyber-primary);
            }

            .cyber-cart-count {
                background: rgba(0, 240, 255, 0.1);
                color: var(--cyber-primary);
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: bold;
                letter-spacing: 1px;
            }

            /* Cart Items */
            .cyber-cart-items {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .cyber-cart-item {
                display: grid;
                grid-template-columns: 100px 2fr 1fr 1fr 1fr;
                gap: 20px;
                background: var(--cyber-card-bg);
                border: 1px solid rgba(0, 240, 255, 0.1);
                border-radius: 10px;
                padding: 20px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .cyber-cart-item:hover {
                border-color: var(--cyber-primary);
                box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1);
                transform: translateY(-3px);
            }

            .cyber-cart-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(0, 240, 255, 0.03) 0%, rgba(255, 0, 240, 0.03) 100%);
                z-index: -1;
            }

            .cyber-cart-item-image {
                position: relative;
                width: 100px;
                height: 100px;
                border-radius: 8px;
                overflow: hidden;
            }

            .cyber-cart-item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease;
            }

            .cyber-hover-glow:hover {
                transform: scale(1.05);
                filter: brightness(1.1) saturate(1.1);
            }

            .cyber-item-actions {
                position: absolute;
                top: 5px;
                right: 5px;
                display: flex;
                flex-direction: column;
                gap: 5px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .cyber-cart-item:hover .cyber-item-actions {
                opacity: 1;
            }

            .cyber-delete-btn,
            .cyber-wishlist-btn {
                width: 25px;
                height: 25px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
            }

            .cyber-delete-btn {
                color: var(--cyber-danger);
            }

            .cyber-wishlist-btn {
                color: var(--cyber-accent);
            }

            .cyber-delete-btn:hover,
            .cyber-wishlist-btn:hover {
                transform: scale(1.1);
                background: rgba(0, 240, 255, 0.3);
            }

            [data-tooltip] {
                position: relative;
            }

            [data-tooltip]::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(10, 10, 26, 0.9);
                color: var(--cyber-light);
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 0.7rem;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            [data-tooltip]:hover::after {
                opacity: 1;
            }

            .cyber-cart-item-details {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .cyber-cart-item-details h3 {
                margin: 0 0 10px 0;
                font-size: 1.1rem;
            }

            .cyber-cart-item-details h3 a {
                color: var(--cyber-light);
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .cyber-cart-item-details h3 a:hover {
                color: var(--cyber-primary);
            }

            .cyber-item-specs {
                display: flex;
                gap: 15px;
                margin-top: 10px;
            }

            .cyber-spec {
                display: flex;
                align-items: center;
                font-size: 0.8rem;
                color: var(--cyber-light);
                opacity: 0.8;
            }

            .cyber-spec i {
                margin-right: 5px;
                color: var(--cyber-primary);
                font-size: 0.9rem;
            }

            .cyber-cart-item-price,
            .cyber-cart-item-quantity,
            .cyber-cart-item-total {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cyber-price {
                color: var(--cyber-primary);
                font-weight: bold;
                font-size: 1.1rem;
            }

            .cyber-quantity-selector {
                display: flex;
                align-items: center;
                border: 1px solid rgba(0, 240, 255, 0.3);
                border-radius: 5px;
                overflow: hidden;
            }

            .cyber-qty-btn {
                width: 30px;
                height: 30px;
                background: rgba(0, 240, 255, 0.1);
                color: var(--cyber-light);
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cyber-qty-btn:hover {
                background: rgba(0, 240, 255, 0.3);
                color: var(--cyber-primary);
            }

            .cyber-qty-value {
                width: 40px;
                text-align: center;
                font-size: 0.9rem;
            }

            .cyber-total-price {
                color: var(--cyber-accent);
                font-weight: bold;
                font-size: 1.1rem;
            }

            /* Empty Cart */
            .cyber-empty-cart {
                text-align: center;
                padding: 50px 20px;
                background: var(--cyber-card-bg);
                border-radius: 10px;
                border: 1px dashed rgba(0, 240, 255, 0.3);
            }

            .cyber-empty-icon {
                font-size: 3rem;
                color: rgba(0, 240, 255, 0.3);
                margin-bottom: 20px;
            }

            .cyber-empty-cart h3 {
                color: var(--cyber-light);
                margin-bottom: 10px;
            }

            .cyber-empty-cart p {
                color: var(--cyber-light);
                opacity: 0.7;
                margin-bottom: 20px;
            }

            .cyber-explore-btn {
                display: inline-flex;
                align-items: center;
                padding: 10px 20px;
                background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary));
                color: var(--cyber-dark);
                text-decoration: none;
                border-radius: 50px;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .cyber-explore-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
            }

            .cyber-explore-btn i {
                margin-left: 10px;
                transition: transform 0.3s ease;
            }

            .cyber-explore-btn:hover i {
                transform: translateX(5px);
            }

            /* Cart Summary */
            .cyber-cart-summary {
                background: var(--cyber-card-bg);
                border: 1px solid rgba(0, 240, 255, 0.1);
                border-radius: 10px;
                padding: 25px;
                margin-bottom: 30px;
                position: sticky;
                top: 20px;
            }

            .cyber-summary-title {
                color: var(--cyber-primary);
                font-size: 1.2rem;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
            }

            .cyber-summary-title i {
                margin-right: 10px;
            }

            .cyber-summary-details {
                margin-bottom: 20px;
            }

            .cyber-summary-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                color: var(--cyber-light);
                font-size: 0.9rem;
                border-bottom: 1px dashed rgba(0, 240, 255, 0.2);
            }

            .cyber-free {
                color: var(--cyber-accent);
            }

            .cyber-total-row {
                display: flex;
                justify-content: space-between;
                padding: 15px 0;
                margin-top: 10px;
                font-size: 1.1rem;
                font-weight: bold;
            }

            .cyber-grand-total {
                color: var(--cyber-primary);
                font-size: 1.3rem;
            }

            /* Promo Section */
            .cyber-promo-section {
                margin-bottom: 25px;
            }

            .cyber-promo-input {
                display: flex;
                border: 1px solid rgba(0, 240, 255, 0.3);
                border-radius: 5px;
                overflow: hidden;
            }

            .cyber-promo-input input {
                flex: 1;
                padding: 10px 15px;
                background: rgba(20, 20, 40, 0.5);
                border: none;
                color: var(--cyber-light);
                outline: none;
            }

            .cyber-promo-input input::placeholder {
                color: rgba(224, 224, 255, 0.5);
            }

            .cyber-promo-btn {
                padding: 0 15px;
                background: rgba(0, 240, 255, 0.1);
                color: var(--cyber-primary);
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .cyber-promo-btn:hover {
                background: rgba(0, 240, 255, 0.3);
            }

            /* Checkout Buttons */
            .cyber-checkout-btns {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-bottom: 20px;
            }

            .cyber-checkout-btn,
            .cyber-previous-orders-btn {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px 20px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .cyber-checkout-btn {
                background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
                color: var(--cyber-dark);
            }

            .cyber-checkout-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
            }

            .cyber-previous-orders-btn {
                background: rgba(0, 240, 255, 0.1);
                color: var(--cyber-light);
                border: 1px solid rgba(0, 240, 255, 0.3);
            }

            .cyber-previous-orders-btn:hover {
                background: rgba(0, 240, 255, 0.2);
                transform: translateY(-3px);
            }

            .cyber-security-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                color: rgba(224, 224, 255, 0.7);
                gap: 8px;
            }

            .cyber-security-badge i {
                color: var(--cyber-accent);
            }

            /* Recommendations */
            .cyber-recommendations {
                background: var(--cyber-card-bg);
                border: 1px solid rgba(0, 240, 255, 0.1);
                border-radius: 10px;
                padding: 20px;
            }

            .cyber-recommendations-title {
                color: var(--cyber-primary);
                font-size: 1.1rem;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
            }

            .cyber-recommendations-title i {
                margin-right: 10px;
            }

            .cyber-recommendations-slider {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .cyber-recommendation-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 10px;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .cyber-recommendation-item:hover {
                background: rgba(0, 240, 255, 0.05);
            }

            .cyber-recommendation-item img {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: 5px;
            }

            .cyber-recommendation-item h4 {
                margin: 0 0 5px 0;
                font-size: 0.9rem;
                color: var(--cyber-light);
            }

            /* Mini Cart */
            .cyber-mini-cart {
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: var(--cyber-card-bg);
                border: 1px solid rgba(0, 240, 255, 0.3);
                border-radius: 50px;
                padding: 10px 20px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                transform: translateY(100px);
                opacity: 0;
                transition: all 0.5s ease;
            }

            .cyber-mini-cart.visible {
                transform: translateY(0);
                opacity: 1;
            }

            .cyber-mini-cart-icon {
                position: relative;
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, var(--cyber-primary), var(--cyber-secondary));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--cyber-dark);
                font-size: 1.2rem;
            }

            .cyber-mini-cart-count {
                position: absolute;
                top: -5px;
                right: -5px;
                width: 20px;
                height: 20px;
                background: var(--cyber-danger);
                color: white;
                border-radius: 50%;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cyber-mini-cart-total {
                font-weight: bold;
                color: var(--cyber-primary);
            }

            .cyber-mini-checkout-btn {
                padding: 8px 15px;
                background: var(--cyber-accent);
                color: var(--cyber-dark);
                border-radius: 20px;
                text-decoration: none;
                font-size: 0.8rem;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .cyber-mini-checkout-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 3px 10px rgba(0, 240, 255, 0.3);
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
                background: var(--cyber-primary);
                top: -100px;
                left: -100px;
                animation: float 15s infinite ease-in-out;
            }

            .orb-2 {
                width: 200px;
                height: 200px;
                background: var(--cyber-secondary);
                bottom: -50px;
                right: -50px;
                animation: float 12s infinite ease-in-out reverse;
            }

            .orb-3 {
                width: 150px;
                height: 150px;
                background: var(--cyber-accent);
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

                0%,
                100% {
                    transform: translate(0, 0);
                }

                50% {
                    transform: translate(20px, 20px);
                }
            }

            /* Responsive Adjustments */
            @media (max-width: 992px) {
                .cyber-cart-item {
                    grid-template-columns: 80px 1fr;
                    grid-template-rows: auto auto auto;
                    gap: 15px;
                }

                .cyber-cart-item-price,
                .cyber-cart-item-quantity,
                .cyber-cart-item-total {
                    justify-content: flex-start;
                }

                .cyber-cart-item-quantity {
                    grid-column: 1;
                    grid-row: 2;
                }

                .cyber-cart-item-price {
                    grid-column: 2;
                    grid-row: 2;
                }

                .cyber-cart-item-total {
                    grid-column: 2;
                    grid-row: 3;
                }
            }

            @media (max-width: 768px) {
                .cyber-title {
                    font-size: 2rem;
                }

                .cyber-cart-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 10px;
                }

                .cyber-mini-cart {
                    bottom: 20px;
                    right: 20px;
                    padding: 8px 15px;
                }
            }

            @media (max-width: 576px) {
                .cyber-cart-item {
                    padding: 15px;
                    gap: 10px;
                }

                .cyber-cart-item-image {
                    width: 70px;
                    height: 70px;
                }

                .cyber-checkout-btn,
                .cyber-previous-orders-btn {
                    padding: 12px 15px;
                    font-size: 0.9rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
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

                // Show/hide mini cart on scroll
                const miniCart = document.querySelector('.cyber-mini-cart');
                let lastScrollPosition = window.scrollY;

                window.addEventListener('scroll', function() {
                    const currentScrollPosition = window.scrollY;

                    if (currentScrollPosition > 200) {
                        miniCart.classList.add('visible');

                        if (currentScrollPosition < lastScrollPosition) {
                            // Scrolling up
                            miniCart.style.transform = 'translateY(0)';
                        } else {
                            // Scrolling down
                            miniCart.style.transform = 'translateY(70px)';
                        }
                    } else {
                        miniCart.classList.remove('visible');
                    }

                    lastScrollPosition = currentScrollPosition;
                });

                // Quantity buttons functionality
                document.querySelectorAll('.cyber-qty-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.getAttribute('data-item');
                        const isPlus = this.classList.contains('plus');
                        const qtyElement = this.parentElement.querySelector('.cyber-qty-value');
                        let qty = parseInt(qtyElement.textContent);

                        if (isPlus) {
                            qty++;
                        } else if (qty > 1) {
                            qty--;
                        }

                        qtyElement.textContent = qty;

                        // In a real app, you would send an AJAX request here to update the cart
                        // Example:
                        // fetch(`/update-cart/${itemId}`, {
                        //     method: 'POST',
                        //     headers: {
                        //         'Content-Type': 'application/json',
                        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        //     },
                        //     body: JSON.stringify({ quantity: qty })
                        // })
                        // .then(response => response.json())
                        // .then(data => {
                        //     // Update totals
                        // });
                    });
                });

                // Add hover effect to cart items
                const cartItems = document.querySelectorAll('.cyber-cart-item');
                cartItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        const img = this.querySelector('img');
                        if (img) {
                            img.style.transform = 'scale(1.05)';
                            img.style.filter = 'brightness(1.1) saturate(1.1)';
                        }
                    });

                    item.addEventListener('mouseleave', function() {
                        const img = this.querySelector('img');
                        if (img) {
                            img.style.transform = '';
                            img.style.filter = '';
                        }
                    });
                });

                // Pulse animation for cart items
                setInterval(function() {
                    const items = document.querySelectorAll('.cyber-cart-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.boxShadow = '0 0 15px rgba(0, 240, 255, 0.2)';
                            setTimeout(() => {
                                item.style.boxShadow = '';
                            }, 1000);
                        }, index * 200);
                    });
                }, 8000);
            });
        </script>
    @endpush
@endsection
