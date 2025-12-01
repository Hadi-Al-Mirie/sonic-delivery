<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function getAuthenticatedUser()
    {
        return Auth::user();
    }

    public function updateProfile(UpdateProfileRequest $request): void
    {
        $user = User::findOrFail(Auth::user()->id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->location = $request->location;

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $imagePath;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
    }
}