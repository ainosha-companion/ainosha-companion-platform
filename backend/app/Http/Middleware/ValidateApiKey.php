<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Responses\UnauthorizedResponse;
use Closure;
use Illuminate\Http\Request;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check if API key validation is enabled
        if (!config('api.key_validation_enabled', true)) {
            return $next($request);
        }

        // Get the API key from the request
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');

        // Get the valid API key from config
        $validApiKey = config('api.key');

        // If no API key is provided or it doesn't match
        if (!$apiKey || $apiKey !== $validApiKey) {
            return new UnauthorizedResponse();
        }

        return $next($request);
    }
}
