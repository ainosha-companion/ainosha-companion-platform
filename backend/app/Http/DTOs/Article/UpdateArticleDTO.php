<?php

declare(strict_types=1);

namespace App\Http\DTOs\Article;

use App\Enums\ArticleStatus;
use App\ValueObjects\Content\ArticleId;

readonly class UpdateArticleDTO
{
    /**
     * @param ArticleId $id
     * @param string|null $title
     * @param string|null $slug
     * @param int|null $categoryId
     * @param string|null $thumbnail
     * @param string|null $abstract
     * @param string|null $html
     * @param string|null $markdown
     * @param ArticleStatus|null $status
     * @param array<string>|null $tags
     */
    public function __construct(
        public ArticleId $id,
        public ?string $title = null,
        public ?string $slug = null,
        public ?int $categoryId = null,
        public ?string $thumbnail = null,
        public ?string $abstract = null,
        public ?string $html = null,
        public ?string $markdown = null,
        public ?ArticleStatus $status = null,
        public ?array $tags = null,
    ) {}
}
