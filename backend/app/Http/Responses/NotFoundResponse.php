<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Response;

class NotFoundResponse extends AbstractResponse
{
    public function __construct(string $message)
    {
        $status = Response::HTTP_NOT_FOUND;

        parent::__construct($status);

        $this->setSuccess(false);
        $this->setResult(['message' => $message]);
    }
}