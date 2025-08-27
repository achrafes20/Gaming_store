@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">ADD <span class="cyber-accent">COUPON</span></h1>
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
    <div class="cyber-form-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="cyber-form-container">
                        <div class="cyber-form-header">
                            <h3 class="cyber-form-title">COUPON <span class="cyber-accent">DATABASE</span> ENTRY</h3>
                            <p class="cyber-form-subtitle">Fill in the coupon details below</p>
                        </div>
                        <div class="cyber-form-body">
                            <form method="POST" action="/storecoupon" id="cyber-coupon-form">
                                @csrf
                                <div class="cyber-form-group">
                                    <div class="cyber-input-container">
                                        <input type="text" placeholder="COUPON CODE" name="code" id="code"
                                            value="{{ old('code') }}" class="cyber-input" required>
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
                                <div class="cyber-form-row">
                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="number" placeholder="DISCOUNT VALUE" name="discount"
                                                id="discount" value="{{ old('discount') }}" class="cyber-input" required
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
                                    <div class="cyber-form-group">
                                        <div class="cyber-select-container">
                                            <select name="type" id="type" class="cyber-select" required>
                                                <option value="">SELECT TYPE</option>
                                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>
                                                    Percentage</option>
                                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed
                                                    Amount</option>
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
                                </div>
                                <div class="cyber-form-row">
                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="number" placeholder="USAGE LIMIT" name="usage_limit"
                                                id="usage_limit" value="{{ old('usage_limit') }}" class="cyber-input"
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
                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="date" placeholder="EXPIRES AT" name="expires_at" id="expires_at"
                                                value="{{ old('expires_at') }}" class="cyber-input" required>
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
                                </div>
                                <div class="cyber-form-submit">
                                    <button type="submit" class="cyber-submit-btn">
                                        <span class="cyber-btn-text">CREATE COUPON</span>
                                        <span class="cyber-btn-icon">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                        <span class="cyber-btn-pulse"></span>
                                    </button>
                                </div>
                            </form>
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
        <link rel="stylesheet" href="{{ asset('assets/css/addcoupon.css') }}">
    @endpush
    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">

        <script src="{{ asset('assets/js/addcoupon.js') }}"></script>
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
