@extends("Layouts.master")
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeonGrid - Cyberpunk Tech Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cyberpunk Font -->
    <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            height: 100%;
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

        .category-btn {
            background: transparent;
            color: var(--neon-blue);
            border: 1px solid var(--neon-blue);
            margin: 0 5px 10px;
            transition: all 0.3s ease;
        }

        .category-btn:hover, .category-btn.active {
            background: var(--neon-blue);
            color: var(--dark-bg);
            font-weight: bold;
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
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            transition: transform 0.5s ease;
            max-height: 100%;
            width: auto;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .badge-cyber {
            background: var(--neon-pink);
            color: white;
            font-weight: bold;
        }

        .price {
            font-weight: bold;
            color: var(--neon-blue);
        }

        .old-price {
            text-decoration: line-through;
            color: #666;
        }

        .discount {
            color: var(--neon-pink);
            font-weight: bold;
        }

        .search-box {
            background: rgba(13, 2, 33, 0.7);
            border: 1px solid var(--neon-blue);
            color: white;
        }

        .search-box:focus {
            background: rgba(13, 2, 33, 0.9);
            color: white;
            box-shadow: 0 0 10px var(--neon-blue);
        }

        .product-overlay {
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

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .quick-view {
            background: var(--neon-blue);
            color: var(--dark-bg);
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
        }

        .edit-btn, .delete-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background: rgba(5, 217, 232, 0.2);
            border: 1px solid var(--neon-blue);
        }

        .edit-btn:hover {
            background: var(--neon-blue);
            color: var(--dark-bg);
        }

        .delete-btn {
            background: rgba(255, 42, 109, 0.2);
            border: 1px solid var(--neon-pink);
        }

        .delete-btn:hover {
            background: var(--neon-pink);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Scanline Effect -->
    <div class="scanline"></div>



    <!-- Main Content -->
    <div class="container">
        <!-- Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="cyber-font neon-text-pink mb-4 glitch-effect" data-text="TECH COLLECTION">TECH COLLECTION</h3>
                <p class="neon-text-blue">Browse our cutting-edge electronic devices</p>
                <div class="d-flex flex-wrap">
                    <button class="btn category-btn active" data-filter="*">ALL PRODUCTS</button>
                    @foreach ($categories as $item)
                    <button class="btn category-btn" data-filter="._{{$item->id}}">{{ strtoupper($item->name) }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsGrid">
            @foreach ($products as $item)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 _{{$item->category_id}}">
                <div class="product-card p-3">
                    <div class="product-image mb-3">
                        <a href="/single-product/{{$item->id}}">
                            <img style="max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid var(--cyber-primary);
            object-fit: cover;
            " src="{{asset($item->imagepath)}}" alt="{{$item->name}}">
                            <div class="product-overlay">
                                <div class="quick-view">
                                    <i class="fas fa-eye"></i> QUICK VIEW
                                </div>
                            </div>
                        </a>
                    </div>
                    <h5 class="neon-text-blue">{{$item->name}}</h5>
                    <div class="d-flex align-items-center mb-3">
                        <span class="price me-2">{{number_format($item->price, 2)}} Dh</span>
                        @if($item->old_price && $item->price < $item->old_price)
                        <span class="old-price me-2">${{number_format($item->old_price, 2)}}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-cube me-2 neon-text-blue"></i>
                        <span  style="color: grey">{{$item->quantity}} IN STOCK</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/addproducttocart/{{ $item->id }}" class="btn btn-cyber btn-sm">
                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                        </a>
                        @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'salesman'))
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

    <!-- Footer -->


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Isotope JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Isotope
            const grid = document.getElementById('productsGrid');
            if (grid) {
                const iso = new Isotope(grid, {
                    itemSelector: '.col-lg-3',
                    layoutMode: 'fitRows'
                });

                // Filter items on button click
                document.querySelectorAll('.category-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove active class from all buttons
                        document.querySelectorAll('.category-btn').forEach(btn => {
                            btn.classList.remove('active');
                        });

                        // Add active class to clicked button
                        this.classList.add('active');

                        // Filter items
                        const filterValue = this.getAttribute('data-filter');
                        iso.arrange({ filter: filterValue });
                    });
                });
            }

            // Glitch effect on hover for cyberpunk elements
            document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
                el.addEventListener('mouseenter', function() {
                    this.classList.add('glitch-effect');
                });

                el.addEventListener('mouseleave', function() {
                    this.classList.remove('glitch-effect');
                });
            });
        });
    </script>
</body>
</html>
@endsection
