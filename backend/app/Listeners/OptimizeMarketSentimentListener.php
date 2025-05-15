<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Analytics\MarketSentiment;
use App\Repositories\EloquentMarketSentimentRepository;
use App\Services\N8nService;
use App\Events\TriggerMarketSentimentDataEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OptimizeMarketSentimentListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 60; // 1 minute

    public function __construct(
        private readonly N8nService $n8nService,
        private readonly EloquentMarketSentimentRepository $repository,
    ) {
    }
    
    /**
     * Handle the market sentiment data optimization event.
     *
     * @param TriggerMarketSentimentDataEvent $event
     *
     * @return void
     */
    public function handle(TriggerMarketSentimentDataEvent $event): void
    {
        try {
            Log::info('OptimizeMarketSentimentListener: handle()');

            // Trigger n8n workflow to get market sentiment data
            $result = $this->n8nService->getMarketSentiment();

            Log::info('Market sentiment result', [
                'result' => $result->toArray(),
            ]);

            // Create a new MarketSentiment entity with the result data
            $marketSentiment = MarketSentiment::preload(
                type: 'market_sentiment',
                context: ['result' => $result->toArray()],
            );

            // Save the updated market sentiment to the repository (DB)
            $this->repository->save($marketSentiment);

            Log::info('Market sentiment data successfully updated and saved');
        } catch (\Exception $e) {
            Log::error("Failed to optimize market sentiment: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
