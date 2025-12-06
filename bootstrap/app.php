<?php

use App\Models\SystemError;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware): void {

    // Aliases
    $middleware->alias([
        'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        'activity'   => \App\Http\Middleware\ActivityLogger::class,
        'pagevisit'  => \App\Http\Middleware\PageVisitLogger::class,
    ]);

    // Web group
    $middleware->group('web', [

        // Sessions & Cookies (ضروري يكونوا بالبداية)
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,

        // Activity Logger
        'activity',

        // Page Visit Logger
        'pagevisit',

        // Errors & CSRF & Bindings
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
               $exceptions->report(function (Throwable $e) {

            try {
                SystemError::create([
                    'message'     => $e->getMessage(),
                    'file'        => $e->getFile(),
                    'line'        => $e->getLine(),
                    'type'        => get_class($e),
                    'trace'       => $e->getTraceAsString(),
                    'url'         => request()->fullUrl() ?? null,
                    'user_id'     => auth()->id(),
                    'ip'          => request()->ip() ?? null,
                    'user_agent'  => request()->header('User-Agent'),
                ]);
            } catch (Throwable $error) {
            }

        });

    })->create();
