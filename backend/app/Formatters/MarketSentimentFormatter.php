<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Entities\Analytics\MarketSentiment;
use App\Entities\Analytics\MarketSentimentState;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\Log;
use App\Formatters\Config\PdfConfig;
use App\Formatters\Traits\PdfLoggingTrait;
use Barryvdh\Snappy\PdfWrapper as SnappyPdf;

/**
 * Formatter for converting MarketSentiment data into presentable formats (HTML, PDF).
 */
class MarketSentimentFormatter
{
    use PdfLoggingTrait;

    /**
     * @param ViewFactory $viewFactory Factory for rendering Blade views
     */
    public function __construct(
        private readonly ViewFactory $viewFactory,
        private readonly SnappyPdf $pdfGenerator,
        private readonly PdfConfig $pdfConfig
    ) {
    }

    /**
     * Format market sentiment data into a state object with HTML and PDF representation.
     *
     * @param MarketSentimentState $state The market sentiment state object to format
     * @return MarketSentimentState State object with HTML and PDF content set
     */
    public function format(MarketSentimentState $state): MarketSentimentState
    {
        $marketSentiment = $state->getMarketSentiment();
        Log::info('Market sentiment data: ' . json_encode($marketSentiment->getContext()));

        // Only generate HTML if we have valid context data
        $context = $marketSentiment->getContext();
        if ($this->isValidContext($context)) {
            try {
                // Generate HTML
                $html = $this->generateHtml($this->sanitizeContext($context));
                $state->setHtml($html);

                // Generate PDF
                if ($this->canGeneratePdf($marketSentiment)) {
                    try {
                        $pdf = $this->generatePdf($this->prepareContext($marketSentiment));
                        $state->setPdf($pdf);
                        $this->logSuccess($marketSentiment, $pdf);
                    } catch (\Throwable $e) {
                        $this->logError($marketSentiment, $e);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Failed to generate HTML for market sentiment', [
                    'error' => $e->getMessage(),
                    'context' => $context,
                ]);
            }
        } else {
            Log::warning('Cannot generate HTML: Invalid market sentiment context format', [
                'context_keys' => array_keys($context),
            ]);
        }

        return $state;
    }

    /**
     * Check if the context has the expected structure for rendering.
     *
     * @param array $context The context data
     * @return bool True if the context is valid for rendering
     */
    private function isValidContext(array $context): bool
    {
        // Minimum required keys for rendering the report
        return !empty($context) &&
               isset($context['result']) &&
               is_array($context['result']);
    }

    /**
     * Sanitize the context data to ensure the view won't break on missing fields.
     *
     * @param array $context The original context data
     * @return array Sanitized context data with fallbacks for missing fields
     */
    private function sanitizeContext(array $context): array
    {
        // Create a safe structure with empty defaults
        $safeContext = [
            'formatted_content' => [
                'title' => $this->getNestedValue($context, ['result', 'market_analysis', 'title'], 'Market Insight Report'),
                'executive_summary' => $this->getNestedValue($context, ['result', 'market_analysis', 'executive_summary'], ''),
                'bitcoin_analysis' => $this->getNestedValue($context, ['result', 'market_analysis', 'bitcoin_analysis'], ''),
                'altcoin_analysis' => $this->getNestedValue($context, ['result', 'market_analysis', 'altcoin_analysis'], ''),
                'market_comparison' => [
                    'us_markets' => $this->getNestedValue($context, ['result', 'market_analysis', 'market_comparison', 'us_markets'], ''),
                    'asian_markets' => $this->getNestedValue($context, ['result', 'market_analysis', 'market_comparison', 'asian_markets'], ''),
                    'comparative_outlook' => $this->getNestedValue($context, ['result', 'market_analysis', 'market_comparison', 'comparative_outlook'], ''),
                ],
                'technical_section' => $this->getNestedValue($context, ['result', 'market_analysis', 'technical_section'], ''),
                'sentiment_outlook' => $this->getNestedValue($context, ['result', 'market_analysis', 'sentiment_outlook'], ''),
                'volume_insights' => $this->getNestedValue($context, ['result', 'market_analysis', 'volume_insights'], ''),
                'correlation_analysis' => $this->getNestedValue($context, ['result', 'market_analysis', 'correlation_analysis'], ''),
                'risk_factors' => $this->getNestedValue($context, ['result', 'market_analysis', 'risk_factors'], ''),
                'recommendations' => $this->getNestedValue($context, ['result', 'market_analysis', 'recommendations'], []),
                'recommendations_html' => $this->getNestedValue($context, ['result', 'market_analysis', 'recommendations_html'], ''),
            ],
            'content_metadata' => [
                'target_audience' => $this->getNestedValue($context, ['result', 'content_metadata', 'target_audience'], 'Beginner'),
                'reading_time' => $this->getNestedValue($context, ['result', 'content_metadata', 'reading_time'], 'Estimated reading time is approximately 5 minutes'),
                'complexity_level' => $this->getNestedValue($context, ['result', 'content_metadata', 'complexity_level'], 1),
                'key_topics' => $this->getNestedValue($context, ['result', 'content_metadata', 'key_topics'], []),
            ],
        ];

        // Add academic evaluation if exists
        if (isset($context['result']['academic_evaluation'])) {
            $safeContext['academic_evaluation'] = $context['result']['academic_evaluation'];
            $safeContext['formatted_content']['academic_evaluation'] = $this->getNestedValue($context, ['result', 'market_analysis', 'academic_evaluation'], [
                'score' => 0,
                'classification' => 'Informational Grade',
                'summary' => '',
                'recommendations' => [],
            ]);
        }

        return $safeContext;
    }

    /**
     * Safely get a nested value from an array with a default fallback.
     *
     * @param array $array The array to extract value from
     * @param array $keys Sequence of keys to navigate the nested structure
     * @param mixed $default Default value if the path doesn't exist
     * @return mixed Retrieved value or default
     */
    private function getNestedValue(array $array, array $keys, $default)
    {
        $current = $array;

        foreach ($keys as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return $default;
            }
            $current = $current[$key];
        }

        return $current ?? $default;
    }

    /**
     * Generate HTML representation of market sentiment data.
     *
     * @param array $context The sanitized context data for market sentiment
     * @return string The rendered HTML
     */
    private function generateHtml(array $context): string
    {
        // Render the blade view with the sanitized context data
        $html = $this->viewFactory->make('analytics.market_insight_report_v1', [
            'json' => $context
        ])->render();

        // Clean the HTML by removing newlines and normalizing whitespace
        // This ensures the JSON response doesn't contain literal \n characters
        return str_replace(["\r\n", "\r", "\n"], '', $html);
    }

    /**
     * Generate PDF from market sentiment data using PDF template.
     *
     * @param array $context The sanitized context data for market sentiment
     * @return string Base64 encoded PDF content
     * @throws \Exception If PDF generation fails
     */
    public function generatePdf(array $context): string
    {
        try {
            $html = $this->renderPdfTemplate($context);
            $pdf = $this->createPdfFromHtml($html);
            $base64 = $this->convertToBase64($pdf);

            $this->logPdfGeneration($pdf, $base64);

            return $base64;
        } catch (\Throwable $e) {
            $this->logPdfError($e);
            throw $e;
        }
    }

    /**
     * Check if PDF can be generated from market sentiment
     *
     * @param MarketSentiment $marketSentiment
     * @return bool
     */
    private function canGeneratePdf(MarketSentiment $marketSentiment): bool
    {
        $context = $marketSentiment->getContext();
        return $this->isValidContext($context);
    }

    /**
     * Prepare context data for PDF generation
     *
     * @param MarketSentiment $marketSentiment
     * @return array
     */
    private function prepareContext(MarketSentiment $marketSentiment): array
    {
        return $this->sanitizeContext($marketSentiment->getContext());
    }

    /**
     * Render PDF template with context data
     *
     * @param array $context
     * @return string
     */
    private function renderPdfTemplate(array $context): string
    {
        $html = $this->viewFactory->make('analytics.market_insight_report_pdf_v1', [
            'json' => $context
        ])->render();

        // For PDF generation, we don't need to remove all newlines as they help
        // structure the HTML for the PDF renderer, but we should normalize whitespace
        return $html;
    }

    /**
     * Create PDF from HTML content
     *
     * @param string $html
     * @return string
     */
    private function createPdfFromHtml(string $html): string
    {
        $this->pdfConfig->applyTo($this->pdfGenerator);
        return $this->pdfGenerator->getOutputFromHtml($html);
    }

    /**
     * Convert PDF content to base64
     *
     * @param string $pdf
     * @return string
     */
    private function convertToBase64(string $pdf): string
    {
        return base64_encode($pdf);
    }
}