@extends('Layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NeonCore - Cyberpunk Tech Store</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    </head>

    <body>

        <div class="scanline"></div>





        <div class="container">
            <div class="section-title">
                <h3 class="cyber-font neon-text-pink">OUR <span class="neon-text-blue">PRODUCTS</span></h3>

            </div>

            <div class="row" id="productsGrid">
                @foreach ($products as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 _{{ $item->category_id }}">
                        <div class="product-card p-3" >
                            <div class="product-image mb-3" >
                                <a href="/single-product/{{ $item->id }}">
                                    <img style="max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid var(--cyber-primary);
            object-fit: cover;"
                                        src="{{ asset($item->imagepath) }}" alt="{{ $item->name }}">
                                    <div class="product-overlay">
                                        <div class="quick-view">
                                            <i class="fas fa-eye"></i> QUICK VIEW
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <h5 class="neon-text-blue">{{ $item->name }}</h5>
                            <div class="d-flex align-items-center mb-3">
                                <span class="price me-2">{{ number_format($item->price, 2) }} Dh</span>
                                @if ($item->old_price && $item->price < $item->old_price)
                                    <span class="old-price me-2">${{ number_format($item->old_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-cube me-2 neon-text-blue"></i>
                                <span style="color: grey">{{ $item->quantity }} IN STOCK</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($item->quantity == 0)
                                    <a class="btn btn-cyber btn-sm">
                                        <i class="fas "></i> OUT OF STOCK
                                    </a>
                                @else
                                    <a href="/addproducttocart/{{ $item->id }}" class="btn btn-cyber btn-sm">
                                        <i class="fas fa-shopping-cart"></i> ADD TO CART
                                    </a>
                                @endif
  <form action="{{ route('favorites.toggle', $item->id) }}" method="POST">
    @csrf
    @auth
        <button type="submit"
            class="favorite-btn {{ auth()->user()->favorites()->where('product_id', $item->id)->exists() ? 'active' : '' }}">
            <i class="{{ auth()->user()->favorites()->where('product_id', $item->id)->exists() ? 'fas fa-heart' : 'far fa-heart' }}"></i>
        </button>
    @else
        <a href="{{ route('login') }}" class="favorite-btn">
            <i class="far fa-heart"></i>
        </a>
    @endauth
</form>






                                @if (Auth::check() && (Auth::user()->role == 'admin' ))
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


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


        <script src="{{ asset('assets/js/product.js') }}"></script>
    </body>

    </html>
@endsection
