@extends('dashboard.layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="container mt-5">
        <h3>User Profile</h3>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))
                            ? asset('storage/' . $user->profile_picture)
                            : asset('assets/images/user-default.png') }}"
                            alt="Profile Picture" class="img-fluid" width="150">
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p>Email: {{ $user->email }}</p>
                        <p>Phone: {{ $user->phone }}</p>
                        <p>Location: {{ $user->location }}</p>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
