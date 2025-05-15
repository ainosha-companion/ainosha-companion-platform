<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Http\Responses\FailedAuthenticationResponse;
use App\Http\Responses\ForbiddenResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param string $permission The permission to check (enum case name or dot notation)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return new FailedAuthenticationResponse('You must be logged in to access this resource');
        }

        $user = Auth::user();

        // Convert permission string to Permission enum
        try {
            $permissionEnum = $this->resolvePermissionEnum($permission);
        } catch (\Throwable $e) {
            // If permission cannot be resolved, deny access and log error
            $this->logPermissionError($user->id, $permission, $e);
            return new ForbiddenResponse('Invalid permission format or undefined permission');
        }

        if (!$user->hasPermissionTo($permissionEnum->value)) {
            return new ForbiddenResponse('You do not have the required permission');
        }

        return $next($request);
    }

    /**
     * Resolve a permission string to a Permission enum case
     *
     * @param string $permission
     * @return Permission
     * @throws \InvalidArgumentException If permission cannot be resolved
     */
    private function resolvePermissionEnum(string $permission): Permission
    {
        // If it's an enum case name (e.g., ARTICLE_CREATE)
        if (!str_contains($permission, '.')) {
            try {
                return constant(Permission::class . '::' . $permission);
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException("Invalid permission name: {$permission}");
            }
        }

        // If it's in dot notation (e.g., article.create), find the matching enum case
        foreach (Permission::cases() as $case) {
            if ($case->value === $permission) {
                return $case;
            }
        }

        throw new \InvalidArgumentException("No Permission enum case found for: {$permission}");
    }

    /**
     * Log permission resolution errors
     *
     * @param int $userId
     * @param string $permission
     * @param \Throwable $exception
     * @return void
     */
    private function logPermissionError(int $userId, string $permission, \Throwable $exception): void
    {
        Log::error("Permission resolution error", [
            'userId' => $userId,
            'permission' => $permission,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}