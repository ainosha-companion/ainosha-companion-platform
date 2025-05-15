<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Key Validation
    |--------------------------------------------------------------------------
    |
    | This option controls whether API key validation is enabled.
    | When disabled, the middleware will not check for API keys.
    |
    */
    'key_validation_enabled' => env('API_KEY_VALIDATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This is the API key that will be used to validate requests.
    | It's recommended to use a long, random string.
    | You can generate one using: `php artisan tinker` and then `Str::random(64)`
    |
    */
    'key' => env('API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | This is the current API version.
    |
    */
    'version' => env('API_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | These options control the rate limiting for the API.
    |
    */
    'rate_limiting' => [
        'enabled' => env('API_RATE_LIMITING_ENABLED', true),
        'max_attempts' => env('API_RATE_LIMITING_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('API_RATE_LIMITING_DECAY_MINUTES', 1),
    ],
];
