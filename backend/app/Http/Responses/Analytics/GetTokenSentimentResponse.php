<?php

declare(strict_types=1);

namespace App\Http\Responses\Analytics;

use App\Entities\Analytics\TokenReportState;
use App\Http\Responses\AbstractResponse;
use Illuminate\Http\Response;

class GetTokenSentimentResponse extends AbstractResponse
{
    /**
     * Constructs a response containing the HTML representation of token sentiment.
     * If HTML is not available, the response will indicate this with html_available = false.
     *
     * @param TokenReportState $tokenReportState The formatted token report state
     */
    public function __construct(TokenReportState $tokenReportState)
    {
        parent::__construct(Response::HTTP_OK);

        $tokenReport = $tokenReportState->getTokenReport();
        $token = $tokenReport->getToken();

        $this->setSuccess(true);
        $this->setResult([
            'report' => [
                'id' => $tokenReport->getId(),
                'type' => $tokenReport->getType()->value,
                'html' => $tokenReportState->getHtml(),
                'pdf' => $tokenReportState->getPdf(),
                'created_at' => $tokenReport->getCreatedAt()->toDateTimeString(),
                'expired_at' => $tokenReport->getExpiredAt()->toDateTimeString(),
                'token' => [
                    'symbol' => $token->getSymbol(),
                    'name' => $token->getName(),
                    'address' => $token->getContractAddress(),
                ],
            ],
        ]);
    }
}
