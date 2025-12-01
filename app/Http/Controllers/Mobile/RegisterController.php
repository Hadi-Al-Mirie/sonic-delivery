<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\RegisterRequest;
use App\Services\Mobile\AuthService;

class RegisterController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();

        $result = $this->authService->register($validated);
        $user = $result['user'];
        $token = $result['token'];

        return response()->json([
            'message' => 'Registered successful.',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
            ],
            'token' => $token,
        ], 201);
    }
}