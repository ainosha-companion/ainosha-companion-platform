<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::orderBy(Category::NAME, 'asc')->get();

            return response()->json([
                '_metadata' => [
                    'success' => true
                ],
                'result' => [
                    'categories' => $categories
                ]
            ]);
        } catch (Throwable $e) {
            return response()->json([
                '_metadata' => [
                    'success' => false,
                    'error' => $e->getMessage()
                ],
                'result' => null
            ], 500);
        }
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                '_metadata' => [
                    'success' => true
                ],
                'result' => [
                    'category' => $category
                ]
            ]);
        } catch (Throwable $e) {
            return response()->json([
                '_metadata' => [
                    'success' => false,
                    'error' => $e->getMessage()
                ],
                'result' => null
            ], 500);
        }
    }
}
