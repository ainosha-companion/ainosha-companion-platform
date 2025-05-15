<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDocumentationController;

Route::get('/', function () {
    return 'Hello, World!';
});

// API Documentation Routes
Route::get('/api-docs', [ApiDocumentationController::class, 'redoc'])->name('api.docs.redoc');
Route::get('/api-docs/v1.yaml', [ApiDocumentationController::class, 'openApiSpec'])->name('api.docs.spec');

