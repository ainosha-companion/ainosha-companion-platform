<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use App\Http\DTOs\Article\UpdateArticleDTO;
use App\Handlers\Article\UpdateArticleHandler;

class ArticleController extends Controller
{
    public function __construct(
        private readonly UpdateArticleHandler $handler
    ) {}

    /**
     * Display a listing of the articles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = Article::with(['category', 'author', 'tags'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'articles' => $articles
            ]
        ]);
    }

    // /**
    //  *  Send request to N8n in order to create article
    //  *
    //  * @param CreateArticleRequest $request
    //  * @return JsonResponse
    //  */
    // public function store(CreateArticleRequest $request): JsonResponse
    // {

    // }

    /**
     * Display the specified article by slug.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article retrieved successfully',
                'article' => $article
            ]
        ]);
    }

    /**
     * Update the specified article in storage.
     *
     * @param UpdateArticleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        // Create DTO from request data
        $dto = new UpdateArticleDTO(
            id: $id,
            title: $validated['title'] ?? null,
            slug: $validated['slug'] ?? null,
            categoryId: $validated['category_id'] ?? null,
            thumbnail: $validated['thumbnail'] ?? null,
            abstract: $validated['abstract'] ?? null,
            html: $validated['html'] ?? null,
            markdown: $validated['markdown'] ?? null,
            status: $validated['status'] ?? null,
            tags: $validated['tags'] ?? null,
        );

        // Use handler to update article
        $article = $this->handler->handle($dto);

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article updated successfully',
                'article' => $article
            ]
        ]);
    }

    /**
     * Remove the specified article from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article deleted successfully'
            ]
        ]);
    }

    /**
     * Publish the specified article.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function publish(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->status = 'published';
        $article->save();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article published successfully',
                'article' => $article->load(['category', 'author', 'tags'])
            ]
        ]);
    }

    /**
     * Archive the specified article.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function archive(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->status = 'archived';
        $article->save();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article archived successfully',
                'article' => $article->load(['category', 'author', 'tags'])
            ]
        ]);
    }

    /**
     * Set the specified article to draft status.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function draft(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->status = 'draft';
        $article->save();

        return response()->json([
            '_metadata' => [
                'success' => true
            ],
            'result' => [
                'message' => 'Article set to draft successfully',
                'article' => $article->load(['category', 'author', 'tags'])
            ]
        ]);
    }
}
