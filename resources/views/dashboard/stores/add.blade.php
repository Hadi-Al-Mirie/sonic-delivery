@extends('dashboard.layouts.app')

@section('title', 'Add Store')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Add New Store</h1>
        <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
    </div>

    <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Store Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5"></textarea>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Store Logo</label>
            <input type="file" name="logo" id="logo" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Store</button>
    </form>
</div>
@endsection
