<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\User\UpdateProfileRequest;
use App\Services\Mobile\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $userData = $this->userService->updateProfile($user, $request, $validated);

        return response()->json([
            'user' => $userData,
        ], 200);
    }

    public function getProfile()
    {
        $user = Auth::user();

        $userData = $this->userService->getProfile($user);

        return response()->json([
            'user' => $userData,
        ], 200);
    }
}