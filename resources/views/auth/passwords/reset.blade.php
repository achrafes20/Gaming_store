@extends('Layouts.master')

@section('content')
<br>
<br>
    <!-- Cyberpunk Reset Password Section -->

    <div class="cyber-reset-section">
        <div class="cyber-reset-container">
            <!-- Floating Tech Elements -->
            <div class="cyber-reset-orb orb-1"></div>
            <div class="cyber-reset-orb orb-2"></div>
            <div class="cyber-circuit-line"></div>

            <!-- Reset Card -->
            <div class="cyber-reset-card" data-aos="zoom-in">
                <!-- Header with animated gradient -->
                <div class="cyber-reset-header">
                    <div class="cyber-reset-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2>RESET PASSWORD</h2>
                    <p>Redefine your password to secure your account</p>
                    <div class="cyber-pulse-animation">
                        <div class="pulse-circle"></div>
                        <div class="pulse-circle delay-1"></div>
                    </div>
                </div>

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.update') }}" class="cyber-reset-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div class="cyber-form-group">
                        <div class="cyber-input-container">
                            <input id="email" type="email" class="cyber-form-input @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            <label for="email" class="cyber-input-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <div class="cyber-input-highlight"></div>
                        </div>
                        @error('email')
                            <div class="cyber-error-message">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="cyber-form-group">
                        <div class="cyber-input-container">
                            <input id="password" type="password" class="cyber-form-input @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password">
                            <label for="password" class="cyber-input-label">
                                <i class="fas fa-lock"></i> New Password
                            </label>
                            <div class="cyber-input-highlight"></div>
                            <button type="button" class="cyber-password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="cyber-error-message">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="cyber-form-group">
                        <div class="cyber-input-container">
                            <input id="password-confirm" type="password" class="cyber-form-input"
                                   name="password_confirmation" required autocomplete="new-password">
                            <label for="password-confirm" class="cyber-input-label">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <div class="cyber-input-highlight"></div>
                            <button type="button" class="cyber-password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="cyber-reset-button">
                        <span class="cyber-button-text">RESET PASSWORD</span>
                        <span class="cyber-button-icon">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                        <span class="cyber-button-glow"></span>
                    </button>

                    <!-- Back to Login Link -->
                    <div class="cyber-reset-footer">
                        Back to <a href="{{ route('login') }}" class="cyber-login-link">login</a> page
                    </div>
                </form>
            </div>

            <!-- Security Badge -->
            <div class="cyber-security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>256-bit Encryption • Secure Reset</span>
            </div>
        </div>
    </div>
@push('styles')
<style>
        /* Cyberpunk/Futurist Theme Variables */
        :root {
            --cyber-primary: #00f0ff;
            --cyber-secondary: #ff00f0;
            --cyber-accent: #00ff88;
            --cyber-dark: #0a0a1a;
            --cyber-darker: #050510;
            --cyber-light: #e0e0ff;
            --cyber-card-bg: rgba(20, 20, 40, 0.8);
            --cyber-error: #ff003c;
            --cyber-weak: #ff3e3e;
            --cyber-medium: #ffcc00;
            --cyber-strong: #00ff88;
        }

        /* Base Styles */
        body {
    background-color: var(--cyber-darker);
    color: var(--cyber-light);
    font-family: 'Orbitron', 'Rajdhani', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

        /* Reset Section */
        .cyber-reset-section {
    min-height: 100vh; /* occupe toute la hauteur */
    display: flex;
    align-items: center; /* centre verticalement */
    justify-content: center; /* centre horizontalement */
    padding: 20px;
    position: relative;
    background: radial-gradient(circle at center, var(--cyber-dark), var(--cyber-darker));
    width: 100%;
}

        .cyber-reset-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            z-index: 1;
        }

        /* Floating Tech Elements */
        .cyber-reset-orb {
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

        .cyber-circuit-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,240,255,0.03)" stroke-width="1"/></svg>');
            opacity: 0.1;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(20px, 20px);
            }
        }

        /* Reset Card */
        .cyber-reset-card {
            background: var(--cyber-card-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 240, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .cyber-reset-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 240, 255, 0.05) 0%, rgba(255, 0, 240, 0.05) 100%);
            z-index: -1;
        }

        /* Reset Header */
        .cyber-reset-header {
            padding: 30px;
            text-align: center;
            background: linear-gradient(90deg, rgba(0, 240, 255, 0.1), rgba(255, 0, 240, 0.1));
            position: relative;
            overflow: hidden;
        }

        .cyber-reset-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: rgba(0, 240, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--cyber-primary);
            border: 2px solid var(--cyber-primary);
        }

        .cyber-reset-header h2 {
            margin: 10px 0 5px;
            font-size: 1.8rem;
            background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cyber-reset-header p {
            margin: 0;
            font-size: 0.9rem;
            color: rgba(224, 224, 255, 0.7);
        }

        .cyber-pulse-animation {
            position: relative;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15px;
        }

        .pulse-circle {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--cyber-primary);
            opacity: 0;
            animation: pulse 3s infinite;
        }

        .pulse-circle.delay-1 {
            animation-delay: 1s;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(6);
                opacity: 0;
            }
        }

        /* Reset Form */
        .cyber-reset-form {
            padding: 30px;
        }

        .cyber-form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .cyber-input-container {
            position: relative;
        }

        .cyber-form-input {
            width: 100%;
            padding: 15px 15px 15px 15px;
            background: rgba(10, 10, 26, 0.7);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 8px;
            color: var(--cyber-light);
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .cyber-form-input:focus {
            outline: none;
            border-color: var(--cyber-primary);
            box-shadow: 0 0 0 2px rgba(0, 240, 255, 0.2);
        }

        .cyber-form-input:focus + .cyber-input-label {
            transform: translateY(-25px) translateX(-15px) scale(0.8);
            color: var(--cyber-primary);
        }

        .cyber-form-input:not(:placeholder-shown) + .cyber-input-label {
            transform: translateY(-25px) translateX(-15px) scale(0.8);
        }

        .cyber-input-label {
            position: absolute;
            top: 2%;
            left: 25px;
            color: rgba(224, 224, 255, 0.7);
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cyber-input-label i {
            font-size: 1rem;
            position: absolute;
            left: -30px;
        }

        .cyber-input-highlight {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--cyber-primary);
            transition: all 0.3s ease;
        }

        .cyber-form-input:focus ~ .cyber-input-highlight {
            width: 100%;
        }

        .cyber-password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(224, 224, 255, 0.5);
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .cyber-password-toggle:hover {
            color: var(--cyber-primary);
        }

        /* Error Message */
        .cyber-error-message {
            margin-top: 8px;
            font-size: 0.8rem;
            color: var(--cyber-error);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cyber-error-message i {
            font-size: 0.9rem;
        }

        /* Reset Button */
        .cyber-reset-button {
            position: relative;
            width: 100%;
            padding: 15px;
            background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
            color: var(--cyber-dark);
            border: none;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            z-index: 1;
            font-family: 'Orbitron', sans-serif;
        }

        .cyber-reset-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
        }

        .cyber-reset-button:active {
            transform: translateY(0);
        }

        .cyber-button-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .cyber-reset-button:hover .cyber-button-glow {
            left: 100%;
        }

        /* Back to Login Link */
        .cyber-reset-footer {
            text-align: center;
            font-size: 0.9rem;
            color: rgba(224, 224, 255, 0.7);
            margin-top: 20px;
        }

        .cyber-login-link {
            color: var(--cyber-primary);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .cyber-login-link:hover {
            text-decoration: underline;
        }

        /* Security Badge */
        .cyber-security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            font-size: 0.8rem;
            color: rgba(224, 224, 255, 0.5);
        }

        .cyber-security-badge i {
            color: var(--cyber-accent);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .cyber-reset-header {
                padding: 20px;
            }

            .cyber-reset-form {
                padding: 20px;
            }

            .cyber-reset-header h2 {
                font-size: 1.5rem;
            }

            .cyber-form-input {
                padding: 12px 12px 12px 12px;
            }

            .cyber-reset-button {
                padding: 12px;
            }

            .cyber-input-label i {
                left: -25px;
            }
        }

        @media (max-width: 480px) {
            .cyber-reset-card {
                border-radius: 10px;
            }

            .cyber-input-label {
                font-size: 0.9rem;
            }
        }
    </style>
    @endpush

@push('scripts')
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Rajdhani:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation library
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Toggle password visibility
            const passwordToggles = document.querySelectorAll('.cyber-password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Add floating animation to orbs
            setInterval(function() {
                const orbs = document.querySelectorAll('.cyber-reset-orb');
                orbs.forEach(orb => {
                    const randomX = Math.random() * 20 - 10;
                    const randomY = Math.random() * 20 - 10;
                    orb.style.transform = `translate(${randomX}px, ${randomY}px)`;
                });
            }, 3000);

            // Add pulse effect to reset button periodically
            setInterval(function() {
                const resetBtn = document.querySelector('.cyber-reset-button');
                if (resetBtn) {
                    resetBtn.style.boxShadow = '0 0 15px rgba(0, 240, 255, 0.5)';
                    setTimeout(() => {
                        resetBtn.style.boxShadow = '';
                    }, 1000);
                }
            }, 5000);
        });
    </script>
@endpush
@endsection

