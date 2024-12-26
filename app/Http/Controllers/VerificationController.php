<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Helpers\SMSHelper;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|exists:users,phone',
                'verification_code' => 'required',
            ]);
            $code = Cache::get('verification_code_' . $validated['phone']);
            if (!$code) {
                return response()->json(['message' => 'Expired code, request a new one .'], 400);
            }
            if ($code != $validated['verification_code']) {
                return response()->json(['message' => 'Wrong code,try again .'], 400);
            }
            $user = User::where('phone', $validated['phone'])->firstOrFail();
            DB::transaction(function () use ($user, &$token) {
                $user->is_verified = true;
                $token = $user->createToken('auth_token')->plainTextToken;
                $user->save();
            });
            Cache::forget('verification_code_' . $validated['phone']);
            return response()->json([
                'message' => 'Account successfully verified.',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'token' => $token,
                ],
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
            $code = Cache::get('verification_code_' . $validated['phone']);
            if (!$code) {
                $code = SMSHelper::generateCode();
                Cache::put('verification_code_' . $validated['phone'], $code, now()->addMinutes(10));
            }
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
