<?php

declare(strict_types=1);

namespace App\Handlers\Analytics;

use App\Entities\Analytics\MarketSentiment;
use App\Entities\Analytics\MarketSentimentState;
use App\Enums\MarketSentimentType;
use App\Repositories\EloquentMarketSentimentRepository;
use App\Events\TriggerMarketSentimentDataEvent;
use App\Formatters\MarketSentimentFormatter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GetMarketSentimentHandler
{
    private const CACHE_KEY = 'market_sentiment_state';
    private const CACHE_LOCK_KEY = 'market_sentiment_dispatch_lock';
    private const CACHE_LOCK_TTL_MINUTES = 15;

    public function __construct(
        private readonly EloquentMarketSentimentRepository $marketSentimentRepository,
        private readonly MarketSentimentFormatter $formatter,
    ) {
    }

    /**
     * Handle the GetMarketSentiment request and format it into a state object.
     *
     * @return MarketSentimentState
     */
    public function handle(): MarketSentimentState
    {
        Log::info('GetMarketSentimentHandler: handle()');

        // Try to retrieve from cache first
        $cachedState = $this->getFromCache();
        if ($cachedState !== null) {
            Log::info('Using cached market sentiment data');
            return $cachedState;
        }

        // Retrieve the latest sentiment from the repository
        $latestSentiment = $this->marketSentimentRepository->getLatest();

        // Handle case when no sentiment data is available
        if ($latestSentiment === null) {
            $this->triggerRefreshEvent();
            Log::warning('No market sentiment data available, returning placeholder');
            return $this->createPlaceholderState();
        }

        // Handle expired sentiment data
        if ($latestSentiment->isExpired()) {
            Log::info('Market sentiment data is expired, triggering refresh');
            $this->triggerRefreshEvent();
        }

        // Create a new state object and format it
        $state = new MarketSentimentState($latestSentiment);
        $state = $this->formatter->format($state);
        $this->saveToCache($state);

        return $state;
    }

    /**
     * Retrieve cached market sentiment state.
     *
     * @return MarketSentimentState|null
     */
    private function getFromCache(): ?MarketSentimentState
    {
        $cachedData = Cache::get(self::CACHE_KEY);
        if (!$cachedData || !isset($cachedData['sentiment'], $cachedData['html'])) {
            return null;
        }

        try {
            $sentiment = $this->rebuildSentimentFromCache($cachedData['sentiment']);

            // If sentiment is expired, return null to trigger a refresh
            if ($sentiment->isExpired()) {
                return null;
            }

            // Rebuild the state object
            $state = new MarketSentimentState($sentiment);

            // Only set HTML if it's not empty
            if (!empty($cachedData['html'])) {
                $state->setHtml($cachedData['html']);
            }

            return $state;
        } catch (\Exception $e) {
            Log::error('Failed to rebuild market sentiment from cache', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Rebuild a MarketSentiment object from cached data.
     *
     * @param array $data Cached sentiment data
     * @return MarketSentiment
     */
    private function rebuildSentimentFromCache(array $data): MarketSentiment
    {
        return MarketSentiment::instance(
            id: $data['id'],
            type: $data['type'],
            context: $data['context'],
            createdAt: Carbon::parse($data['createdAt']),
            expiredAt: Carbon::parse($data['expiredAt'])
        );
    }

    /**
     * Save market sentiment state to cache.
     *
     * @param MarketSentimentState $state The state to cache
     * @return void
     */
    private function saveToCache(MarketSentimentState $state): void
    {
        $sentiment = $state->getMarketSentiment();
        $ttl = $this->calculateCacheTtl($sentiment);

        // If negative TTL, don't cache
        if ($ttl <= 0) {
            return;
        }

        $expiresAt = now()->addSeconds($ttl);

        $cacheData = [
            'sentiment' => [
                'id' => $sentiment->getId(),
                'type' => $sentiment->getType(),
                'context' => $sentiment->getContext(),
                'createdAt' => $sentiment->getCreatedAt()->toDateTimeString(),
                'expiredAt' => $sentiment->getExpiredAt()->toDateTimeString(),
            ],
            'html' => $state->getHtml(),
        ];

        Cache::put(self::CACHE_KEY, $cacheData, $expiresAt);
        Log::info('Market sentiment data cached', ['expires_at' => $expiresAt]);
    }

    /**
     * Calculate cache TTL based on sentiment expiration time.
     *
     * @param MarketSentiment $sentiment The sentiment entity
     * @return int TTL in seconds
     */
    private function calculateCacheTtl(MarketSentiment $sentiment): int
    {
        return (int) $sentiment->getExpiredAt()->diffInSeconds(now());
    }

    /**
     * Create a placeholder state when no data is available.
     *
     * @return MarketSentimentState
     */
    private function createPlaceholderState(): MarketSentimentState
    {
        $placeholderSentiment = MarketSentiment::preload(
            MarketSentimentType::BASIC->value,
            ['message' => 'No current data available']
        );

        // Create a state with no HTML - this indicates to the frontend
        // that no report is available yet
        return new MarketSentimentState($placeholderSentiment);
    }

    /**
     * Trigger the event to fetch new market sentiment data.
     *
     * @return void
     */
    private function triggerRefreshEvent(): void
    {
        // Use a lock to prevent multiple simultaneous refresh requests
        if (!Cache::has(self::CACHE_LOCK_KEY)) {
            Cache::put(self::CACHE_LOCK_KEY, true, now()->addMinutes(self::CACHE_LOCK_TTL_MINUTES));
            TriggerMarketSentimentDataEvent::dispatch();
            Log::info('Market sentiment refresh event dispatched');
        } else {
            Log::info('Market sentiment refresh already in progress');
        }
    }
}