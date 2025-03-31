<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Exclude routes starting with 'customer/' from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'customer/*',
            '/scan-qr',
            '/get-products',
            '/get-credit-cards',
            '/place-order',
            '/associate-customer-table',
            '/get-shops',
            '/get-profile',
            '/update-profile',
            '/get-credit-cards',
            '/add-card',
            '/delete-card',
            '/change-password',
            '/forgot-password',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();