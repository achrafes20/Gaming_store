<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@extends('Layouts.master')
@section('content')
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
                                        <a href="/single-product/{{ $item->id }}">
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

                                        <form action="{{ url('/removeproduct/' . $item->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="cyber-btn danger" title="Delete" style="cursor: pointer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>


                                        <a href="/editproduct/{{ $item->id }}" class="cyber-btn success" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="/AddProductImages/{{ $item->id }}" class="cyber-btn dark"
                                            title="Add Images">
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


    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/producttable.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/producttable.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
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
    @endpush
@endsection
