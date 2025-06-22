<?php

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
        // Register the password change required middleware
        $middleware->alias([
            'password.change.required' => \App\Http\Middleware\CheckPasswordChangeRequired::class,
        ]);
        
        // Apply middleware to web routes (except login/logout)
        $middleware->web(append: [
            \App\Http\Middleware\CheckPasswordChangeRequired::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
