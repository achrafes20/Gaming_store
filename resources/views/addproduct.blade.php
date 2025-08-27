@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">ADD <span class="cyber-accent">PRODUCT</span></h1>
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
    <div class="cyber-form-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="cyber-form-container">
                        <div class="cyber-form-header">
                            <h3 class="cyber-form-title">PRODUCT <span class="cyber-accent">DATABASE</span> ENTRY</h3>
                            <p class="cyber-form-subtitle">Fill in the product specifications below</p>
                        </div>
                        <div class="cyber-form-body">
                            <form method="POST" enctype="multipart/form-data" action="/storeproduct"
                                id="cyber-product-form">
                                @csrf()
                                <div class="cyber-form-group">
                                    <div class="cyber-input-container">
                                        <input type="text" placeholder="PRODUCT NAME" name="name" id="name"
                                            value="{{ old('name') }}" class="cyber-input">
                                        <div class="cyber-input-border"></div>
                                        <div class="cyber-input-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                    <span class="cyber-error">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="cyber-form-row">
                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="number" placeholder="PRICE (CREDITS)" name="price"
                                                id="price" value="{{ old('price') }}" class="cyber-input">
                                            <div class="cyber-input-border"></div>
                                            <div class="cyber-input-icon">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <span class="cyber-error">
                                            @error('price')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="cyber-form-group">
                                        <div class="cyber-input-container">
                                            <input type="number" placeholder="QUANTITY" name="quantity" id="quantity"
                                                value="{{ old('quantity') }}" class="cyber-input">
                                            <div class="cyber-input-border"></div>
                                            <div class="cyber-input-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                        </div>
                                        <span class="cyber-error">
                                            @error('quantity')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="cyber-form-group">
                                    <div class="cyber-textarea-container">
                                        <textarea name="description" id="description" placeholder="PRODUCT DESCRIPTION" class="cyber-textarea">{{ old('description') }}</textarea>
                                        <div class="cyber-textarea-border"></div>
                                        <div class="cyber-textarea-icon">
                                            <i class="fas fa-align-left"></i>
                                        </div>
                                    </div>
                                    <span class="cyber-error">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="cyber-form-group">
                                    <div class="cyber-select-container">
                                        <select name="category_id" id="category_id" class="cyber-select">
                                            @foreach ($allcategories as $item)
                                                <option value="{{ $item->id }} " {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="cyber-select-border"></div>
                                        <div class="cyber-select-icon">
                                            <i class="fas fa-list"></i>
                                        </div>
                                    </div>
                                    <span class="cyber-error">
                                        @error('category_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="cyber-form-group">
                                    <div class="cyber-file-container">
                                        <label for="photo" class="cyber-file-label">
                                            <span class="cyber-file-icon"><i class="fas fa-camera"></i></span>
                                            <span class="cyber-file-text">UPLOAD MAIN IMAGE</span>
                                            <input type="file" name="photo" id="photo" class="cyber-file-input">
                                        </label>
                                        <div class="cyber-file-preview" id="cyber-file-preview"></div>
                                    </div>
                                    <span class="cyber-error">
                                        @error('photo')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="cyber-form-group">
                                    <div class="cyber-file-container">
                                        <label for="photos" class="cyber-file-label">
                                            <span class="cyber-file-icon"><i class="fas fa-images"></i></span>
                                            <span class="cyber-file-text">UPLOAD ADDITIONAL IMAGES (MULTIPLE)</span>
                                            <input type="file" name="photos[]" id="photos"
                                                class="cyber-file-input" multiple>
                                        </label>
                                        <div class="cyber-multi-preview" id="cyber-multi-preview"></div>
                                    </div>
                                    <span class="cyber-error">
                                        @error('photos.*')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="cyber-form-submit">
                                    <button type="submit" class="cyber-submit-btn">
                                        <span class="cyber-btn-text">UPLOAD TO DATABASE</span>
                                        <span class="cyber-btn-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </span>
                                        <span class="cyber-btn-pulse"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
        <link rel="stylesheet" href="{{ asset('assets/css/addproduct.css') }}">
    @endpush
    @push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/js/addproduct.js') }}"></script>
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
