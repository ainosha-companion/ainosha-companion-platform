<?php

declare(strict_types=1);

namespace App\Exceptions\Analytics;

use Exception;

class FailedToTriggerN8nServiceException extends Exception
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}