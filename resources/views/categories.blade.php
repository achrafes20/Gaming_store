@extends("Layouts.master")
@section('content')
<!-- Cyber Products Section -->
<div class="cyber-products-section">
    <div class="container">
        <!-- Cyber Product Filters -->
        <div class="cyber-filters">
            <div class="cyber-filter-header">
                <h2 class="cyber-glitch" data-text="TECH COLLECTION">TECH COLLECTION</h2>
                <p>Browse our cutting-edge electronic devices</p>
            </div>

            <div class="cyber-filter-tabs">
                <div class="cyber-filter-scroll">
                    <ul>
                        <li class="cyber-filter-active" data-filter="*">ALL PRODUCTS</li>
                        @foreach ($categories as $item)
                        <li data-filter="._{{$item->id}}">{{ strtoupper($item->name) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cyber Products Grid -->
        <div class="cyber-products-grid" >
            @foreach ($products as $item)
            <div class="cyber-product-card _{{$item->category_id}}" data-aos="fade-up">


                <div class="cyber-product-image">
                    <a href="/single-product/{{$item->id}}">
                        <img src="{{asset($item->imagepath)}}" alt="{{$item->name}}" class="cyber-hover-glow">
                        <div class="cyber-product-overlay">
                            <div class="cyber-product-view">
                                <i class="fas fa-eye"></i> QUICK VIEW
                            </div>
                        </div>
                    </a>
                </div>

                <div class="cyber-product-info">
                    <h3>{{$item->name}}</h3>
                    <div class="cyber-product-meta">
                        <div class="cyber-product-price">
                            <span class="cyber-currency">$</span>{{number_format($item->price, 2)}}
                        </div>
                        <div class="cyber-product-stock">
                            <i class="fas fa-cube"></i> {{$item->quantity}} IN STOCK
                        </div>
                    </div>

                    <div class="cyber-product-actions">
                        <a href="/addproducttocart/{{ $item->id }}" class="cyber-cart-btn">
                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                        </a>

                        @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'salesman'))
                        <div class="cyber-admin-actions">
                            <a href="/editproduct/{{ $item->id }}" class="cyber-edit-btn">
                                <i class="fas fa-cog"></i>
                            </a>
                            <a href="/removeproduct/{{ $item->id }}" class="cyber-delete-btn">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="cyber-product-glow"></div>
            </div>
            @endforeach
        </div>



    </div>
</div>

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

    /* Cyber Products Section */
    .cyber-products-section {
        padding: 100px 0;
        background: var(--cyber-darker);
        position: relative;
    }

    /* Cyber Filters */
    .cyber-filters {
        margin-bottom: 60px;
    }

    .cyber-filter-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .cyber-filter-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: var(--cyber-primary);
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    .cyber-filter-header p {
        color: rgba(224, 224, 255, 0.7);
        font-size: 1.1rem;
    }

    .cyber-filter-tabs {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -20px;
        padding: 0 20px;
    }

    .cyber-filter-scroll {
        display: inline-block;
        white-space: nowrap;
    }

    .cyber-filter-tabs ul {
        list-style: none;
        display: inline-flex;
        gap: 15px;
        padding: 5px 0;
    }

    .cyber-filter-tabs li {
        display: inline-block;
        padding: 10px 25px;
        background: rgba(0, 240, 255, 0.1);
        color: var(--cyber-light);
        border-radius: 30px;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.8rem;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .cyber-filter-tabs li:hover {
        background: rgba(0, 240, 255, 0.3);
    }

    .cyber-filter-active {
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent)) !important;
        color: var(--cyber-dark) !important;
        font-weight: bold;
    }

    /* Cyber Products Grid */
    .cyber-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .cyber-product-card {
        background: var(--cyber-card-bg);
        border: 1px solid rgba(0, 240, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .cyber-product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);
        border-color: var(--cyber-primary);
    }

    .cyber-product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--cyber-accent);
        color: var(--cyber-dark);
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: bold;
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cyber-product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .cyber-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: all 0.5s ease;
    }

    .cyber-hover-glow:hover {
        transform: scale(1.05);
        filter: brightness(1.1) saturate(1.1);
    }

    .cyber-product-overlay {
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

    .cyber-product-card:hover .cyber-product-overlay {
        opacity: 1;
    }

    .cyber-product-view {
        background: var(--cyber-primary);
        color: var(--cyber-dark);
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cyber-product-info {
        padding: 20px;
    }

    .cyber-product-info h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-light);
        margin-bottom: 15px;
        font-size: 1.2rem;
    }

    .cyber-product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .cyber-product-price {
        font-family: 'Orbitron', sans-serif;
        color: var(--cyber-primary);
        font-size: 1.3rem;
        font-weight: bold;
    }

    .cyber-currency {
        font-size: 0.9rem;
        margin-right: 2px;
    }

    .cyber-product-stock {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        color: rgba(224, 224, 255, 0.7);
    }

    .cyber-product-stock i {
        color: var(--cyber-accent);
    }

    .cyber-product-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cyber-cart-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent));
        color: var(--cyber-dark);
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .cyber-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3);
    }

    .cyber-admin-actions {
        display: flex;
        gap: 10px;
    }

    .cyber-edit-btn, .cyber-delete-btn {
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

    .cyber-edit-btn {
        background: rgba(0, 217, 255, 0.2);
        border: 1px solid rgb(0, 217, 255);
    }

    .cyber-edit-btn:hover {
        background: rgb(0, 217, 255);
    }

    .cyber-delete-btn {
        background: rgba(255, 0, 0, 0.2);
        border: 1px solid var(--cyber-error);
    }

    .cyber-delete-btn:hover {
        background: var(--cyber-error);
    }

    .cyber-product-glow {
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

    .cyber-product-card:hover .cyber-product-glow {
        opacity: 1;
    }

    /* Cyber Pagination */
    .cyber-pagination {
        display: flex;
        justify-content: center;
    }

    .cyber-pagination ul {
        display: flex;
        list-style: none;
        gap: 10px;
    }

    .cyber-pagination li a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(0, 240, 255, 0.1);
        color: var(--cyber-light);
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cyber-pagination li a:hover {
        background: rgba(0, 240, 255, 0.3);
    }

    .cyber-pagination-active {
        background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent)) !important;
        color: var(--cyber-dark) !important;
        font-weight: bold;
    }

    /* Cyber Glitch Effect */
    .cyber-glitch {
        position: relative;
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

    /* Responsive Styles */
    @media (max-width: 992px) {
        .cyber-products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cyber-filter-header h2 {
            font-size: 2rem;
        }

        .cyber-product-info h3 {
            font-size: 1.1rem;
        }

        .cyber-product-price {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 576px) {
        .cyber-products-grid {
            grid-template-columns: 1fr;
        }

        .cyber-filter-tabs li {
            padding: 8px 15px;
            font-size: 0.7rem;
        }

        .cyber-product-actions {
            flex-direction: column;
            gap: 10px;
        }

        .cyber-cart-btn {
            width: 100%;
            justify-content: center;
        }

        .cyber-admin-actions {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Isotope for filtering
        const grid = document.querySelector('.cyber-products-grid');
        if (grid) {
            const iso = new Isotope(grid, {
                itemSelector: '.cyber-product-card',
                layoutMode: 'fitRows'
            });

            // Filter items on button click
            const filterButtons = document.querySelectorAll('.cyber-filter-tabs li');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('cyber-filter-active'));

                    // Add active class to clicked button
                    this.classList.add('cyber-filter-active');

                    // Filter items
                    const filterValue = this.getAttribute('data-filter');
                    iso.arrange({ filter: filterValue });
                });
            });
        }

        // Product card hover effect
        const productCards = document.querySelectorAll('.cyber-product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = 'scale(1.05)';
                    img.style.filter = 'brightness(1.1) saturate(1.1)';
                }
            });

            card.addEventListener('mouseleave', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = '';
                    img.style.filter = '';
                }
            });
        });
    });
</script>
@endpush
@endsection
