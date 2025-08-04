@extends('Layouts.master')
@section('content')
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Fresh and Organic</p>
                        <h1>Check Out Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- check out section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-accordion-wrap">
                        <div class="accordion" id="accordionExample">
                            <div class="card single-accordion">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Billing Address
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="billing-address-form">
                                            <form action="/StoreOrder" method="POST" id="store-order" name="store-order">
                                                @csrf
                                                <p><input type="text" required placeholder="Name" id="name"
                                                        name="name"></p>
                                                <p><input type="email" required placeholder="Email" id="email"
                                                        name="email"></p>
                                                <p><input type="text" required placeholder="Address" id="address"
                                                        name="address"></p>
                                                <p><input type="tel" required placeholder="Phone" id="phone"
                                                        name="phone"></p>
                                                <p>
                                                    <textarea name="note" id="note" cols="30" rows="10" placeholder="Say Something"></textarea>
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card single-accordion">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Card Details
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="card-details">
                                            <div class="cart-section mt-10 mb-10">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-12">
                                                            <div class="cart-table-wrap">
                                                                <table class="cart-table">
                                                                    <thead class="cart-table-head">
                                                                        <tr class="table-head-row">
                                                                            <th class="product-remove"></th>
                                                                            <th class="product-image">Product Image</th>
                                                                            <th class="product-name">Name</th>
                                                                            <th class="product-price">Price</th>
                                                                            <th class="product-quantity">Quantity</th>
                                                                            <th class="product-total">Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($cartProducts as $item)
                                                                            <tr class="table-body-row">
                                                                                <td class="product-remove"><a
                                                                                        href="/deletecartitem/{{ $item->id }}"><i
                                                                                            class="far fa-window-close"></i></a>
                                                                                </td>
                                                                                <td class="product-image"><img
                                                                                        src="{{ asset($item->product->imagepath) }}"
                                                                                        alt=""></td>
                                                                                <td class="product-name">
                                                                                    <a
                                                                                        href="/single-product/{{ $item->product->id }}">
                                                                                        {{ $item->product->name }}
                                                                                </td>
                                                                                <td class="product-price">
                                                                                    {{ $item->product->price }}</td>
                                                                                <td class="product-quantity">
                                                                                    {{ $item->quantity }}</td>
                                                                                <td class="product-total">
                                                                                    {{ number_format($item->quantity * $item->product->price, 2) }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <div class="total-section">
                                                                <table class="total-table">
                                                                    <thead class="total-table-head">
                                                                        <tr class="table-total-row">
                                                                            <th>Total</th>
                                                                            <th>Price</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="total-data">
                                                                            <td><strong>Total: </strong></td>
                                                                            <td>
                                                                                {{ $cartProducts->sum(function ($item) {
                                                                                    return $item->product->price * $item->quantity;
                                                                                }) }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </div>

                                                            <!-- <div class="coupon-section">
                      <h3>Apply Coupon</h3>
                      <div class="coupon-form-wrap">
                       <form action="index.html">
                        <p><input type="text" placeholder="Coupon"></p>
                        <p><input type="submit" value="Apply"></p>
                       </form>
                      </div>
                     </div>
                                    -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-10">
                    <div class="cart-buttons">
                        <a onclick="event.preventDefault(); document.getElementById('store-order').submit();"
                        class="boxed-btn black">Place Order</a><!-- on a fait ca car on a pas poser cette bouton dans form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end check out section -->
@endsection
