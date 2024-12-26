<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Helpers\SMSHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            $code = SMSHelper::generateCode();
            Cache::put('verification_code_' . $validated['phone'], $code, now()->addMinutes(10));
            SMSHelper::sendSMS($validated['phone'], $code);
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role_id' => 3,
            ]);
            return response()->json(['message' => 'Registration successful. Check your SMS for the verification code.'], 201);
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
