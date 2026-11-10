<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// - login and register pages are only for guests
class NotLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return redirect()->route("index");
        }

        return $next($request);
    }
}
