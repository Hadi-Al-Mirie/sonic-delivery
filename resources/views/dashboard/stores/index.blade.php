@extends('dashboard.layouts.app')

@section('title', 'Stores List')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Stores</h1>
        <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">Add New Store</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                    <tr>
                        <td>{{ $store->name }}</td>
                        {{-- <td>{{ $store->description }}</td> --}}
                        <td>{{ $store->location }}</td>
                        {{-- <td>
                            @if ($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" width="50" height="50" alt="Logo">
                            @endif
                        </td> --}}
                        <td>
                            <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-sm btn-primary">Show</a>
                            <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this store?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $stores->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
