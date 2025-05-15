<?php

declare(strict_types=1);

namespace App\ValueObjects\Content;

readonly class ArticleId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}