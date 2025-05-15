# API Key Authentication

This document explains how to use API key authentication for public API endpoints in Laravel 11.

## Overview

The API key middleware provides a simple way to secure public API endpoints without requiring user authentication. It validates requests by checking for a valid API key in either the request headers or query parameters.

## Configuration

API key settings are stored in the `config/api.php` file and can be configured through environment variables:

```
# .env file
API_KEY=your_api_key_here
API_KEY_VALIDATION_ENABLED=true
```

## Generating an API Key

You can generate a new API key using the provided Artisan command:

```bash
# Generate a new API key and update the .env file
php artisan api:key:generate

# Generate a key without updating the .env file (just display it)
php artisan api:key:generate --show
```

## Using the API Key in Requests

When making requests to protected endpoints, you can provide the API key in one of two ways:

### 1. Using the X-API-Key Header (Recommended)

```bash
curl -X GET "https://your-api.com/api/v1/public/articles" \
  -H "X-API-Key: your_api_key_here"
```

### 2. Using a Query Parameter

```bash
curl -X GET "https://your-api.com/api/v1/public/articles?api_key=your_api_key_here"
```

## Applying the Middleware

The middleware is already applied to all public content routes. If you need to apply it to other routes, you can use the `api.key` middleware alias:

```php
// Protect a single route
Route::get('/some-endpoint', [SomeController::class, 'method'])->middleware('api.key');

// Protect a group of routes
Route::prefix('some-prefix')->middleware('api.key')->group(function () {
    // Routes here will require an API key
});
```

## Middleware Registration (Laravel 11)

In Laravel 11, middleware is registered in the `bootstrap/app.php` file using the new configuration API:

```php
use App\Http\Middleware\ValidateApiKey;

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api.key' => ValidateApiKey::class,
        ]);
    })
    // ...
```

## Error Responses

If a request to a protected endpoint is missing the API key or provides an invalid key, the middleware will return a standardized `UnauthorizedResponse` with a 401 Unauthorized status:

```json
{
    "_metadata": {
        "success": false
    },
    "result": {
        "message": "Invalid or missing API key"
    }
}
```

This response follows the same structure as other API responses in the application, ensuring consistency across all endpoints.

## Disabling API Key Validation

You can disable API key validation by setting `API_KEY_VALIDATION_ENABLED=false` in your `.env` file. This can be useful for development or testing environments. 
