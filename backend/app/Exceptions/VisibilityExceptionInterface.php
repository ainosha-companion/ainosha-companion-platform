<?php

declare(strict_types=1);

namespace App\Exceptions;

interface VisibilityExceptionInterface
{
    /**
     * Returns the visibility of the exception.
     *
     * @return string
     */
    public function getVisibility(): string;

    /**
     * Returns the status of the exception.
     *
     * @return int
     */
    public function getStatus(): int;
}
