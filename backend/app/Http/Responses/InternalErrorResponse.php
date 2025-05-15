<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Response;

class InternalErrorResponse extends AbstractResponse
{
    public function __construct(string $message)
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        parent::__construct($status);

        $this->setSuccess(false);
        $this->setResult(['message' => $message]);
    }
}