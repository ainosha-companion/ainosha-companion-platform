<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Analytics\GetMarketSentimentHandler;
use App\Http\Responses\Analytics\GetMarketSentimentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MarketSentimentController extends Controller
{
    /**
     * @var GetMarketSentimentHandler
     */
    private GetMarketSentimentHandler $getMarketSentimentHandler;

    /**
     * MarketSentimentController constructor.
     *
     * @param GetMarketSentimentHandler $getMarketSentimentHandler
     */
    public function __construct(GetMarketSentimentHandler $getMarketSentimentHandler)
    {
        $this->getMarketSentimentHandler = $getMarketSentimentHandler;
    }

    /**
     * Controller method for GET /api/v1/analytics/market-sentiment
     * Returns market sentiment data formatted as HTML
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDailyInsight(Request $request): JsonResponse
    {
        // Get formatted market sentiment state
        $marketSentimentState = $this->getMarketSentimentHandler->handle();

        // Log whether HTML is available
        if (!$marketSentimentState->hasHtml()) {
            Log::info('Market sentiment HTML is not available', [
                'type' => $marketSentimentState->getMarketSentiment()->getType(),
                'expired' => $marketSentimentState->getMarketSentiment()->isExpired(),
            ]);
        }

        return new GetMarketSentimentResponse($marketSentimentState);
    }
}