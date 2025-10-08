<?php

use App\Console\Commands\SyncRolesAndPermissionsCommand;
use App\Http\Middleware\ApiAuthorizeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting()
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum SPA auth
        $middleware->statefulApi();

        $middleware->appendToGroup('auth:api', [
            'auth:sanctum',
            ApiAuthorizeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withCommands([
        SyncRolesAndPermissionsCommand::class,
    ])->create();
