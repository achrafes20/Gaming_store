@extends('Layouts.master')
@section('content')
    <!-- Hero Section Futuriste -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">CATEGORIES</span></h1>
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

    <!-- Categories Section - Style Cyberpunk -->
    <div class="cyber-categories-section">
        <div class="container">
            <div class="cyber-action-btns">
                <a href="/addcategory" class="cyber-btn add-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>ADD NEW CATEGORY</span>
                    <div class="cyber-btn-hover"></div>
                </a>
            </div>

            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($category as $item)
                    <div class="cyber-category-accordion" data-aos="fade-up">
                        <div class="cyber-category-header" id="cyberHeading{{ $item->id }}">
                            <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                data-target="#cyberCollapse{{ $item->id }}" aria-expanded="true"
                                aria-controls="cyberCollapse{{ $item->id }}">
                                <span class="cyber-category-name">{{ $item->name }}</span>
                                <span class="cyber-category-date">{{ $item->created_at->format('M d, Y') }}</span>
                                <i class="fas fa-chevron-down cyber-accordion-icon"></i>
                            </button>
                        </div>

                        <div id="cyberCollapse{{ $item->id }}" class="cyber-category-collapse show"
                            aria-labelledby="cyberHeading{{ $item->id }}" data-parent="#cyberAccordion">
                            <div class="cyber-category-body">
                                <form method="POST" enctype="multipart/form-data" action="/storecategory" id="cyber-category-form-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">

                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="text" placeholder="CATEGORY NAME" name="name"
                                                value="{{ $item->name }}" class="cyber-input" required>
                                            <div class="cyber-input-border"></div>
                                            <div class="cyber-input-icon">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                        </div>
                                        <span class="cyber-error">
                                            @error('name') {{ $message }} @enderror
                                        </span>
                                    </div>

                                    <div class="cyber-form-group">
                                        <div class="cyber-textarea-container">
                                            <textarea name="description" placeholder="CATEGORY DESCRIPTION" class="cyber-textarea">{{ $item->description }}</textarea>
                                            <div class="cyber-textarea-border"></div>
                                            <div class="cyber-textarea-icon">
                                                <i class="fas fa-align-left"></i>
                                            </div>
                                        </div>
                                        <span class="cyber-error">
                                            @error('description') {{ $message }} @enderror
                                        </span>
                                    </div>

                                    <div class="cyber-form-group">
                                        <div class="cyber-file-container">
                                            <label for="photo-{{ $item->id }}" class="cyber-file-label">
                                                <span class="cyber-file-icon"><i class="fas fa-camera"></i></span>
                                                <span class="cyber-file-text">UPLOAD CATEGORY IMAGE</span>
                                                <input type="file" name="photo" id="photo-{{ $item->id }}" class="cyber-file-input">
                                            </label>

                                            @if(!empty($item->imagepath))
                                                <div class="cyber-file-preview" id="cyber-file-preview-{{ $item->id }}">
                                                    <img src="{{ asset($item->imagepath) }}" alt="Category Image">
                                                </div>
                                            @endif
                                        </div>
                                        <span class="cyber-error">
                                            @error('photo') {{ $message }} @enderror
                                        </span>
                                    </div>

                                    <div class="cyber-form-actions">
                                        <button type="submit" class="cyber-submit-btn update-btn">
                                            <span class="cyber-btn-text">UPDATE CATEGORY</span>
                                            <span class="cyber-btn-icon">
                                                <i class="fas fa-save"></i>
                                            </span>
                                            <span class="cyber-btn-pulse"></span>
                                        </button>

                                        <a href="/removecategory/{{ $item->id }}" class="cyber-btn danger-btn">
                                            <i class="fas fa-trash"></i>
                                            <span>DELETE</span>
                                            <div class="cyber-btn-hover"></div>
                                        </a>
                                    </div>
                                </form>
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
            --cyber-organic: #00ff88;
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

        /* Categories Section */
        .cyber-categories-section {
            padding: 60px 0 100px;
            position: relative;
        }

        .cyber-action-btns {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
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

        .add-btn {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            color: var(--cyber-dark);
        }

        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }

        .danger-btn {
            background: linear-gradient(90deg, var(--cyber-danger), #ff0066);
            color: var(--cyber-light);
        }

        .danger-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 0, 60, 0.4);
        }

        /* Accordion Styles */
        .cyber-accordion-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cyber-category-accordion {
            background: var(--cyber-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cyber-category-accordion:hover {
            box-shadow: 0 8px 25px rgba(0, 240, 255, 0.2);
        }

        .cyber-category-header {
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

        .cyber-category-name {
            font-weight: bold;
            color: var(--cyber-primary);
            font-size: 1.1rem;
            margin-right: 20px;
            flex: 1;
        }

        .cyber-category-date {
            color: var(--cyber-light);
            opacity: 0.8;
            font-size: 0.9rem;
            margin-right: 20px;
        }

        .cyber-accordion-icon {
            transition: transform 0.3s ease;
            color: var(--cyber-primary);
        }

        .cyber-accordion-btn.collapsed .cyber-accordion-icon {
            transform: rotate(-90deg);
        }

        .cyber-category-collapse {
            transition: all 0.4s ease;
        }

        .cyber-category-body {
            padding: 30px;
        }

        /* Form Elements */
        .cyber-form-group {
            margin-bottom: 25px;
        }

        .cyber-input-container,
        .cyber-textarea-container,
        .cyber-file-container {
            position: relative;
        }

        .cyber-input,
        .cyber-textarea {
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
            min-height: 120px;
            resize: vertical;
        }

        .cyber-input-icon,
        .cyber-textarea-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--cyber-primary);
            z-index: 2;
        }

        .cyber-input-border,
        .cyber-textarea-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--cyber-accent);
            transition: width 0.4s ease;
        }

        .cyber-input:focus,
        .cyber-textarea:focus {
            outline: none;
            border-color: var(--cyber-primary);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
        }

        .cyber-input:focus ~ .cyber-input-border,
        .cyber-textarea:focus ~ .cyber-textarea-border {
            width: 100%;
        }

        /* File Input */
        .cyber-file-label {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: var(--cyber-form-bg);
            border: 1px dashed var(--cyber-border);
            border-radius: 5px;
            color: var(--cyber-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cyber-file-label:hover {
            border-color: var(--cyber-primary);
            background: rgba(0, 240, 255, 0.05);
        }

        .cyber-file-icon {
            margin-right: 15px;
            color: var(--cyber-primary);
            font-size: 1.2rem;
        }

        .cyber-file-text {
            flex: 1;
            text-align: left;
        }

        .cyber-file-input {
            display: none;
        }

        .cyber-file-preview {
            margin-top: 15px;
        }

        .cyber-file-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid var(--cyber-primary);
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

        /* Form Actions */
        .cyber-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .cyber-submit-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            color: var(--cyber-dark);
            border: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            margin-left: 10px;
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
        }

        @media (max-width: 768px) {
            .cyber-accordion-btn {
                flex-wrap: wrap;
            }

            .cyber-category-name {
                flex: 0 0 100%;
                margin-bottom: 10px;
            }

            .cyber-category-date {
                margin-right: 0;
                margin-left: auto;
            }

            .cyber-form-actions {
                flex-direction: column;
            }

            .cyber-category-body {
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

            .cyber-input,
            .cyber-textarea {
                padding: 12px 12px 12px 40px;
            }

            .cyber-input-icon,
            .cyber-textarea-icon {
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

            // Add pulse animation to category accordions periodically
            setInterval(function() {
                const accordions = document.querySelectorAll('.cyber-category-accordion');
                accordions.forEach((accordion, index) => {
                    setTimeout(() => {
                        accordion.style.boxShadow = '0 0 20px rgba(0, 240, 255, 0.3)';
                        setTimeout(() => {
                            accordion.style.boxShadow = '0 5px 15px rgba(0, 240, 255, 0.1)';
                        }, 1000);
                    }, index * 300);
                });
            }, 10000);

            // Image preview functionality
            document.querySelectorAll('.cyber-file-input').forEach(input => {
                input.addEventListener('change', function() {
                    const previewId = 'cyber-file-preview-' + this.id.split('-')[1];
                    const previewDiv = document.getElementById(previewId);

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            if (!previewDiv) {
                                const newPreview = document.createElement('div');
                                newPreview.id = previewId;
                                newPreview.className = 'cyber-file-preview';
                                newPreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                                input.closest('.cyber-file-container').appendChild(newPreview);
                            } else {
                                previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                            }
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
