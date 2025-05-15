<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\Period;
use App\Entities\Analytics\BlockchainToken;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TriggerTokenSentimentDataEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public BlockchainToken $token,
        public Period $period,
    ) {
    }
}
