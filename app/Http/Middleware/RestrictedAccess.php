<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// - route middleware, used as restricted_access:owner,staff
class RestrictedAccess
{
    public function handle(Request $request, Closure $next, ...$allowed)
    {
        if (!Auth::check()) {
            return redirect()->route("flogin");
        }

        if ($request->userEx && in_array($request->userEx->user_type, $allowed)) {
            return $next($request);
        }

        return response()->view(
            "error",
            ["error" => "You are not authorized to view this page."],
            403,
        );
    }
}
