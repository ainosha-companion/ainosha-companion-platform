<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Analytics\TokenReport;
use App\Events\TriggerTokenSentimentDataEvent;
use App\Services\N8nService;
use App\Repositories\EloquentTokenReportRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TokenSentimentListener implements ShouldQueue
{
    use InteractsWithQueue;

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

    /**
     * Cache lock key prefix for token sentiment data processing
     */
    private const CACHE_LOCK_KEY_PREFIX = 'token_report_dispatch_lock:';

    public function __construct(
        private readonly N8nService $n8nService,
        private readonly EloquentTokenReportRepository $tokenReportRepository,
    ) {
    }

    /**
     * Handle the token sentiment data event.
     *
     * @param TriggerTokenSentimentDataEvent $event
     *
     * @return void
     */
    public function handle(TriggerTokenSentimentDataEvent $event): void
    {
        try {
            Log::info('TokenSentimentListener: handle()', [
                'token_symbol' => $event->token->getSymbol(),
                'period' => $event->period->value,
            ]);

            // Trigger n8n workflow to get token sentiment data
            $result = $this->n8nService->getTokenSentiment($event->token, $event->period);

            Log::info('Token sentiment result', [
                'token_symbol' => $event->token->getSymbol(),
                'period' => $event->period->value,
                'has_html' => !empty($result->getHtmlContent()),
                'has_pdf' => !empty($result->getPdfUrl()),
            ]);

            // Create token report with the data from N8n
            $tokenReport = TokenReport::preload(
                token: $event->token,
                type: $event->period,
                context: ['result' => $result->toArray()],
            );

            // Save the token report to the repository (DB)
            $this->tokenReportRepository->save($tokenReport);

            // Clear the lock after successful processing
            $lockKey = self::CACHE_LOCK_KEY_PREFIX . $event->token->getSymbol() . ':' . $event->period->value;
            Cache::forget($lockKey);

            Log::info('Token sentiment data successfully updated and saved', [
                'token_symbol' => $event->token->getSymbol(),
                'period' => $event->period->value,
                'report_id' => $tokenReport->getId(),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to process token sentiment: " . $e->getMessage(), [
                'token_symbol' => $event->token->getSymbol(),
                'period' => $event->period->value,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Generate a unique ID for the job to prevent overlapping.
     *
     * @param TriggerTokenSentimentDataEvent $event
     * @return string
     */
    protected function uniqueId(TriggerTokenSentimentDataEvent $event): string
    {
        return 'token-sentiment-' . $event->token->getSymbol() . '-' . $event->period->value;
    }
}
