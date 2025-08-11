<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@extends('Layouts.master')
@section('content')
    <!-- Cyberpunk Hero Section -->
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">PRODUCT <span class="cyber-accent">DATABASE</span></h1>
                        <p class="cyber-subtitle">Manage your inventory in real-time</p>
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

    <!-- Cyberpunk Dashboard Section -->
    <div class="cyber-dashboard-section">
        <div class="container">
            <div class="cyber-action-buttons">
                <a href="/addproduct" class="cyber-action-btn add-product">
                    <i class="fas fa-plus-circle"></i>
                    <span>ADD PRODUCT</span>
                    <div class="cyber-btn-hover"></div>
                </a>
                <a href="/categoryadmin" class="cyber-action-btn add-category">
                    <i class="fas fa-layer-group"></i>
                    <span>CATEGORY</span>
                    <div class="cyber-btn-hover"></div>
                </a>
                <a href="/coupons" class="cyber-action-btn add-category">
                    <i class="fas fa-layer-group"></i>
                    <span>COUPON</span>
                    <div class="cyber-btn-hover"></div>
                </a>
                <a href="/users" class="cyber-action-btn add-category">
                    <i class="fas fa-layer-group"></i>
                    <span>USERS</span>
                    <div class="cyber-btn-hover"></div>
                </a>
                <a href="/orders" class="cyber-action-btn add-category">
                    <i class="fas fa-layer-group"></i>
                    <span>ORDERS</span>
                    <div class="cyber-btn-hover"></div>
                </a>
            </div>

            <!-- Table -->
            <div class="cyber-table-container">
                <table id="myTable" class="cyber-table display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>PRODUCT NAME</th>
                            <th>PRICE</th>
                            <th>QUANTITY</th>
                            <th>IMAGE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>
                                    <span class="cyber-quantity {{ $item->quantity < 10 ? 'low-stock' : '' }}">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cyber-product-img">
                                         <a href="/single-product/{{$item->id}}">
                                        <img src='{{ asset($item->imagepath) }}' alt="{{ $item->name }}">

                                        <div class="product-overlay">
                                <div class="quick-view">
                                    <i class="fas fa-eye"></i> QUICK VIEW
                                </div>
                            </div>
                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="cyber-action-btns">
                                        <a href="/removeproduct/{{ $item->id }}" class="cyber-btn danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="/editproduct/{{ $item->id }}" class="cyber-btn success" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="/AddProductImages/{{ $item->id }}" class="cyber-btn dark" title="Add Images">
                                            <i class="fas fa-images"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            --cyber-table-bg: rgba(20, 20, 40, 0.6);
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
        .product-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: flex; align-items: center; justify-content: center; opacity: 0; transition: all 0.3s ease; }
        .quick-view { background: var(--neon-blue); color: var(--dark-bg); padding: 8px 15px; border-radius: 30px; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; }

        .cyber-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 L0,100 Z" fill="none" stroke="rgba(0,240,255,0.1)" stroke-width="0.5" stroke-dasharray="5,5"/></svg>');
            opacity: 0.5;
        }

        .cyber-hero-text { position: relative; z-index: 2; text-align: center; padding: 20px; }

        .cyber-title {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--cyber-light), var(--cyber-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .cyber-title .cyber-accent { background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-secondary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .cyber-subtitle { color: var(--cyber-primary); font-size: 1.2rem; letter-spacing: 3px; }

        .cyber-pulse-animation { position: relative; height: 100px; display: flex; justify-content: center; align-items: center; }
        .pulse-circle { position: absolute; width: 20px; height: 20px; border-radius: 50%; background-color: var(--cyber-accent); opacity: 0; animation: pulse 3s infinite; }
        .pulse-circle.delay-1 { animation-delay: 1s; }
        .pulse-circle.delay-2 { animation-delay: 2s; }
        @keyframes pulse { 0% { transform: scale(0.8); opacity: 0.8; } 100% { transform: scale(10); opacity: 0; } }

        /* Dashboard Section */
        .cyber-dashboard-section { padding: 50px 0 100px; position: relative; }
        .cyber-action-buttons { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .cyber-action-btn { position: relative; display: flex; align-items: center; padding: 15px 25px; border-radius: 5px; text-decoration: none; overflow: hidden; transition: all 0.3s ease; z-index: 1; }
        .cyber-action-btn i { margin-right: 10px; font-size: 1.2rem; }
        .cyber-action-btn span { position: relative; z-index: 2; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .cyber-btn-hover { position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); transition: all 0.5s ease; z-index: 1; }
        .cyber-action-btn:hover .cyber-btn-hover { left: 100%; }
        .add-product { background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary)); color: var(--cyber-dark); box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3); }
        .add-category { background: linear-gradient(90deg, var(--cyber-primary), var(--cyber-secondary)); color: var(--cyber-dark); box-shadow: 0 5px 15px rgba(0, 240, 255, 0.3); }
        .cyber-action-btn:hover { transform: translateY(-3px); }

        /* Cyber Table */
        .cyber-table-container { background: var(--cyber-table-bg); border: 1px solid var(--cyber-border); border-radius: 10px; padding: 20px; box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1); overflow: hidden; }
        .cyber-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .cyber-table thead th { background: rgba(0, 240, 255, 0.1); color: var(--cyber-primary); padding: 15px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; border: none; }
        .cyber-table tbody tr { background: rgba(20, 20, 40, 0.5); transition: all 0.3s ease; }
        .cyber-table tbody tr:hover { background: rgba(0, 240, 255, 0.05); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 240, 255, 0.1); }
        .cyber-table tbody td { padding: 15px; vertical-align: middle; border: none; border-top: 1px solid var(--cyber-border); border-bottom: 1px solid var(--cyber-border); }
        .cyber-table tbody td:first-child { border-left: 1px solid var(--cyber-border); border-radius: 5px 0 0 5px; }
        .cyber-table tbody td:last-child { border-right: 1px solid var(--cyber-border); border-radius: 0 5px 5px 0; }

        .cyber-quantity { display: inline-block; padding: 5px 10px; border-radius: 20px; background: rgba(0, 240, 255, 0.1); font-weight: bold; }
        .low-stock { background: rgba(255, 0, 60, 0.2); color: var(--cyber-danger); animation: pulseWarning 1.5s infinite; }
        @keyframes pulseWarning { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

        .cyber-product-img { width: 80px; height: 80px; border-radius: 5px; overflow: hidden; position: relative; }
        .cyber-product-img img { width: 100%; height: 100%; object-fit: cover; transition: all 0.3s ease; }
        .cyber-img-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 240, 255, 0.05); transition: all 0.3s ease; }
        .cyber-table tbody tr:hover .cyber-product-img img { transform: scale(1.1); }
        .cyber-table tbody tr:hover .cyber-img-overlay { background: rgba(0, 240, 255, 0.2); }

        .cyber-action-btns { display: flex; gap: 10px; }
        .cyber-btn { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .cyber-btn::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); transition: all 0.5s ease; }
        .cyber-btn:hover::before { left: 100%; }
        .cyber-btn i { position: relative; z-index: 2; }
        .danger { background: var(--cyber-danger); color: white; }
        .success { background: var(--cyber-accent); color: var(--cyber-dark); }
        .dark { background: var(--cyber-dark); color: var(--cyber-light); border: 1px solid var(--cyber-border); }
        .cyber-btn:hover { transform: translateY(-3px) scale(1.1); }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: var(--cyber-light) !important; border: 1px solid var(--cyber-border) !important; background: var(--cyber-table-bg) !important; margin: 0 5px; border-radius: 5px !important; transition: all 0.3s ease !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: linear-gradient(90deg, var(--cyber-primary), transparent) !important; color: white !important; border: 1px solid var(--cyber-primary) !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(90deg, var(--cyber-accent), var(--cyber-primary)) !important; color: var(--cyber-dark) !important; border: none !important; font-weight: bold; }
        .dataTables_wrapper .dataTables_filter input { background: var(--cyber-table-bg); border: 1px solid var(--cyber-border); color: var(--cyber-light); padding: 5px 10px; border-radius: 5px; }
        .dataTables_wrapper .dataTables_filter input:focus { outline: none; border-color: var(--cyber-primary); box-shadow: 0 0 10px rgba(0, 240, 255, 0.3); }
        .dataTables_wrapper .dataTables_length select { background: var(--cyber-table-bg); border: 1px solid var(--cyber-border); color: var(--cyber-light); padding: 5px; border-radius: 5px; }

        /* Floating Elements */
        .cyber-floating-elements { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1; overflow: hidden; }
        .cyber-orb { position: absolute; border-radius: 50%; filter: blur(40px); opacity: 0.2; }
        .orb-1 { width: 300px; height: 300px; background: var(--cyber-primary); top: -100px; left: -100px; animation: float 15s infinite ease-in-out; }
        .orb-2 { width: 200px; height: 200px; background: var(--cyber-secondary); bottom: -50px; right: -50px; animation: float 12s infinite ease-in-out reverse; }
        .orb-3 { width: 150px; height: 150px; background: var(--cyber-accent); top: 50%; right: 10%; animation: float 10s infinite ease-in-out 2s; }
        .cyber-circuit-line { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,240,255,0.03)" stroke-width="1"/></svg>'); opacity: 0.1; }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 20px); } }

        /* Responsive Adjustments */
        @media (max-width: 992px) { .cyber-title { font-size: 2.5rem; } .cyber-table thead th { padding: 12px; font-size: 0.9rem; } .cyber-table tbody td { padding: 12px; } }
        @media (max-width: 768px) {
            .cyber-hero-section { height: 250px; }
            .cyber-title { font-size: 2rem; }
            .cyber-action-buttons { flex-direction: column; }
            .cyber-action-btn { width: 100%; justify-content: center; }
            .cyber-table thead { display: none; }
            .cyber-table tbody tr { display: block; margin-bottom: 15px; border: 1px solid var(--cyber-border); border-radius: 5px; }
            .cyber-table tbody td { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; border: none; border-bottom: 1px solid var(--cyber-border); }
            .cyber-table tbody td:before { content: attr(data-label); font-weight: bold; color: var(--cyber-primary); margin-right: 15px; text-transform: uppercase; font-size: 0.8rem; }
            .cyber-table tbody td:last-child { border-bottom: none; }
            .cyber-action-btns { justify-content: center; }
        }
        @media (max-width: 576px) { .cyber-title { font-size: 1.8rem; } .cyber-subtitle { font-size: 1rem; } .cyber-table-container { padding: 15px; } }
    </style>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Enable default DataTables UI with built-in search
            let table = new DataTable('#myTable');
        });
    </script>
    @endpush
@endsection
