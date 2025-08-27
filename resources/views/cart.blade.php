@extends('Layouts.master')
@section('content')
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
                                        <form action="{{ url('/deletecartitem/' . $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="cyber-delete-btn">

                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
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
                                    <div class="cyber-price">{{ number_format($item->product->price, 2) }} Dh</div>
                                </div>
                                <div class="cyber-cart-item-quantity">
                                    <div class="cyber-quantity-selector">
                                        @if ($item->quantity > 1)
                                            <form action="{{ url('/cart_decrement/' . $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit" class="cyber-qty-btn"
                                                    style="border:none;background:none;cursor:pointer;">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="/deletecartitem/{{ $item->id }}" class="cyber-qty-btn"
                                                style="text-decoration: none;">
                                                <i class="fas fa-chevron-down"></i>
                                            </a>
                                        @endif
                                        <span class="cyber-qty-value">{{ $item->quantity }}</span>
                                        @if ($item->quantity < $item->product->quantity)
                                            <form action="{{ url('/cart_increment/' . $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit" class="cyber-qty-btn"
                                                    style="border:none;background:none;cursor:pointer;">
                                                    <i class="fas fa-chevron-up"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="cyber-cart-item-total">
                                    <div class="cyber-total-price">
                                        {{ number_format($item->quantity * $item->product->price, 2) }} Dh</div>
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
                                <a href="/categories" class="cyber-explore-btn">
                                    <span>Explore Products</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="col-lg-4">
                        <div class="cyber-cart-summary">
                            <h3 class="cyber-summary-title"><i class="fas fa-receipt"></i> ORDER SUMMARY</h3>
                            <div class="cyber-summary-details">
                                <?php
                                $subtotal = $cartProducts->sum(function ($item) {
                                    return $item->product->price * $item->quantity;
                                });
                                $discount = session('coupon.discount', 0);
                                $total = $subtotal - $discount;

                                ?>
                                <div class="cyber-summary-row">
                                    <span>Subtotal </span>
                                    <span>{{ number_format($subtotal, 2) }} Dh</span>
                                </div>
                                <div class="cyber-summary-row">
                                    <span>Shipping</span>
                                    <span class="cyber-free">FREE</span>
                                </div>
                                @if ($discount > 0)
                                    <div class="cyber-summary-row">
                                        <span>Discount ({{ session('coupon.code') }})</span>
                                        <span style="color: red">- {{ number_format($discount, 2) }} Dh</span>
                                    </div>
                                @endif
                                <div class="cyber-total-row">
                                    <span>Total</span>
                                    <span class="cyber-grand-total">{{ number_format($total, 2) }} Dh</span>
                                </div>
                            </div>
                            @if (count($cartProducts) > 0)
                                <div class="cyber-promo-section">
                                    <div class="cyber-promo-input">
                                        @if (!session('coupon'))
                                            <form method="POST" action="{{ route('coupon.apply') }}">
                                                @csrf
                                                <input type="text" name="code" placeholder="Enter your coupon code"
                                                    class="cyber-input">
                                                <button type="submit" class="cyber-promo-btn">Apply</button>
                                            </form>
                                        @else
                                            <div class="cyber-coupon-active">
                                                <span class="cyber-coupon-code" style="margin-left: 20px">
                                                    <strong>{{ session('coupon.code') }}</strong>
                                                </span>
                                                <form method="POST" action="{{ route('coupon.remove') }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="cyber-promo-btn remove-btn"
                                                        style="color: red;margin-left: 20px">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    @if (session('coupon'))
                                        <br>
                                        <p style="color: rgb(92, 253, 6)">
                                            Coupon applied:
                                            @if (session('coupon.type') === 'fixed')
                                                -{{ number_format(session('coupon.value'), 2) }} Dh
                                            @else
                                                -{{ session('coupon.value') }}%
                                            @endif
                                        </p>
                                    @endif
                                    <br>
                                    @error('code')
                                        <p style="color: red">{{ $message }}</p>
                                    @enderror

                                    @error('code1')
                                        <p style="color: red">{{ $message }}</p>
                                    @enderror

                                    @error('code2')
                                        <p style="color: red">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <div class="cyber-checkout-btns">
                                @if (count($cartProducts) > 0)
                                    <a href="/Completeorder" class="cyber-checkout-btn">
                                        <span>PROCEED TO CHECKOUT</span>
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @else
                                    <a href="/categories" class="cyber-checkout-btn">
                                        <span>EXPLORE PRODUCTS</span>
                                        <i class="fas fa-shopping-basket"></i>
                                    </a>
                                @endif

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
    </div>
    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>
    <div class="cyber-mini-cart">
        <div class="cyber-mini-cart-icon">
            <i class="fas fa-shopping-basket"></i>
            <span class="cyber-mini-cart-count">{{ count($cartProducts) }}</span>
        </div>
        <div class="cyber-mini-cart-total">
            ${{ number_format($cartProducts->sum(function ($item) {return $item->product->price * $item->quantity;}),2) }}
        </div>
        @if (count($cartProducts) == 0)
            <a href="/categories" class="cyber-explore-btn">
                <span>Explore Products</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        @else
            <a href="/Completeorder" class="cyber-mini-checkout-btn">CHECKOUT</a>
        @endif
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="{{ asset('assets/js/cart.js') }}"></script>
    @endpush
@endsection
