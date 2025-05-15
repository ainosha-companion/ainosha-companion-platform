<?php

declare(strict_types=1);

namespace App\Domain\Content\Entities;

use DateTimeInterface;
use App\Domain\Content\ValueObjects\CategoryId;

readonly class Category
{
    /**
     * @param CategoryId $id
     * @param string $name
     * @param string $slug
     * @param string|null $description
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     * @param DateTimeInterface|null $deletedAt
     */
    private function __construct(
        public CategoryId $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
        public ?DateTimeInterface $deletedAt = null,
    ) {}

    /**
     * Create a new category
     *
     * @param CategoryId $id
     * @param string $name
     * @param string $slug
     * @param string|null $description
     * @return self
     */
    public static function create(
        CategoryId $id,
        string $name,
        string $slug,
        ?string $description = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            slug: $slug,
            description: $description,
        );
    }

    /**
     * Create an instance from existing data
     *
     * @param CategoryId $id
     * @param string $name
     * @param string $slug
     * @param string|null $description
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     * @param DateTimeInterface|null $deletedAt
     * @return self
     */
    public static function instance(
        CategoryId $id,
        string $name,
        string $slug,
        ?string $description = null,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null,
        ?DateTimeInterface $deletedAt = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            slug: $slug,
            description: $description,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    /**
     * Create a new instance with updated name
     */
    public function withName(string $name): self
    {
        return new self(
            id: $this->id,
            name: $name,
            slug: $this->slug,
            description: $this->description,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
        );
    }

    /**
     * Create a new instance with updated slug
     */
    public function withSlug(string $slug): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            slug: $slug,
            description: $this->description,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
        );
    }

    /**
     * Create a new instance with updated description
     */
    public function withDescription(?string $description): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            slug: $this->slug,
            description: $description,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: $this->deletedAt,
        );
    }
}