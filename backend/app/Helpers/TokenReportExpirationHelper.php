<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\Period;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Config;

class TokenReportExpirationHelper
{
    private const DEFAULT_MAX_EXPIRATION_SECONDS = 21600; // 6 hours

    /**
     * Calculate the expiration time for a token report based on its type and creation time.
     *
     * @param Period $type
     * @param CarbonInterface $createdAt
     * @return CarbonInterface
     */
    public static function calculateExpiredAt(Period $type, CarbonInterface $createdAt): CarbonInterface
    {
        $periodSeconds = max(Period::toSeconds($type), 300); // Ensure minimum period of 5 minutes
        $maxExpirationSeconds = Config::get('analytics.token_sentiment.default_expiration_seconds', self::DEFAULT_MAX_EXPIRATION_SECONDS);

        return $createdAt->addSeconds(
            min($periodSeconds, $maxExpirationSeconds)
        );
    }

    /**
     * Calculate the cache expiration time in seconds for a token report.
     *
     * @param Period $type
     * @return int
     */
    public static function calculateCacheExpirationSeconds(Period $type): int
    {
        $periodSeconds = max(Period::toSeconds($type), 300); // Ensure minimum period of 5 minutes
        $maxExpirationSeconds = Config::get('analytics.token_sentiment.default_expiration_seconds', self::DEFAULT_MAX_EXPIRATION_SECONDS);

        return min($periodSeconds, $maxExpirationSeconds);
    }
}