<?php

use App\Http\Middleware\SetLocale;
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
        // Global middleware
        $middleware->append([
            // Your global middleware here
        ]);
        
        // Web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\ShareNavigationData::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.mandatory.polls' => \App\Http\Middleware\CheckMandatoryPolls::class,
            'check.mandatory.surveys' => \App\Http\Middleware\CheckMandatorySurveys::class,
            'debug.permission' => \App\Http\Middleware\DebugPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
