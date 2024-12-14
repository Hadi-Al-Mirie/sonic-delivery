<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Helpers\SMSHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function register(Request $request)
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
    public function login(Request $request)
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
            if (!$user->is_verified) {
                return response()->json(['message' => 'Account is not verified.'], 403);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
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
    public function verify(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|exists:users,phone',
                'verification_code' => 'required',
            ]);
            $cachedCode = Cache::get('verification_code_' . $validated['phone']);
            if (!$cachedCode || $cachedCode != $validated['verification_code']) {
                return response()->json(['message' => 'Invalid or expired verification code.'], 400);
            }
            $user = User::where('phone', $validated['phone'])->first();
            $user->is_verified = true;
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            Cache::forget('verification_code_' . $validated['phone']);
            return response()->json([
                'message' => 'Account successfully verified.',
                'user' => $user,
                'token' => $token,
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
    public function resendCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|exists:users,phone',
            ]);
            $code = SMSHelper::generateCode();
            Cache::put('verification_code_' . $validated['phone'], $code, now()->addMinutes(10));
            SMSHelper::sendSMS($validated['phone'], $code);
            return response()->json(['message' => 'Verification code resent successfully.'], 200);
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