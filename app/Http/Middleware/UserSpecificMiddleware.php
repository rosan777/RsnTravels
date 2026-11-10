<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Models\Favorite;
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

        // - heart and cart counters for the top bar
        $fav_keys = Auth::check() ? Favorite::where("user_id", Auth::id())->get()->map(fn ($f) => "{$f->item_type}:{$f->item_id}")->all() : [];
        View::share("favKeys", $fav_keys);
        View::share("cartCount", count($request->session()->get("cart", [])));
        View::share("pendingApprovals", $userEx && $userEx->user_type == "owner" ? Booking::where("status", "Pending")->count() : 0);
        View::share("userEx", $userEx);
        View::share("lang", $lang);
        View::share("t", $texts[$lang]);

        return $next($request);
    }
}
