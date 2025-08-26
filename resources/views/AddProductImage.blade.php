@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">PRODUCT IMAGES</span></h1>
                        <p class="cyber-subtitle">{{ $product->name }}</p>
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


    <div class="cyber-upload-section">
        <div class="container">
            <div class="cyber-upload-container">
                <form action="/storeProductImage" method="POST" enctype="multipart/form-data" class="cyber-upload-form">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">

                    <div class="cyber-file-upload">
                        <label for="photos" class="cyber-upload-label">
                            <div class="cyber-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="cyber-upload-text">SELECT IMAGE FILES</div>
                            <input type="file" name="photos[]" id="photos" class="cyber-file-input" multiple>
                        </label>
                        <div class="cyber-upload-preview" id="cyber-upload-preview"></div>
                    </div>

                    <div class="cyber-upload-submit">
                        <button type="submit" class="cyber-upload-btn">
                            <span class="cyber-btn-icon"><i class="fas fa-save"></i></span>
                            <span class="cyber-btn-text">UPLOAD TO DATABASE</span>
                            <span class="cyber-btn-pulse"></span>
                        </button>
                    </div>

                    <span class="cyber-error">
                        @error('photos.*')
                            {{ $message }}
                        @enderror
                    </span>
                </form>
            </div>
        </div>
    </div>


    <div class="cyber-gallery-section">
        <div class="container">
            <div class="cyber-gallery-title">
                <h3>EXISTING <span class="cyber-accent">IMAGES</span></h3>
                <div class="cyber-title-underline"></div>
            </div>

            <div class="cyber-gallery-grid">
                @foreach ($productImages as $item)
                    <div class="cyber-gallery-item" data-aos="fade-up">
                        <div class="cyber-image-container">
                            <img src="{{ asset($item->imagepath) }}" alt="Product Image" class="cyber-gallery-img">
                            <div class="cyber-image-overlay">
                                <form action="{{ url('/removeproductphoto/' . $item->id) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="cyber-delete-btn" style="cursor: pointer">
        <i class="fas fa-trash"></i> DELETE
    </button>
</form>

                            </div>
                            <div class="cyber-image-border"></div>
                        </div>
                    </div>
                @endforeach
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
        <link rel="stylesheet" href="{{ asset('assets/css/addproductimage.css') }}">
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script src="{{ asset('assets/js/addproductimage.js') }}"></script>
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
