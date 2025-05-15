<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Article Request Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for AI-generated article requests.
    |
    */

    // Number of days until an article request expires if not processed
    'article_request_expiration_days' => env('ARTICLE_REQUEST_EXPIRATION_DAYS', 5),

    // Maximum allowed concurrent requests per user
    'max_concurrent_requests_per_user' => env('MAX_CONCURRENT_REQUESTS_PER_USER', 3),

    // Default settings for article generation
    'default_article_settings' => [
        'minimum_words' => 500,
        'maximum_words' => 2000,
        'number_of_chapters' => 3,
        'introduction_length' => 100,
        'conclusion_length' => 100,
    ],

    'n8n' => [
        'webhook_article_creation_flow_url' => env('N8N_WEBHOOK_ARTICLE_CREATION_FLOW_URL'),
    ],
];