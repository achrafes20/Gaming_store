@extends('Layouts.master')
@section('content')
    <!-- Cyberpunk Hero Section -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">PRODUCT IMAGES</span></h1>
                        <p class="cyber-subtitle">{{ $product->name }}</p>
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

    <!-- Cyberpunk Upload Section -->
    <div class="cyber-upload-section">
        <div class="container">
            <div class="cyber-upload-container">
                <form action="/storeProductImage" method="POST" enctype="multipart/form-data" class="cyber-upload-form">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">

                    <div class="cyber-file-upload">
                        <label for="photo" class="cyber-upload-label">
                            <div class="cyber-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="cyber-upload-text">SELECT IMAGE FILE</div>
                            <input type="file" name="photo" id="photo" class="cyber-file-input">
                        </label>
                        <div class="cyber-upload-preview" id="cyber-upload-preview"></div>
                    </div>

                    <div class="cyber-upload-submit">
                        <button type="submit" class="cyber-upload-btn">
                            <span class="cyber-btn-icon"><i class="fas fa-save"></i></span>
                            <span class="cyber-btn-text">UPLOAD TO DATABASE</span>
                            <span class="cyber-btn-pulse"></span>
                        </button>
                    </div>

                    <span class="cyber-error">
                        @error('photo')
                            {{ $message }}
                        @enderror
                    </span>
                </form>
            </div>
        </div>
    </div>

    <!-- Cyberpunk Gallery Section -->
    <div class="cyber-gallery-section">
        <div class="container">
            <div class="cyber-gallery-title">
                <h3>EXISTING <span class="cyber-accent">IMAGES</span></h3>
                <div class="cyber-title-underline"></div>
            </div>

            <div class="cyber-gallery-grid">
                @foreach ($productImages as $item)
                <div class="cyber-gallery-item" data-aos="fade-up">
                    <div class="cyber-image-container">
                        <img src="{{ asset($item->imagepath) }}" alt="Product Image" class="cyber-gallery-img">
                        <div class="cyber-image-overlay">
                            <a href="/removeproductphoto/{{ $item->id }}" class="cyber-delete-btn">
                                <i class="fas fa-trash"></i>
                                DELETE
                            </a>
                        </div>
                        <div class="cyber-image-border"></div>
                    </div>
                </div>
                @endforeach
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
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--cyber-light), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cyber-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .cyber-subtitle {
            color: var(--cyber-primary);
            font-size: 1.2rem;
            letter-spacing: 3px;
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

        /* Upload Section */
        .cyber-upload-section {
            padding: 50px 0;
            position: relative;
        }

        .cyber-upload-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--cyber-bg);
            border: 1px solid var(--cyber-border);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
        }

        .cyber-upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cyber-file-upload {
            width: 100%;
            margin-bottom: 20px;
        }

        .cyber-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--cyber-form-bg);
            border: 2px dashed var(--cyber-border);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .cyber-upload-label:hover {
            border-color: var(--cyber-primary);
            background: rgba(0, 240, 255, 0.05);
        }

        .cyber-upload-icon {
            font-size: 3rem;
            color: var(--cyber-primary);
            margin-bottom: 15px;
        }

        .cyber-upload-text {
            font-size: 1.2rem;
            color: var(--cyber-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cyber-file-input {
            display: none;
        }

        .cyber-upload-preview {
            margin-top: 20px;
            display: none;
            text-align: center;
        }

        .cyber-upload-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 2px solid var(--cyber-primary);
        }

        .cyber-upload-submit {
            width: 100%;
            text-align: center;
        }

        .cyber-upload-btn {
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

        .cyber-upload-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.5);
        }

        .cyber-btn-icon {
            margin-right: 10px;
        }

        .cyber-btn-text {
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

        .cyber-error {
            display: block;
            margin-top: 15px;
            color: var(--cyber-danger);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Gallery Section */
        .cyber-gallery-section {
            padding: 50px 0 100px;
        }

        .cyber-gallery-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .cyber-gallery-title h3 {
            font-size: 2rem;
            color: var(--cyber-light);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .cyber-gallery-title .cyber-accent {
            background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .cyber-title-underline {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--cyber-primary), transparent);
            margin: 0 auto;
        }

        .cyber-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .cyber-gallery-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .cyber-image-container {
            position: relative;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            overflow: hidden;
        }

        .cyber-gallery-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .cyber-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .cyber-image-border {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .cyber-gallery-item:hover .cyber-image-overlay {
            opacity: 1;
        }

        .cyber-gallery-item:hover .cyber-image-border {
            border-color: var(--cyber-primary);
        }

        .cyber-gallery-item:hover .cyber-gallery-img {
            transform: scale(1.1);
        }

        .cyber-delete-btn {
            padding: 10px 20px;
            background: var(--cyber-danger);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .cyber-delete-btn:hover {
            background: #d1002a;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 0, 60, 0.3);
        }

        .cyber-delete-btn i {
            margin-right: 8px;
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
                font-size: 2.2rem;
            }

            .cyber-gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .cyber-hero-section {
                height: 250px;
            }

            .cyber-title {
                font-size: 2rem;
            }

            .cyber-subtitle {
                font-size: 1rem;
            }

            .cyber-upload-container {
                padding: 20px;
            }

            .cyber-upload-label {
                padding: 30px;
            }

            .cyber-upload-icon {
                font-size: 2.5rem;
            }

            .cyber-upload-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .cyber-title {
                font-size: 1.8rem;
            }

            .cyber-gallery-grid {
                grid-template-columns: 1fr;
            }

            .cyber-upload-btn {
                padding: 12px 25px;
                font-size: 0.9rem;
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

            // File input preview
            const fileInput = document.getElementById('photo');
            const filePreview = document.getElementById('cyber-upload-preview');

            if (fileInput && filePreview) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            filePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                            filePreview.style.display = 'block';
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // Add pulse animation to upload button periodically
            setInterval(function() {
                const uploadBtn = document.querySelector('.cyber-upload-btn');
                if (uploadBtn) {
                    uploadBtn.style.boxShadow = '0 0 20px rgba(0, 255, 136, 0.5)';
                    setTimeout(() => {
                        uploadBtn.style.boxShadow = '0 5px 15px rgba(0, 255, 136, 0.3)';
                    }, 1000);
                }
            }, 5000);
        });
    </script>
    @endpush
@endsection
