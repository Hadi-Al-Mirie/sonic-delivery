<?php

namespace App\Services\Mobile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
class UserService
{
    public function updateProfile(User $user, Request $request, array $validated): array
    {
        DB::transaction(function () use ($request, $user, $validated) {
            if ($request->has('first_name')) {
                $user->first_name = $validated['first_name'];
            }
            if ($request->has('last_name')) {
                $user->last_name = $validated['last_name'];
            }
            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture) {
                    Storage::disk('images')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('users_avatars', 'images');
                $user->profile_picture = $path;
            }
            if ($request->has('location')) {
                $user->location = $validated['location'];
            }
            $user->save();
        });

        $images = Storage::disk('images');
        /** @var FilesystemAdapter $images */
        $profilePicture = $user->profile_picture
            ? $images->url($user->profile_picture)
            : null;

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'location' => $user->location,
            'profile_picture' => $profilePicture,
        ];
    }

    public function getProfile(User $user): array
    {
        $images = Storage::disk('images');
        /** @var FilesystemAdapter $images */
        $profilePicture = $user->profile_picture
            ? $images->url($user->profile_picture)
            : null;

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'location' => $user->location,
            'profile_picture' => $profilePicture,
        ];
    }
}