<?php

declare(strict_types=1);

namespace App\Formatters\Traits;

use Illuminate\Support\Facades\Log;
use App\Entities\Analytics\MarketSentiment;
use App\Entities\Analytics\TokenReport;

trait PdfLoggingTrait
{
    /**
     * Log successful PDF generation
     *
     * @param string $pdf Raw PDF content
     * @param string $base64 Base64 encoded PDF content
     * @return void
     */
    private function logPdfGeneration(string $pdf, string $base64): void
    {
        Log::info('PDF generated successfully', [
            'size' => strlen($pdf),
            'base64_size' => strlen($base64)
        ]);
    }

    /**
     * Log PDF generation error
     *
     * @param \Throwable $e
     * @return void
     */
    private function logPdfError(\Throwable $e): void
    {
        Log::error('Failed to generate PDF', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Log successful PDF generation
     *
     * @param MarketSentiment|TokenReport $entity
     * @param string $pdf Base64 encoded PDF content
     * @return void
     */
    private function logSuccess(MarketSentiment|TokenReport $entity, string $pdf): void
    {
        if ($entity instanceof MarketSentiment) {
            Log::info('Market sentiment formatted with PDF successfully', [
                'type' => $entity->getType(),
                'pdf_size' => strlen($pdf)
            ]);
        } elseif ($entity instanceof TokenReport) {
            Log::info('Token report formatted with PDF successfully', [
                'token' => $entity->getToken()->getSymbol(),
                'type' => $entity->getType()->value,
                'pdf_size' => strlen($pdf)
            ]);
        }
    }

    /**
     * Log entity PDF generation error
     *
     * @param MarketSentiment|TokenReport $entity
     * @param \Throwable $e
     * @return void
     */
    private function logError(MarketSentiment|TokenReport $entity, \Throwable $e): void
    {
        if ($entity instanceof MarketSentiment) {
            Log::error('Failed to format market sentiment with PDF', [
                'type' => $entity->getType(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } elseif ($entity instanceof TokenReport) {
            Log::error('Failed to format token report with PDF', [
                'token' => $entity->getToken()->getSymbol(),
                'type' => $entity->getType()->value,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}