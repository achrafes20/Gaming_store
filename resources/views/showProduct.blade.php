@extends('Layouts.master')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - NeonTech</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cyberpunk Font -->
    <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">
    <style>
        :root {
            --neon-pink: #ff2a6d;
            --neon-blue: #05d9e8;
            --neon-purple: #d300c5;
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

        .neon-border-pink {
            border: 1px solid var(--neon-pink);
            box-shadow: 0 0 10px var(--neon-pink), inset 0 0 10px var(--neon-pink);
        }

        .neon-border-blue {
            border: 1px solid var(--neon-blue);
            box-shadow: 0 0 10px var(--neon-blue), inset 0 0 10px var(--neon-blue);
        }

        .product-card {
            background-color: rgba(13, 2, 33, 0.8);
            transition: all 0.3s ease;
            border: 1px solid var(--neon-purple);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(255, 42, 109, 0.3);
        }

        .btn-cyber {
            background: linear-gradient(45deg, var(--neon-pink), var(--neon-purple));
            color: white;
            border: none;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-cyber:hover {
            background: linear-gradient(45deg, var(--neon-purple), var(--neon-pink));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 42, 109, 0.4);
        }

        .glitch-effect {
            position: relative;
        }

        .glitch-effect::before {
            content: attr(data-text);
            position: absolute;
            left: -2px;
            text-shadow: 2px 0 var(--neon-blue);
            color: var(--neon-pink);
            background: var(--dark-bg);
            overflow: hidden;
            clip: rect(0, 900px, 0, 0);
            animation: glitch-effect 3s infinite linear alternate-reverse;
        }

        @keyframes glitch-effect {
            0% { clip: rect(30px, 9999px, 50px, 0) }
            10% { clip: rect(10px, 9999px, 30px, 0) }
            20% { clip: rect(50px, 9999px, 30px, 0) }
            30% { clip: rect(30px, 9999px, 10px, 0) }
            40% { clip: rect(40px, 9999px, 60px, 0) }
            50% { clip: rect(50px, 9999px, 30px, 0) }
            60% { clip: rect(20px, 9999px, 60px, 0) }
            70% { clip: rect(40px, 9999px, 20px, 0) }
            80% { clip: rect(60px, 9999px, 50px, 0) }
            90% { clip: rect(30px, 9999px, 60px, 0) }
            100% { clip: rect(70px, 9999px, 40px, 0) }
        }

        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                transparent 95%,
                rgba(5, 217, 232, 0.1) 96%
            );
            background-size: 100% 5px;
            z-index: 1000;
            pointer-events: none;
            animation: scanline 4s linear infinite;
        }

        @keyframes scanline {
            0% { transform: translateY(0) }
            100% { transform: translateY(100vh) }
        }

        .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            transition: transform 0.5s ease;
        }

        .product-image:hover img {
            transform: scale(1.05);
        }

        .spec-item {
            border-left: 3px solid var(--neon-blue);
            padding-left: 15px;
            margin-bottom: 15px;
        }

        .thumb-img {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .thumb-img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Scanline Effect -->
    <div class="scanline"></div>

    <!-- Header -->


    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6">
                @if($product->ProductPhotos->count() > 0)
                <div class="product-image mb-4 neon-border-pink p-2">
                    <img style="height:500px;" src="{{ asset($product->imagepath) }}" class="img-fluid w-100" alt="{{ $product->name }}" id="mainImage">
                </div>
                <div class="row">
                    <div class="col-2 thumb">
            <img style="width: 80px;height:80px;" src="{{ asset($product->imagepath) }}" class="img-fluid thumb-img neon-border-blue p-1" alt="{{ $product->name }} variation">
        </div>
                    @foreach ($product->ProductPhotos as $item)
                    <div class="col-2 thumb">
                        <img style="width: 80px;height:80px;" src="{{ asset($item->imagepath) }}" class="img-fluid thumb-img neon-border-blue p-1" alt="{{ $product->name }} variation">
                    </div>
                    @endforeach
                </div>
                @else
                <div class="product-image mb-4 neon-border-pink p-2">
                    <img src="{{ asset($product->imagepath) }}" class="img-fluid w-100" alt="{{ $product->name }}">
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <h2 class="cyber-font neon-text-pink mb-3">{{ $product->name }}</h2>


                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <span class="neon-text-blue fs-4">{{ number_format($product->price, 2) }} Dh</span>
                        @if($product->price < $product->old_price)
                        <span class="text-decoration-line-through text-muted ms-2">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->price < $product->old_price)
                    <span class="badge bg-danger">-{{ round(($product->old_price - $product->price) / $product->old_price * 100) }}%</span>
                    @endif
                </div>

                <div class="mb-4">

                    <div class="progress neon-border-blue" style="height: 5px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ rand(80, 100) }}%"></div>
                    </div>
                </div>

                <p class="mb-4">{{ $product->description }}</p>



                <div class="row mb-4">

                    <div class="col-md-6">
                        <label for="qtySelect" class="form-label neon-text-blue">QUANTITY:</label>
                        <div class="input-group">
                            <button class="btn btn-outline-info" type="button" id="minusBtn">-</button>
                            <input type="text" class="form-control bg-dark text-white text-center neon-border-blue" value="1" id="qtyInput" max="{{ $product->quantity }}">
                            <button class="btn btn-outline-info" type="button" id="plusBtn">+</button>
                        </div>
                        <small style="color: grey">{{ $product->quantity }} available</small>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <a href="/addproducttocart/{{ $product->id }}" class="btn btn-cyber btn-lg py-3">ADD TO CART</a>

                </div>

                <div class="cyber-tech-specs mt-4 product-card p-3">
                    <h5 class="neon-text-blue mb-3"><i class="fas fa-info-circle"></i> TECHNICAL SPECIFICATIONS</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><span class="neon-text-pink">Model:</span> {{ $product->name }}</li>
                        <li class="mb-2"><span class="neon-text-pink">Category:</span> {{ $product->Category->name }}</li>

                        <li class="mb-2"><span class="neon-text-pink">Warranty:</span> 2 Years</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->

        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="cyber-font neon-text-pink mb-4">YOU MAY ALSO LIKE</h3>

                <div class="row">
                    @foreach ($relatedProducts as $item)
                    <div class="col-md-3 mb-4">
                        <div class="product-card p-3 h-100">
                            <img src="{{ asset($item->imagepath) }}" class="img-fluid mb-3">
                            <h5 class="neon-text-blue">{{ $item->name }}</h5>
                            <p class="text-muted">${{ number_format($item->price, 2) }}</p>
                            <a href="/single-product/{{ $item->id }}" class="btn btn-cyber w-100">VIEW</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script>
        // Thumbnail image click handler
        document.querySelectorAll('.thumb-img').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;

                // Remove active class from all thumbs
                document.querySelectorAll('.thumb-img').forEach(i => {
                    i.classList.remove('neon-border-pink');
                    i.classList.add('neon-border-blue');
                });

                // Add active class to clicked thumb
                this.classList.remove('neon-border-blue');
                this.classList.add('neon-border-pink');
            });
        });

        // Quantity buttons
        document.getElementById('plusBtn').addEventListener('click', function() {
            const qtyInput = document.getElementById('qtyInput');
            const max = parseInt(qtyInput.getAttribute('max'));
            if (parseInt(qtyInput.value) < max) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
            }
        });

        document.getElementById('minusBtn').addEventListener('click', function() {
            const qtyInput = document.getElementById('qtyInput');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });

        // Glitch effect on hover for cyberpunk elements
        document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.classList.add('glitch-effect');
            });

            el.addEventListener('mouseleave', function() {
                this.classList.remove('glitch-effect');
            });
        });

        // Initialize Bootstrap tabs
        var tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabEls.forEach(function(tabEl) {
            tabEl.addEventListener('click', function (event) {
                event.preventDefault();
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            });
        });
    </script>
</body>
</html>
@endsection
