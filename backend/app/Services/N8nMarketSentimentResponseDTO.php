<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Analytics\FailedToTriggerN8nServiceException;
use Illuminate\Support\Collection;

class N8nMarketSentimentResponseDTO
{
    /**
     * @var array
     */
    private array $marketAnalysis;

    /**
     * @var array
     */
    private array $contentMetadata;

    /**
     * @var array
     */
    private array $academicEvaluation;

    /**
     * @var array
     */
    private array $metadata;

    /**
     * @param array $marketAnalysis
     * @param array $contentMetadata
     * @param array $academicEvaluation
     * @param array $metadata
     */
    public function __construct(
        array $marketAnalysis,
        array $contentMetadata,
        array $academicEvaluation,
        array $metadata
    ) {
        $this->marketAnalysis = $marketAnalysis;
        $this->contentMetadata = $contentMetadata;
        $this->academicEvaluation = $academicEvaluation;
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
        $requiredKeys = ['market_analysis', 'content_metadata', 'academic_evaluation', 'metadata'];
        foreach ($requiredKeys as $key) {
            if (!isset($result[$key])) {
                throw FailedToTriggerN8nServiceException::create("Invalid n8n response: missing '{$key}' key in result");
            }
        }

        return new self(
            $result['market_analysis'],
            $result['content_metadata'],
            $result['academic_evaluation'],
            $result['metadata']
        );
    }

    /**
     * Get the market analysis data.
     *
     * @return array
     */
    public function getMarketAnalysis(): array
    {
        return $this->marketAnalysis;
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
     * Get the academic evaluation.
     *
     * @return array
     */
    public function getAcademicEvaluation(): array
    {
        return $this->academicEvaluation;
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
     * Get the overall academic score.
     *
     * @return int
     */
    public function getAcademicScore(): int
    {
        return $this->academicEvaluation['overall_score'] ?? 0;
    }

    /**
     * Get the academic classification.
     *
     * @return string
     */
    public function getAcademicClassification(): string
    {
        return $this->academicEvaluation['classification'] ?? '';
    }

    /**
     * Get the academic recommendations.
     *
     * @return Collection
     */
    public function getAcademicRecommendations(): Collection
    {
        return collect($this->academicEvaluation['recommendations'] ?? []);
    }

    /**
     * Get the market analysis title.
     *
     * @return string
     */
    public function getMarketAnalysisTitle(): string
    {
        return $this->marketAnalysis['title'] ?? '';
    }

    /**
     * Get the executive summary.
     *
     * @return string
     */
    public function getExecutiveSummary(): string
    {
        return $this->marketAnalysis['executive_summary'] ?? '';
    }

    /**
     * Get the recommendations.
     *
     * @return Collection
     */
    public function getRecommendations(): Collection
    {
        return collect($this->marketAnalysis['recommendations'] ?? []);
    }

    /**
     * Get the recommendations HTML.
     *
     * @return string
     */
    public function getRecommendationsHtml(): string
    {
        return $this->marketAnalysis['recommendations_html'] ?? '';
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'market_analysis' => $this->marketAnalysis,
            'content_metadata' => $this->contentMetadata,
            'academic_evaluation' => $this->academicEvaluation,
            'metadata' => $this->metadata,
        ];
    }
}