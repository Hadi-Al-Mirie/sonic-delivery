<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Services\Admin\ProfileService;

class ProfileController extends Controller
{
    public function show(ProfileService $profileService)
    {
        $user = $profileService->getAuthenticatedUser();
        return view('dashboard.profile.show', compact('user'));
    }

    public function edit(ProfileService $profileService)
    {
        $user = $profileService->getAuthenticatedUser();
        return view('dashboard.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request, ProfileService $profileService)
    {
        $profileService->updateProfile($request);
        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
