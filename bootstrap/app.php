<?php

use App\Http\Middleware\NotLoggedIn;
use App\Http\Middleware\RestrictedAccess;
use App\Http\Middleware\UserSpecificMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // - Vercel sits in front of the app, so trust its https headers
        $middleware->trustProxies(at: "*");
        $middleware->web(append: [UserSpecificMiddleware::class]);
        $middleware->alias([
            "restricted_access" => RestrictedAccess::class,
            "not_logged_in" => NotLoggedIn::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // - wrong url or id: friendly "Not found" page instead of a crash
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->view("error", ["error" => "Not found."], 404);
        });
    })->create();
