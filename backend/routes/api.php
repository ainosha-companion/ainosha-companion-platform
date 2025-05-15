<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarketSentimentController;
use App\Http\Controllers\TokenSentimentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Enums\Permission;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::get('me', [UserController::class, 'getMe'])
        ->middleware('auth:sanctum');
});


Route::prefix('analytics')->group(function () {
    Route::get('market-sentiment', [MarketSentimentController::class, 'getDailyInsight'])
        ->middleware('auth:sanctum');
    Route::post('token', [TokenSentimentController::class, 'getSentiment'])
        ->middleware('auth:sanctum');
});


// Permission management routes (requires super-admin role)
Route::prefix('permissions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::get('/{permission}', [PermissionController::class, 'show']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('/{permission}', [PermissionController::class, 'update']);
    Route::delete('/{permission}', [PermissionController::class, 'destroy']);
    });

Route::prefix('roles')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

Route::prefix('content')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleController::class)->except(['show']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->where('slug', '(?!published)[A-Za-z0-9\-\_]+');
    Route::controller(ArticleController::class)->group(function () {
        Route::post('/articles/{id}/publish', 'publish');
        Route::post('/articles/{id}/archive', 'archive');
        Route::post('/articles/{id}/draft', 'draft');
    });

    // Category & Tag Routes
    Route::apiResource('categories', CategoryController::class)->parameters(['categories' => 'slug']);
    Route::apiResource('tags', TagController::class)->parameters(['tags' => 'slug']);
    Route::get('/articles/{articleId}/tags', [TagController::class, 'getByArticle']);
});



