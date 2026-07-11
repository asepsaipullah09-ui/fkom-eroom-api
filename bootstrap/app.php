<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mencegah redirect ke halaman login web saat API gagal autentikasi
        $middleware->redirectGuestsTo(fn () => null);
        $middleware->redirectUsersTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();