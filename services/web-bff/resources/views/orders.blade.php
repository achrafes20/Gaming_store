@extends('Layouts.master')
@section('content')
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
                                            @foreach ($item->order_details as $detail)
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
                                                        {{ number_format($detail->product->price, 2) }} Dh
                                                    </div>
                                                    <div class="cyber-table-col">
                                                        {{ $detail->quantity }}
                                                    </div>
                                                    <div class="cyber-table-col">
                                                        {{ number_format($detail->quantity * $detail->product->price, 2) }}
                                                        Dh
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
                                                <span>{{ number_format(
                                                    $item->order_details->sum(function ($x) {
                                                        return $x->product->price * $x->quantity;
                                                    }),
                                                    2,
                                                ) }}
                                                    Dh</span>
                                            </div>
                                            <div class="cyber-summary-row">
                                                <span>Shipping</span>
                                                <span class="cyber-free">FREE</span>
                                            </div>
                                            <div class="cyber-summary-row">
                                                <span>Tax</span>
                                                <span>0.00 Dh</span>
                                            </div>
                                            @if ($item->discount > 0)
                                                <div class="cyber-summary-row">
                                                    <span>Discount </span>
                                                    <span style="color: red">-
                                                        {{ number_format($item->discount, 2) }} Dh</span>
                                                </div>
                                            @endif
                                            <div class="cyber-total-row">
                                                <span>TOTAL</span>
                                                @if ($item->discount > 0)
                                                    <span
                                                        class="cyber-total">{{ number_format($item->total - $item->discount, 2) }}
                                                        Dh</span>
                                                @else
                                                    <span
                                                        class="cyber-total">{{ number_format(
                                                            $item->order_details->sum(function ($x) {
                                                                return $x->product->price * $x->quantity;
                                                            }),
                                                            2,
                                                        ) }}
                                                        Dh</span>
                                                @endif
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
    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/orders.css') }}">
    @endpush
    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="{{ asset('assets/js/orders.js') }}"></script>
    @endpush
@endsection
