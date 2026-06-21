<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureOtpVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'otp.verified' => EnsureOtpVerified::class,
            'admin' => EnsureAdmin::class,
        ]);

        // Webhook gateway pembayaran tidak mengirim CSRF token.
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
