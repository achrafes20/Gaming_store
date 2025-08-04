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

                            @foreach ($orders as $item)
                                <div class="card single-accordion">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Order Number {{ $item->id }}
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="billing-address-form">
                                                <form>
                                                     <p><input type="tel" value="Created at : {{ $item->created_at }}" required
                                                            placeholder="Phone" id="phone" name="phone" readonly></p>
                                                    <p><input type="text" value="{{ $item->name }}" required
                                                            placeholder="Name" id="name" name="name" readonly></p>
                                                    <p><input type="email" value="{{ $item->email }}" required
                                                            placeholder="Email" id="email" name="email" readonly></p>
                                                    <p><input type="text" value="{{ $item->address }}" required
                                                            placeholder="Address" id="address" name="address" readonly></p>
                                                    <p><input type="tel" value="{{ $item->phone }}" required
                                                            placeholder="Phone" id="phone" name="phone" readonly></p>

                                                    <p>
                                                        <textarea name="note" id="note" cols="30" rows="10" placeholder="Say Something" readonly>{{ $item->note }}</textarea>
                                                    </p>
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-12">
                                                            <div class="cart-table-wrap">
                                                                <table class="cart-table">
                                                                    <thead class="cart-table-head">
                                                                        <tr class="table-head-row">

                                                                            <th class="product-image">Product Image</th>
                                                                            <th class="product-name">Name</th>
                                                                            <th class="product-price">Price</th>
                                                                            <th class="product-quantity">Quantity</th>
                                                                            <th class="product-total">Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($item->orderdetails as $detail)
                                                                            <tr class="table-body-row">
                                                                                <td class="product-image"><img
                                                                                        src="{{ asset($detail->product->imagepath) }}"
                                                                                        alt=""></td>
                                                                                <td class="product-name">
                                                                                    <a
                                                                                        href="/single-product/{{ $detail->product->id }}">
                                                                                        {{ $detail->product->name }}
                                                                                </td>
                                                                                <td class="product-price">
                                                                                    {{ $detail->product->price }}</td>
                                                                                <td class="product-quantity">
                                                                                    {{ $detail->quantity }}</td>
                                                                                <td class="product-total">
                                                                                    {{ number_format($detail->quantity * $detail->product->price, 2) }}
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
                                                                                {{ $item->orderdetails->sum(function ($x) {
                                                                                    return $x->product->price * $x->quantity;
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
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end check out section -->
@endsection
