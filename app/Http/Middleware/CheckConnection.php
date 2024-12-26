<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
class CheckConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->checkInternetConnection()) {
            return $next($request);
        } else {
            return response()->json(["message" => "you are not connected !"], 408);
        }
    }
    private function checkInternetConnection(): bool
    {
        try {
            $response = Http::timeout(5)->get('https://www.google.com');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
