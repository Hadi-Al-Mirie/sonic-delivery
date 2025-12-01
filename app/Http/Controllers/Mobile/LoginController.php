<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\LoginRequest;
use App\Services\Mobile\AuthService;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, AuthService $authService)
    {
        return $authService->login($request);
    }
}
