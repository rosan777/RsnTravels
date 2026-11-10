<?php

namespace App\Http\Middleware;

use App\Models\UserEx;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

// - runs on every web request: attaches $request->userEx and the language texts
class UserSpecificMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $userEx = null;
        if (Auth::check()) {
            $userEx = UserEx::where("user_id", Auth::id())->first();
        }
        $request->userEx = $userEx;

        $texts = require app_path("Support/translations.php");
        $lang = $request->session()->get("lang", "en");
        if (!isset($texts[$lang])) {
            $lang = "en";
        }

        View::share("userEx", $userEx);
        View::share("lang", $lang);
        View::share("t", $texts[$lang]);

        return $next($request);
    }
}
