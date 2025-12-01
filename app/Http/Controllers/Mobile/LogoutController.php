<?php

namespace App\Http\Controllers\Mobile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout done .",
        ], 200);
    }
}
