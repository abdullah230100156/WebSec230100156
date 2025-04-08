@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')
    <form action="{{ route('products_save', $product->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
            </div>
        @endforeach

        <div class="row mb-2">
            <div class="col-6">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" name="code" required value="{{$product->code}}">
            </div>
            <div class="col-6">
                <label for="model" class="form-label">Model:</label>
                <input type="text" class="form-control" name="model" required value="{{$product->model}}">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required value="{{$product->name}}">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-6">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" placeholder="Price" name="price" required value="{{$product->price}}">
            </div>
            <div class="col-6">
                <label for="photo" class="form-label">Photo:</label>
                <input type="file" class="form-control" name="photo" />
                @if (!empty($product->photo))
                <img src="{{ asset('storage/' . $product->photo) }}" class="img-thumbnail" alt="{{ $product->name }}" width="100%">
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" required>{{$product->description}}</textarea>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label for="stock" class="form-label">Stock:</label>
                <input type="number" class="form-control" placeholder="Stock" name="stock" required value="{{$product->in_stock}}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
