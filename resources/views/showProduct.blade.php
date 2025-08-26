@extends('Layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $product->name }} - NeonTech</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <link href="https://fonts.cdnfonts.com/css/cyberpunk" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/showproduct.css') }}">
    </head>

    <body>

        <div class="scanline"></div>


        <div class="container">
            <div class="row">

                <div class="col-lg-6">
                    @if ($product->ProductPhotos->count() > 0)
                        <div id="productCarousel" class="carousel slide carousel-container" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="product-image mb-4 neon-border-pink p-2" style="height: 400px;">
                                        <img src="{{ asset($product->imagepath) }}" class="d-block w-100"
                                            alt="{{ $product->name }}">
                                    </div>
                                </div>
                                @foreach ($product->ProductPhotos as $index => $item)
                                    <div class="carousel-item">
                                        <div class="product-image mb-4 neon-border-pink p-2" style="height: 400px;">
                                            <img src="{{ asset($item->imagepath) }}" class="d-block w-100"
                                                alt="{{ $product->name }} variation {{ $index + 1 }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <div class="thumb-container">
                            <div class="thumb">
                                <img src="{{ asset($product->imagepath) }}" class="thumb-img active"
                                    alt="{{ $product->name }}" data-bs-target="#productCarousel" data-bs-slide-to="0">
                            </div>
                            @foreach ($product->ProductPhotos as $index => $item)
                                <div class="thumb">
                                    <img src="{{ asset($item->imagepath) }}" class="thumb-img"
                                        alt="{{ $product->name }} variation {{ $index + 1 }}"
                                        data-bs-target="#productCarousel" data-bs-slide-to="{{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="product-image mb-4 neon-border-pink p-2">
                            <img src="{{ asset($product->imagepath) }}" class="img-fluid w-100"
                                alt="{{ $product->name }}">
                        </div>
                    @endif
                </div>


                <div class="col-lg-6">
                    <h2 class="cyber-font neon-text-pink mb-3">{{ $product->name }}</h2>

                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <span class="neon-text-blue fs-4">{{ number_format($product->price, 2) }} Dh</span>
                            @if ($product->price < $product->old_price)
                                <span
                                    class="text-decoration-line-through text-muted ms-2">${{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>
                        @if ($product->price < $product->old_price)
                            <span
                                class="badge bg-danger">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="progress neon-border-blue" style="height: 5px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ rand(80, 100) }}%"></div>
                        </div>
                    </div>

                    <p class="mb-4">{{ $product->description }}</p>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small style="color: grey">{{ $product->quantity }} available</small>
                        </div>
                    </div>



                    <div class="mb-4">
                        <h5 class="neon-text-blue mb-3">CUSTOMER RATINGS</h5>

                        @if ($product->review_products->count() > 0)
                            @foreach ($product->review_products as $review)
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="rating-value">({{ $review->rating }}/5)</div>
                                </div>
                            @endforeach
                        @else
                            <p class="no-reviews">No reviews yet.</p>
                        @endif
                    </div>

                    @if ($product->quantity == 0)
                        <div class="d-grid gap-3">
                            <a class="btn btn-cyber btn-lg py-3">OUT OF STOCK</a>
                        </div>
                    @else
                        <div class="d-grid gap-3">
                            <form action="{{ url('/addproducttocart/' . $product->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-cyber btn-lg py-3">
                                    ADD TO CART
                                </button>

                            </form>

                        </div>
                    @endif

                    <div class="cyber-tech-specs mt-4 product-card p-3">
                        <h5 class="neon-text-blue mb-3"><i class="fas fa-info-circle"></i> TECHNICAL SPECIFICATIONS</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><span class="neon-text-pink">Model:</span> {{ $product->name }}</li>
                            <li class="mb-2"><span class="neon-text-pink">Category:</span> {{ $product->Category->name }}
                            </li>
                            <li class="mb-2"><span class="neon-text-pink">Warranty:</span> 2 Years</li>
                        </ul>
                    </div>
                </div>
            </div>





            <div class="scanline"></div>

            <div class="container">
                <!-- Reviews Section -->
                <div class="review-section">
                    <h3 class="section-title neon-text-pink"><i class="fas fa-comment-alt me-2"></i>CUSTOMER REVIEWS</h3>

                    {{-- Liste des reviews --}}
                    @if ($product->review_products->count() > 0)
                        @foreach ($product->review_products as $review)
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-name">{{ $review->name }}</div>
                                    <div class="review-rating">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $review->rating)
                                                <span class="stars">★</span>
                                            @else
                                                <span class="stars" style="color: #444;">★</span>
                                            @endif
                                        @endfor
                                        <span class="ms-1">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>
                                <div class="review-comment">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-reviews">
                            <i class="fas fa-comment-slash fa-3x mb-3"></i>
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endif

                    {{-- Formulaire pour poster un review --}}
                    @if ($canReview)
                        <div class="cyber-review-form">
                            <h4 class="neon-text-blue mb-4"><i class="fas fa-pen me-2"></i>WRITE YOUR REVIEW</h4>
                            <form action="{{ route('review_products.store', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="rating" class="form-label">RATING</label>
                                        <select name="rating" id="rating" class="form-control" required>
                                            <option value="">-- SELECT RATING --</option>
                                            <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                            <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                            <option value="3">⭐⭐⭐ (3/5)</option>
                                            <option value="2">⭐⭐ (2/5)</option>
                                            <option value="1">⭐ (1/5)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="form-label">YOUR REVIEW</label>
                                    <textarea name="comment" id="comment" rows="4" class="form-control"
                                        placeholder="Share your experience with this product..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-cyber"><i
                                        class="fas fa-paper-plane me-2"></i>SUBMIT REVIEW</button>
                            </form>
                        </div>
                    @elseif(auth()->check())
                        <div class="cyber-alert">
                            <i class="fas fa-info-circle me-2"></i>
                            @if ($product->review_products()->where('user_id', auth()->id())->exists())
                                You have already submitted a review for this product.
                            @else
                                You must purchase this product before writing a review.
                            @endif
                        </div>
                    @endif
                </div>
            </div>












            <br>

            <h3 class="cyber-font neon-text-pink mb-4">YOU MAY ALSO LIKE</h3>
            <div class="row" id="productsGrid">
                @foreach ($relatedProducts as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 _{{ $item->category_id }}">
                        <div class="product-card p-3" style="height: 470px;">
                            <div class="product-image mb-3">
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

                            <h5 class="neon-text-blue">{{ \Illuminate\Support\Str::limit($item->name, 60) }}</h5>

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
                                    <form action="{{ url('/addproducttocart/' . $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-cyber btn-sm">
                                            <i class="fas fa-shopping-cart"></i> ADD TO CART
                                        </button>
                                    </form>
                                @endif
                                @if (Auth::check() && Auth::user()->role == 'admin')
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


        <script src="{{ asset('assets/js/showproduct.js') }}"></script>
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
    </body>

    </html>
@endsection
