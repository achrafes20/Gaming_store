@extends('layouts.master')

@section('content')
<!-- Cyberpunk Login Section -->
<div class="cyber-auth-section">
    <div class="cyber-auth-container">
        <!-- Floating Tech Elements -->
        <div class="cyber-auth-orb orb-1"></div>
        <div class="cyber-auth-orb orb-2"></div>
        <div class="cyber-circuit-line"></div>

        <!-- Login Card -->
        <div class="cyber-auth-card" data-aos="zoom-in">
            <!-- Header with animated gradient -->
            <div class="cyber-auth-header">
                <div class="cyber-auth-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>SECURE ACCESS</h2>
                <p>Enter your credentials to access your tech universe</p>
                <div class="cyber-pulse-animation">
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle delay-1"></div>
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="cyber-auth-form">
                @csrf

                <!-- Email Field -->
                <div class="cyber-form-group">
                    <div class="cyber-input-container">
                        <input id="email" type="email" class="cyber-form-input @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                               name="password" required autocomplete="current-password">
                        <label for="password" class="cyber-input-label">
                            <i class="fas fa-lock"></i> Password
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

                <!-- Remember Me & Forgot Password -->
                <div class="cyber-form-options">
                    <label class="cyber-checkbox-container">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="cyber-checkbox-checkmark"></span>
                        <span class="cyber-checkbox-label">Remember Me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="cyber-forgot-password" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="cyber-auth-button">
                    <span class="cyber-button-text">LOGIN</span>
                    <span class="cyber-button-icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <span class="cyber-button-glow"></span>
                </button>

                <!-- Register Link -->
                <div class="cyber-auth-footer">
                    Don't have an account? <a href="{{ route('register') }}" class="cyber-register-link">Join the Tech Revolution</a>
                </div>
            </form>
        </div>

        <!-- Security Badge -->
        <div class="cyber-security-badge">
            <i class="fas fa-shield-alt"></i>
            <span>256-bit Encryption • Secure Login</span>
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
    }

    /* Base Styles */
    body {
        background-color: var(--cyber-darker);
        color: var(--cyber-light);
        font-family: 'Orbitron', 'Rajdhani', sans-serif;
        overflow-x: hidden;
    }

    /* Auth Section */
    .cyber-auth-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        background: radial-gradient(circle at center, var(--cyber-dark), var(--cyber-darker));
    }

    .cyber-auth-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        z-index: 1;
    }

    /* Auth Card */
    .cyber-auth-card {
        background: var(--cyber-card-bg);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(0, 240, 255, 0.2);
        backdrop-filter: blur(10px);
        position: relative;
    }

    .cyber-auth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 240, 255, 0.05) 0%, rgba(255, 0, 240, 0.05) 100%);
        z-index: -1;
    }

    /* Auth Header */
    .cyber-auth-header {
        padding: 30px;
        text-align: center;
        background: linear-gradient(90deg, rgba(0, 240, 255, 0.1), rgba(255, 0, 240, 0.1));
        position: relative;
        overflow: hidden;
    }

    .cyber-auth-icon {
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

    .cyber-auth-header h2 {
        margin: 10px 0 5px;
        font-size: 1.8rem;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cyber-auth-header p {
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

    /* Auth Form */
    .cyber-auth-form {
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
        padding: 15px 15px 15px 45px;
        background: rgba(10, 10, 26, 0.7);
        border: 1px solid rgba(0, 240, 255, 0.2);
        border-radius: 8px;
        color: var(--cyber-light);
        font-size: 1rem;
        transition: all 0.3s ease;
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
        top: 0px;
        left: 0px;
        color: rgba(224, 224, 255, 0.7);
        pointer-events: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cyber-input-label i {
        font-size: 1rem;
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

    /* Form Options */
    .cyber-form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .cyber-checkbox-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        user-select: none;
    }

    .cyber-checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .cyber-checkbox-checkmark {
        height: 18px;
        width: 18px;
        background: rgba(10, 10, 26, 0.7);
        border: 1px solid rgba(0, 240, 255, 0.3);
        border-radius: 4px;
        margin-right: 8px;
        transition: all 0.3s ease;
    }

    .cyber-checkbox-container:hover input ~ .cyber-checkbox-checkmark {
        border-color: var(--cyber-primary);
    }

    .cyber-checkbox-container input:checked ~ .cyber-checkbox-checkmark {
        background: var(--cyber-primary);
        border-color: var(--cyber-primary);
    }

    .cyber-checkbox-checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .cyber-checkbox-container input:checked ~ .cyber-checkbox-checkmark:after {
        display: block;
    }

    .cyber-checkbox-container .cyber-checkbox-checkmark:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid var(--cyber-dark);
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .cyber-checkbox-label {
        font-size: 0.9rem;
        color: rgba(224, 224, 255, 0.7);
    }

    .cyber-forgot-password {
        font-size: 0.9rem;
        color: rgba(224, 224, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cyber-forgot-password:hover {
        color: var(--cyber-primary);
    }

    /* Auth Button */
    .cyber-auth-button {
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
    }

    .cyber-auth-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
    }

    .cyber-auth-button:active {
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

    .cyber-auth-button:hover .cyber-button-glow {
        left: 100%;
    }

    /* Social Login */
    .cyber-social-login {
        margin: 30px 0;
        text-align: center;
    }

    .cyber-social-login p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 15px;
        position: relative;
    }

    .cyber-social-login p::before,
    .cyber-social-login p::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: rgba(0, 240, 255, 0.3);
    }

    .cyber-social-login p::before {
        left: 0;
    }

    .cyber-social-login p::after {
        right: 0;
    }

    .cyber-social-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .cyber-social-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(10, 10, 26, 0.7);
        color: white;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cyber-social-btn:hover {
        transform: translateY(-3px);
    }

    .cyber-social-btn.google {
        border: 1px solid rgba(219, 68, 55, 0.3);
    }

    .cyber-social-btn.google:hover {
        background: #DB4437;
        box-shadow: 0 5px 15px rgba(219, 68, 55, 0.3);
    }

    .cyber-social-btn.facebook {
        border: 1px solid rgba(59, 89, 152, 0.3);
    }

    .cyber-social-btn.facebook:hover {
        background: #3B5998;
        box-shadow: 0 5px 15px rgba(59, 89, 152, 0.3);
    }

    .cyber-social-btn.twitter {
        border: 1px solid rgba(29, 161, 242, 0.3);
    }

    .cyber-social-btn.twitter:hover {
        background: #1DA1F2;
        box-shadow: 0 5px 15px rgba(29, 161, 242, 0.3);
    }

    /* Auth Footer */
    .cyber-auth-footer {
        text-align: center;
        font-size: 0.9rem;
        color: rgba(224, 224, 255, 0.7);
        margin-top: 20px;
    }

    .cyber-register-link {
        color: var(--cyber-primary);
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .cyber-register-link:hover {
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

    /* Floating Elements */
    .cyber-auth-orb {
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
        .cyber-auth-header {
            padding: 20px;
        }

        .cyber-auth-form {
            padding: 20px;
        }

        .cyber-auth-header h2 {
            font-size: 1.5rem;
        }

        .cyber-form-input {
            padding: 12px 12px 12px 40px;
        }

        .cyber-auth-button {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .cyber-auth-card {
            border-radius: 10px;
        }

        .cyber-form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .cyber-forgot-password {
            align-self: flex-end;
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

        // Toggle password visibility
        const passwordToggle = document.querySelector('.cyber-password-toggle');
        if (passwordToggle) {
            passwordToggle.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        // Add floating animation to orbs
        setInterval(function() {
            const orbs = document.querySelectorAll('.cyber-auth-orb');
            orbs.forEach(orb => {
                const randomX = Math.random() * 20 - 10;
                const randomY = Math.random() * 20 - 10;
                orb.style.transform = `translate(${randomX}px, ${randomY}px)`;
            });
        }, 3000);

        // Add pulse effect to login button periodically
        setInterval(function() {
            const loginBtn = document.querySelector('.cyber-auth-button');
            if (loginBtn) {
                loginBtn.style.boxShadow = '0 0 15px rgba(0, 240, 255, 0.5)';
                setTimeout(() => {
                    loginBtn.style.boxShadow = '';
                }, 1000);
            }
        }, 5000);

        // Social login buttons animation
        const socialBtns = document.querySelectorAll('.cyber-social-btn');
        socialBtns.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            btn.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    });
</script>
@endpush
@endsection
