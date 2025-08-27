@extends('Layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NeonCore - Cyberpunk Tech Store</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<<<<<<< HEAD
        <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
=======
        <style>
            :root {
                --neon-pink: #ff2a6d;
                --neon-blue: #05d9e8;
                --neon-purple: #d300c5;
                --neon-green: #00ff9d;
                --dark-bg: #0d0221;
                --darker-bg: #02000d;
            }

            body {
                background-color: var(--dark-bg);
                color: #fff;
                font-family: 'Orbitron', sans-serif;
                overflow-x: hidden;
                background-image:
                    linear-gradient(rgba(5, 217, 232, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(5, 217, 232, 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .cyber-font {
                font-family: 'Orbitron', sans-serif;
            }

            .neon-text-pink {
                color: var(--neon-pink);
                text-shadow: 0 0 5px var(--neon-pink), 0 0 10px var(--neon-pink);
            }

            .neon-text-blue {
                color: var(--neon-blue);
                text-shadow: 0 0 5px var(--neon-blue), 0 0 10px var(--neon-blue);
            }

            .neon-text-green {
                color: var(--neon-green);
                text-shadow: 0 0 5px var(--neon-green), 0 0 10px var(--neon-green);
            }

            .hero-section {
                position: relative;
                height: 400px;
                background: linear-gradient(135deg, #0a0a1a 0%, #1a1a3a 50%, #0a0a1a 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                margin-bottom: 50px;
            }

            .hero-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(5, 217, 232, 0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
                opacity: 0.5;
            }

            .hero-content {
                position: relative;
                z-index: 2;
                text-align: center;
                padding: 20px;
            }

            .hero-subtitle {
                color: var(--neon-blue);
                font-size: 1.2rem;
                letter-spacing: 3px;
                text-transform: uppercase;
                margin-bottom: 15px;
                text-shadow: 0 0 10px rgba(5, 217, 232, 0.5);
            }

            .hero-title {
                font-size: 3.5rem;
                font-weight: bold;
                color: var(--neon-pink);
                margin-bottom: 30px;
                text-transform: uppercase;
                letter-spacing: 2px;
            }

            .hero-title span {
                color: var(--neon-blue);
            }

            .pulse-animation {
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
                background-color: var(--neon-blue);
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

            .product-card {
                background-color: rgba(13, 2, 33, 0.8);
                transition: all 0.3s ease;
                border: 1px solid var(--neon-purple);
                height: 100%;
                position: relative;
                overflow: hidden;
                margin-bottom: 30px;
                padding: 20px;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(255, 42, 109, 0.3);
            }

            .product-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(to bottom right,
                        transparent 45%,
                        rgba(5, 217, 232, 0.1) 50%,
                        transparent 55%);
                transform: rotate(45deg);
                z-index: 1;
                pointer-events: none;
                animation: scan 6s linear infinite;
            }

            @keyframes scan {
                0% {
                    transform: translateY(-100%) rotate(45deg);
                }

                100% {
                    transform: translateY(100%) rotate(45deg);
                }
            }

            .product-image {
                position: relative;
                height: 250px;
                margin-bottom: 20px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.3);
            }

            .product-image img {
                max-height: 100%;
                width: auto;
                transition: transform 0.5s ease;
                z-index: 2;
            }

            .product-card:hover .product-image img {
                transform: scale(1.05);
            }

            .product-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: all 0.3s ease;
            }

            .product-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: var(--neon-pink);
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-weight: bold;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                z-index: 3;
            }

            .product-info {
                text-align: center;
            }

            .product-name {
                font-size: 1.4rem;
                color: var(--neon-blue);
                margin-bottom: 15px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            .product-price {
                font-size: 1.4rem;
                color: rgb(0, 102, 255);
                margin-bottom: 15px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .product-desc {
                color: #fff;
                opacity: 0.8;
                margin-bottom: 20px;
                font-size: 0.95rem;
                min-height: 60px;
            }

            .btn-cyber {
                background: linear-gradient(45deg, var(--neon-pink), var(--neon-purple));
                color: white;
                border: none;
                font-weight: bold;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
                z-index: 1;
                padding: 12px 25px;
                border-radius: 50px;
                text-transform: uppercase;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .btn-cyber:hover {
                background: linear-gradient(45deg, var(--neon-purple), var(--neon-pink));
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(255, 42, 109, 0.4);
                color: white;
            }

            .btn-cyber::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg,
                        transparent,
                        rgba(255, 255, 255, 0.2),
                        transparent);
                transition: 0.5s;
                z-index: -1;
            }

            .btn-cyber:hover::before {
                left: 100%;
            }

            .btn-cyber i {
                margin-right: 10px;
            }

            .admin-actions {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 15px;
                flex-wrap: wrap;
            }

            .admin-btn {
                padding: 8px 15px;
                border-radius: 5px;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: bold;
                text-decoration: none;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
            }

            .admin-btn i {
                margin-right: 5px;
            }

            .delete-btn {
                background: var(--neon-pink);
                color: white;
            }

            .delete-btn:hover {
                background: #d1002a;
                transform: translateY(-2px);
            }

            .edit-btn {
                background: var(--neon-blue);
                color: var(--dark-bg);
            }

            .edit-btn:hover {
                background: #00d0e0;
                transform: translateY(-2px);
                color: var(--dark-bg);
            }

            .section-title {
                text-align: center;
                margin-bottom: 50px;
            }

            .section-title h3 {
                font-size: 2.5rem;
                text-transform: uppercase;
                letter-spacing: 3px;
                color: var(--neon-pink);
                margin-bottom: 15px;
            }

            .section-title p {
                color: var(--neon-blue);
                opacity: 0.8;
                max-width: 600px;
                margin: 0 auto;
                font-size: 1.1rem;
            }

            .scanline {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to bottom,
                        transparent 95%,
                        rgba(5, 217, 232, 0.1) 96%);
                background-size: 100% 5px;
                z-index: 1000;
                pointer-events: none;
                animation: scanline 4s linear infinite;
            }

            @keyframes scanline {
                0% {
                    transform: translateY(0)
                }

                100% {
                    transform: translateY(100vh)
                }
            }

            /* Responsive Adjustments */
            @media (max-width: 992px) {
                .hero-title {
                    font-size: 2.8rem;
                }

                .section-title h3 {
                    font-size: 2rem;
                }

                .product-card {
                    padding: 15px;
                }

                .product-image {
                    height: 200px;
                }
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.2rem;
                }

                .hero-subtitle {
                    font-size: 1rem;
                }

                .section-title h3 {
                    font-size: 1.8rem;
                }

                .product-name {
                    font-size: 1.2rem;
                }

                .btn-cyber {
                    padding: 10px 20px;
                    font-size: 0.8rem;
                }
            }

            @media (max-width: 576px) {
                .hero-section {
                    height: 300px;
                }

                .hero-title {
                    font-size: 1.8rem;
                }

                .section-title h3 {
                    font-size: 1.5rem;
                }

                .product-image {
                    height: 180px;
                }

                .admin-actions {
                    flex-direction: column;
                    align-items: center;
                }

                .admin-btn {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
>>>>>>> cdb3baa6643f071c4272a3c2f06e49e621824e4f
    </head>

    <body>
        <div class="scanline"></div>
        <div class="container">
            <div class="section-title">
                <h3 class="cyber-font neon-text-pink">OUR <span class="neon-text-blue">PRODUCTS</span></h3>
            </div>
            <div class="row" id="productsGrid">
                @foreach ($products as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 _{{ $item->category_id }}">
                        <div class="product-card p-3" style="height: 470px;">
                            <div class="product-image mb-1">
                                <a href="/single-product/{{ $item->id }}">
                                    <img style="max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid var(--cyber-primary);
            object-fit: cover;"
                                        src="{{ asset($item->imagepath) }}" alt="{{ $item->name }}">
                                    <div class="product-overlay">
                                        <div class="quick-view">
                                            <i class="fas fa-eye"></i> QUICK VIEW
                                        </div>
                                    </div>
                                </a>
                            </div>
<<<<<<< HEAD
                            <h5 class="neon-text-blue">{{ \Illuminate\Support\Str::limit($item->name, 60) }}</h5>
=======
                            <div class="product-info">
                                <h3 class="product-name">{{ $item->name }}</h3>
                                <h3 class="product-price">{{ $item->price }} Dh</h3>
>>>>>>> cdb3baa6643f071c4272a3c2f06e49e621824e4f

                            <div class="d-flex align-items-center mb-3">
                                <span class="price me-2">{{ number_format($item->price, 2) }} Dh</span>
                                @if ($item->old_price && $item->price < $item->old_price)
                                    <span class="old-price me-2">${{ number_format($item->old_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-cube me-2 neon-text-blue"></i>
                                <span style="color: grey">{{ $item->quantity }} IN STOCK</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($item->quantity == 0)
                                    <a class="btn btn-cyber btn-sm">
                                        <i class="fas "></i> OUT OF STOCK
                                    </a>
                                @else
                                    <form action="{{ url('/addproducttocart/' . $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-cyber btn-sm">
                                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('favorites.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    @auth
                                        <button type="submit"
                                            class="favorite-btn {{ auth()->user()->favorites()->where('product_id', $item->id)->exists() ? 'active' : '' }}">
                                            <i
                                                class="{{ auth()->user()->favorites()->where('product_id', $item->id)->exists() ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="favorite-btn">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @endauth
                                </form>
                                @if (Auth::check() && Auth::user()->role == 'admin')
                                    <div class="admin-actions">
                                        <a href="/editproduct/{{ $item->id }}" class="edit-btn">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="/removeproduct/{{ $item->id }}" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/js/product.js') }}"></script>
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
    </body>

    </html>
@endsection
