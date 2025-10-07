<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|exists:users,phone',
                'password' => 'required|string',
            ]);
            $user = User::where('phone', $validated['phone'])->first();
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }
            // if (!$user->is_verified) {
            //     return response()->json(['message' => 'Account is not verified.'], 403);
            // }
            $token = $user->createToken('auth_token')->plainTextToken;
            $profilePicture = $user->profile_picture
                ? Storage::disk('images')->url($user->profile_picture)
                : null;
            return response()->json([
                'message' => 'Login successful.',
                'user' => [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "phone" => $user->phone,
                    "location" => $user->location,
                    "profile_picture" => $profilePicture,
                ],
                'token' => $token
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
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