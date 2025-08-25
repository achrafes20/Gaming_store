@extends('Layouts.master')
@section('content')
<div class="cyber-hero-section">
    <div class="cyber-hero-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="cyber-hero-text">
                    <p class="cyber-subtitle">Next-Gen Technology</p>
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

<div class="cyber-checkout-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cyber-accordion-wrap">
                    <div class="cyber-accordion" id="cyberAccordion">
                        <form action="/StoreOrder" method="POST" id="store-order" name="store-order">
                            @csrf

                            <!-- Billing Address -->
                            <div class="cyber-accordion-card" data-aos="fade-up">
                                <div class="cyber-accordion-header" id="cyberHeadingBilling">
                                    <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                        data-target="#cyberCollapseBilling" aria-expanded="true"
                                        aria-controls="cyberCollapseBilling" style="cursor: default">
                                        <i class="fas fa-address-card cyber-accordion-icon"></i>
                                        <span class="cyber-accordion-title">BILLING ADDRESS</span>
                                    </button>
                                </div>
                                <div id="cyberCollapseBilling" class="cyber-accordion-collapse show"
                                    aria-labelledby="cyberHeadingBilling" data-parent="#cyberAccordion">
                                    <div class="cyber-accordion-body">
                                        <div class="cyber-billing-form">
                                            <!-- Name -->
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-user"></i></div>
                                                <input type="text" required placeholder="FULL NAME" id="name" name="name"
                                                    class="cyber-input" value="{{ old('name', Auth::user()->name ?? '') }}">
                                                <span class="cyber-error">@error('name') {{ $message }} @enderror</span>
                                                <div class="cyber-input-underline"></div>
                                            </div>

                                            <!-- Email -->
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-envelope"></i></div>
                                                <input type="email" required placeholder="{{ Auth::user()->email }}" id="email"
                                                    name="email" class="cyber-input" readonly value="{{ Auth::user()->email }}">
                                                <div class="cyber-input-underline"></div>
                                            </div>

                                            <!-- Region & City -->
                                            <div class="cyber-form-row">
                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon"><i class="fas fa-globe"></i></div>
                                                    <select id="region" name="region" class="cyber-input" required>
                                                        <option value="">-- SELECT REGION --</option>
                                                        @if(old('region'))
                                                            <option value="{{ old('region') }}" selected>{{ old('region') }}</option>
                                                        @endif
                                                    </select>
                                                    <span class="cyber-error">@error('region') {{ $message }} @enderror</span>
                                                    <div class="cyber-input-underline"></div>
                                                </div>

                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon"><i class="fas fa-city"></i></div>
                                                    <select id="city" name="city" class="cyber-input" required>
                                                        <option value="">-- SELECT CITY --</option>
                                                        @if(old('city'))
                                                            <option value="{{ old('city') }}" selected>{{ old('city') }}</option>
                                                        @endif
                                                    </select>
                                                    <span class="cyber-error">@error('city') {{ $message }} @enderror</span>
                                                    <div class="cyber-input-underline"></div>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-map-marker-alt"></i></div>
                                                <input type="text" required placeholder="DELIVERY ADDRESS" id="address"
                                                    name="address" class="cyber-input" value="{{ old('address') }}">
                                                <span class="cyber-error">@error('address') {{ $message }} @enderror</span>
                                                <div class="cyber-input-underline"></div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-phone"></i></div>
                                                <input type="tel" required placeholder="CONTACT NUMBER" id="phone"
                                                    name="phone" class="cyber-input" value="{{ old('phone', Auth::user()->phone ?? '') }}">
                                                <span class="cyber-error">@error('phone') {{ $message }} @enderror</span>
                                                <div class="cyber-input-underline"></div>
                                            </div>

                                            <!-- Note -->
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-comment-dots"></i></div>
                                                <textarea name="note" id="note" cols="30" rows="4" placeholder="SPECIAL INSTRUCTIONS (OPTIONAL)"
                                                    class="cyber-textarea">{{ old('note') }}</textarea>
                                                <div class="cyber-textarea-underline"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="cyber-accordion-card" data-aos="fade-up">
                                <div class="cyber-accordion-header" id="cyberHeadingPayment">
                                    <button class="cyber-accordion-btn collapsed" type="button" data-toggle="collapse"
                                        data-target="#cyberCollapsePayment" aria-expanded="false"
                                        aria-controls="cyberCollapsePayment" style="cursor: default">
                                        <i class="fas fa-credit-card cyber-accordion-icon"></i>
                                        <span class="cyber-accordion-title">PAYMENT METHOD</span>
                                    </button>
                                </div>
                                <div id="cyberCollapsePayment" class="cyber-accordion-collapse collapse"
                                    aria-labelledby="cyberHeadingPayment" data-parent="#cyberAccordion">
                                    <div class="cyber-accordion-body cyber-payment-section">
                                        <h3 class="cyber-payment-title">PAYMENT METHOD</h3>
                                        <div class="cyber-form-group">
                                            <label class="cyber-radio">
                                                <input type="radio" name="payment_method" value="cod" checked>
                                                <span>💵 Cash on Delivery</span>
                                            </label>
                                            <label class="cyber-radio">
                                                <input type="radio" name="payment_method" value="card">
                                                <span>💳 Credit/Debit Card</span>
                                            </label>
                                        </div>

                                        <!-- Card Form -->
                                        <div id="card-form" style="display:none; margin-top:20px;">
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-credit-card"></i></div>
                                                <input type="text" name="card_number" placeholder="CARD NUMBER" class="cyber-input" value="{{ old('card_number') }}">
                                                <div class="cyber-input-underline"></div>
                                                <span class="cyber-error">@error('card_number') {{ $message }} @enderror</span>
                                            </div>

                                            <div class="cyber-form-row">
                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon"><i class="fas fa-calendar-alt"></i></div>
                                                    <input type="text" name="expiry_date" placeholder="MM/YY" class="cyber-input" value="{{ old('expiry_date') }}">
                                                    <div class="cyber-input-underline"></div>
                                                    <span class="cyber-error">@error('expiry_date') {{ $message }} @enderror</span>
                                                </div>
                                                <div class="cyber-form-group">
                                                    <div class="cyber-input-icon"><i class="fas fa-lock"></i></div>
                                                    <input type="text" name="cvv" placeholder="CVV" class="cyber-input" value="{{ old('cvv') }}">
                                                    <div class="cyber-input-underline"></div>
                                                    <span class="cyber-error">@error('cvv') {{ $message }} @enderror</span>
                                                </div>
                                            </div>

                                            <div class="cyber-form-group">
                                                <div class="cyber-input-icon"><i class="fas fa-user"></i></div>
                                                <input type="text" name="card_name" placeholder="CARDHOLDER NAME" class="cyber-input" value="{{ old('card_name') }}">
                                                <div class="cyber-input-underline"></div>
                                                <span class="cyber-error">@error('card_name') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="cyber-accordion-card" data-aos="fade-up">
                                <div class="cyber-accordion-header" id="cyberHeadingThree">
                                    <button class="cyber-accordion-btn collapsed" type="button" data-toggle="collapse"
                                        data-target="#cyberCollapseThree" aria-expanded="false"
                                        aria-controls="cyberCollapseThree" style="cursor: default">
                                        <i class="fas fa-shopping-basket cyber-accordion-icon"></i>
                                        <span class="cyber-accordion-title">ORDER SUMMARY</span>
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
                                                                    <a href="/deletecartitem/{{ $item->id }}" class="cyber-delete-btn">
                                                                        <i class="fas fa-times-circle"></i>
                                                                    </a>
                                                                </td>
                                                                <td class="cyber-product-image">
                                                                    <div class="cyber-product-img-container">
                                                                        <img src="{{ asset($item->product->imagepath) }}"
                                                                            alt="{{ $item->product->name }}" class="cyber-product-img">
                                                                        <div class="cyber-img-overlay"></div>
                                                                    </div>
                                                                </td>
                                                                <td class="cyber-product-name">
                                                                    <a href="/single-product/{{ $item->product->id }}" class="cyber-product-link">
                                                                        {{ $item->product->name }}
                                                                    </a>
                                                                </td>
                                                                <td class="cyber-product-price">{{ number_format($item->product->price, 2) }}Dh</td>
                                                                <td class="cyber-product-quantity">
                                                                    <span class="cyber-quantity-badge">{{ $item->quantity }}</span>
                                                                </td>
                                                                <td class="cyber-product-total">{{ number_format($item->quantity * $item->product->price, 2) }}Dh</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="cyber-order-total-section">
                                                <div class="cyber-total-card">
                                                    <?php
                                                    $subtotal = $cartProducts->sum(fn($item) => $item->product->price * $item->quantity);
                                                    $discount = session('coupon.discount', 0);
                                                    $total = max(0, $subtotal - $discount);
                                                    ?>
                                                    <h3 class="cyber-total-title">ORDER TOTAL</h3>
                                                    <div class="cyber-total-row">
                                                        <span>SUBTOTAL ({{ count($cartProducts) }} items)</span>
                                                        <span>{{ number_format($subtotal, 2) }} Dh</span>
                                                    </div>
                                                    <div class="cyber-total-row">
                                                        <span>SHIPPING</span>
                                                        <span class="cyber-free">FREE</span>
                                                    </div>
                                                    @if ($discount > 0)
                                                        <div class="cyber-total-row">
                                                            <span>Discount ({{ session('coupon.code', '') }})</span>
                                                            <span style="color: red">- {{ number_format($discount, 2) }} Dh</span>
                                                        </div>
                                                    @endif
                                                    <div class="cyber-grand-total-row">
                                                        <span>GRAND TOTAL</span>
                                                        <span class="cyber-grand-total">{{ number_format($total, 2) }} Dh</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Order Button -->
                            <div class="col-lg-12 cyber-order-btn-container mt-3">
                                @if (auth()->user()->email_verified_at)
                                    <button type="submit" class="cyber-order-btn">
                                        <span class="cyber-btn-text">CONFIRM ORDER</span>
                                        <span class="cyber-btn-icon">
                                            <i class="fas fa-paper-plane"></i>
                                        </span>
                                        <span class="cyber-btn-pulse"></span>
                                    </button>
                                @else
                                    <span class="cyber-btn-text" style="background-color: red">
                                        ⚠️ Please verify your email address to confirm your order
                                    </span>
                                @endif
                            </div>

                        </form> <!-- Form closed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Elements -->
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
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="{{ asset('assets/js/completeorder.js') }}"></script>

<script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Initialize AOS animation library
                    AOS.init({
                        duration: 800,
                        easing: 'ease-in-out',
                        once: true
                    });

                    // Payment method toggle
                    const radios = document.querySelectorAll("input[name='payment_method']");
                    const cardForm = document.getElementById("card-form");

                    radios.forEach(radio => {
                        radio.addEventListener("change", function () {
                            if (this.value === "card") {
                                cardForm.style.display = "block";
                            } else {
                                cardForm.style.display = "none";
                            }
                        });
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

                    // Load regions and cities
                    fetch('assets/js/morocco.json')
                        .then(response => response.json())
                        .then(data => {
                            const regionSelect = document.getElementById("region");
                            const citySelect = document.getElementById("city");

                            // Clear existing options except the first one
                            while (regionSelect.options.length > 1) {
                                regionSelect.remove(1);
                            }

                            // Fill regions
                            data.regions.data.forEach(region => {
                                let option = document.createElement("option");
                                option.value = region.names.en;
                                option.textContent = region.names.en;
                                regionSelect.appendChild(option);
                            });

                            // When a region is selected
                            regionSelect.addEventListener("change", function () {
                                const selectedRegionName = this.value;

                                // Reset city list
                                citySelect.innerHTML = '<option value="">-- SELECT CITY --</option>';

                                // If a region is selected
                                if (selectedRegionName) {
                                    // Enable city select
                                    citySelect.disabled = false;

                                    // Filter cities for this region
                                    const cities = data.cities.data.filter(city => {
                                        const region = data.regions.data.find(r => r.id === city.region_id);
                                        return region && region.names.en === selectedRegionName;
                                    });

                                    // Add cities to select
                                    cities.forEach(city => {
                                        let option = document.createElement("option");
                                        option.value = city.names.en;
                                        option.textContent = city.names.en;
                                        citySelect.appendChild(option);
                                    });
                                } else {
                                    citySelect.disabled = true;
                                }
                            });

                            // Pre-select values if there are old values
                            @if(old('region'))
                                regionSelect.value = "{{ old('region') }}";
                                regionSelect.dispatchEvent(new Event('change'));

                                // Set timeout to allow the cities to load before selecting
                                setTimeout(() => {
                                    @if(old('city'))
                                        citySelect.value = "{{ old('city') }}";
                                    @endif
                                }, 100);
                            @endif
                        })
                        .catch(error => {
                            console.error('Error loading regions and cities:', error);
                        });
                });
            </script>
@endpush
@endsection
