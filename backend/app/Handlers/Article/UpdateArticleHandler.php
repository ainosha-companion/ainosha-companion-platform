<?php

declare(strict_types=1);

namespace App\Handlers\Article;

use App\Http\DTOs\Article\UpdateArticleDTO;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class UpdateArticleHandler
{
    /**
     * Handle updating an article efficiently
     *
     * @param UpdateArticleDTO $dto
     *
     * @return Article
     */
    public function handle(UpdateArticleDTO $dto): Article
    {
        return DB::transaction(function () use ($dto) {
            // Find the article using Eloquent
            $article = Article::findOrFail($dto->id);

            // Check each property for changes
            if ($dto->title !== null && $dto->title !== $article->title) {
                $article->title = $dto->title;
            }

            if ($dto->slug !== null && $dto->slug !== $article->slug) {
                $article->slug = $dto->slug;
            }

            if ($dto->categoryId !== null && $dto->categoryId !== $article->category_id) {
                $article->category_id = $dto->categoryId;
            }

            if ($dto->thumbnail !== null && $dto->thumbnail !== $article->thumbnail) {
                $article->thumbnail = $dto->thumbnail;
            }

            if ($dto->abstract !== null && $dto->abstract !== $article->abstract) {
                $article->abstract = $dto->abstract;
            }

            if ($dto->html !== null && $dto->html !== $article->html) {
                $article->html = $dto->html;
            }

            if ($dto->markdown !== null && $dto->markdown !== $article->markdown) {
                $article->markdown = $dto->markdown;
            }

            if ($dto->status !== null && $dto->status !== $article->status) {
                $article->status = $dto->status;
            }

            if ($dto->tags !== null && $dto->tags !== $article->tag) {
                $article->tags = $dto->tags;
            }

            // Save the article if any changes were made
            if ($article->isDirty()) {
                $article->save();
            }

            // Return the article with its relationships
            return $article->load(['category', 'author', 'tags']);
        });
    }
}
