<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TriggerMarketSentimentDataEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}