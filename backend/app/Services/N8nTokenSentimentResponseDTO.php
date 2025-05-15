<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Analytics\FailedToTriggerN8nServiceException;
use Illuminate\Support\Collection;

class N8nTokenSentimentResponseDTO
{
    /**
     * @var array
     */
    private array $tokenAnalysis;

    /**
     * @var array
     */
    private array $contentMetadata;

    /**
     * @var array
     */
    private array $technicalEvaluation;

    /**
     * @var array
     */
    private array $metadata;

    /**
     * @param array $tokenAnalysis
     * @param array $contentMetadata
     * @param array $technicalEvaluation
     * @param array $metadata
     */
    public function __construct(
        array $tokenAnalysis,
        array $contentMetadata,
        array $technicalEvaluation,
        array $metadata
    ) {
        $this->tokenAnalysis = $tokenAnalysis;
        $this->contentMetadata = $contentMetadata;
        $this->technicalEvaluation = $technicalEvaluation;
        $this->metadata = $metadata;
    }

    /**
     * Create a DTO from the n8n response array.
     *
     * @param array $response
     * @return self
     * @throws FailedToTriggerN8nServiceException
     */
    public static function fromResponse(array $response): self
    {
        if (!isset($response['result'])) {
            throw FailedToTriggerN8nServiceException::create('Invalid n8n response: missing "result" key');
        }

        $result = $response['result'];

        // Validate required keys
        $requiredKeys = ['token_analysis', 'content_metadata', 'technical_evaluation', 'metadata'];
        foreach ($requiredKeys as $key) {
            if (!isset($result[$key])) {
                throw FailedToTriggerN8nServiceException::create("Invalid n8n response: missing '{$key}' key in result");
            }
        }

        return new self(
            $result['token_analysis'],
            $result['content_metadata'],
            $result['technical_evaluation'],
            $result['metadata']
        );
    }

    /**
     * Get the token analysis data.
     *
     * @return array
     */
    public function getTokenAnalysis(): array
    {
        return $this->tokenAnalysis;
    }

    /**
     * Get the content metadata.
     *
     * @return array
     */
    public function getContentMetadata(): array
    {
        return $this->contentMetadata;
    }

    /**
     * Get the technical evaluation.
     *
     * @return array
     */
    public function getTechnicalEvaluation(): array
    {
        return $this->technicalEvaluation;
    }

    /**
     * Get the metadata.
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Get the target audience.
     *
     * @return string
     */
    public function getTargetAudience(): string
    {
        return $this->contentMetadata['target_audience'] ?? '';
    }

    /**
     * Get the reading time.
     *
     * @return string
     */
    public function getReadingTime(): string
    {
        return $this->contentMetadata['reading_time'] ?? '';
    }

    /**
     * Get the complexity level.
     *
     * @return int
     */
    public function getComplexityLevel(): int
    {
        return $this->contentMetadata['complexity_level'] ?? 0;
    }

    /**
     * Get the key topics.
     *
     * @return Collection
     */
    public function getKeyTopics(): Collection
    {
        return collect($this->contentMetadata['key_topics'] ?? []);
    }

    /**
     * Get the overall technical score.
     *
     * @return int
     */
    public function getTechnicalScore(): int
    {
        return $this->technicalEvaluation['overall_score'] ?? 0;
    }

    /**
     * Get the risk assessment.
     *
     * @return string
     */
    public function getRiskAssessment(): string
    {
        return $this->technicalEvaluation['risk_assessment'] ?? '';
    }

    /**
     * Get the investment recommendations.
     *
     * @return Collection
     */
    public function getInvestmentRecommendations(): Collection
    {
        return collect($this->technicalEvaluation['recommendations'] ?? []);
    }

    /**
     * Get the token analysis title.
     *
     * @return string
     */
    public function getTokenAnalysisTitle(): string
    {
        return $this->tokenAnalysis['title'] ?? '';
    }

    /**
     * Get the executive summary.
     *
     * @return string
     */
    public function getExecutiveSummary(): string
    {
        return $this->tokenAnalysis['executive_summary'] ?? '';
    }

    /**
     * Get the price outlook.
     *
     * @return string
     */
    public function getPriceOutlook(): string
    {
        return $this->tokenAnalysis['price_outlook'] ?? '';
    }

    /**
     * Get the recommendations.
     *
     * @return Collection
     */
    public function getRecommendations(): Collection
    {
        return collect($this->tokenAnalysis['recommendations'] ?? []);
    }

    /**
     * Get the recommendations HTML.
     *
     * @return string
     */
    public function getRecommendationsHtml(): string
    {
        return $this->tokenAnalysis['recommendations_html'] ?? '';
    }

    /**
     * Get the HTML report content.
     *
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->tokenAnalysis['html_content'] ?? '';
    }

    /**
     * Get the PDF report URL.
     *
     * @return string
     */
    public function getPdfUrl(): string
    {
        return $this->tokenAnalysis['pdf_url'] ?? '';
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token_analysis' => $this->tokenAnalysis,
            'content_metadata' => $this->contentMetadata,
            'technical_evaluation' => $this->technicalEvaluation,
            'metadata' => $this->metadata,
        ];
    }
}
