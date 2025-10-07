@extends('dashboard.layouts.app')


@section('title', 'Edit Profile')

@section('content')
    <div class="container mt-5">
        <h3>Edit Profile</h3>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))
                                ? asset('storage/' . $user->profile_picture)
                                : asset('assets/images/user-default.png') }}"
                                alt="Profile Picture" class="img-fluid" width="150">
                            <div class="mt-2">
                                <label for="profile_picture">Upload New Profile Picture (Optional)</label>
                                <input type="file" class="form-control" name="profile_picture" id="profile_picture">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" name="location"
                                    value="{{ old('location', $user->location) }}">
                            </div>

                            <div class="form-group">
                                <label for="password">New Password (Optional)</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Update Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
