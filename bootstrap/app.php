<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',     // ✅ make sure your api routes are enabled
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 🟢 Add Sanctum + other API middlewares
        $middleware->alias([
            'stateful' => EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->group('api', [
            'stateful',           // Sanctum SPA/React-friendly auth
            'throttle:api',       // API rate limiting
            'bindings',           // Route model binding
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
