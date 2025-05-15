<?php

declare(strict_types=1);

namespace App\ValueObjects\User;

final class UserId 
{
    public function __construct(private readonly int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals($other): bool
    {
        return $other instanceof self && $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}