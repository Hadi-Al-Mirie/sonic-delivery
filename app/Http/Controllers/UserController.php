<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'profile_picture' => 'nullable|image|max:5120',
                'location' => 'nullable|string',
            ]);
            $user = Auth::user();
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
            $profilePicture = $user->profile_picture
                ? Storage::disk('images')->url($user->profile_picture)
                : null;
            return response()->json([
                'user' => [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "phone" => $user->phone,
                    "location" => $user->location,
                    "profile_picture" => $profilePicture,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation errors:',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function getProfile()
    {
        try {
            $user = Auth::user();
            $profilePicture = $user->profile_picture
                ? Storage::disk('images')->url($user->profile_picture)
                : null;
            return response()->json([
                'user' => [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "phone" => $user->phone,
                    "location" => $user->location,
                    "profile_picture" => $profilePicture,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
