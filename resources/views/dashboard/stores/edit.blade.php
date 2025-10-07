@extends('dashboard.layouts.app')

@section('title', 'Edit Store')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Edit Store</h1>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
        </div>

        <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Store Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $store->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" style="height: 100px">{{ $store->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ $store->location }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Store Logo</label>
                <input type="file" name="logo" id="logo" class="form-control">
                @if ($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" width="100" height="100" alt="Logo"
                        class="mt-2">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update Store</button>
        </form>
    </div>
@endsection
