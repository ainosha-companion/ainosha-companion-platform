<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\BadValidationResponse;
use App\Http\Responses\InternalErrorResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\VisibilityErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        //Handle Validation exceptions.
        if ($e instanceof ValidationException) {
            return new BadValidationResponse($e->validator->errors()->toJson());
        }

        // Handle Authentication exceptions.
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null
            ], 401);
        }

        // Handle Not Found errors
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return new NotFoundResponse('Resource not found.');
        }

        // Handle Access Denied errors
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return new VisibilityErrorResponse('Access denied.');
        }

        // Fallback: return an Internal Server Error for any other exceptions.
        Log::error($e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
        ]);

        return new InternalErrorResponse('Internal server error. Please try again later.');
    }
}
