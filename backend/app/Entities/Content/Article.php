<?php

declare(strict_types=1);

namespace App\Entities\Content;

use DateTimeInterface;
use App\Enums\ArticleStatus;
use App\ValueObjects\Content\ArticleId;
use App\ValueObjects\Content\UserId;
use App\ValueObjects\Content\CategoryId;

readonly class Article
{
    /**
     * @param ArticleId $id
     * @param UserId $userId
     * @param CategoryId|null $categoryId
     * @param string $title
     * @param string $slug
     * @param string|null $thumbnail
     * @param string|null $abstract
     * @param string $html
     * @param string $markdown
     * @param ArticleStatus $status
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     * @param DateTimeInterface|null $deletedAt
     * @param array<string> $tags
     * @param object|null $user
     * @param object|null $category
     */
    private function __construct(
        public ArticleId $id,
        public UserId $userId,
        public ?CategoryId $categoryId,
        public string $title,
        public string $slug,
        public ?string $thumbnail,
        public ?string $abstract,
        public string $html,
        public string $markdown,
        public ArticleStatus $status,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
        public ?DateTimeInterface $deletedAt = null,
        public array $tags = [],
        public ?object $user = null,
        public ?object $category = null,
    ) {}

    /**
     * Create a new article
     *
     * @param ArticleId $id
     * @param UserId $userId
     * @param string $title
     * @param string $slug
     * @param string $html
     * @param string $markdown
     * @param CategoryId|null $categoryId
     * @param string|null $thumbnail
     * @param string|null $abstract
     * @param array<string> $tags
     * @return self
     */
    public static function create(
        ArticleId $id,
        UserId $userId,
        string $title,
        string $slug,
        string $html,
        string $markdown,
        ?CategoryId $categoryId = null,
        ?string $thumbnail = null,
        ?string $abstract = null,
        array $tags = [],
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            categoryId: $categoryId,
            title: $title,
            slug: $slug,
            thumbnail: $thumbnail,
            abstract: $abstract,
            html: $html,
            markdown: $markdown,
            status: ArticleStatus::DRAFT,
            tags: $tags,
        );
    }

    /**
     * Create an instance from existing data
     *
     * @param ArticleId $id
     * @param UserId $userId
     * @param string $title
     * @param string $slug
     * @param string $html
     * @param string $markdown
     * @param ArticleStatus $status
     * @param CategoryId|null $categoryId
     * @param string|null $thumbnail
     * @param string|null $abstract
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     * @param DateTimeInterface|null $deletedAt
     * @param array<string> $tags
     * @return self
     */
    public static function instance(
        ArticleId $id,
        UserId $userId,
        string $title,
        string $slug,
        string $html,
        string $markdown,
        ArticleStatus $status,
        ?CategoryId $categoryId = null,
        ?string $thumbnail = null,
        ?string $abstract = null,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null,
        ?DateTimeInterface $deletedAt = null,
        array $tags = [],
        ?object $user = null,
        ?object $category = null,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            categoryId: $categoryId,
            title: $title,
            slug: $slug,
            thumbnail: $thumbnail,
            abstract: $abstract,
            html: $html,
            markdown: $markdown,
            status: $status,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
            tags: $tags,
            user: $user,
            category: $category,
        );
    }

    public function isDraft(): bool
    {
        return $this->status === ArticleStatus::DRAFT;
    }

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::PUBLISHED;
    }

    public function isArchived(): bool
    {
        return $this->status === ArticleStatus::ARCHIVED;
    }

    public function publish(): self
    {
        return new self(
            id: $this->id,
            userId: $this->userId,
            categoryId: $this->categoryId,
            title: $this->title,
            slug: $this->slug,
            thumbnail: $this->thumbnail,
            abstract: $this->abstract,
            html: $this->html,
            markdown: $this->markdown,
            status: ArticleStatus::PUBLISHED,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
            tags: $this->tags,
        );
    }

    public function archive(): self
    {
        return new self(
            id: $this->id,
            userId: $this->userId,
            categoryId: $this->categoryId,
            title: $this->title,
            slug: $this->slug,
            thumbnail: $this->thumbnail,
            abstract: $this->abstract,
            html: $this->html,
            markdown: $this->markdown,
            status: ArticleStatus::ARCHIVED,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
            tags: $this->tags,
        );
    }

    public function draft(): self
    {
        return new self(
            id: $this->id,
            userId: $this->userId,
            categoryId: $this->categoryId,
            title: $this->title,
            slug: $this->slug,
            thumbnail: $this->thumbnail,
            abstract: $this->abstract,
            html: $this->html,
            markdown: $this->markdown,
            status: ArticleStatus::DRAFT,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
            tags: $this->tags,
        );
    }
}