<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Response;

class BadValidationResponse extends AbstractResponse
{
    public function __construct(string $message)
    {
        $status = Response::HTTP_BAD_REQUEST;

        parent::__construct($status);

        $this->setSuccess(false);
        $this->setResult(['message' => $message]);
    }
}