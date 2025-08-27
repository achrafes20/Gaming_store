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
                                    <span class="cyber-detail-value">{{ $order->phone ?? 'N/A' }} |
                                        {{ $order->email ?? 'N/A' }}</span>
                                </div>
                                <div class="cyber-detail-row">
                                    <span class="cyber-detail-label">Total Amount:</span>
                                    <span
                                        class="cyber-detail-value cyber-total-amount">${{ number_format($sommeOrder - $order->discount, 2) }}</span>
                                </div>
                                @if (!empty($order->note))
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
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    duration: 1000,
                    once: true
                });

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
