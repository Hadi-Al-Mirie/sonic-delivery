<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\VerifyRequest;
use App\Http\Requests\Mobile\Auth\ResendCodeRequest;
use App\Services\Mobile\VerificationService;

class VerificationController extends Controller
{
    protected VerificationService $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function verify(VerifyRequest $request)
    {
        $validated = $request->validated();

        $result = $this->verificationService->verify(
            $validated['phone'],
            $validated['verification_code']
        );

        return response()->json($result['response'], $result['status']);
    }

    public function resendCode(ResendCodeRequest $request)
    {
        $validated = $request->validated();

        $result = $this->verificationService->resendCode($validated['phone']);

        return response()->json($result['response'], $result['status']);
    }
}
