<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function login(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}