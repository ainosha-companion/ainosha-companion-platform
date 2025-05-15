<?php

declare(strict_types=1);

namespace App\Handlers\Analytics;

use App\Http\DTOs\Analytics\GetTokenSentimentDTO;
use App\Entities\Analytics\TokenReport;
use App\Entities\Analytics\TokenReportState;
use App\Enums\Period;
use App\Repositories\EloquentTokenReportRepository;
use App\Repositories\EloquentBlockchainTokenRepository;
use App\Formatters\TokenReportFormatter;
use App\Events\TriggerTokenSentimentDataEvent;
use App\Exceptions\Analytics\TokenReportNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class GetTokenSentimentHandler
{
    private const CACHE_KEY_PREFIX = 'token_report_state:';
    private const CACHE_LOCK_KEY_PREFIX = 'token_report_dispatch_lock:';
    private const CACHE_LOCK_TTL_MINUTES = 15;

    public function __construct(
        private readonly EloquentTokenReportRepository $tokenReportRepository,
        private readonly EloquentBlockchainTokenRepository $tokenRepository,
        private readonly TokenReportFormatter $formatter,
    ) {
    }

    /**
     * Handle the GetTokenSentiment request and format it into a state object.
     *
     * @param GetTokenSentimentDTO $getTokenSentimentDTO
     * @return TokenReportState
     */
    public function handle(GetTokenSentimentDTO $getTokenSentimentDTO): TokenReportState
    {
        $tokenSymbol = $getTokenSentimentDTO->symbol;
        $type = $getTokenSentimentDTO->period;
        $token = $this->tokenRepository->findBySymbol($tokenSymbol);

        Log::info('GetTokenSentimentHandler: handle()', ['token' => $tokenSymbol, 'period' => $type->value]);

        // Try to retrieve from cache first
        $cacheKey = self::CACHE_KEY_PREFIX . $tokenSymbol . ':' . $type->value;
        $cachedState = $this->getFromCache($cacheKey);
        if ($cachedState !== null) {
            Log::info('Using cached token report data', ['token' => $tokenSymbol]);
            return $cachedState;
        }

        // Retrieve the latest report from the repository
        try {
            $latestReport = $this->tokenReportRepository->getLatestForToken($tokenSymbol, $type);
        } catch (TokenReportNotFoundException $e) {
            $this->triggerRefreshEvent($token, $type);
            Log::warning('No token report data available, returning placeholder', ['token' => $tokenSymbol]);
            return $this->createPlaceholderState($token, $type);
        }

        // Handle expired report data
        if ($latestReport->isExpired()) {
            Log::info('Token report data is expired, triggering refresh', ['token' => $tokenSymbol]);
            $this->triggerRefreshEvent($token, $type);
        }

        // Format and cache the report data
        $state = $this->formatter->format($latestReport);
        $this->saveToCache($cacheKey, $state);

        return $state;
    }

    /**
     * Retrieve cached token report state.
     *
     * @param string $cacheKey
     * @return TokenReportState|null
     */
    private function getFromCache(string $cacheKey): ?TokenReportState
    {
        $cachedData = Cache::get($cacheKey);
        if (!$cachedData || !isset($cachedData['report'], $cachedData['html'])) {
            return null;
        }

        try {
            $report = $this->rebuildReportFromCache($cachedData['report']);

            // If report is expired, return null to trigger a refresh
            if ($report->isExpired()) {
                return null;
            }

            // Rebuild the state object
            $state = new TokenReportState($report);

            // Only set HTML if it's not empty
            if (!empty($cachedData['html'])) {
                $state->setHtml($cachedData['html']);
            }

            return $state;
        } catch (\Exception $e) {
            Log::error('Failed to rebuild token report from cache', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Rebuild a TokenReport object from cached data.
     *
     * @param array $data Cached report data
     * @return TokenReport
     */
    private function rebuildReportFromCache(array $data): TokenReport
    {
        $token = $this->tokenRepository->findBySymbol($data['tokenSymbol']);

        return TokenReport::instance(
            id: $data['id'],
            token: $token,
            type: Period::from($data['type']),
            context: $data['context'],
            createdAt: Carbon::parse($data['createdAt']),
            expiredAt: Carbon::parse($data['expiredAt'])
        );
    }

    /**
     * Save token report state to cache.
     *
     * @param string $cacheKey
     * @param TokenReportState $state The state to cache
     * @return void
     */
    private function saveToCache(string $cacheKey, TokenReportState $state): void
    {
        $report = $state->getTokenReport();
        $ttl = $this->calculateCacheTtl($report);

        // If negative TTL, don't cache
        if ($ttl <= 0) {
            return;
        }

        $expiresAt = now()->addSeconds($ttl);

        $cacheData = [
            'report' => [
                'id' => $report->getId(),
                'tokenSymbol' => $report->getToken()->getSymbol(),
                'type' => $report->getType()->value,
                'context' => $report->getContext(),
                'createdAt' => $report->getCreatedAt()->toDateTimeString(),
                'expiredAt' => $report->getExpiredAt()->toDateTimeString(),
            ],
            'html' => $state->getHtml(),
        ];

        Cache::put($cacheKey, $cacheData, $expiresAt);
        Log::info('Token report data cached', [
            'token' => $report->getToken()->getSymbol(),
            'expires_at' => $expiresAt
        ]);
    }

    /**
     * Calculate cache TTL based on report expiration time.
     *
     * @param TokenReport $report The report entity
     * @return int TTL in seconds
     */
    private function calculateCacheTtl(TokenReport $report): int
    {
        return (int) $report->getExpiredAt()->diffInSeconds(now());
    }

    /**
     * Create a placeholder state when no data is available.
     *
     * @param mixed $token
     * @param Period $type
     * @return TokenReportState
     */
    private function createPlaceholderState($token, Period $type): TokenReportState
    {
        $placeholderReport = TokenReport::preload(
            $token,
            $type,
            ['message' => 'No current data available']
        );

        // Create a state with no HTML - this indicates to the frontend
        // that no report is available yet
        return new TokenReportState($placeholderReport);
    }

    /**
     * Trigger the event to fetch new token report data.
     *
     * @param mixed $token
     * @param Period $type
     * @return void
     */
    private function triggerRefreshEvent($token, Period $type): void
    {
        $lockKey = self::CACHE_LOCK_KEY_PREFIX . $token->getSymbol() . ':' . $type->value;

        // Use a lock to prevent multiple simultaneous refresh requests
        if (!Cache::has($lockKey)) {
            Cache::put($lockKey, true, now()->addMinutes(self::CACHE_LOCK_TTL_MINUTES));
            TriggerTokenSentimentDataEvent::dispatch($token, $type);
            Log::info('Token report refresh event dispatched', [
                'token' => $token->getSymbol(),
                'period' => $type->value
            ]);
        } else {
            Log::info('Token report refresh already in progress', [
                'token' => $token->getSymbol()
            ]);
        }
    }
}
