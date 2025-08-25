@extends('Layouts.master')
@section('content')
    <div class="scanline"></div>




    <div class="cyber-slider">

        <div class="cyber-slide active" style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">
            <div class="cyber-slide-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="cyber-hero-content">
                            <div class="cyber-glitch" data-text="NEW GENERATION">NEW GENERATION</div>
                            <h1 class="cyber-title">ULTIMATE TECH <span class="cyber-accent">COLLECTION</span></h1>
                            <p class="cyber-subtitle">Experience the future of technology today with our cutting-edge
                                devices</p>
                            <div class="cyber-buttons">
                                <a href="/categories" class="btn-cyber-primary">
                                    <span>EXPLORE PRODUCTS</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="/reviews" class="btn-cyber-secondary">
                                    <span style="margin-right: 7px">CONTACT US</span>
                                    <i class="fas fa-comment-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="cyber-slide" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="cyber-slide-overlay"></div>
            <div class="container text-center">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cyber-hero-content">
                            <div class="cyber-glitch" data-text="INNOVATION">INNOVATION</div>
                            <h1 class="cyber-title">PREMIUM <span class="cyber-accent">ELECTRONICS</span></h1>
                            <p class="cyber-subtitle">Discover the latest tech innovations with our 100% authentic
                                collection</p>
                            <div class="cyber-buttons justify-content-center">
                                <a href="/categories" class="btn-cyber-primary">
                                    <span>SHOP NOW</span>
                                    <i class="fas fa-shopping-bag"></i>
                                </a>
                                <a href="/reviews" class="btn-cyber-secondary">
                                    <span style="margin-right: 7px">GET SUPPORT</span>
                                    <i class="fas fa-headset"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="cyber-slide" style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 50%, #2c3e50 100%);">
            <div class="cyber-slide-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 offset-lg-5 text-right">
                        <div class="cyber-hero-content">
                            <div class="cyber-glitch" data-text="EXCLUSIVE">EXCLUSIVE</div>
                            <h1 class="cyber-title">MEGA <span class="cyber-accent">DISCOUNTS</span></h1>
                            <p class="cyber-subtitle">Limited time offers on our most advanced tech products</p>
                            <div class="cyber-buttons justify-content-end">
                                <a href="/categories" class="btn-cyber-primary">
                                    <span>VIEW DEALS</span>
                                    <i class="fas fa-tag"></i>
                                </a>
                                <a href="/reviews" class="btn-cyber-secondary">
                                    <span style="margin-right: 7px">CONTACT US</span>
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="cyber-features-section">
        <div class="container">
            <div class="cyber-features-grid">
                <div class="cyber-feature-card" data-aos="fade-up">
                    <div class="cyber-feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="cyber-feature-content">
                        <h3>LIGHTNING DELIVERY</h3>
                        <p>Same-day shipping on all orders</p>
                    </div>
                    <div class="cyber-feature-pulse"></div>
                </div>

                <div class="cyber-feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="cyber-feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="cyber-feature-content">
                        <h3>24/7 TECH SUPPORT</h3>
                        <p>Expert assistance around the clock</p>
                    </div>
                    <div class="cyber-feature-pulse"></div>
                </div>

                <div class="cyber-feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="cyber-feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="cyber-feature-content">
                        <h3>HASSLE-FREE RETURNS</h3>
                        <p>30-day money back guarantee</p>
                    </div>
                    <div class="cyber-feature-pulse"></div>
                </div>
            </div>
        </div>
    </div>



    <div class="cyber-products-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-section-header" data-aos="fade-up">
                        <div class="cyber-section-glitch" data-text="OUR COLLECTION" style="text-align: center">OUR
                            COLLECTION</div>
                        <h2 style="text-align: center">FEATURED <span class="cyber-accent">CATEGORIES</span></h2>
                        <p>Explore our cutting-edge technology categories and discover the future </p>
                    </div>
                </div>
            </div>

            <div class="cyber-products-grid">
                @foreach ($categories as $item)
                    <div class="cyber-product-card" data-aos="fade-up">
                        <div class="cyber-product-image">
                            <a href="/product/{{ $item->id }}">
                                <img src="{{ url($item->imagepath) }}" alt="{{ $item->name }}"
                                    class="cyber-product-hover">
                                <div class="cyber-product-overlay">
                                    <div class="cyber-product-badge">EXPLORE</div>
                                </div>
                            </a>
                        </div>
                        <div class="cyber-product-info">
                            <h3>{{ $item->name }}</h3>
                            <p>{{ Str::limit($item->description, 100) }}</p>
                            <a href="/product/{{ $item->id }}" class="btn-cyber-primary w-100">VIEW PRODUCTS</a>

                            @if (Auth::check() && (Auth::user() && Auth::user()->role == 'admin'))
                                <form action="{{ url('/removecategory/' . $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="cyber-delete-btn mt-2" style="cursor: pointer;margin-top:5px"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i> DELETE CATEGORY
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="cyber-product-glow"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="{{ asset('assets/js/welcome.js') }}"></script>
@endpush
