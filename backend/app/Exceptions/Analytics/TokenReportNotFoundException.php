<?php

declare(strict_types=1);

namespace App\Exceptions\Analytics;

use App\Exceptions\NotFoundExceptionInterface;
use Exception;

class TokenReportNotFoundException extends Exception implements NotFoundExceptionInterface
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
