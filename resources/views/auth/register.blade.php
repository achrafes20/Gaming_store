@extends('layouts.master')

@section('content')
<!-- Cyberpunk Registration Section -->
<div class="cyber-register-section">
    <div class="cyber-register-container">
        <!-- Floating Tech Elements -->
        <div class="cyber-register-orb orb-1"></div>
        <div class="cyber-register-orb orb-2"></div>
        <div class="cyber-circuit-line"></div>

        <!-- Registration Card -->
        <div class="cyber-register-card" data-aos="zoom-in">
            <!-- Header with animated gradient -->
            <div class="cyber-register-header">
                <div class="cyber-register-icon">
                    <i class="fas fa-user-astronaut"></i>
                </div>
                <h2>JOIN THE TECH REVOLUTION</h2>
                <p>Create your account to access exclusive tech deals</p>
                <div class="cyber-pulse-animation">
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle delay-1"></div>
                </div>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="cyber-register-form">
                @csrf

                <!-- Name Field -->
                <div class="cyber-form-group">
                    <div class="cyber-input-container">
                        <input id="name" type="text" class="cyber-form-input @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <label for="name" class="cyber-input-label">
                            <i class="fas fa-user-tag"></i> Username
                        </label>
                        <div class="cyber-input-highlight"></div>
                    </div>
                    @error('name')
                        <div class="cyber-error-message">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="cyber-form-group">
                    <div class="cyber-input-container">
                        <input id="email" type="email" class="cyber-form-input @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email">
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
                <button type="submit" class="cyber-register-button">
                    <span class="cyber-button-text">CREATE ACCOUNT</span>
                    <span class="cyber-button-icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <span class="cyber-button-glow"></span>
                </button>



                <!-- Login Link -->
                <div class="cyber-register-footer">
                    Already have an account? <a href="{{ route('login') }}" class="cyber-login-link">Access your Tech Universe</a>
                </div>
            </form>
        </div>

        <!-- Security Badge -->
        <div class="cyber-security-badge">
            <i class="fas fa-shield-alt"></i>
            <span>256-bit Encryption • Secure Registration</span>
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
        overflow-x: hidden;
    }

    /* Register Section */
    .cyber-register-section {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        background: radial-gradient(circle at center, var(--cyber-dark), var(--cyber-darker));
    }

    .cyber-register-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        z-index: 1;
    }

    /* Register Card */
    .cyber-register-card {
        background: var(--cyber-card-bg);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(0, 240, 255, 0.2);
        backdrop-filter: blur(10px);
        position: relative;
    }

    .cyber-register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 240, 255, 0.05) 0%, rgba(255, 0, 240, 0.05) 100%);
        z-index: -1;
    }

    /* Register Header */
    .cyber-register-header {
        padding: 30px;
        text-align: center;
        background: linear-gradient(90deg, rgba(0, 240, 255, 0.1), rgba(255, 0, 240, 0.1));
        position: relative;
        overflow: hidden;
    }

    .cyber-register-icon {
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

    .cyber-register-header h2 {
        margin: 10px 0 5px;
        font-size: 1.8rem;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cyber-register-header p {
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

    /* Register Form */
    .cyber-register-form {
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
        top: 2px;
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

    /* Password Strength Meter */
    .cyber-password-strength {
        margin-top: 10px;
    }

    .strength-meter {
        width: 100%;
        height: 5px;
        background: rgba(224, 224, 255, 0.1);
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .strength-bar {
        height: 100%;
        width: 0%;
        background: var(--cyber-weak);
        transition: all 0.3s ease;
    }

    .strength-text {
        font-size: 0.8rem;
        color: rgba(224, 224, 255, 0.7);
    }

    .strength-text span {
        color: var(--cyber-weak);
    }

    /* Password Match Indicator */
    .cyber-password-match {
        margin-top: 10px;
        font-size: 0.8rem;
        color: rgba(224, 224, 255, 0.7);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cyber-password-match i {
        color: var(--cyber-error);
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

    /* Terms Checkbox */
    .cyber-checkbox-container {
        display: flex;
        align-items: center;
        position: relative;
        cursor: pointer;
        user-select: none;
        font-size: 0.9rem;
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
        color: rgba(224, 224, 255, 0.7);
    }

    .cyber-terms-link {
        color: var(--cyber-primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cyber-terms-link:hover {
        text-decoration: underline;
    }

    /* Register Button */
    .cyber-register-button {
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

    .cyber-register-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
    }

    .cyber-register-button:active {
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

    .cyber-register-button:hover .cyber-button-glow {
        left: 100%;
    }

    /* Social Register */
    .cyber-social-register {
        margin: 30px 0;
        text-align: center;
    }

    .cyber-social-register p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 15px;
        position: relative;
    }

    .cyber-social-register p::before,
    .cyber-social-register p::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: rgba(0, 240, 255, 0.3);
    }

    .cyber-social-register p::before {
        left: 0;
    }

    .cyber-social-register p::after {
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

    /* Register Footer */
    .cyber-register-footer {
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

    /* Floating Elements */
    .cyber-register-orb {
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
        .cyber-register-header {
            padding: 20px;
        }

        .cyber-register-form {
            padding: 20px;
        }

        .cyber-register-header h2 {
            font-size: 1.5rem;
        }

        .cyber-form-input {
            padding: 12px 12px 12px 40px;
        }

        .cyber-register-button {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .cyber-register-card {
            border-radius: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Font Awesome (remplacez YOUR-KIT-CODE par votre vrai code) -->
<script src="https://kit.fontawesome.com/YOUR-KIT-CODE.js" crossorigin="anonymous"></script>

<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Vos scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Votre code JavaScript ici...
        // (Copiez le reste du code que je vous ai fourni précédemment)
    });
</script>
@endpush
@endsection
