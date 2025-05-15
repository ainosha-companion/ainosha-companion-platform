<?php

declare(strict_types=1);

namespace App\ValueObjects\Content;

class AuthorId
{
    public function __construct(
        private readonly int $value
    ) {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(AuthorId $other): bool
    {
        return $this->value === $other->value;
    }
}