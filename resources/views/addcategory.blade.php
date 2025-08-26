@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">ADD <span class="cyber-accent">CATEGORY</span></h1>
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
                            <h3 class="cyber-form-title">CATEGORY <span class="cyber-accent">DATABASE</span> ENTRY</h3>
                            <p class="cyber-form-subtitle">Fill in the category specifications below</p>
                        </div>

                        <div class="cyber-form-body">
                            <form method="POST" enctype="multipart/form-data" action="/storecategory"
                                id="cyber-category-form">
                                @csrf()
                                <div class="cyber-form-group">
                                    <div class="cyber-input-container">
                                        <input type="text" placeholder="CATEGORY NAME" name="name" id="name"
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

                                <div class="cyber-form-group">
                                    <div class="cyber-textarea-container">
                                        <textarea name="description" id="description" placeholder="CATEGORY DESCRIPTION" class="cyber-textarea">{{ old('description') }}</textarea>
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
                                    <div class="cyber-file-container">
                                        <label for="photo" class="cyber-file-label">
                                            <span class="cyber-file-icon"><i class="fas fa-camera"></i></span>
                                            <span class="cyber-file-text">UPLOAD CATEGORY IMAGE</span>
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

                                <div class="cyber-form-submit">
                                    <button type="submit" class="cyber-submit-btn">
                                        <span class="cyber-btn-text">CREATE CATEGORY</span>
                                        <span class="cyber-btn-icon">
                                            <i class="fas fa-plus-circle"></i>
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
         <link rel="stylesheet" href="{{ asset('assets/css/addcategory.css') }}">
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="{{ asset('assets/js/addcategory.js') }}"></script>
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
