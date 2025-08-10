@extends('Layouts.master')
@section('content')
    <!-- Cyberpunk Hero Section -->
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

    <!-- Cyberpunk Form Section -->
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
                                                id="discount" value="{{ old('discount') }}" class="cyber-input" required min="1">
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
                                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage</option>
                                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
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
                                                    id="usage_limit" value="{{ old('usage_limit') }}" class="cyber-input" min="1">
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
                                            <input type="date" placeholder="EXPIRES AT" name="expires_at"
                                                id="expires_at" value="{{ old('expires_at') }}" class="cyber-input" required>
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
            --cyber-bg: rgba(10, 10, 26, 0.8);
            --cyber-border: rgba(0, 240, 255, 0.2);
            --cyber-form-bg: rgba(20, 20, 40, 0.6);
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

        .cyber-title {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--cyber-light), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cyber-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
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
            background-color: var(--cyber-accent);
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

        /* Form Section */
        .cyber-form-section {
            padding: 80px 0;
            position: relative;
        }

        .cyber-form-container {
            background: var(--cyber-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .cyber-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,240,255,0.05)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
            opacity: 0.5;
        }

        .cyber-form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .cyber-form-title {
            font-size: 1.8rem;
            color: var(--cyber-light);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cyber-form-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .cyber-form-subtitle {
            color: var(--cyber-light);
            opacity: 0.8;
            font-size: 1rem;
        }

        /* Form Elements */
        .cyber-form-group {
            margin-bottom: 30px;
            position: relative;
        }

        .cyber-form-row {
            display: flex;
            gap: 20px;
        }

        .cyber-form-row .cyber-form-group {
            flex: 1;
        }

        .cyber-input-container,
        .cyber-textarea-container,
        .cyber-select-container {
            position: relative;
        }

        .cyber-input,
        .cyber-textarea,
        .cyber-select {
            width: 100%;
            padding: 15px 15px 15px 50px;
            background: var(--cyber-form-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 5px;
            color: var(--cyber-light);
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .cyber-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .cyber-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .cyber-input-icon,
        .cyber-textarea-icon,
        .cyber-select-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--cyber-primary);
            z-index: 2;
        }

        .cyber-input-border,
        .cyber-textarea-border,
        .cyber-select-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--cyber-accent);
            transition: width 0.4s ease;
        }

        .cyber-input:focus,
        .cyber-textarea:focus,
        .cyber-select:focus {
            outline: none;
            border-color: var(--cyber-primary);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
        }

        .cyber-input:focus ~ .cyber-input-border,
        .cyber-textarea:focus ~ .cyber-textarea-border,
        .cyber-select:focus ~ .cyber-select-border {
            width: 100%;
        }

        /* Error Messages */
        .cyber-error {
            display: block;
            margin-top: 5px;
            color: var(--cyber-danger);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Submit Button */
        .cyber-form-submit {
            text-align: center;
            margin-top: 40px;
        }

        .cyber-submit-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 40px;
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
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

        .cyber-submit-btn:hover {
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
            background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
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
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(20px, 20px);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .cyber-title {
                font-size: 2.5rem;
            }

            .cyber-form-container {
                padding: 30px;
            }

            .cyber-form-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .cyber-form-row {
                flex-direction: column;
                gap: 0;
            }

            .cyber-hero-section {
                height: 250px;
            }

            .cyber-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .cyber-form-container {
                padding: 20px 15px;
            }

            .cyber-title {
                font-size: 1.8rem;
            }

            .cyber-input,
            .cyber-textarea,
            .cyber-select {
                padding: 12px 12px 12px 40px;
            }

            .cyber-input-icon,
            .cyber-textarea-icon,
            .cyber-select-icon {
                left: 10px;
                top: 12px;
            }

            .cyber-submit-btn {
                padding: 12px 30px;
                font-size: 0.9rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add pulse animation to form inputs periodically
            setInterval(function() {
                const inputs = document.querySelectorAll('.cyber-input, .cyber-textarea, .cyber-select');
                inputs.forEach((input, index) => {
                    setTimeout(() => {
                        input.style.boxShadow = '0 0 10px rgba(0, 240, 255, 0.3)';
                        setTimeout(() => {
                            input.style.boxShadow = '';
                        }, 1000);
                    }, index * 300);
                });
            }, 8000);

            // Set minimum date for expiration date
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const minDate = tomorrow.toISOString().split('T')[0];
            document.getElementById('expires_at').min = minDate;
        });
    </script>
    @endpush
@endsection
