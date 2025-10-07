@extends('dashboard.layouts.app')

@section('title', 'Users List')

@section('content')
<div class="container mt-5">
    <h1 class="h3 mb-4">Users List</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="phone" class="form-control" placeholder="Search by phone" value="{{ request('phone') }}">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Add New User
                </a>
            </div>
        </div>
        
        
    </div>

    

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email ? $user->email : 'Not Found' }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->role ? $user->role->name : 'Not Found' }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">View</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
