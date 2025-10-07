@extends('dashboard.layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="container mt-5">
        <h1 class="h3 mb-4">User Details</h1>

        <div class="mb-3">
            <strong>Name: </strong> {{ $user->first_name }} {{ $user->last_name }}
        </div>

        <div class="mb-3">
            <strong>Email: </strong> {{ $user->email }}
        </div>

        <div class="mb-3">
            <strong>Phone: </strong> {{ $user->phone }}
        </div>

        <div class="mb-3">
            <strong>Role: </strong> {{ $user->role->name }}
        </div>

        <div class="mb-3">
            <strong>Location: </strong> {{ $user->location }}
        </div>

        <div class="mb-3">
            <strong>Profile Picture: </strong>
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" width="100" height="100"
                    alt="Profile Picture">
            @else
                No profile picture
            @endif
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
