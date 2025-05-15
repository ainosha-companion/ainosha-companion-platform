<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Response;

class UnauthorizedResponse extends AbstractResponse
{
    public function __construct(string $message = 'Invalid or missing API key')
    {
        $status = Response::HTTP_UNAUTHORIZED;

        parent::__construct($status);

        $this->setSuccess(false);
        $this->setResult(['message' => $message]);
    }
}