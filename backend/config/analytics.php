<?php

declare(strict_types=1);

return [
    'n8n' => [
        'webhook_market_sentiment_basic_flow_url' => env('N8N_WEBHOOK_MARKET_SENTIMENT_BASIC_FLOW_URL'),
        'webhook_token_sentiment_basic_flow_url' => env('N8N_WEBHOOK_TOKEN_SENTIMENT_BASIC_FLOW_URL'),
    ],
    'market_sentiment' => [
        'reload_times' => [
            '00:00', '06:00', '12:00', '18:00',
        ],
    ],
    'token_sentiment' => [
        'default_expiration_seconds' => 6 * 60 * 60, // 6 hours
    ],
];
