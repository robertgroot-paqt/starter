<?php

use App\Console\Commands\SyncRolesAndPermissionsCommand;
use App\Http\Middleware\ApiAuthorizeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting()
    ->withMiddleware(function (Middleware $middleware): void {
        // Stop laravel from redirecting to login on authenticated
        $middleware->redirectTo(guests: fn () => null, users: fn () => null);

        // Sanctum SPA auth with cookie
        $middleware->statefulApi();

        $middleware->appendToGroup('auth:api', [
            'auth:sanctum',
            ApiAuthorizeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })
    ->withCommands([
        SyncRolesAndPermissionsCommand::class,
    ])->create();
