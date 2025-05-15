<?php

declare(strict_types=1);

namespace App\Enums;

enum Period: string
{
    case GENERIC = 'generic';
    case MINUTE = '1m';
    case FIVE_MINUTES = '5m';
    case FIFTEEN_MINUTES = '15m';
    case THIRTY_MINUTES = '30m';
    case HOUR = '1h';
    case FOUR_HOURS = '4h';
    case DAY = '1d';
    case WEEK = '7d';
    case MONTH = '30d';

    /**
     * Returns the array of cases.
     *
     * @return array
     */
    public static function toArray(): array
    {
        return array_map(
            fn (Period $period) => $period->value,
            self::cases()
        );
    }

    /**
     * @param Period $period
     *
     * @return int
     */
    public static function toSeconds(Period $period): int
    {
        return match ($period) {
            Period::GENERIC => 0,
            Period::MINUTE => 60,
            Period::FIVE_MINUTES => 300,
            Period::FIFTEEN_MINUTES => 900,
            Period::THIRTY_MINUTES => 1800,
            Period::HOUR => 3600,
            Period::FOUR_HOURS => 14400,
            Period::DAY => 86400,
            Period::WEEK => 604800,
            Period::MONTH => 2592000,
        };
    }
}