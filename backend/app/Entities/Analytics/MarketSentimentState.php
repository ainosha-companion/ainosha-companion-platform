<?php

declare(strict_types=1);

namespace App\Entities\Analytics;

/**
 * MarketSentimentState represents the processed state of market sentiment data
 * with various output formats (HTML, PDF).
 */
class MarketSentimentState
{
    private ?string $html = null;
    private ?string $pdf = null;

    /**
     * @param MarketSentiment $marketSentiment The underlying market sentiment data
     */
    public function __construct(
        private readonly MarketSentiment $marketSentiment
    ) {
    }

    /**
     * Get the underlying market sentiment entity.
     *
     * @return MarketSentiment
     */
    public function getMarketSentiment(): MarketSentiment
    {
        return $this->marketSentiment;
    }

    /**
     * Get the HTML representation of the market sentiment.
     *
     * @return string|null HTML content or null if not set
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    /**
     * Check if the HTML representation is available.
     *
     * @return bool True if HTML is available
     */
    public function hasHtml(): bool
    {
        return $this->html !== null && trim($this->html) !== '';
    }

    /**
     * Set the HTML representation of the market sentiment.
     *
     * @param string $html The HTML content
     * @return self
     */
    public function setHtml(string $html): self
    {
        $this->html = $html;
        return $this;
    }

    /**
     * Get the PDF representation of the market sentiment.
     *
     * @return string|null PDF content or null if not set
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    /**
     * Set the PDF representation of the market sentiment.
     *
     * @param string $pdf The PDF content
     * @return self
     */
    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }
}