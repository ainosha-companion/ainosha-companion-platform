<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\DTOs\Analytics\GetTokenSentimentDTO;
use App\Handlers\Analytics\GetTokenSentimentHandler;
use App\Enums\Period;
use App\Http\Requests\Analytics\GetTokenSentimentRequest;
use App\Http\Responses\Analytics\GetTokenSentimentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class TokenSentimentController extends Controller
{
    /**
     * POST /api/v1/analytics/token
     * Returns token sentiment data formatted as HTML
     *
     * @param GetTokenSentimentRequest $request
     * @param GetTokenSentimentHandler $handler
     *
     * @return JsonResponse
     */
    public function getSentiment(GetTokenSentimentRequest $request, GetTokenSentimentHandler $handler): JsonResponse
    {
        $validated = $request->validated();
        $getTokenSentimentDTO = new GetTokenSentimentDTO(
            symbol: $validated['symbol'],
            period: Period::tryFrom($validated['period']),
        );

        // Get formatted token report state
        $tokenReportState = $handler->handle($getTokenSentimentDTO);

        // Log whether HTML is available
        if (!$tokenReportState->hasHtml()) {
            Log::info('Token report HTML is not available', [
                'token' => $tokenReportState->getTokenReport()->getToken()->getSymbol(),
                'type' => $tokenReportState->getTokenReport()->getType()->value,
                'expired' => $tokenReportState->getTokenReport()->isExpired(),
            ]);
        }

        return new GetTokenSentimentResponse($tokenReportState);
    }
}
