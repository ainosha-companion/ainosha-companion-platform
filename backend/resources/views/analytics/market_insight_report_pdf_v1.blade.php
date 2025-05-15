<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $json['formatted_content']['title'] }}</title>
    <style>
        @page {
            margin: 1cm;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #1e40af;
            --text-color: #1e293b;
            --light-text-color: #64748b;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --border-radius: 0.5rem;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .metadata {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 10px;
        }

        .metadata span {
            display: inline-block;
            margin-right: 15px;
        }

        .audience-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #2563eb;
            color: white;
        }

        .executive-summary {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #2563eb;
        }

        section {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        h2 {
            color: #2563eb;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .market-grid {
            width: 100%;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .market-grid-row {
            width: 100%;
            display: flex;
            margin-bottom: 15px;
        }

        .market-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            margin-right: 15px;
            margin-bottom: 15px;
            width: 47%;
            float: left;
            page-break-inside: avoid;
        }

        .market-card-full {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 15px;
            width: 97%;
            clear: both;
            page-break-inside: avoid;
        }

        .market-card h3, .market-card-full h3 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .trend {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .trend.bearish {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .trend.bullish {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .trend.neutral {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .confidence {
            display: inline-block;
            font-size: 10px;
            color: #64748b;
            margin-left: 5px;
        }

        .outlook {
            margin-bottom: 8px;
        }

        .outlook strong {
            color: #1e40af;
        }

        .recommendations-list {
            padding-left: 20px;
            margin-top: 5px;
        }

        .recommendations-list li {
            margin-bottom: 8px;
        }

        .recommendations-list li:last-child {
            margin-bottom: 0;
        }

        .criterion-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 10px;
            width: 47%;
            float: left;
            margin-right: 15px;
            page-break-inside: avoid;
        }

        .criterion-card h4 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .score-bar {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin-bottom: 8px;
            position: relative;
        }

        .score-fill {
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 4px;
        }

        .score-text {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .topics {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
            justify-content: center;
        }

        .topic {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            background-color: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>{{ $json['formatted_content']['title'] }}</h1>
        <div class="metadata">
            <span>
                Reading Time: {{ $json['content_metadata']['reading_time'] }}
            </span>
            <span>
                Complexity: {{ $json['content_metadata']['complexity_level'] }}/10
            </span>
            <span class="audience-badge">
                {{ $json['content_metadata']['target_audience'] }}
            </span>
        </div>
    </header>

    <div class="executive-summary">
        <p>{{ $json['formatted_content']['executive_summary'] }}</p>
    </div>

    <section>
        <h2>Market Analysis</h2>
        <div class="market-grid clearfix">
            <div class="market-card">
                <h3>Bitcoin</h3>
                <div class="trend bearish">Bearish</div>
                <div class="outlook">
                    <strong>Short-term:</strong> {{ explode('.', $json['formatted_content']['bitcoin_analysis'])[0] }}.
                </div>
                <p>{{ $json['formatted_content']['bitcoin_analysis'] }}</p>
            </div>
            <div class="market-card">
                <h3>Ethereum</h3>
                <div class="trend bearish">Bearish</div>
                <div class="outlook">
                    <strong>Short-term:</strong> {{ explode('.', $json['formatted_content']['altcoin_analysis'])[0] }}.
                </div>
                <p>{{ $json['formatted_content']['altcoin_analysis'] }}</p>
            </div>
        </div>
    </section>

    <section>
        <h2>Global Market Comparison</h2>
        <div class="market-grid clearfix">
            <div class="market-card">
                <h3>US Markets</h3>
                <p>{{ $json['formatted_content']['market_comparison']['us_markets'] }}</p>
            </div>
            <div class="market-card">
                <h3>Asian Markets</h3>
                <p>{{ $json['formatted_content']['market_comparison']['asian_markets'] }}</p>
            </div>
        </div>
        <div class="market-card-full">
            <h3>Regional Outlook Comparison</h3>
            <p>{{ $json['formatted_content']['market_comparison']['comparative_outlook'] }}</p>
        </div>
    </section>

    <section>
        <h2>Market Sentiment</h2>
        <div class="market-card-full">
            <h3>Fear &amp; Greed Index</h3>
            <div class="trend bearish">Extreme Fear</div>
            <p>{{ $json['formatted_content']['sentiment_outlook'] }}</p>
        </div>
    </section>

    <section>
        <h2>Technical Analysis</h2>
        <div class="market-card-full">
            <h3>Technical Indicators</h3>
            <p>{{ $json['formatted_content']['technical_section'] }}</p>
        </div>
    </section>

    <section>
        <h2>Volume &amp; Trading Patterns</h2>
        <div class="market-card-full">
            <h3>Volume Insights</h3>
            <p>{{ $json['formatted_content']['volume_insights'] }}</p>
        </div>
    </section>

    <section>
        <h2>Market Correlations</h2>
        <div class="market-card-full">
            <h3>Asset Relationships</h3>
            <p>{{ $json['formatted_content']['correlation_analysis'] }}</p>
        </div>
    </section>

    <section>
        <h2>Risk Assessment</h2>
        <div class="market-card-full">
            <h3>Key Risk Factors</h3>
            <p>{{ $json['formatted_content']['risk_factors'] }}</p>
        </div>
    </section>

    <section>
        <h2>Recommendations</h2>
        @if (!empty($json['formatted_content']['recommendations_html']))
            {!! $json['formatted_content']['recommendations_html'] !!}
        @elseif (!empty($json['formatted_content']['recommendations']))
            <ul class="recommendations-list">
                @foreach ($json['formatted_content']['recommendations'] as $rec)
                    <li>{{ $rec }}</li>
                @endforeach
            </ul>
        @else
            <ul class="recommendations-list">
                <li>Check market conditions regularly</li>
            </ul>
        @endif
    </section>

    @if (!empty($json['formatted_content']['academic_evaluation']))
        <section>
            <h2>Academic Quality Assessment</h2>
            <div class="market-card-full">
                <h3>Quality Classification</h3>
                @php
                    $classification = $json['formatted_content']['academic_evaluation']['classification'];
                    $score = $json['formatted_content']['academic_evaluation']['score'];
                    $trendClass = match ($classification) {
                        'Research Grade' => 'bullish',
                        'Professional Grade' => 'neutral',
                        default => 'bearish'
                    };
                @endphp
                <div class="trend {{ $trendClass }}">
                    {{ $classification }}
                    <span class="confidence">Score: {{ $score }}/100</span>
                </div>

                <p>{{ $json['formatted_content']['academic_evaluation']['summary'] ?? 'This analysis has been evaluated against academic financial research standards.' }}</p>

                <h4>Areas for Improvement:</h4>
                <ul class="recommendations-list">
                    @foreach ($json['formatted_content']['academic_evaluation']['recommendations'] ?? ['No specific improvements needed.'] as $rec)
                        <li>{{ $rec }}</li>
                    @endforeach
                </ul>
            </div>

            @if (!empty($json['academic_evaluation']['criteria']))
                <div class="market-grid clearfix">
                    @foreach ($json['academic_evaluation']['criteria'] as $key => $criterion)
                        <div class="criterion-card">
                            <h4>{{ ucwords(str_replace('_', ' ', $key)) }}</h4>
                            <div class="score-bar">
                                @php
                                    $score = $criterion['score'];
                                    $fillColor = $score >= 85 ? '#10b981' : ($score >= 70 ? '#2563eb' : ($score >= 60 ? '#f59e0b' : '#ef4444'));
                                @endphp
                                <div class="score-fill" style="width: {{ $score }}%; background-color: {{ $fillColor }}"></div>
                            </div>
                            <div class="score-text">Score: {{ $score }}/100</div>
                            <p>{{ $criterion['explanation'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    <footer>
        <p>This report was generated on {{ now()->format('F j, Y') }} based on market data and analysis with Ainosha Platform.</p>
        <div class="topics">
            @foreach ($json['content_metadata']['key_topics'] ?? [] as $topic)
                <span class="topic">{{ $topic }}</span>
            @endforeach
        </div>
    </footer>
</div>
</body>
</html>