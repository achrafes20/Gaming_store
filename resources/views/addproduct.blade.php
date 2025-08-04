@extends('Layouts.master')
@section('content')


    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">

                        <h1>Add Product</h1>
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
                        <form method="POST" enctype="multipart/form-data" action="/storeproduct" id="fruitkha-contact">  <!-- endctype est obligé !! -->
                            @csrf()
                            <p>
                                <input type="text" style="width: 100%" placeholder="Name" name="name" id="name"
                                    value="{{ old('name') }}">
                                <span class="text-danger">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>

                            </p>
                            <p style="display: flex">
                                <input type="number" style="width: 100%" class="mr-4" placeholder="Price" name="price"
                                    id="price" value="{{ old('name') }}">
                                <span class="text-danger">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <input type="number" style="width: 100%" placeholder="quantity" name="quantity"
                                    id="quantity" value="{{ old('name') }}">
                                <span class="text-danger">
                                    @error('quantity')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p>
                                <textarea name="description" id="description" cols="30" rows="10" placeholder="description">{{ old('name') }}</textarea>
                            </p>
                            <span class="text-danger">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </span>
                            <p>
                                <select class="form-control" required name="category_id" id="category_id">
                                    @foreach ($allcategories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('category_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p>
                                <input type="file" class="form-control" name="photo" id="photo">
                                <span class="text-danger">
                                    @error('photo')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p><input type="submit" value="Submit"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact form -->

@endsection
