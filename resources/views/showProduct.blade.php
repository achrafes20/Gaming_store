@extends('Layouts.master')
@section('content')
    <!-- single product -->
    <div class="single-product mt-150 mb-150">
        <div class="container">
            <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">Product</span> Details</h3>
                </div>
            </div>
        </div>
            <div class="row">
                @if($product->ProductPhotos->count()>1)
                <div class="col-md-6">
                    <div class="testimonial-sliders">
                        @foreach ($product->ProductPhotos as $item)
                            <div class="single-testimonial-slider">
                                <div class="client-avater">
                                    <img style="width:50%; height:500px; max-width:none; border:5px black; border-radius:5px 100px !important;"
                                        src="{{ asset($item->imagepath) }}" alt="">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="col-md-3">
                    <div class="single-product-img">
                        <img src="{{ asset($product->imagepath) }}" alt="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="single-product-content">
                        <h3>{{ $product->name }}</h3>
                        <p class="single-product-pricing"><span>Per Kg</span> {{ $product->price }}</p>
                        <p>{{ $product->description }}</p>
                        <div class="single-product-form">
                            <form action="index.html">
                                <input type="number" placeholder="0">
                            </form>
                            <a href="/addproducttocart/{{ $product->id }}" class="cart-btn"><i
                                    class="fas fa-shopping-cart"></i> Add to Cart</a>
                            <p><strong>Category: </strong>{{ $product->Category->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end single product -->
    <!-- more products -->
  <!-- more products -->
<div class="more-products mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">Related</span> Products</h3>
                </div>
            </div>
        </div>

        <!-- Produits liés -->
        <div class="row">
            @foreach ($relatedProducts as $item)
                <div class="col-lg-4 col-md-6 text-center mb-4">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="/single-product/{{ $item->id }}">
                                <img src="{{ asset($item->imagepath) }}" alt=""
                                    style="max-height: 250px; max-width: 250px;">
                            </a>
                        </div>
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->description }}</p>
                        <a href="/addproducttocart/{{ $item->id }}" class="cart-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart</a>
                    </div>
                </div>
            @endforeach
        </div>a
    </div>
</div>
<!-- end more products -->
@endsection
