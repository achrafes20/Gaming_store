<!DOCTYPE html>
<html lang="en">

<head >
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TechSphere - Futuristic Electronics eCommerce Platform">

    <!-- title -->
    <title>NextLevelGaming | Next-Gen Electronics</title>

    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/cyber-main.css') }}">


    @stack('styles')
</head>

<body class="cyber-body">
    <!-- Cyberpunk Preloader -->
    <div class="cyber-loader">
        <div class="cyber-loader-inner">
            <div class="cyber-loader-circle"></div>
            <div class="cyber-loader-text">
                <span>N</span>
                <span>E</span>
                <span>X</span>
                <span>T</span>
                <span>L</span>
                <span>E</span>
                <span>V</span>
                <span>E</span>
                <span>L</span>
                <span>G</span>
                <span>A</span>
                <span>M</span>
                <span>I</span>
                <span>N</span>
                <span>G</span>
            </div>
            <div class="cyber-loader-progress">
                <div class="cyber-loader-bar"></div>
            </div>
        </div>
    </div>
    <!-- PreLoader Ends -->

    <!-- Cyber Header -->
    <header class="cyber-header" id="cyberHeader">
        <div class="cyber-header-container">
            <!-- Cyber Logo -->
           <div class="cyber-logo">
    <a href="/">
        <img src="{{ asset('assets/img/logo.png') }}" alt="NextLevel Logo" class="cyber-logo-img">

        <div class="cyber-logo-text">
            <div class="cyber-logo-glitch" data-text="NextLevel">NextLevel</div>
            <span class="cyber-logo-sub" style="margin-left: 160px">Gaming</span>
        </div>
    </a>
</div>




            <!-- Cyber Navigation -->
            <nav class="cyber-nav">
                <ul class="cyber-nav-list">
                    <li class="cyber-nav-item">
                        <a href="/" class="cyber-nav-link">
                            <span class="cyber-nav-icon"><i class="fas fa-home"></i></span>
                            <span class="cyber-nav-text">HOME</span>
                        </a>
                    </li>
                    <li class="cyber-nav-item">
                        <a href="/categories" class="cyber-nav-link">
                            <span class="cyber-nav-icon"><i class="fas fa-microchip"></i></span>
                            <span class="cyber-nav-text">PRODUCTS</span>
                        </a>
                    </li>
                    @if(Auth::check() && (Auth::user() && Auth::user()->role == 'admin' || Auth::user()->role == 'salesman'))
                    <li class="cyber-nav-item">
                        <a href="/ProductsTable" class="cyber-nav-link">
                            <span class="cyber-nav-icon" style="color: red"><i class="fas fa-gear"></i></span>
                            <span class="cyber-nav-text" style="color: red">Admin Page</span>
                        </a>
                    </li>
                    @endif
                    <li class="cyber-nav-item">
                        <a href="/reviews" class="cyber-nav-link">
                            <span class="cyber-nav-icon"><i class="fas fa-star"></i></span>
                            <span class="cyber-nav-text">REVIEWS</span>
                        </a>
                    </li>

                    <!-- Auth Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="cyber-nav-item">
                                <a href="{{ route('login') }}" class="cyber-nav-link">
                                    <span class="cyber-nav-icon"><i class="fas fa-sign-in-alt"></i></span>
                                    <span class="cyber-nav-text">LOGIN</span>
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="cyber-nav-item">
                                <a href="{{ route('register') }}" class="cyber-nav-link">
                                    <span class="cyber-nav-icon"><i class="fas fa-user-plus"></i></span>
                                    <span class="cyber-nav-text">REGISTER</span>
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="cyber-nav-item cyber-nav-dropdown">
                            <a href="#" class="cyber-nav-link">
                                <span class="cyber-nav-icon"><i class="fas fa-user-astronaut"></i></span>
                                <span class="cyber-nav-text">{{ strtoupper(Auth::user()->name) }}</span>

                            </a>
                            <ul class="cyber-dropdown-menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> LOGOUT
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest

                    <li class="cyber-nav-item cyber-nav-cart">
                        <a href="/cart" class="cyber-nav-link">
                            <span class="cyber-nav-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cyber-cart-badge">{{ count($cartProducts) }}</span>
                            </span>
                            <span class="cyber-nav-text">CART</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Cyber Search -->
            <div class="cyber-search">
                <button class="cyber-search-btn">
                    <i class="fas fa-search"></i>
                </button>
                <div class="cyber-search-container">
                    <form action="/search" method="post">
                        @csrf
                        <input type="text" name="searchkey" placeholder="SEARCH TECH..." class="cyber-search-input">
                        <button type="submit" class="cyber-search-submit">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    <button class="cyber-search-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="cyber-mobile-toggle">
                <span class="cyber-toggle-line"></span>
                <span class="cyber-toggle-line"></span>
                <span class="cyber-toggle-line"></span>
            </button>
        </div>
    </header>
    <!-- End Cyber Header -->

    <!-- Floating Tech Elements -->
    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    <!-- Main Content -->
    <main class="cyber-main">
        @yield('content')
    </main>

    <!-- Cyber Footer -->
    <footer class="cyber-footer">
        <div class="cyber-footer-grid">
            <!-- About Column -->
            <div class="cyber-footer-column" data-aos="fade-up">
                <h3 class="cyber-footer-title">ABOUT NEXTLEVELGAMING</h3>
                <div class="cyber-footer-logo" style="font-size: 20px">NEXTLEVELGAMING</div>
                <p class="cyber-footer-text">
                    We deliver the future today. Cutting-edge electronics for the next generation of tech enthusiasts
                    and professionals pushing the boundaries of innovation.
                </p>
                <div class="cyber-footer-social">
                    <a style="text-decoration: none;" href="https://www.facebook.com/profile.php?id=100080119152617" class="cyber-social-link"><i class="fab fa-facebook-f"></i></a>

                    <a style="text-decoration: none;" href="https://www.instagram.com/achraf_esserrar/" class="cyber-social-link"><i class="fab fa-instagram"></i></a>
                    <a style="text-decoration: none;" href="https://www.linkedin.com/in/achraf-es-serrar-300bb2279/" class="cyber-social-link"><i class="fab fa-linkedin-in"></i></a>

                </div>
            </div>

            <!-- Quick Links -->
            <div class="cyber-footer-column" data-aos="fade-up" data-aos-delay="100">
                <h3 class="cyber-footer-title">QUICK LINKS</h3>
                <ul class="cyber-footer-links">
                    <li><a href="/"><i class="fas fa-arrow-right"></i> Home</a></li>
                    <li><a href="/categories"><i class="fas fa-arrow-right"></i> Products</a></li>
                    <li><a href="/reviews"><i class="fas fa-arrow-right"></i> Reviews</a></li>
                    <li><a href="/login"><i class="fas fa-arrow-right"></i> Login</a></li>
                    <li><a href="/register"><i class="fas fa-arrow-right"></i> Register</a></li>
                    <li><a href="/cart"><i class="fas fa-arrow-right"></i> Cart</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="cyber-footer-column" data-aos="fade-up" data-aos-delay="200">
                <h3 class="cyber-footer-title">CONTACT US</h3>
                <ul class="cyber-footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Tétouan,Morocco</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>achrafes764@gmail.com</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>0620830449</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>24/7 Support</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="cyber-footer-column" data-aos="fade-up" data-aos-delay="300">
                <h3 class="cyber-footer-title">NEWSLETTER</h3>
                <p class="cyber-footer-text">
                    Subscribe to get updates on new tech releases, exclusive deals and insider access.
                </p>
                <form class="cyber-newsletter-form" method="POST" enctype="multipart/form-data" action="/sub">
                    @csrf
                    <input type="email" name="email" id="email" placeholder="Your Email" class="cyber-newsletter-input">
                    <button type="submit" class="cyber-newsletter-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="cyber-copyright">
            <div class="cyber-copyright-container">
                <p>&copy; 2025 NEXTLEVELGAMING. ALL RIGHTS RESERVED.</p>
                <div class="cyber-payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-bitcoin"></i>
                    <i class="fab fa-ethereum"></i>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Cyber Footer -->

    <!-- Back to Top -->
    <button class="cyber-back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('assets/js/cyber-main.js') }}"></script>

    @stack('scripts')
</body>
</html>
