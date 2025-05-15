<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Entities\Analytics\TokenReport;
use App\Entities\Analytics\TokenReportState;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\Log;
use App\Formatters\Config\PdfConfig;
use App\Formatters\Traits\PdfLoggingTrait;
use Barryvdh\Snappy\PdfWrapper as SnappyPdf;

/**
 * Formatter for converting TokenReport data into presentable formats (HTML, PDF).
 */
class TokenReportFormatter
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
     * Format token report data into a state object with HTML representation.
     *
     * @param TokenReport $tokenReport The token report data to format
     * @return TokenReportState State object with HTML content set
     */
    public function format(TokenReport $tokenReport): TokenReportState
    {
        $state = new TokenReportState($tokenReport);

        Log::info('Token report data: ' . json_encode($tokenReport->getContext()));

        // Only generate HTML if we have valid context data
        $context = $tokenReport->getContext();
        if ($this->isValidContext($context)) {
            try {
                $html = $this->generateHtml($this->sanitizeContext($context));
                $state->setHtml($html);
            } catch (\Throwable $e) {
                Log::error('Failed to generate HTML for token report', [
                    'error' => $e->getMessage(),
                    'token' => $tokenReport->getToken()->getSymbol(),
                    'context' => $context,
                ]);
            }
        } else {
            Log::warning('Cannot generate HTML: Invalid token report context format', [
                'token' => $tokenReport->getToken()->getSymbol(),
                'context_keys' => is_array($context) ? array_keys($context) : 'non-array',
            ]);
        }

        return $state;
    }

    /**
     * Format token report data into a state object with HTML and PDF representation.
     *
     * @param TokenReport $tokenReport The token report data to format
     * @return TokenReportState State object with HTML and PDF content set
     */
    public function formatWithPdf(TokenReport $tokenReport): TokenReportState
    {
        $state = new TokenReportState($tokenReport);

        // First set HTML content
        $state = $this->format($tokenReport);

        if ($this->canGeneratePdf($tokenReport)) {
            try {
                $pdf = $this->generatePdf($this->prepareContext($tokenReport));
                $state->setPdf($pdf);
                $this->logSuccess($tokenReport, $pdf);
            } catch (\Throwable $e) {
                $this->logError($tokenReport, $e);
            }
        }

        return $state;
    }

    /**
     * Check if the context has the expected structure for rendering.
     *
     * @param mixed $context The context data
     * @return bool True if the context is valid for rendering
     */
    private function isValidContext($context): bool
    {
        // Minimum required keys for rendering the report
        return is_array($context) &&
            !empty($context) &&
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
        // $safeContext = [
        //     'formatted_content' => [
        //         'title' => $this->getNestedValue($context, ['result', 'token_analysis', 'title'], 'Token Analysis Report'),
        //         'executive_summary' => $this->getNestedValue($context, ['result', 'token_analysis', 'executive_summary'], ''),
        //         'project_overview' => $this->getNestedValue($context, ['result', 'token_analysis', 'project_overview'], ''),
        //         'price_analysis' => $this->getNestedValue($context, ['result', 'token_analysis', 'price_analysis'], ''),
        //         'market_data' => [
        //             'trading_volume' => $this->getNestedValue($context, ['result', 'token_analysis', 'market_data', 'trading_volume'], ''),
        //             'liquidity' => $this->getNestedValue($context, ['result', 'token_analysis', 'market_data', 'liquidity'], ''),
        //             'market_cap' => $this->getNestedValue($context, ['result', 'token_analysis', 'market_data', 'market_cap'], ''),
        //         ],
        //         'technical_analysis' => $this->getNestedValue($context, ['result', 'token_analysis', 'technical_analysis'], ''),
        //         'fundamental_analysis' => $this->getNestedValue($context, ['result', 'token_analysis', 'fundamental_analysis'], ''),
        //         'sentiment_analysis' => $this->getNestedValue($context, ['result', 'token_analysis', 'sentiment_analysis'], ''),
        //         'peer_comparison' => $this->getNestedValue($context, ['result', 'token_analysis', 'peer_comparison'], ''),
        //         'risk_assessment' => $this->getNestedValue($context, ['result', 'token_analysis', 'risk_assessment'], ''),
        //         'recommendations' => $this->getNestedValue($context, ['result', 'token_analysis', 'recommendations'], []),
        //         'recommendations_html' => $this->getNestedValue($context, ['result', 'token_analysis', 'recommendations_html'], ''),
        //     ],
        //     'content_metadata' => [
        //         'target_audience' => $this->getNestedValue($context, ['result', 'content_metadata', 'target_audience'], 'Beginner'),
        //         'reading_time' => $this->getNestedValue($context, ['result', 'content_metadata', 'reading_time'], 'Estimated reading time is approximately 5 minutes'),
        //         'complexity_level' => $this->getNestedValue($context, ['result', 'content_metadata', 'complexity_level'], 1),
        //         'key_topics' => $this->getNestedValue($context, ['result', 'content_metadata', 'key_topics'], []),
        //     ],
        // ];

        // // Add academic evaluation if exists
        // if (isset($context['result']['academic_evaluation'])) {
        //     $safeContext['academic_evaluation'] = $context['result']['academic_evaluation'];
        //     $safeContext['formatted_content']['academic_evaluation'] = $this->getNestedValue($context, ['result', 'token_analysis', 'academic_evaluation'], [
        //         'score' => 0,
        //         'classification' => 'Informational Grade',
        //         'summary' => '',
        //         'recommendations' => [],
        //     ]);
        // }
        $safeContext = [];

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
     * Generate HTML representation of token report data.
     *
     * @param array $context The sanitized context data for token report
     * @return string The rendered HTML
     */
    private function generateHtml(array $context): string
    {
        // Render the blade view with the sanitized context data
        // return $this->viewFactory->make('analytics.token_analysis_report_v1', [
        //     'json' => $context
        // ])->render();
        // Render the blade view with the sanitized context data
        return 'test';
    }

    /**
     * Generate PDF from token report data using PDF template.
     *
     * @param array $context The sanitized context data for token report
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
     * Check if PDF can be generated from token report
     *
     * @param TokenReport $tokenReport
     * @return bool
     */
    private function canGeneratePdf(TokenReport $tokenReport): bool
    {
        $context = $tokenReport->getContext();
        return $this->isValidContext($context);
    }

    /**
     * Prepare context data for PDF generation
     *
     * @param TokenReport $tokenReport
     * @return array
     */
    private function prepareContext(TokenReport $tokenReport): array
    {
        return $this->sanitizeContext($tokenReport->getContext());
    }

    /**
     * Render PDF template with context data
     *
     * @param array $context
     * @return string
     */
    private function renderPdfTemplate(array $context): string
    {
        // return $this->viewFactory->make('analytics.token_analysis_report_pdf_v1', [
        //     'json' => $context
        // ])->render();
        return 'test';
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
