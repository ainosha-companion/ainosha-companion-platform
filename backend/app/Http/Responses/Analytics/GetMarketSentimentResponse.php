<?php

declare(strict_types=1);

namespace App\Http\Responses\Analytics;

use App\Entities\Analytics\MarketSentimentState;
use App\Http\Responses\AbstractResponse;
use Illuminate\Http\Response;

class GetMarketSentimentResponse extends AbstractResponse
{
    /**
     * Constructs a response containing the HTML representation of market sentiment.
     * If HTML is not available, the response will indicate this with html_available = false.
     *
     * @param MarketSentimentState $marketSentimentState The formatted market sentiment state
     */
    public function __construct(MarketSentimentState $marketSentimentState)
    {
        parent::__construct(Response::HTTP_OK);

        $this->setSuccess(true);
        $this->setResult(
            [
                'market' => [
                    'type' => $marketSentimentState->getMarketSentiment()->getType(),
                    'html' => $marketSentimentState->getHtml(),
                    'pdf' => $marketSentimentState->getPdf(),
                    'expired_at' => $marketSentimentState->getMarketSentiment()->getExpiredAt()?->toDateTimeString(),
                ],
            ]
        );
    }
}