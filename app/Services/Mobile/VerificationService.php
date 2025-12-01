<?php

namespace App\Services\Mobile;

use App\Helpers\SMSHelper;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    public function verify(string $phone, string $verificationCode): array
    {
        $code = Cache::get('verification_code_' . $phone);

        if (!$code) {
            return [
                'status' => 400,
                'response' => [
                    'message' => 'Expired code, request a new one .',
                ],
            ];
        }

        if ($code != $verificationCode) {
            return [
                'status' => 400,
                'response' => [
                    'message' => 'Wrong code,try again .',
                ],
            ];
        }

        $user = User::where('phone', $phone)->firstOrFail();

        $token = null;

        DB::transaction(function () use ($user, &$token) {
            $user->is_verified = true;
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->save();
        });

        Cache::forget('verification_code_' . $phone);

        return [
            'status' => 200,
            'response' => [
                'message' => 'Account successfully verified.',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'token' => $token,
                ],
            ],
        ];
    }

    public function resendCode(string $phone): array
    {
        $code = Cache::get('verification_code_' . $phone);

        if (!$code) {
            $code = SMSHelper::generateCode();
            Cache::put('verification_code_' . $phone, $code, now()->addMinutes(10));
        }

        SMSHelper::sendSMS($phone, $code);

        return [
            'status' => 200,
            'response' => [
                'message' => 'Verification code resent successfully.',
            ],
        ];
    }
}
