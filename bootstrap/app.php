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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'designer.guest' => \App\Http\Middleware\RedirectIfDesigner::class,
            'designer.auth' => \App\Http\Middleware\RedirectIfNotDesigner::class,
            'marketer.guest' => \App\Http\Middleware\RedirectIfMarketer::class,
            'marketer.auth' => \App\Http\Middleware\RedirectIfNotMarketer::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Apply locale middleware globally
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
