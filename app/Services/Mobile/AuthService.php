<?php

namespace App\Services\Mobile;

use App\Http\Requests\Mobile\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('phone', $validated['phone'])->first();

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // if (!$user->is_verified) {
        //     return response()->json(['message' => 'Account is not verified.'], 403);
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        /** @var FilesystemAdapter $images */
        $images = Storage::disk('images');
        $profilePicture = $user->profile_picture
            ? $images->url($user->profile_picture)
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
            'token' => $token,
        ], 200);
    }

    public function register(array $data): array
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role_id' => 3,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
