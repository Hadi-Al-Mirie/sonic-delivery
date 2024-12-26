<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'profile_picture' => 'nullable',
            ]);
            $user = Auth::user();
            if ($request->has('first_name')) {
                $user->first_name = $validated["first_name"];
            }
            if ($request->has('last_name')) {
                $user->last_name = $validated["last_name"];
            }
            if ($request->has('profile_picture')) {
                if ($user->profile_picture != null) {
                    Storage::disk('images')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('users_avatars', 'images');
                $user->profile_picture = $path;
            }
            $user->save();
            return response()->json([
                'message' => 'Profile updated successfully.',
                'user' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation errors :',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'content' => $e->getMessage(),
            ], 500);
        }
    }

}
