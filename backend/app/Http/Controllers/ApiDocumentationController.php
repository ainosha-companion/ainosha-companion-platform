<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiDocumentationController extends Controller
{
    /**
     * Display the API documentation using Redoc
     *
     * @return View
     */
    public function redoc(): View
    {
        return view('redoc');
    }

    /**
     * Serve the OpenAPI specification file
     *
     * @return BinaryFileResponse
     */
    public function openApiSpec(): BinaryFileResponse
    {
        return response()->file(base_path('v1.yaml'));
    }
}