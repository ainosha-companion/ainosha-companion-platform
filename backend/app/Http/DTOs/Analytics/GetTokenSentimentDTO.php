<?php

declare(strict_types=1);

namespace App\Http\DTOs\Analytics;

use App\Enums\Period;

class GetTokenSentimentDTO
{
    public function __construct(
        public string $symbol,
        public Period $period,
    ) {
    }
}
