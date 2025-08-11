@extends('Layouts.master')
@section('content')
    <!-- Hero Section Futuriste -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <p class="cyber-subtitle">Fresh and Organic</p>
                        <h1 class="cyber-title">CHECKOUT TERMINAL</h1>
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

    <!-- Checkout Section - Style Cyberpunk -->
    <div class="cyber-checkout-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cyber-accordion-wrap">
                        <div class="cyber-accordion" id="cyberAccordion">
                            <!-- Billing Address Section -->
                            <div class="cyber-accordion-card" data-aos="fade-up">
                                <div class="cyber-accordion-header" id="cyberHeadingOne">
                                    <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                        data-target="#cyberCollapseOne" aria-expanded="true"
                                        aria-controls="cyberCollapseOne">
                                        <i class="fas fa-address-card cyber-accordion-icon"></i>
                                        <span class="cyber-accordion-title">BILLING ADDRESS</span>
                                        <i class="fas fa-chevron-down cyber-accordion-arrow"></i>
                                    </button>
                                </div>

                                <div id="cyberCollapseOne" class="cyber-accordion-collapse show"
                                    aria-labelledby="cyberHeadingOne" data-parent="#cyberAccordion">
                                    <div class="cyber-accordion-body">
                                        <div class="cyber-billing-form">
                                            <form action="/StoreOrder" method="POST" id="store-order" name="store-order">
                                                @csrf
                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <input type="text" required placeholder="FULL NAME" id="name"
                                                        name="name" class="cyber-input">
                                                    <div class="cyber-input-underline"></div>
                                                </div>

                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <input type="email" required placeholder="EMAIL ADDRESS"
                                                        id="email" name="email" class="cyber-input">
                                                    <div class="cyber-input-underline"></div>
                                                </div>

                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <input type="text" required placeholder="DELIVERY ADDRESS"
                                                        id="address" name="address" class="cyber-input">
                                                    <div class="cyber-input-underline"></div>
                                                </div>

                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <input type="tel" required placeholder="CONTACT NUMBER"
                                                        id="phone" name="phone" class="cyber-input">
                                                    <div class="cyber-input-underline"></div>
                                                </div>

                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </div>
                                                    <textarea name="note" id="note" cols="30" rows="4" placeholder="SPECIAL INSTRUCTIONS (OPTIONAL)"
                                                        class="cyber-textarea"></textarea>
                                                    <div class="cyber-textarea-underline"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary Section -->
                            <div class="cyber-accordion-card" data-aos="fade-up">
                                <div class="cyber-accordion-header" id="cyberHeadingThree">
                                    <button class="cyber-accordion-btn collapsed" type="button" data-toggle="collapse"
                                        data-target="#cyberCollapseThree" aria-expanded="false"
                                        aria-controls="cyberCollapseThree">
                                        <i class="fas fa-shopping-basket cyber-accordion-icon"></i>
                                        <span class="cyber-accordion-title">ORDER SUMMARY</span>
                                        <i class="fas fa-chevron-down cyber-accordion-arrow"></i>
                                    </button>
                                </div>
                                <div id="cyberCollapseThree" class="cyber-accordion-collapse"
                                    aria-labelledby="cyberHeadingThree" data-parent="#cyberAccordion">
                                    <div class="cyber-accordion-body">
                                        <div class="cyber-cart-section">
                                            <div class="cyber-cart-table-wrap">
                                                <table class="cyber-cart-table">
                                                    <thead class="cyber-cart-table-head">
                                                        <tr class="cyber-table-head-row">
                                                            <th class="cyber-product-remove"></th>
                                                            <th class="cyber-product-image">PRODUCT</th>
                                                            <th class="cyber-product-name">DETAILS</th>
                                                            <th class="cyber-product-price">PRICE</th>
                                                            <th class="cyber-product-quantity">QTY</th>
                                                            <th class="cyber-product-total">TOTAL</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cartProducts as $item)
                                                            <tr class="cyber-table-body-row">
                                                                <td class="cyber-product-remove">
                                                                    <a href="/deletecartitem/{{ $item->id }}"
                                                                        class="cyber-delete-btn">
                                                                        <i class="fas fa-times-circle"></i>
                                                                    </a>
                                                                </td>
                                                                <td class="cyber-product-image">
                                                                    <div class="cyber-product-img-container">
                                                                        <img src="{{ asset($item->product->imagepath) }}"
                                                                            alt="{{ $item->product->name }}"
                                                                            class="cyber-product-img">
                                                                        <div class="cyber-img-overlay"></div>
                                                                    </div>
                                                                </td>
                                                                <td class="cyber-product-name">
                                                                    <a href="/single-product/{{ $item->product->id }}"
                                                                        class="cyber-product-link">
                                                                        {{ $item->product->name }}
                                                                    </a>
                                                                </td>
                                                                <td class="cyber-product-price">
                                                                    {{ number_format($item->product->price, 2) }}Dh
                                                                </td>
                                                                <td class="cyber-product-quantity">
                                                                    <span
                                                                        class="cyber-quantity-badge">{{ $item->quantity }}</span>
                                                                </td>
                                                                <td class="cyber-product-total">
                                                                    {{ number_format($item->quantity * $item->product->price, 2) }}Dh
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="cyber-order-total-section">
                                                <div class="cyber-total-card">
                                                    <h3 class="cyber-total-title">ORDER TOTAL</h3>
                                                    <div class="cyber-total-row">
                                                        <span>SUBTOTAL</span>
                                                        <span>{{ number_format(
                                                            $cartProducts->sum(function ($item) {
                                                                return $item->product->price * $item->quantity;
                                                            }),
                                                            2,
                                                        ) }}
                                                            Dh</span>
                                                    </div>
                                                    <div class="cyber-total-row">
                                                        <span>SHIPPING</span>
                                                        <span class="cyber-free">FREE</span>
                                                    </div>
                                                    <div class="cyber-total-row">
                                                        <span>Discount</span>
                                                        <span style="color: red;">- {{ session('discount', 0) }} Dh</span>
                                                    </div>
                                                    <div class="cyber-grand-total-row">
                                                        <span>GRAND TOTAL</span>
                                                        <span
                                                            class="cyber-grand-total">{{ number_format(
                                                                $cartProducts->sum(function ($item) {
                                                                    return $item->product->price * $item->quantity;
                                                                }) - session('discount', 0),
                                                                2,
                                                            ) }}
                                                            Dh</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Place Order Button -->
                <div class="col-lg-12 cyber-order-btn-container">
                    <button onclick="event.preventDefault(); document.getElementById('store-order').submit();"
                        class="cyber-order-btn">
                        <span class="cyber-btn-text">CONFIRM ORDER</span>
                        <span class="cyber-btn-icon">
                            <i class="fas fa-paper-plane"></i>
                        </span>
                        <span class="cyber-btn-pulse"></span>
                    </button>
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
                --cyber-organic: #00ff88;
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

            .cyber-subtitle {
                color: var(--cyber-organic);
                font-size: 1.2rem;
                letter-spacing: 3px;
                text-transform: uppercase;
                margin-bottom: 15px;
                text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
            }

            .cyber-title {
                font-size: 3.5rem;
                font-weight: 700;
                background: linear-gradient(90deg, var(--cyber-organic), var(--cyber-primary));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 30px;
                text-transform: uppercase;
                letter-spacing: 2px;
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
                background-color: var(--cyber-organic);
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

            /* Checkout Section */
            .cyber-checkout-section {
                padding: 80px 0;
                position: relative;
            }

            .cyber-accordion-wrap {
                max-width: 1200px;
                margin: 0 auto;
            }

            .cyber-accordion-card {
                background: var(--cyber-bg);
                border: 1px solid var(--cyber-border);
                border-radius: 8px;
                margin-bottom: 20px;
                box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1);
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .cyber-accordion-card:hover {
                box-shadow: 0 8px 25px rgba(0, 240, 255, 0.2);
            }

            .cyber-accordion-header {
                background: rgba(20, 20, 40, 0.6);
                padding: 0;
                border-bottom: 1px solid var(--cyber-border);
            }

            .cyber-accordion-btn {
                width: 100%;
                padding: 20px;
                background: transparent;
                border: none;
                color: var(--cyber-light);
                text-align: left;
                display: flex;
                align-items: center;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Orbitron', sans-serif;
                position: relative;
            }

            .cyber-accordion-btn:hover {
                background: rgba(0, 240, 255, 0.05);
            }

            .cyber-accordion-btn:not(.collapsed) {
                background: rgba(0, 240, 255, 0.1);
            }

            .cyber-accordion-icon {
                margin-right: 15px;
                color: var(--cyber-organic);
                font-size: 1.2rem;
                width: 25px;
                text-align: center;
            }

            .cyber-accordion-title {
                flex: 1;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: bold;
            }

            .cyber-accordion-arrow {
                transition: transform 0.3s ease;
                color: var(--cyber-primary);
            }

            .cyber-accordion-btn.collapsed .cyber-accordion-arrow {
                transform: rotate(-90deg);
            }

            .cyber-accordion-collapse {
                transition: all 0.4s ease;
            }

            .cyber-accordion-body {
                padding: 30px;
            }

            /* Billing Form */
            .cyber-billing-form {
                max-width: 800px;
                margin: 0 auto;
            }

            .cyber-form-group {
                position: relative;
                margin-bottom: 30px;
            }

            .cyber-input-icon {
                position: absolute;
                left: 15px;
                top: 15px;
                color: var(--cyber-primary);
                z-index: 2;
            }

            .cyber-input,
            .cyber-textarea {
                width: 100%;
                padding: 15px 15px 15px 50px;
                background: rgba(20, 20, 40, 0.5);
                border: 1px solid var(--cyber-border);
                border-radius: 5px;
                color: var(--cyber-light);
                font-family: 'Rajdhani', sans-serif;
                font-size: 1rem;
                transition: all 0.3s ease;
                position: relative;
                z-index: 1;
            }

            .cyber-textarea {
                min-height: 120px;
                resize: vertical;
            }

            .cyber-input:focus,
            .cyber-textarea:focus {
                outline: none;
                border-color: var(--cyber-organic);
                box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
            }

            .cyber-input-underline,
            .cyber-textarea-underline {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--cyber-organic);
                transition: width 0.4s ease;
            }

            .cyber-input:focus~.cyber-input-underline,
            .cyber-textarea:focus~.cyber-textarea-underline {
                width: 100%;
            }

            /* Cart Table */
            .cyber-cart-table-wrap {
                overflow-x: auto;
            }

            .cyber-cart-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0 15px;
            }

            .cyber-cart-table-head {
                background: rgba(0, 240, 255, 0.1);
            }

            .cyber-table-head-row th {
                padding: 15px;
                text-align: left;
                color: var(--cyber-primary);
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 0.8rem;
                font-weight: bold;
                border-bottom: 1px solid var(--cyber-border);
            }

            .cyber-table-body-row {
                background: rgba(20, 20, 40, 0.5);
                transition: all 0.3s ease;
            }

            .cyber-table-body-row:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1);
            }

            .cyber-table-body-row td {
                padding: 15px;
                border-top: 1px solid var(--cyber-border);
                border-bottom: 1px solid var(--cyber-border);
            }

            .cyber-table-body-row td:first-child {
                border-left: 1px solid var(--cyber-border);
                border-radius: 5px 0 0 5px;
            }

            .cyber-table-body-row td:last-child {
                border-right: 1px solid var(--cyber-border);
                border-radius: 0 5px 5px 0;
            }

            .cyber-product-remove a {
                color: var(--cyber-danger);
                transition: all 0.3s ease;
            }

            .cyber-product-remove a:hover {
                color: var(--cyber-light);
                transform: scale(1.2);
            }

            .cyber-product-img-container {
                width: 80px;
                height: 80px;
                border-radius: 5px;
                overflow: hidden;
                position: relative;
            }

            .cyber-product-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease;
            }

            .cyber-img-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 240, 255, 0.05);
                transition: all 0.3s ease;
            }

            .cyber-product-img-container:hover .cyber-product-img {
                transform: scale(1.1);
            }

            .cyber-product-img-container:hover .cyber-img-overlay {
                background: rgba(0, 240, 255, 0.2);
            }

            .cyber-product-link {
                color: var(--cyber-light);
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .cyber-product-link:hover {
                color: var(--cyber-organic);
            }

            .cyber-quantity-badge {
                display: inline-block;
                padding: 5px 10px;
                background: rgba(0, 240, 255, 0.1);
                border-radius: 20px;
                min-width: 30px;
                text-align: center;
            }

            /* Order Total */
            .cyber-order-total-section {
                margin-top: 40px;
            }

            .cyber-total-card {
                background: rgba(20, 20, 40, 0.5);
                border: 1px solid var(--cyber-border);
                border-radius: 8px;
                padding: 25px;
                max-width: 400px;
                margin-left: auto;
            }

            .cyber-total-title {
                color: var(--cyber-primary);
                font-size: 1.3rem;
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 1px;
                position: relative;
                padding-bottom: 10px;
            }

            .cyber-total-title::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 50px;
                height: 2px;
                background: linear-gradient(90deg, var(--cyber-primary), transparent);
            }

            .cyber-total-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border-bottom: 1px dashed var(--cyber-border);
                color: var(--cyber-light);
            }

            .cyber-free {
                color: var(--cyber-organic);
            }

            .cyber-grand-total-row {
                display: flex;
                justify-content: space-between;
                padding: 15px 0;
                margin-top: 10px;
                font-size: 1.2rem;
                font-weight: bold;
            }

            .cyber-grand-total {
                color: var(--cyber-organic);
                font-size: 1.3rem;
            }

            /* Place Order Button */
            .cyber-order-btn-container {
                text-align: center;
                margin-top: 50px;
            }

            .cyber-order-btn {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 18px 40px;
                background: linear-gradient(90deg, var(--cyber-organic), var(--cyber-primary));
                color: var(--cyber-dark);
                border: none;
                border-radius: 50px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 2px;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Orbitron', sans-serif;
                box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
            }

            .cyber-order-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0, 255, 136, 0.5);
            }

            .cyber-btn-text {
                position: relative;
                z-index: 2;
            }

            .cyber-btn-icon {
                margin-left: 15px;
                position: relative;
                z-index: 2;
            }

            .cyber-btn-pulse {
                position: absolute;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-organic));
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
                .cyber-title {
                    font-size: 2.8rem;
                }

                .cyber-cart-table-head {
                    display: none;
                }

                .cyber-table-body-row {
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 20px;
                    border: 1px solid var(--cyber-border);
                    border-radius: 5px;
                }

                .cyber-table-body-row td {
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                    border: none;
                    padding: 10px 15px;
                }

                .cyber-table-body-row td::before {
                    content: attr(data-label);
                    font-weight: bold;
                    color: var(--cyber-primary);
                    margin-right: 15px;
                    text-transform: uppercase;
                    font-size: 0.8rem;
                }

                .cyber-product-remove {
                    order: 1;
                    text-align: right;
                }

                .cyber-product-image {
                    order: 2;
                    width: 100%;
                    text-align: center;
                }

                .cyber-product-name {
                    order: 3;
                }

                .cyber-product-price {
                    order: 4;
                }

                .cyber-product-quantity {
                    order: 5;
                }

                .cyber-product-total {
                    order: 6;
                    font-weight: bold;
                    color: var(--cyber-organic);
                }

                .cyber-total-card {
                    max-width: 100%;
                }
            }

            @media (max-width: 576px) {
                .cyber-title {
                    font-size: 2.2rem;
                }

                .cyber-subtitle {
                    font-size: 1rem;
                }

                .cyber-accordion-body {
                    padding: 20px 15px;
                }

                .cyber-order-btn {
                    padding: 15px 30px;
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

                // Add hover effect to product images
                const productImages = document.querySelectorAll('.cyber-product-img');
                productImages.forEach(img => {
                    img.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.1)';
                    });

                    img.addEventListener('mouseleave', function() {
                        this.style.transform = '';
                    });
                });

                // Add pulse animation to accordion cards periodically
                setInterval(function() {
                    const accordionCards = document.querySelectorAll('.cyber-accordion-card');
                    accordionCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.boxShadow = '0 0 20px rgba(0, 255, 136, 0.3)';
                            setTimeout(() => {
                                card.style.boxShadow =
                                    '0 5px 15px rgba(0, 240, 255, 0.1)';
                            }, 1000);
                        }, index * 300);
                    });
                }, 8000);

                // Add focus effects to form inputs
                const inputs = document.querySelectorAll('.cyber-input, .cyber-textarea');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.querySelector('.cyber-input-icon').style.color =
                            'var(--cyber-organic)';
                    });

                    input.addEventListener('blur', function() {
                        this.parentElement.querySelector('.cyber-input-icon').style.color =
                            'var(--cyber-primary)';
                    });
                });
            });
        </script>
    @endpush
@endsection
