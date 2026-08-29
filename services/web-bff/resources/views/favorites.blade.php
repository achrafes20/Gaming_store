@extends('Layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NeonGrid - Cyberpunk Tech Store</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    </head>

    <body>
        <div class="scanline"></div>
        <section class="page-title-section mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="cyber-font ">
                            <span class="neon-text-pink">FAVORITES</span>
                            <span class="neon-text-blue">COLLECTION</span>
                        </h1>
                        <p class="mt-3 neon-text-blue">Your personally curated tech selection from the future</p>
                        <div class="title-underline">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <div class="container">
            <div class="row" id="productsGrid">
                @foreach ($favorites as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 _{{ $item->product->category_id }}">
                        <div class="product-card p-3" style="height: 470px;">
                            <div class="product-image mb-1">
                                <a href="/single-product/{{ $item->product->id }}">
                                    <img style="max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid var(--cyber-primary);
            object-fit: cover;"
                                        src="{{ asset($item->product->imagepath) }}" alt="{{ $item->product->name }}">
                                    <div class="product-overlay">
                                        <div class="quick-view">
                                            <i class="fas fa-eye"></i> QUICK VIEW
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <h5 class="neon-text-blue">{{ \Illuminate\Support\Str::limit($item->product->name, 60) }}</h5>

                            <div class="d-flex align-items-center mb-3">
                                <span class="price me-2">{{ number_format($item->product->price, 2) }} Dh</span>
                                @if ($item->product->old_price && $item->product->price < $item->product->old_price)
                                    <span class="old-price me-2">${{ number_format($item->product->old_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-cube me-2 neon-text-blue"></i>
                                <span style="color: grey">{{ $item->product->quantity }} IN STOCK</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($item->product->quantity == 0)
                                    <a class="btn btn-cyber btn-sm">
                                        <i class="fas "></i> OUT OF STOCK
                                    </a>
                                @else
                                    <form action="{{ url('/addproducttocart/' . $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-cyber btn-sm">
                                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('favorites.toggle', $item->product->id) }}" method="POST">
                                    @csrf
                                    @auth
                                        <button type="submit"
                                            class="favorite-btn {{ auth()->user()->favorites()->where('product_id', $item->product->id)->exists() ? 'active' : '' }}">
                                            <i
                                                class="{{ auth()->user()->favorites()->where('product_id', $item->product->id)->exists() ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="favorite-btn">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @endauth
                                </form>
                                @if (Auth::check() && Auth::user()->role == 'admin')
                                    <div class="admin-actions">
                                        <a href="/editproduct/{{ $item->product->id }}" class="edit-btn">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <form action="{{ url('/removeproduct/' . $item->product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
        <script src="{{ asset('assets/js/categories.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('success'))
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
    </body>

    </html>
@endsection
