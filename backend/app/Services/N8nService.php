<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Analytics\FailedToTriggerN8nServiceException;
use App\Entities\Analytics\BlockchainToken;
use App\Enums\Period;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Entities\Content\ArticleRequest;
use App\Models\Category;
class N8nService
{
    private const TIMEOUT_SECONDS = 1800;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    /**
     * Trigger the market sentiment workflow.
     *
     * @return N8nMarketSentimentResponseDTO
     * @throws FailedToTriggerN8nServiceException
     */
    public function getMarketSentiment(): N8nMarketSentimentResponseDTO
    {
        $webhookUrl = config('analytics.n8n.webhook_market_sentiment_basic_flow_url');
        $workflowName = 'market sentiment';

        Log::info("Triggering {$workflowName} workflow", ['webhook_url' => $webhookUrl]);

        try {
            $responseBody = $this->sendRequest(self::METHOD_GET, $webhookUrl);
            $decodedResponse = $this->extractOutput($responseBody, $webhookUrl);

            // Create DTO from the response
            $marketSentimentDTO = N8nMarketSentimentResponseDTO::fromResponse($decodedResponse);

            Log::info("{$workflowName} workflow triggered successfully", [
                'webhook_url' => $webhookUrl,
                'response'    => $responseBody,
            ]);

            return $marketSentimentDTO;
        } catch (Throwable $e) {
            $this->logError($workflowName, $webhookUrl, $e);
            throw FailedToTriggerN8nServiceException::create("Failed to trigger {$workflowName} workflow: " . $e->getMessage());
        }
    }

    /**
     * Trigger the token sentiment workflow.
     *
     * @param BlockchainToken  $token
     * @param Period $period
     *
     * @return N8nTokenSentimentResponseDTO
     * @throws FailedToTriggerN8nServiceException
     */
    public function getTokenSentiment(BlockchainToken $token, Period $period): N8nTokenSentimentResponseDTO
    {
        $webhookUrl = config('analytics.n8n.webhook_token_sentiment_basic_flow_url');
        $workflowName = 'token sentiment';

        Log::info("Triggering {$workflowName} workflow", ['webhook_url' => $webhookUrl]);

        try {
            $payload = [
                'context' => 'Analyze the latest market trends for ' . $token->getName(),
                'period'  => $period->value,
            ];

            $responseBody = $this->sendRequest(self::METHOD_POST, $webhookUrl, $payload);

            $decodedResponse = $this->extractOutput($responseBody, $webhookUrl);

            // Create DTO from the response
            $tokenSentimentDTO = N8nTokenSentimentResponseDTO::fromResponse($decodedResponse);

            Log::info("{$workflowName} workflow triggered successfully", [
                'webhook_url' => $webhookUrl,
                'response'    => $responseBody,
            ]);

            return $tokenSentimentDTO;
        } catch (Throwable $e) {
            $this->logError($workflowName, $webhookUrl, $e);
            throw FailedToTriggerN8nServiceException::create("Failed to trigger {$workflowName} workflow: " . $e->getMessage());
        }
    }

    /**
     * Send a HTTP request using the specified method.
     *
     * @param string $method HTTP method ('GET' or 'POST')
     * @param string $webhookUrl The URL to call
     * @param array $data Optional payload for POST requests
     *
     * @return string
     * @throws FailedToTriggerN8nServiceException
     * @throws ConnectionException
     */
    private function sendRequest(string $method, string $webhookUrl, array $data = []): string
    {
        $http = Http::timeout(self::TIMEOUT_SECONDS);
        $response = ($method === 'POST')
            ? $http->post($webhookUrl, $data)
            : $http->get($webhookUrl);

        if ($response->failed()) {
            $errorMessage = sprintf(
                'Request failed. Status code: %s, Body: %s',
                $response->status(),
                $response->body()
            );
            Log::error($errorMessage, ['webhook_url' => $webhookUrl]);
            throw FailedToTriggerN8nServiceException::create($errorMessage);
        }

        return $response->body();
    }

    /**
     * Extract the 'output' element from the JSON response.
     *
     * @param string $responseBody
     * @param string $webhookUrl
     *
     * @return array
     * @throws FailedToTriggerN8nServiceException
     */
    private function extractOutput(string $responseBody, string $webhookUrl): array
    {
        $decoded = json_decode($responseBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorMessage = 'Invalid JSON response from n8n: ' . $responseBody;
            Log::error($errorMessage, ['webhook_url' => $webhookUrl]);
            throw FailedToTriggerN8nServiceException::create($errorMessage);
        }

        return $decoded;
    }

    /**
     * Log an error with the specific workflow context.
     *
     * @param string    $workflowName
     * @param string    $webhookUrl
     * @param Throwable $e
     *
     * @return void
     */
    private function logError(string $workflowName, string $webhookUrl, Throwable $e): void
    {
        $errorMessage = sprintf('Failed to trigger %s workflow: %s', $workflowName, $e->getMessage());
        Log::error($errorMessage, [
            'webhook_url' => $webhookUrl,
            'exception'   => $e->getTraceAsString(),
        ]);
    }

    /**
     * Trigger the article creation workflow.
     *
     * @param ArticleRequest $articleRequest
     *
     * @return bool
     */
    public function createArticle(ArticleRequest $articleRequest): bool
    {
        $webhookUrl = config('content.n8n.webhook_article_creation_flow_url');
        $workflowName = 'article creation';

        Log::info("Triggering {$workflowName} workflow", ['webhook_url' => $webhookUrl]);

        try {
            $payload = [
                'request_id' => $articleRequest->getId()->getValue(),
                'topic' => $articleRequest->getRequestData()['topic'],
                'status' => $articleRequest->getStatus()->value,
                'haveDeepResearch' => false,
                'style' => $articleRequest->getRequestData()['style'],
                'targetAudience' => $articleRequest->getRequestData()['target_audience'],
                'category' => Category::find($articleRequest->getRequestData()['category_id'])->id,
                'minWordCount' => $articleRequest->getRequestData()['minimum_words'],
                'maxWordCount' => $articleRequest->getRequestData()['maximum_words'],
                'chapterNumber' => $articleRequest->getRequestData()['number_of_chapters'],
                'introductionLength' => $articleRequest->getRequestData()['introduction_length'],
                'conclusionLength' => $articleRequest->getRequestData()['conclusion_length'],
                'reference' => $articleRequest->getRequestData()['reference_articles'] ? implode(',', $articleRequest->getRequestData()['reference_articles']) : '',
                'metaKeywords' => $articleRequest->getRequestData()['meta_keywords'] ? implode(',', $articleRequest->getRequestData()['meta_keywords']) : '',
            ];

            $responseBody = $this->sendRequest(self::METHOD_POST, $webhookUrl, $payload);
            $decodedResponse = $this->extractOutput($responseBody, $webhookUrl);

            Log::info("{$workflowName} workflow triggered successfully", [
                'webhook_url' => $webhookUrl,
                'response'    => $responseBody,
                'decoded_response' => $decodedResponse,
            ]);

            return true;
        } catch (Throwable $e) {
            $this->logError($workflowName, $webhookUrl, $e);
            throw FailedToTriggerN8nServiceException::create("Failed to trigger {$workflowName} workflow: " . $e->getMessage());
        }
    }
}
