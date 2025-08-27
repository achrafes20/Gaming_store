@extends('Layouts.master')
@section('content')
@if(Auth::check())
    <div class="cyber-breadcrumb-section">
        <div class="cyber-breadcrumb-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-breadcrumb-text">
                        <div class="cyber-glitch" data-text="SHARE YOUR EXPERIENCE">SHARE YOUR EXPERIENCE</div>
                        <h1>ADD TECH REVIEW</h1>
                        <div class="cyber-pulse-animation">
                            <div class="pulse-circle"></div>
                            <div class="pulse-circle delay-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cyber-review-form-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="cyber-form-container" data-aos="fade-up">
                        <div class="cyber-form-header">
                            <h2><i class="fas fa-comment-alt"></i> REVIEW FORM</h2>
                            <p>Share your thoughts about our products and service</p>
                        </div>
                        <form method="POST" action="/storereview" id="cyber-review-form">
                            @csrf()
                            <div class="cyber-form-grid">
                                <div class="cyber-input-group">
                                    <input type="text" name="name" id="name" value="{{ Auth::user()->name }}"
                                        placeholder="{{ Auth::user()->name }}" readonly required>
                                    <label for="name">YOUR NAME</label>
                                    <div class="cyber-input-highlight"></div>
                                    @error('name')
                                        <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="cyber-input-group">
                                    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}"
                                        required readonly placeholder="{{ Auth::user()->email }}">
                                    <label for="email">EMAIL ADDRESS</label>
                                    <div class="cyber-input-highlight"></div>
                                    @error('email')
                                        <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="cyber-input-group">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        required>
                                    <label for="phone">PHONE NUMBER</label>
                                    <div class="cyber-input-highlight"></div>
                                    @error('phone')
                                        <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="cyber-input-group">
                                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                        required>
                                    <label for="subject">REVIEW TITLE</label>
                                    <div class="cyber-input-highlight"></div>
                                    @error('subject')
                                        <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="cyber-textarea-group">
                                <textarea name="message" id="message" required>{{ old('message') }}</textarea>
                                <label for="message">YOUR REVIEW</label>
                                <div class="cyber-textarea-highlight"></div>
                                @error('message')
                                    <div class="cyber-error-message"><i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="cyber-submit-btn">
                                <span>SUBMIT REVIEW</span>
                                <i class="fas fa-paper-plane"></i>
                                <div class="cyber-btn-glow"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="cyber-testimonials-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-center">
                    <div style="text-align: center" class="cyber-section-header" data-aos="fade-up">
                        <div class="cyber-glitch" data-text="TECH FEEDBACK">TECH FEEDBACK</div>
                        <h2 style="text-align: center">CUSTOMER <span class="cyber-accent">REVIEWS</span></h2>
                        <p style="text-align: center">What our clients say about our products and service</p>
                    </div>
                    <div class="cyber-testimonials-slider">
                        @foreach ($reviews as $item)
                            <div class="cyber-testimonial-card" data-aos="fade-up">
                                <div class="cyber-client-avatar">
                                </div>
                                <div class="cyber-client-meta">
                                    <h3>{{ $item->name }} <span>{{ $item->subject }}</span></h3>
                                    <div class="cyber-testimonial-body">
                                        <p>{{ $item->message }}</p>
                                    </div>
                                    <div class="cyber-testimonial-footer">
                                        <div class="cyber-client-contact">
                                            <i class="fas fa-envelope"></i> {{ $item->email }}

                                        </div>
                                        @if (Auth::check() && Auth::user()->role == 'admin')
                                        <form action="{{ url('/removereview/' . $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="delete-btn" style="cursor: pointer;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="cyber-testimonial-glow"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/reviews.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="{{ asset('assets/js/reviews.js') }}"></script>
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
    @endpush
@endsection
