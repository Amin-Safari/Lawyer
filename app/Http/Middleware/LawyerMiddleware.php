<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LawyerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->lawyer) {
            return redirect()->route('home')->with('error', 'شما دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
