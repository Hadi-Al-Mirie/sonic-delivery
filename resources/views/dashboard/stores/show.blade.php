@extends('dashboard.layouts.app')

@section('title', 'Store Details')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Store Details</h1>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>{{ $store->name }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $store->description ?? 'No description provided' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Location:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $store->location }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Logo:</strong>
                    </div>
                    <div class="col-md-8">
                        @if ($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" width="150" height="150"
                                alt="Store Logo">
                        @else
                            <p>No logo available</p>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Number of Products:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $store->products->count() }}
                    </div>
                </div>

                <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-warning">Edit Store</a>
            </div>
        </div>
    </div>
@endsection
