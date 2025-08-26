@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">CATEGORIES</span></h1>
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


    <div class="cyber-categories-section">
        <div class="container">
            <div class="cyber-action-btns">
                <a href="/addcategory" class="cyber-btn add-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>ADD NEW CATEGORY</span>
                    <div class="cyber-btn-hover"></div>
                </a>
            </div>

            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($category as $item)
                        <div class="cyber-category-accordion" data-aos="fade-up">
                            <div class="cyber-category-header" id="cyberHeading{{ $item->id }}">
                                <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                    data-target="#cyberCollapse{{ $item->id }}" aria-expanded="true"
                                    aria-controls="cyberCollapse{{ $item->id }}">
                                    <span class="cyber-category-name">{{ $item->name }}</span>
                                    <span class="cyber-category-date">{{ $item->created_at->format('M d, Y') }}</span>
                                    <i class="fas fa-chevron-down cyber-accordion-icon"></i>
                                </button>
                            </div>

                            <div id="cyberCollapse{{ $item->id }}" class="cyber-category-collapse show"
                                aria-labelledby="cyberHeading{{ $item->id }}" data-parent="#cyberAccordion">
                                <div class="cyber-category-body">
                                    <form method="POST" enctype="multipart/form-data" action="/storecategory"
                                        id="cyber-category-form-{{ $item->id }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">

                                        <div class="cyber-form-group">
                                            <div class="cyber-input-container">
                                                <input type="text" placeholder="CATEGORY NAME" name="name"
                                                    value="{{ $item->name }}" class="cyber-input" required>
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
                                                <textarea name="description" placeholder="CATEGORY DESCRIPTION" class="cyber-textarea">{{ $item->description }}</textarea>
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
                                                <label for="photo-{{ $item->id }}" class="cyber-file-label">
                                                    <span class="cyber-file-icon"><i class="fas fa-camera"></i></span>
                                                    <span class="cyber-file-text">UPLOAD CATEGORY IMAGE</span>
                                                    <input type="file" name="photo" id="photo-{{ $item->id }}"
                                                        class="cyber-file-input">
                                                </label>

                                                @if (!empty($item->imagepath))
                                                    <div class="cyber-file-preview"
                                                        id="cyber-file-preview-{{ $item->id }}">
                                                        <img src="{{ asset($item->imagepath) }}" alt="Category Image">
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="cyber-error">
                                                @error('photo')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>

                                        <div class="cyber-form-actions">
                                            <button type="submit" class="cyber-submit-btn update-btn">
                                                <span class="cyber-btn-text">UPDATE CATEGORY</span>
                                                <span class="cyber-btn-icon">
                                                    <i class="fas fa-save"></i>
                                                </span>
                                                <span class="cyber-btn-pulse"></span>
                                            </button>
                                        </form>
                                            <form action="{{ url('/removecategory/' . $item->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="cyber-btn danger-btn">
        <i class="fas fa-trash"></i>
        <span>DELETE</span>
        <div class="cyber-btn-hover"></div>
    </button>
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


    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/categoryadmin.css') }}">
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script src="{{ asset('assets/js/categoryadmin.js') }}"></script>
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
