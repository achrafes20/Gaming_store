@extends('Layouts.master')
@section('content')
    <!-- Hero Section Futuriste -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">ORDERS</h1>
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

    <!-- Orders Section - Style Cyberpunk -->
    <div class="cyber-orders-section">
        <div class="container">
            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($orders as $item)
                    <div class="cyber-order-accordion" data-aos="fade-up">
                        <div class="cyber-order-header" id="cyberHeading{{ $item->id }}">
                            <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                data-target="#cyberCollapse{{ $item->id }}" aria-expanded="true"
                                aria-controls="cyberCollapse{{ $item->id }}">
                                <span class="cyber-order-badge">ORDER #{{ $item->id }}</span>
                                <span class="cyber-order-date">{{ $item->created_at->format('M d, Y H:i') }}</span>
                                <i class="fas fa-chevron-down cyber-accordion-icon"></i>
                            </button>
                        </div>

                        <div id="cyberCollapse{{ $item->id }}" class="cyber-order-collapse show"
                            aria-labelledby="cyberHeading{{ $item->id }}" data-parent="#cyberAccordion">
                            <div class="cyber-order-body">
                                <div class="cyber-customer-details">
                                    <div class="cyber-detail-field">
                                        <div class="cyber-detail-icon">
                                            <i class="fas fa-user-astronaut"></i>
                                        </div>
                                        <input type="text" value="{{ $item->name }}" readonly>
                                    </div>

                                    <div class="cyber-detail-field">
                                        <div class="cyber-detail-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input type="text" value="{{ $item->email }}" readonly>
                                    </div>

                                    <div class="cyber-detail-field">
                                        <div class="cyber-detail-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <input type="text" value="{{ $item->address }}" readonly>
                                    </div>

                                    <div class="cyber-detail-field">
                                        <div class="cyber-detail-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <input type="text" value="{{ $item->phone }}" readonly>
                                    </div>

                                    <div class="cyber-detail-field full-width">
                                        <div class="cyber-detail-icon">
                                            <i class="fas fa-comment-alt"></i>
                                        </div>
                                        <textarea readonly>{{ $item->note ?: 'No special instructions' }}</textarea>
                                    </div>
                                </div>

                                <div class="cyber-products-table">
                                    <div class="cyber-table-header">
                                        <div class="cyber-table-row">
                                            <div class="cyber-table-col image-col">PRODUCT IMAGE</div>
                                            <div class="cyber-table-col">NAME</div>
                                            <div class="cyber-table-col">PRICE</div>
                                            <div class="cyber-table-col">QUANTITY</div>
                                            <div class="cyber-table-col">TOTAL</div>
                                        </div>
                                    </div>
                                    <div class="cyber-table-body">
                                        @foreach ($item->orderdetails as $detail)
                                        <div class="cyber-table-row">
                                            <div class="cyber-table-col image-col">
                                                <div class="cyber-product-img">
                                                    <img src="{{ asset($detail->product->imagepath) }}"
                                                         alt="{{ $detail->product->name }}"
                                                         class="cyber-hover-glow">
                                                </div>
                                            </div>
                                            <div class="cyber-table-col">
                                                <a href="/single-product/{{ $detail->product->id }}"
                                                   class="cyber-product-link">
                                                    {{ $detail->product->name }}
                                                </a>
                                            </div>
                                            <div class="cyber-table-col">
                                                ${{ number_format($detail->product->price, 2) }}
                                            </div>
                                            <div class="cyber-table-col">
                                                {{ $detail->quantity }}
                                            </div>
                                            <div class="cyber-table-col">
                                                ${{ number_format($detail->quantity * $detail->product->price, 2) }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="cyber-order-summary">
                                    <div class="cyber-summary-card">
                                        <h3 class="cyber-section-title">ORDER SUMMARY</h3>
                                        <div class="cyber-summary-row">
                                            <span>Subtotal</span>
                                            <span>${{ number_format($item->orderdetails->sum(function ($x) {
                                                return $x->product->price * $x->quantity;
                                            }), 2) }}</span>
                                        </div>
                                        <div class="cyber-summary-row">
                                            <span>Shipping</span>
                                            <span class="cyber-free">FREE</span>
                                        </div>
                                        <div class="cyber-summary-row">
                                            <span>Tax</span>
                                            <span>$0.00</span>
                                        </div>
                                        <div class="cyber-total-row">
                                            <span>TOTAL</span>
                                            <span class="cyber-total">${{ number_format($item->orderdetails->sum(function ($x) {
                                                return $x->product->price * $x->quantity;
                                            }), 2) }}</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
            height: 300px;
            background: linear-gradient(135deg, #0a1a3a 0%, #1a3063 50%, #0a1a3a 100%);
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

        /* Orders Section */
        .cyber-orders-section {
            padding: 80px 0;
            position: relative;
        }

        .cyber-accordion-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cyber-order-accordion {
            background: rgba(10, 10, 26, 0.8);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 240, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cyber-order-accordion:hover {
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.2);
        }

        .cyber-order-header {
            background: rgba(20, 20, 40, 0.6);
            padding: 0;
            border-bottom: 1px solid rgba(0, 240, 255, 0.1);
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
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Orbitron', sans-serif;
        }

        .cyber-accordion-btn:hover {
            background: rgba(0, 240, 255, 0.05);
        }

        .cyber-order-badge {
            background: linear-gradient(90deg, var(--cyber-organic), var(--cyber-primary));
            color: var(--cyber-dark);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-right: 15px;
            font-size: 0.9rem;
        }

        .cyber-order-date {
            color: var(--cyber-light);
            opacity: 0.8;
            font-size: 0.9rem;
            margin-right: auto;
        }

        .cyber-accordion-icon {
            transition: transform 0.3s ease;
            color: var(--cyber-primary);
        }

        .cyber-accordion-btn.collapsed .cyber-accordion-icon {
            transform: rotate(-90deg);
        }

        .cyber-order-collapse {
            transition: all 0.4s ease;
        }

        .cyber-order-body {
            padding: 30px;
        }

        .cyber-customer-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .cyber-detail-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cyber-detail-field.full-width {
            grid-column: 1 / -1;
        }

        .cyber-detail-icon {
            width: 40px;
            height: 40px;
            background: rgba(0, 240, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--cyber-organic);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .cyber-customer-details input,
        .cyber-customer-details textarea {
            flex: 1;
            padding: 12px 15px;
            background: rgba(20, 20, 40, 0.5);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 5px;
            color: var(--cyber-light);
            outline: none;
            font-family: 'Rajdhani', sans-serif;
            transition: all 0.3s ease;
        }

        .cyber-customer-details input:focus,
        .cyber-customer-details textarea:focus {
            border-color: var(--cyber-organic);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        .cyber-customer-details textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Products Table */
        .cyber-products-table {
            margin-bottom: 30px;
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .cyber-table-header {
            background: rgba(0, 240, 255, 0.1);
        }

        .cyber-table-row {
            display: flex;
            border-bottom: 1px solid rgba(0, 240, 255, 0.1);
        }

        .cyber-table-header .cyber-table-row {
            border-bottom: none;
        }

        .cyber-table-col {
            padding: 15px;
            flex: 1;
            display: flex;
            align-items: center;
            color: var(--cyber-light);
        }

        .cyber-table-header .cyber-table-col {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
            color: var(--cyber-primary);
        }

        .image-col {
            flex: 0 0 150px;
        }

        .cyber-product-img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            overflow: hidden;
            border: 1px solid rgba(0, 240, 255, 0.2);
        }

        .cyber-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .cyber-product-link {
            color: var(--cyber-light);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .cyber-product-link:hover {
            color: var(--cyber-organic);
            text-decoration: underline;
        }

        /* Order Summary */
        .cyber-order-summary {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .cyber-order-summary {
                grid-template-columns: 1fr;
            }
        }

        .cyber-summary-card {
            background: rgba(20, 20, 40, 0.5);
            border-radius: 8px;
            padding: 25px;
            border: 1px solid rgba(0, 240, 255, 0.1);
        }

        .cyber-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed rgba(0, 240, 255, 0.2);
            color: var(--cyber-light);
        }

        .cyber-summary-row:last-child {
            border-bottom: none;
        }

        .cyber-free {
            color: var(--cyber-organic);
        }

        .cyber-total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            margin-top: 10px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .cyber-total {
            color: var(--cyber-organic);
            font-size: 1.3rem;
        }

        /* Cyber Button */
        .cyber-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 25px;
            background: linear-gradient(90deg, var(--cyber-organic), var(--cyber-primary));
            color: var(--cyber-dark);
            border: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .cyber-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .cyber-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }

        .cyber-button:hover::before {
            left: 100%;
        }

        .cyber-button-text {
            position: relative;
            z-index: 1;
        }

        .cyber-button-icon {
            margin-left: 10px;
            position: relative;
            z-index: 1;
        }

        /* Floating Elements and other components (same as previous example) */
        .cyber-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .cyber-fab-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--cyber-organic), var(--cyber-primary));
            color: var(--cyber-dark);
            border: none;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(0, 255, 136, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cyber-fab-button:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.5);
        }

        .cyber-fab-tooltip {
            position: absolute;
            right: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(10, 10, 26, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            white-space: nowrap;
            border: 1px solid var(--cyber-organic);
        }

        .cyber-fab:hover .cyber-fab-tooltip {
            opacity: 1;
            right: 80px;
        }

        /* Modal */
        .cyber-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .cyber-modal-content {
            background: linear-gradient(145deg, #0a3a1a 0%, #1a6330 100%);
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            border: 1px solid var(--cyber-organic);
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
            overflow: hidden;
            position: relative;
        }

        .cyber-modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,255,136,0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
            opacity: 0.3;
        }

        .cyber-modal-header {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 255, 136, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cyber-modal-header h3 {
            margin: 0;
            color: var(--cyber-organic);
            font-size: 1.3rem;
        }

        .cyber-modal-close {
            background: none;
            border: none;
            color: var(--cyber-light);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .cyber-modal-close:hover {
            color: var(--cyber-organic);
        }

        .cyber-modal-body {
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .cyber-bot-message {
            display: flex;
            margin-bottom: 15px;
        }

        .cyber-bot-avatar {
            width: 40px;
            height: 40px;
            background: var(--cyber-organic);
            color: var(--cyber-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .cyber-bot-text {
            background: rgba(20, 20, 40, 0.7);
            padding: 15px;
            border-radius: 0 10px 10px 10px;
            color: var(--cyber-light);
            line-height: 1.5;
        }

        .cyber-modal-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(0, 255, 136, 0.3);
            display: flex;
        }

        .cyber-modal-footer input {
            flex: 1;
            padding: 12px 15px;
            background: rgba(20, 20, 40, 0.7);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 50px;
            color: var(--cyber-light);
            outline: none;
        }

        .cyber-modal-footer input:focus {
            border-color: var(--cyber-organic);
        }

        .cyber-send-button {
            background: var(--cyber-organic);
            color: var(--cyber-dark);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cyber-send-button:hover {
            transform: scale(1.1);
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
        @media (max-width: 768px) {
            .cyber-title {
                font-size: 2.5rem;
            }

            .cyber-table-row {
                flex-wrap: wrap;
            }

            .cyber-table-col {
                flex: 0 0 50%;
                padding: 10px;
            }

            .image-col {
                flex: 0 0 100%;
                justify-content: center;
            }

            .cyber-products-table .cyber-table-col:nth-child(2),
            .cyber-products-table .cyber-table-col:nth-child(3),
            .cyber-products-table .cyber-table-col:nth-child(4),
            .cyber-products-table .cyber-table-col:nth-child(5) {
                flex: 0 0 50%;
            }

            .cyber-table-header .cyber-table-col {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .cyber-customer-details {
                grid-template-columns: 1fr;
            }

            .cyber-products-table .cyber-table-col {
                flex: 0 0 100%;
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

            // AI Assistant functionality
            const assistantBtn = document.getElementById('cyberAssistant');
            const assistantModal = document.getElementById('assistantModal');
            const closeModal = document.querySelector('.cyber-modal-close');

            assistantBtn.addEventListener('click', function() {
                assistantModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });

            closeModal.addEventListener('click', function() {
                assistantModal.style.display = 'none';
                document.body.style.overflow = '';
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === assistantModal) {
                    assistantModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });

            // Add hover effect to product images
            const productImages = document.querySelectorAll('.cyber-product-img img');
            productImages.forEach(img => {
                img.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                    this.style.filter = 'brightness(1.2) saturate(1.2)';
                });

                img.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.filter = '';
                });
            });

            // Add pulse animation to order accordions periodically
            setInterval(function() {
                const orderAccordions = document.querySelectorAll('.cyber-order-accordion');
                orderAccordions.forEach((accordion, index) => {
                    setTimeout(() => {
                        accordion.style.boxShadow = '0 0 20px rgba(0, 255, 136, 0.3)';
                        setTimeout(() => {
                            accordion.style.boxShadow = '';
                        }, 1000);
                    }, index * 300);
                });
            }, 10000);


        });
    </script>
    @endpush
@endsection
