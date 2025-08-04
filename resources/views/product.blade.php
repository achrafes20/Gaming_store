@extends('Layouts.master')
@section('content')
    <!-- product section -->
    <div class="product-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="section-title">
                        <h3><span class="orange-text">Our</span> Products</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet
                            beatae optio.</p>
                    </div>
                </div>
            </div>
            <div class="row">


                @foreach ($products as $item)
                    <div class="col-lg-4 col-md-6 text-center">
                        <div class="single-product-item">
                            <div class="product-image">
                                <a href="/single-product/{{ $item->id }}"><!-- ou tu peux faire  <a href="/product/{{ $item->id }}"> -->
                                    <img src="{{ asset($item->imagepath) }}" alt=""
                                        style="max-height: 250px !important ; max-width: 250px !important;"></a>
                            </div>
                            <h3>{{ session('locale') == 'en' ?  $item->name  :  $item->nameAr  }}</h3>
                            <p> {{ $item->description }}</p>
                            <a href="/addproducttocart/{{ $item->id }}" class="cart-btn">
                                <i class="fas fa-shopping-cart"></i> Add to Cart</a>


                            @if(Auth::check() && (Auth::user() && Auth::user()->role == 'admin' ||Auth::user()->role == 'salesman'))
                            <p class="mt-3">
                                <a href="/removeproduct/{{ $item->id }}" class="cart-btn"
                                    style="background-color: red"><i class="fas fa-trash"></i> Delete Product</a>
                                <a href="/editproduct/{{ $item->id }}" class="cart-btn"
                                    style="background-color: rgb(0, 217, 255)"><i class="fas fa-cog"></i> Edit Product</a>
                            </p>
                            @endif
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
        <!-- end product section -->
    @endsection
