<?php

declare(strict_types=1);

namespace App\Entities\Analytics;

/**
 * TokenReportState represents the processed state of a token report
 * with various output formats (HTML, PDF).
 */
class TokenReportState
{
    private ?string $html = null;
    private ?string $pdf = null;

    /**
     * @param TokenReport $tokenReport The underlying token report data
     */
    public function __construct(
        private readonly TokenReport $tokenReport
    ) {
    }

    /**
     * Get the underlying token report entity.
     *
     * @return TokenReport
     */
    public function getTokenReport(): TokenReport
    {
        return $this->tokenReport;
    }

    /**
     * Get the HTML representation of the token report.
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
     * Set the HTML representation of the token report.
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
     * Get the PDF representation of the token report.
     *
     * @return string|null PDF content or null if not set
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    /**
     * Set the PDF representation of the token report.
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
