<?php

declare(strict_types=1);

namespace App\Formatters\Config;

use Barryvdh\Snappy\PdfWrapper as SnappyPdf;

class PdfConfig
{
    /**
     * @param array $margins Page margins in millimeters
     * @param string $pageSize Page size (e.g., 'A4', 'Letter')
     * @param array $options Additional PDF generation options
     */
    public function __construct(
        private readonly array $margins = [
            'top' => 10,
            'right' => 10,
            'bottom' => 10,
            'left' => 10
        ],
        private readonly string $pageSize = 'A4',
        private readonly array $options = [
            'enable-local-file-access' => true,
            'encoding' => 'UTF-8',
            'print-media-type' => true,
            'enable-smart-shrinking' => true,
            'no-stop-slow-scripts' => true
        ]
    ) {}

    /**
     * Apply configuration to PDF generator
     *
     * @param SnappyPdf $pdf
     * @return void
     */
    public function applyTo(SnappyPdf $pdf): void
    {
        foreach ($this->margins as $side => $value) {
            $pdf->setOption("margin-{$side}", $value);
        }

        $pdf->setOption('page-size', $this->pageSize);

        foreach ($this->options as $key => $value) {
            $pdf->setOption($key, $value);
        }
    }
}