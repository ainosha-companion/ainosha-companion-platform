<?php

declare(strict_types=1);

namespace App\Enums;

enum MarketSentimentType: string
{
    case BASIC = 'basic';
    case PREMIUM = 'premium';
}