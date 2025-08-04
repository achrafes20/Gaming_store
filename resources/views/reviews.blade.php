@extends('Layouts.master')
@section('content')
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">

                        <h1>Add Review</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- contact form -->
    <div class="contact-from-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-5 mb-lg-0">
                    <div class="form-title">

                    </div>
                    <div id="form_status"></div>
                    <div class="contact-form">
                        <form method="POST" action="/storereview" id="fruitkha-contact">
                            @csrf()
                            <p>
                                <input type="text" placeholder="Name" name="name" id="name"
                                    value="{{ old('name') }}">
                                <span class="text-danger">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <input type="email" placeholder="Email" name="email" id="email"
                                    value="{{ old('email') }}">
                                <span class="text-danger">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p>
                                <input type="text" placeholder="phone" name="phone" id="phone"
                                    value="{{ old('phone') }}">
                                <span class="text-danger">
                                    @error('phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <input type="text" placeholder="Subject" name="subject" id="subject"
                                    value="{{ old('subject') }}">
                                <span class="text-danger">
                                    @error('subject')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p>
                                <textarea name="message" id="message" cols="30" rows="10" placeholder="message">{{ old('message') }}</textarea>
                            </p>
                            <span class="text-danger">
                                @error('message')
                                    {{ $message }}
                                @enderror
                            </span>
                            <p><input type="submit" value="Submit"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact form -->

    <!-- testimonail-section -->
    <div class="testimonail-section mt-80 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-center">
                    <div class="testimonial-sliders">
                        @foreach ($reviews as $item)
                        <div class="single-testimonial-slider">
                            <div class="client-avater">
                                <img src="assets/img/avaters/avatar2.png" alt="">
                            </div>
                            <div class="client-meta">
                                <h3>{{ $item->name }} <span>{{ $item->subject }}</span></h3>
                                <p class="testimonial-body">
                                  {{ $item->message }}
                                </p>
                                <div class="last-icon">
                                    <i class="fas fa-quote-right"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end testimonail-section -->
@endsection
