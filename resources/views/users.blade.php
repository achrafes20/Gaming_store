@extends('Layouts.master')
@section('content')
    <!-- Hero Section Futuriste -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">USERS</span></h1>
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

    <!-- Users Section - Style Cyberpunk -->
    <div class="cyber-users-section">
        <div class="container">
            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($users as $user)
                    <div class="cyber-user-accordion" data-aos="fade-up">
                        <div class="cyber-user-header" id="cyberHeading{{ $user->id }}">
                            <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                data-target="#cyberCollapse{{ $user->id }}" aria-expanded="true"
                                aria-controls="cyberCollapse{{ $user->id }}">
                                <span class="cyber-user-name">{{ $user->name }}</span>
                                <span class="cyber-user-role {{ $user->role === 'admin' ? 'admin-role' : 'client-role' }}">
                                    {{ $user->role === 'admin' ? 'admin' : 'client' }}
                                </span>
                                
                            </button>
                        </div>

                        <div id="cyberCollapse{{ $user->id }}" class="cyber-user-collapse show"
                            aria-labelledby="cyberHeading{{ $user->id }}" data-parent="#cyberAccordion">
                            <div class="cyber-user-body">
                                <div class="cyber-user-details">
                                    <div class="cyber-form-row">
                                        <div class="cyber-form-group">
                                            <div class="cyber-input-container">
                                                <input type="text" value="{{ $user->name }}" class="cyber-input" readonly>
                                                <div class="cyber-input-border"></div>
                                                <div class="cyber-input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cyber-form-group">
                                            <div class="cyber-input-container">
                                                <input type="text" value="{{ $user->email }}" class="cyber-input" readonly>
                                                <div class="cyber-input-border"></div>
                                                <div class="cyber-input-icon">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cyber-form-actions">
                                        @if($user->role == "admin")
                                        <a href="/Users_client/{{ $user->id }}" class="cyber-btn warning-btn">
                                            <i class="fas fa-user-shield"></i>
                                            <span>DEMOTE TO CLIENT</span>
                                            <div class="cyber-btn-hover"></div>
                                        </a>
                                        @else
                                        <a href="/Users_admin/{{ $user->id }}" class="cyber-btn success-btn">
                                            <i class="fas fa-user-crown"></i>
                                            <span>PROMOTE TO ADMIN</span>
                                            <div class="cyber-btn-hover"></div>
                                        </a>
                                        @endif
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
            --cyber-success: #00cc88;
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

        /* Users Section */
        .cyber-users-section {
            padding: 60px 0 100px;
            position: relative;
        }

        /* Accordion Styles */
        .cyber-accordion-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cyber-user-accordion {
            background: var(--cyber-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cyber-user-accordion:hover {
            box-shadow: 0 8px 25px rgba(0, 240, 255, 0.2);
        }

        .cyber-user-header {
            background: var(--cyber-form-bg);
            padding: 0;
            border-bottom: 1px solid var(--cyber-border);
        }

        .cyber-accordion-btn {
            width: 100%;
            padding: 20px;
            background: transparent;
            border: none;
            color: var(--cyber-light);
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Orbitron', sans-serif;
        }

        .cyber-accordion-btn:hover {
            background: rgba(0, 240, 255, 0.05);
        }

        .cyber-user-name {
            font-weight: bold;
            color: var(--cyber-primary);
            font-size: 1.1rem;
            margin-right: 20px;
            flex: 1;
        }

        .cyber-user-role {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .admin-role {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            color: var(--cyber-dark);
        }

        .client-role {
            background: linear-gradient(90deg, var(--cyber-secondary), #ff0066);
            color: var(--cyber-light);
        }

        .cyber-accordion-icon {
            transition: transform 0.3s ease;
            color: var(--cyber-primary);
        }

        .cyber-accordion-btn.collapsed .cyber-accordion-icon {
            transform: rotate(-90deg);
        }

        .cyber-user-collapse {
            transition: all 0.4s ease;
        }

        .cyber-user-body {
            padding: 30px;
        }

        .cyber-user-details {
            display: grid;
            gap: 20px;
        }

        /* Form Elements */
        .cyber-form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .cyber-form-group {
            flex: 1;
        }

        .cyber-input-container {
            position: relative;
        }

        .cyber-input {
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

        .cyber-input[readonly] {
            opacity: 0.8;
        }

        .cyber-input-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--cyber-primary);
            z-index: 2;
        }

        .cyber-input-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--cyber-accent);
            transition: width 0.4s ease;
        }

        /* Form Actions */
        .cyber-form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .cyber-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-left: 15px;
            font-size: 0.9rem;
            border: none;
        }

        .cyber-btn-hover {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .cyber-btn:hover .cyber-btn-hover {
            left: 100%;
        }

        .cyber-btn i {
            margin-right: 10px;
        }

        .success-btn {
            background: linear-gradient(90deg, var(--cyber-success), var(--cyber-accent));
            color: var(--cyber-dark);
        }

        .success-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 204, 136, 0.4);
        }

        .warning-btn {
            background: linear-gradient(90deg, var(--cyber-warning), #ffaa00);
            color: var(--cyber-dark);
        }

        .warning-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
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
        }

        @media (max-width: 768px) {
            .cyber-form-row {
                flex-direction: column;
                gap: 0;
            }

            .cyber-accordion-btn {
                flex-wrap: wrap;
            }

            .cyber-user-name {
                flex: 0 0 100%;
                margin-bottom: 10px;
            }

            .cyber-user-role {
                margin-right: 0;
                margin-left: auto;
            }

            .cyber-form-actions {
                flex-direction: column;
            }

            .cyber-user-body {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .cyber-title {
                font-size: 2rem;
            }

            .cyber-accordion-btn {
                padding: 15px;
            }

            .cyber-btn {
                padding: 10px 20px;
                font-size: 0.8rem;
            }

            .cyber-input {
                padding: 12px 12px 12px 40px;
            }

            .cyber-input-icon {
                left: 10px;
                top: 12px;
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

            // Add pulse animation to user accordions periodically
            setInterval(function() {
                const accordions = document.querySelectorAll('.cyber-user-accordion');
                accordions.forEach((accordion, index) => {
                    setTimeout(() => {
                        accordion.style.boxShadow = '0 0 20px rgba(0, 240, 255, 0.3)';
                        setTimeout(() => {
                            accordion.style.boxShadow = '0 5px 15px rgba(0, 240, 255, 0.1)';
                        }, 1000);
                    }, index * 300);
                });
            }, 10000);
        });
    </script>
    @endpush
@endsection
