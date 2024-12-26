<?php

namespace App\Providers;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('auth', function (Request $request) {
            $ip = $request->ip();
            $phone = $request->input('phone');
            $routeName = $request->route()->getName();
            $key = $ip . $phone . $routeName;
            $max = 1;
            $decay = 600;
            if (RateLimiter::tooManyAttempts($key, $max)) {
                $seconds = RateLimiter::availableIn($key);
                $min = floor($seconds / 60);
                $sec = $seconds % 60;
                return response()->json([
                    'message' => "Too many attempts, please try again in {$min} minutes and {$sec} seconds.",
                    'key' => $key
                ], 429);
            } else {
                RateLimiter::hit($key, $decay);
            }
        });
    }
}
