<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\LoginService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request, LoginService $loginService)
    {
        return $loginService->login($request);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
