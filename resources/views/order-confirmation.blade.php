@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <p class="cyber-subtitle">Order Successfully Processed</p>
                        <h1 class="cyber-title">CONFIRMATION TERMINAL</h1>
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

    <div class="cyber-confirmation-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="cyber-confirmation-card" data-aos="zoom-in">
                        <div class="cyber-confirmation-header">
                            <div class="cyber-confirmation-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2 class="cyber-confirmation-title">ORDER CONFIRMED</h2>
                            <p class="cyber-confirmation-subtitle">Your order has been successfully processed</p>
                        </div>

                        <div class="cyber-confirmation-body">
                            <div class="cyber-order-details">
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Order ID:</span>
                                    <span class="cyber-detail-value">#{{ $orderId ?? 'N/A' }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Order Date:</span>
                                    <span class="cyber-detail-value">{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Customer Name:</span>
                                    <span class="cyber-detail-value">{{ $order->name ?? 'N/A' }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Delivery Address:</span>
                                    <span class="cyber-detail-value">{{ $order->address ?? 'N/A' }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Contact:</span>
                                    <span class="cyber-detail-value">{{ $order->phone ?? 'N/A' }} | {{ $order->email ?? 'N/A' }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Total Amount:</span>
                                    <span class="cyber-detail-value cyber-total-amount">${{ number_format($sommeOrder - $order->discount, 2) }}</span>
                                </div>

                                @if(!empty($order->note))
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Special Instructions:</span>
                                    <span class="cyber-detail-value">{{ $order->note }}</span>
                                </div>
                                @endif
                            </div>










                            <div class="cyber-next-steps">
                                <h3 class="cyber-steps-title">NEXT STEPS</h3>
                                <div class="cyber-steps-list">
                                    <div class="cyber-step-item">
                                        <div class="cyber-step-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="cyber-step-content">
                                            <h4>Confirmation Email</h4>
                                            <p>You will receive an order confirmation email shortly</p>
                                        </div>
                                    </div>
                                    <div class="cyber-step-item">
                                        <div class="cyber-step-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="cyber-step-content">
                                            <h4>Order Preparation</h4>
                                            <p>Your order is being prepared and will be shipped soon</p>
                                        </div>
                                    </div>
                                    <div class="cyber-step-item">
                                        <div class="cyber-step-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="cyber-step-content">
                                            <h4>Delivery Updates</h4>
                                            <p>You will receive SMS updates about your delivery status</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cyber-confirmation-footer">
                            <a href="/" class="cyber-return-btn">
                                <span class="cyber-btn-text">RETURN TO HOMEPAGE</span>
                                <span class="cyber-btn-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                            </a>

                            <a href="/previousorder" class="cyber-track-btn">
                                <span class="cyber-btn-text">VIEW PREVIOUS ORDERS</span>
                                <span class="cyber-btn-icon">
                                    <i class="fas fa-history"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/completeorder.css') }}">
        <style>
            /* Styles spécifiques à la page de confirmation */
            .cyber-confirmation-section {
                padding: 60px 0;
                position: relative;
                z-index: 10;
            }

            .cyber-confirmation-card {
                background: rgba(10, 15, 30, 0.8);
                border: 1px solid rgba(0, 247, 255, 0.3);
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0, 247, 255, 0.2),
                            0 0 40px rgba(0, 247, 255, 0.1);
                overflow: hidden;
                backdrop-filter: blur(10px);
            }

            .cyber-confirmation-header {
                padding: 30px;
                text-align: center;
                background: linear-gradient(90deg, rgba(0, 247, 255, 0.1), rgba(0, 150, 255, 0.1));
                border-bottom: 1px solid rgba(0, 247, 255, 0.3);
            }

            .cyber-confirmation-icon {
                font-size: 60px;
                color: #00f7ff;
                margin-bottom: 15px;
                text-shadow: 0 0 10px rgba(0, 247, 255, 0.7);
            }

            .cyber-confirmation-title {
                font-family: 'Orbitron', sans-serif;
                color: #00f7ff;
                font-size: 24px;
                margin-bottom: 10px;
                text-transform: uppercase;
                letter-spacing: 2px;
            }

            .cyber-confirmation-subtitle {
                color: #a0a0b0;
                font-family: 'Rajdhani', sans-serif;
                font-size: 16px;
            }

            .cyber-confirmation-body {
                padding: 30px;
            }

            .cyber-order-details {
                margin-bottom: 30px;
            }

            .cyber-detail-row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .cyber-detail-row:last-child {
                border-bottom: none;
            }

            .cyber-detail-label {
                color: #a0a0b0;
                font-family: 'Rajdhani', sans-serif;
                font-weight: 600;
            }

            .cyber-detail-value {
                color: #ffffff;
                font-family: 'Rajdhani', sans-serif;
            }

            .cyber-total-amount {
                color: #00f7ff;
                font-weight: bold;
                font-size: 18px;
            }

            .cyber-next-steps {
                margin-top: 30px;
            }

            .cyber-steps-title {
                font-family: 'Orbitron', sans-serif;
                color: #00f7ff;
                font-size: 20px;
                margin-bottom: 20px;
                text-align: center;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .cyber-steps-list {
                display: grid;
                grid-gap: 20px;
            }

            .cyber-step-item {
                display: flex;
                align-items: flex-start;
                background: rgba(0, 247, 255, 0.05);
                padding: 15px;
                border-radius: 6px;
                border-left: 3px solid #00f7ff;
            }

            .cyber-step-icon {
                font-size: 24px;
                color: #00f7ff;
                margin-right: 15px;
                min-width: 30px;
            }

            .cyber-step-content h4 {
                color: #ffffff;
                font-family: 'Rajdhani', sans-serif;
                font-size: 16px;
                margin-bottom: 5px;
                font-weight: 600;
            }

            .cyber-step-content p {
                color: #a0a0b0;
                font-family: 'Rajdhani', sans-serif;
                font-size: 14px;
                margin: 0;
            }

            .cyber-confirmation-footer {
                display: flex;
                justify-content: center;
                gap: 20px;
                padding: 30px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                flex-wrap: wrap;
            }

            .cyber-return-btn, .cyber-track-btn {
                display: inline-flex;
                align-items: center;
                padding: 12px 25px;
                background: rgba(0, 247, 255, 0.1);
                color: #00f7ff;
                font-family: 'Orbitron', sans-serif;
                text-decoration: none;
                text-transform: uppercase;
                letter-spacing: 1px;
                border: 1px solid rgba(0, 247, 255, 0.3);
                border-radius: 4px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .cyber-return-btn:hover, .cyber-track-btn:hover {
                background: rgba(0, 247, 255, 0.2);
                box-shadow: 0 0 15px rgba(0, 247, 255, 0.4);
                transform: translateY(-2px);
            }

            .cyber-btn-text {
                margin-right: 10px;
            }

            .cyber-btn-icon {
                font-size: 16px;
            }

            @media (max-width: 768px) {
                .cyber-confirmation-footer {
                    flex-direction: column;
                    align-items: center;
                }

                .cyber-return-btn, .cyber-track-btn {
                    width: 100%;
                    justify-content: center;
                }

                .cyber-detail-row {
                    flex-direction: column;
                }

                .cyber-detail-value {
                    margin-top: 5px;
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
                // Initialiser AOS
                AOS.init({
                    duration: 1000,
                    once: true
                });

                // Effet de saisie de texte pour le titre
                const title = document.querySelector('.cyber-confirmation-title');
                const originalText = title.textContent;
                title.textContent = '';

                let i = 0;
                const typeWriter = setInterval(function() {
                    if (i < originalText.length) {
                        title.textContent += originalText.charAt(i);
                        i++;
                    } else {
                        clearInterval(typeWriter);
                    }
                }, 50);
            });
        </script>
    @endpush
@endsection
