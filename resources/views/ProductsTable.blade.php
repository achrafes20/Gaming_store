<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
<!--https://datatables.net/ -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><!--https://cdnjs.com/ -->


<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>


@extends('Layouts.master')
@section('content')
    <div class="container mt-5 mb-5">



        <div class="text-right">
            <a href="/addproduct" class="btn btn-primary mt-5 mb-5 w-25">
                <i class="fas fa-plus"></i> Add Product</a>
            <a href="/addproduct" class="btn btn-primary mt-5 mb-5 w-35">
                <i class="fas fa-plus"></i> Add Category</a>

        </div>
        

        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <img src='{{ $item->imagepath }}' width="100" height="100">
                        </td>
                        <td>
                            <a href="/removeproduct/{{ $item->id }}" class="btn btn-danger">
                                <i class="fas fa-trash"></i>Remove</a>

                            <a href="/editproduct/{{ $item->id }}" class="btn btn-success">
                                <i class="fas fa-pen"></i>Edit</a>

                            <a href="/AddProductImages/{{ $item->id }}" class="btn btn-dark">
                                <i class="fas fa-image"></i>Add Images</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    </div>
@endsection
<script>
    $(document).ready(function() {
        let table = new DataTable('#myTable');
    });
</script>
