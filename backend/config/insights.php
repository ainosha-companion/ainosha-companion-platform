<?php

declare(strict_types=1);

return [
    'coinmarketcap' => [
        'url' => [
            'base' => env('COINMARKETCAP_URL_BASE', 'https://pro-api.coinmarketcap.com/v3'),
            'fear_and_greed_historical' => env('COINMARKETCAP_URL_FEAR_AND_GREED_HISTORICAL', '/fear-and-greed/historical'),
        ],
        'security' => [
            'api_key' => env('COINMARKETCAP_API_KEY'),
        ],
    ],
];
