<?php

namespace App\Http\Middleware;

use App\Models\SmsVerification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleSmsRequestsMiddleware
{
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 60)
    {
        $key = 'sms:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'success' => false,
                'message' => "زیادی درخواست ارسال کرده‌اید. لطفاً {$seconds} ثانیه دیگر تلاش کنید."
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
