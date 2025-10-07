<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|unique:users,phone',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'password' => $validated['password'],
                'role_id' => 3,
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Registered successful.',
                'user' => [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "phone" => $user->phone,
                ],
                'token' => $token
            ], 201);
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
