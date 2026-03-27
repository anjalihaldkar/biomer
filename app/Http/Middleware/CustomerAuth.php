<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            session()->put('url.intended', $request->url());
            return redirect()->route('customer.login')
                ->with('error', 'Please login to continue.');
        }

        return $next($request);
    }
}