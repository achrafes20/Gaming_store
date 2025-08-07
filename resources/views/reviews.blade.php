@extends('Layouts.master')
@section('content')
<!-- Cyber Breadcrumb Section -->
<div class="cyber-breadcrumb-section">
    <div class="cyber-breadcrumb-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="cyber-breadcrumb-text">
                    <div class="cyber-glitch" data-text="SHARE YOUR EXPERIENCE">SHARE YOUR EXPERIENCE</div>
                    <h1>ADD TECH REVIEW</h1>
                    <div class="cyber-pulse-animation">
                        <div class="pulse-circle"></div>
                        <div class="pulse-circle delay-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cyber Breadcrumb Section -->

<!-- Cyber Review Form Section -->
<div class="cyber-review-form-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="cyber-form-container" data-aos="fade-up">
                    <div class="cyber-form-header">
                        <h2><i class="fas fa-comment-alt"></i> REVIEW FORM</h2>
                        <p>Share your thoughts about our products and service</p>
                    </div>

                    <form method="POST" action="/storereview" id="cyber-review-form">
                        @csrf()
                        <div class="cyber-form-grid">
                            <div class="cyber-input-group">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                <label for="name">YOUR NAME</label>
                                <div class="cyber-input-highlight"></div>
                                @error('name')
                                    <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="cyber-input-group">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                                <label for="email">EMAIL ADDRESS</label>
                                <div class="cyber-input-highlight"></div>
                                @error('email')
                                    <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="cyber-input-group">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
                                <label for="phone">PHONE NUMBER</label>
                                <div class="cyber-input-highlight"></div>
                                @error('phone')
                                    <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="cyber-input-group">
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required>
                                <label for="subject">REVIEW TITLE</label>
                                <div class="cyber-input-highlight"></div>
                                @error('subject')
                                    <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="cyber-textarea-group">
                            <textarea name="message" id="message" required>{{ old('message') }}</textarea>
                            <label for="message">YOUR REVIEW</label>
                            <div class="cyber-textarea-highlight"></div>
                            @error('message')
                                <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="cyber-submit-btn">
                            <span>SUBMIT REVIEW</span>
                            <i class="fas fa-paper-plane"></i>
                            <div class="cyber-btn-glow"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cyber Review Form Section -->

<!-- Cyber Testimonials Section -->
<div class="cyber-testimonials-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 text-center">
                <div class="cyber-section-header" data-aos="fade-up">
                    <div class="cyber-glitch" data-text="TECH FEEDBACK">TECH FEEDBACK</div>
                    <h2>CUSTOMER <span class="cyber-accent">REVIEWS</span></h2>
                    <p>What our clients say about our products and service</p>
                </div>

                <div class="cyber-testimonials-slider">
                    @foreach ($reviews as $item)
                    <div class="cyber-testimonial-card" data-aos="fade-up">
                        <div class="cyber-client-avatar">
                            <div class="cyber-avatar-circle">
                                <img src="{{ asset('assets/img/cyber-avatar.png') }}" alt="Avatar">
                                <div class="cyber-avatar-glow"></div>
                            </div>
                            <div class="cyber-client-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="cyber-client-meta">
                            <h3>{{ $item->name }} <span>{{ $item->subject }}</span></h3>
                            <div class="cyber-testimonial-body">
                                <p>{{ $item->message }}</p>
                            </div>
                            <div class="cyber-testimonial-footer">
                                <div class="cyber-client-contact">
                                    <i class="fas fa-envelope"></i> {{ $item->email }}
                                    <i class="fas fa-phone"></i> {{ $item->phone }}
                                </div>
                                <div class="cyber-quote-icon">
                                    <i class="fas fa-quote-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="cyber-testimonial-glow"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cyber Testimonials Section -->

@push('styles')
<style>
    /* Cyberpunk Theme Variables */
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

    /* Cyber Breadcrumb Section */
    .cyber-breadcrumb-section {
        position: relative;
        height: 300px;
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .cyber-breadcrumb-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,240,255,0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
        opacity: 0.5;
    }

    .cyber-breadcrumb-text {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .cyber-glitch {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        color: var(--cyber-primary);
        text-transform: uppercase;
        letter-spacing: 3px;
        position: relative;
        margin-bottom: 15px;
    }

    .cyber-glitch::before, .cyber-glitch::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .cyber-glitch::before {
        left: 2px;
        text-shadow: -2px 0 var(--cyber-secondary);
        animation: glitch-anim-1 2s infinite linear alternate-reverse;
    }

    .cyber-glitch::after {
        left: -2px;
        text-shadow: -2px 0 var(--cyber-accent);
        animation: glitch-anim-2 2s infinite linear alternate-reverse;
    }

    .cyber-breadcrumb-text h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        color: var(--cyber-light);
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cyber-pulse-animation {
        position: relative;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
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

    /* Cyber Review Form Section */
    .cyber-review-form-section {
        padding: 80px 0;
        background: var(--cyber-darker);
        position: relative;
    }

    .cyber-form-container {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.2);
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
    }

    .cyber-form-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .cyber-form-header h2 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-primary);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .cyber-form-header p {
        color: rgba(224, 224, 255, 0.7);
    }

    .cyber-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .cyber-input-group {
        position: relative;
        margin-bottom: 10px;
    }

    .cyber-input-group input {
        width: 100%;
        padding: 15px;
        background: rgba(10, 10, 26, 0.7);
        border: none;
        border-bottom: 2px solid rgba(0, 240, 255, 0.3);
        color: var(--cyber-light);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .cyber-input-group input:focus {
        outline: none;
        border-bottom-color: var(--cyber-primary);
    }

    .cyber-input-group label {
        position: absolute;
        top: 15px;
        left: 15px;
        color: rgba(224, 224, 255, 0.7);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .cyber-input-group input:focus + label,
    .cyber-input-group input:not(:placeholder-shown) + label {
        top: -20px;
        left: 0;
        font-size: 0.8rem;
        color: var(--cyber-primary);
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

    .cyber-input-group input:focus ~ .cyber-input-highlight {
        width: 100%;
    }

    .cyber-textarea-group {
        position: relative;
        margin-bottom: 30px;
    }

    .cyber-textarea-group textarea {
        width: 100%;
        padding: 15px;
        background: rgba(10, 10, 26, 0.7);
        border: none;
        border-bottom: 2px solid rgba(0, 240, 255, 0.3);
        color: var(--cyber-light);
        font-size: 1rem;
        min-height: 150px;
        resize: vertical;
        transition: all 0.3s ease;
    }

    .cyber-textarea-group textarea:focus {
        outline: none;
        border-bottom-color: var(--cyber-primary);
    }

    .cyber-textarea-group label {
        position: absolute;
        top: 15px;
        left: 15px;
        color: rgba(224, 224, 255, 0.7);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .cyber-textarea-group textarea:focus + label,
    .cyber-textarea-group textarea:not(:placeholder-shown) + label {
        top: -20px;
        left: 0;
        font-size: 0.8rem;
        color: var(--cyber-primary);
    }

    .cyber-textarea-highlight {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--cyber-primary);
        transition: all 0.3s ease;
    }

    .cyber-textarea-group textarea:focus ~ .cyber-textarea-highlight {
        width: 100%;
    }

    .cyber-error-message {
        color: var(--cyber-error);
        font-size: 0.8rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cyber-submit-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 30px;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
        color: var(--cyber-dark);
        border: none;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        width: 100%;
    }

    .cyber-submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
    }

    .cyber-submit-btn i {
        margin-left: 10px;
        transition: transform 0.3s ease;
    }

    .cyber-submit-btn:hover i {
        transform: translateX(5px);
    }

    .cyber-btn-glow {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.5s ease;
    }

    .cyber-submit-btn:hover .cyber-btn-glow {
        left: 100%;
    }

    /* Cyber Testimonials Section */
    .cyber-testimonials-section {
        padding: 80px 0;
        background: var(--cyber-dark);
        position: relative;
    }

    .cyber-section-header {
        margin-bottom: 60px;
    }

    .cyber-section-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: var(--cyber-light);
        margin-bottom: 15px;
    }

    .cyber-section-header p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 1.1rem;
    }

    .cyber-accent {
        color: var(--cyber-primary);
    }

    .cyber-testimonials-slider {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .cyber-testimonial-card {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.2);
        border-radius: 10px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .cyber-testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
    }

    .cyber-client-avatar {
        margin-bottom: 20px;
        position: relative;
    }

    .cyber-avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--cyber-primary);
        position: relative;
    }

    .cyber-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cyber-avatar-glow {
        position: absolute;
        top: -3px;
        left: -3px;
        width: calc(100% + 6px);
        height: calc(100% + 6px);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 240, 255, 0.3) 0%, transparent 70%);
        animation: pulse-glow 2s infinite;
    }

    .cyber-client-rating {
        margin-top: 10px;
    }

    .cyber-client-rating i {
        color: var(--cyber-accent);
        font-size: 0.9rem;
    }

    .cyber-client-meta {
        flex: 1;
    }

    .cyber-client-meta h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    .cyber-client-meta h3 span {
        display: block;
        font-size: 0.9rem;
        color: var(--cyber-primary);
        margin-top: 5px;
    }

    .cyber-testimonial-body {
        color: rgba(224, 224, 255, 0.7);
        margin-bottom: 20px;
        line-height: 1.7;
    }

    .cyber-testimonial-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .cyber-client-contact {
        font-size: 0.8rem;
        color: rgba(224, 224, 255, 0.5);
        display: flex;
        gap: 15px;
    }

    .cyber-client-contact i {
        margin-right: 5px;
        color: var(--cyber-primary);
    }

    .cyber-quote-icon {
        color: var(--cyber-primary);
        font-size: 1.5rem;
    }

    .cyber-testimonial-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .cyber-testimonial-card:hover .cyber-testimonial-glow {
        opacity: 1;
    }

    /* Animations */
    @keyframes glitch-anim-1 {
        0% { clip: rect(32px, 9999px, 76px, 0); }
        20% { clip: rect(8px, 9999px, 44px, 0); }
        40% { clip: rect(50px, 9999px, 99px, 0); }
        60% { clip: rect(42px, 9999px, 76px, 0); }
        80% { clip: rect(62px, 9999px, 52px, 0); }
        100% { clip: rect(34px, 9999px, 77px, 0); }
    }

    @keyframes glitch-anim-2 {
        0% { clip: rect(76px, 9999px, 36px, 0); }
        20% { clip: rect(54px, 9999px, 66px, 0); }
        40% { clip: rect(39px, 9999px, 35px, 0); }
        60% { clip: rect(45px, 9999px, 40px, 0); }
        80% { clip: rect(60px, 9999px, 72px, 0); }
        100% { clip: rect(31px, 9999px, 15px, 0); }
    }

    @keyframes pulse-glow {
        0%, 100% { opacity: 0.5; transform: scale(0.95); }
        50% { opacity: 1; transform: scale(1.05); }
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .cyber-breadcrumb-text h1 {
            font-size: 2.5rem;
        }

        .cyber-form-container {
            padding: 30px;
        }
    }

    @media (max-width: 768px) {
        .cyber-breadcrumb-text h1 {
            font-size: 2rem;
        }

        .cyber-section-header h2 {
            font-size: 2rem;
        }

        .cyber-form-grid {
            grid-template-columns: 1fr;
        }

        .cyber-testimonial-card {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .cyber-breadcrumb-text h1 {
            font-size: 1.8rem;
        }

        .cyber-section-header h2 {
            font-size: 1.8rem;
        }

        .cyber-testimonial-footer {
            flex-direction: column;
            gap: 15px;
        }

        .cyber-client-contact {
            flex-direction: column;
            gap: 5px;
            align-items: center;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Form input effects
        const inputs = document.querySelectorAll('.cyber-input-group input, .cyber-textarea-group textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('label').style.color = 'var(--cyber-primary)';
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentNode.querySelector('label').style.color = 'rgba(224, 224, 255, 0.7)';
                }
            });
        });

        // Testimonial card hover effect
        const testimonialCards = document.querySelectorAll('.cyber-testimonial-card');
        testimonialCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.querySelector('.cyber-testimonial-glow').style.opacity = '1';
            });

            card.addEventListener('mouseleave', function() {
                this.querySelector('.cyber-testimonial-glow').style.opacity = '0';
            });
        });
    });
</script>
@endpush
@endsection
