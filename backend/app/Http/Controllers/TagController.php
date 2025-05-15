<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $tags = Tag::orderBy(Tag::NAME, 'asc')->get();

            return response()->json([
                '_metadata' => [
                    'success' => true
                ],
                'result' => [
                    'tags' => $tags
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
     * Display the specified tag.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tag = Tag::findOrFail($id);

            return response()->json([
                '_metadata' => [
                    'success' => true
                ],
                'result' => [
                    'tag' => $tag->load('articles')
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
     * Get all tags for a specific article.
     *
     * @param int $articleId
     * @return JsonResponse
     */
    public function getByArticle(int $articleId): JsonResponse
    {
        try {
            $article = Article::findOrFail($articleId);
            $tags = $article->tags;

            return response()->json([
                '_metadata' => [
                    'success' => true
                ],
                'result' => [
                    'tags' => $tags
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
