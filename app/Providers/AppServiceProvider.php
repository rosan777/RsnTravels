<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // - fallback for pages rendered outside the web middleware (for example an unknown url)
        View::composer("*", function ($view) {
            $data = $view->getFactory()->getShared();
            if (!isset($data["t"])) {
                $texts = require app_path("Support/translations.php");
                $view->with(["t" => $texts["en"], "lang" => "en", "userEx" => null]);
            }
        });
    }
}
