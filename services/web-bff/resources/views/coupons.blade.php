@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">COUPONS</span></h1>
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
    <div class="cyber-coupons-section">
        <div class="container">
            <div class="cyber-action-btns">
                <a href="/addcoupon" class="cyber-btn add-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>ADD NEW COUPON</span>
                    <div class="cyber-btn-hover"></div>
                </a>
            </div>
            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($coupons as $coupon)
                        <div class="cyber-coupon-accordion" data-aos="fade-up">
                            <div class="cyber-coupon-header" id="cyberHeading{{ $coupon->id }}">
                                <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                    data-target="#cyberCollapse{{ $coupon->id }}" aria-expanded="true"
                                    aria-controls="cyberCollapse{{ $coupon->id }}">
                                    <span class="cyber-coupon-code">{{ $coupon->code }}</span>
                                    <span
                                        class="cyber-coupon-discount">{{ $coupon->discount }}{{ $coupon->type === 'percent' ? '%' : '$' }}</span>
                                    <span class="cyber-coupon-date">{{ $coupon->created_at->format('M d, Y') }}</span>
                                    <i class="fas fa-chevron-down cyber-accordion-icon"></i>
                                </button>
                            </div>
                            <div id="cyberCollapse{{ $coupon->id }}" class="cyber-coupon-collapse show"
                                aria-labelledby="cyberHeading{{ $coupon->id }}" data-parent="#cyberAccordion">
                                <div class="cyber-coupon-body">
                                    <form method="POST" action="/storecoupon" id="cyber-coupon-form-{{ $coupon->id }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $coupon->id }}">
                                        <div class="cyber-form-row">
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-container">
                                                    <input type="text" placeholder="COUPON CODE" name="code"
                                                        value="{{ $coupon->code }}" class="cyber-input" required>
                                                    <div class="cyber-input-border"></div>
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-tag"></i>
                                                    </div>
                                                </div>
                                                <span class="cyber-error">
                                                    @error('code')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-container">
                                                    <input type="number" placeholder="DISCOUNT VALUE" name="discount"
                                                        value="{{ $coupon->discount }}" class="cyber-input" required
                                                        min="1">
                                                    <div class="cyber-input-border"></div>
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-percentage"></i>
                                                    </div>
                                                </div>
                                                <span class="cyber-error">
                                                    @error('discount')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="cyber-form-row">
                                            <div class="cyber-form-group">
                                                <div class="cyber-select-container">
                                                    <select name="type" class="cyber-select" required>
                                                        <option value="percent"
                                                            {{ $coupon->type === 'percent' ? 'selected' : '' }}>Percentage
                                                        </option>
                                                        <option value="fixed"
                                                            {{ $coupon->type === 'fixed' ? 'selected' : '' }}>Fixed Amount
                                                        </option>
                                                    </select>
                                                    <div class="cyber-select-border"></div>
                                                    <div class="cyber-select-icon">
                                                        <i class="fas fa-list"></i>
                                                    </div>
                                                </div>
                                                <span class="cyber-error">
                                                    @error('type')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-container">
                                                    <input type="number" placeholder="USAGE LIMIT" name="usage_limit"
                                                        value="{{ $coupon->usage_limit }}" class="cyber-input"
                                                        min="1">
                                                    <div class="cyber-input-border"></div>
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-hashtag"></i>
                                                    </div>
                                                </div>
                                                <span class="cyber-error">
                                                    @error('usage_limit')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="cyber-form-group">
                                            <div class="cyber-input-container">
                                                <input type="date" placeholder="EXPIRES AT" name="expires_at"
                                                    value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}"
                                                    class="cyber-input" required>
                                                <div class="cyber-input-border"></div>
                                                <div class="cyber-input-icon">
                                                    <i class="fas fa-calendar-times"></i>
                                                </div>
                                            </div>
                                            <span class="cyber-error">
                                                @error('expires_at')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="cyber-form-actions">
                                            <button type="submit" class="cyber-submit-btn update-btn">
                                                <span class="cyber-btn-text">UPDATE COUPON</span>
                                                <span class="cyber-btn-icon">
                                                    <i class="fas fa-save"></i>
                                                </span>
                                                <span class="cyber-btn-pulse"></span>
                                            </button>
                                    </form>
                                    <form action="{{ url('/RemoveCoupon/' . $coupon->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="cyber-btn danger-btn">

                                            <i class="fas fa-trash"></i>
                                            <span>DELETE</span>
                                            <div class="cyber-btn-hover"></div>
                                        </button>
                                    </form>
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
        <link rel="stylesheet" href="{{ asset('assets/css/coupons.css') }}">
    @endpush
    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script src="{{ asset('assets/js/coupons.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif
    @endpush
@endsection
