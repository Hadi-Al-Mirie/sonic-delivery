@extends('dashboard.layouts.app')

@section('title')
    Edit Product
@endsection

@section('content')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Edit Product</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="6" style="height: 100px;" required>{{ $product->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ $product->stock }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="store_id" class="form-label">Select Store</label>
                <select name="store_id" id="store_id" class="form-select" required>
                    <option value="" disabled>Select a store</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ $product->store_id == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" id="image" class="form-control">
                @if ($product->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $product->image) }}" width="100" class="img-thumbnail">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
@endsection
