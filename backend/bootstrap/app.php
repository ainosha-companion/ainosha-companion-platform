<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\AuthServiceProvider;
use App\Http\Middleware\ValidateApiKey;
use App\Console\Commands\GenerateApiKeyCommand;
use App\Console\Commands\CreateWebhookUserCommand;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Middlewares\CheckPermission;
use App\Console\Commands\SeedPermissionsCommand;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'api.key' => ValidateApiKey::class, // Uses UnauthorizedResponse for invalid API keys
            'role' => RoleMiddleware::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        GenerateApiKeyCommand::class,
        CreateWebhookUserCommand::class,
        SeedPermissionsCommand::class,
    ])
    ->withProviders([
        AuthServiceProvider::class,
    ])
    ->create();
