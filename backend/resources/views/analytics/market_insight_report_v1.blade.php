<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $json['formatted_content']['title'] }}</title>
    <style>
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
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --border-radius: 0.5rem;
        }

        /* Apply different color schemes based on audience */
        .beginner {
            --primary-color: #0ea5e9;
            --secondary-color: #38bdf8;
            --accent-color: #0284c7;
        }

        .intermediate {
            --primary-color: #8b5cf6;
            --secondary-color: #a78bfa;
            --accent-color: #7c3aed;
        }

        .advanced {
            --primary-color: #1d4ed8;
            --secondary-color: #3b82f6;
            --accent-color: #1e40af;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2.25rem;
            margin-bottom: 0.5rem;
        }

        .metadata {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--light-text-color);
            margin-bottom: 1rem;
        }

        .metadata span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .audience-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: var(--primary-color);
            color: white;
        }

        .executive-summary {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--primary-color);
        }

        section {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .market-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .market-grid {
                grid-template-columns: 1fr;
            }
        }

        .market-card {
            background-color: var(--bg-color);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            border: 1px solid var(--border-color);
        }

        .market-card h3 {
            color: var(--accent-color);
            margin-top: 0;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .trend {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .trend.bearish {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .trend.bullish {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .trend.neutral {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .confidence {
            display: inline-block;
            font-size: 0.875rem;
            color: var(--light-text-color);
            margin-left: 0.5rem;
        }

        .outlook {
            margin-bottom: 0.75rem;
        }

        .outlook strong {
            color: var(--accent-color);
        }

        .recommendations-list {
            padding-left: 1.25rem;
        }

        .recommendations-list li {
            margin-bottom: 0.75rem;
        }

        .recommendations-list li:last-child {
            margin-bottom: 0;
        }

        /* Academic evaluation styles */
        .academic-section {
            margin-top: 2rem;
        }

        .criteria-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .criteria-grid {
                grid-template-columns: 1fr;
            }
        }

        .criterion-card {
            background-color: var(--bg-color);
            border-radius: var(--border-radius);
            padding: 1rem;
            border: 1px solid var(--border-color);
        }

        .criterion-card h4 {
            color: var(--accent-color);
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .score-bar {
            height: 8px;
            background-color: var(--border-color);
            border-radius: 4px;
            margin-bottom: 0.75rem;
            overflow: hidden;
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
            font-size: 0.875rem;
            color: var(--light-text-color);
            margin-bottom: 0.5rem;
        }

        .academic-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .research-grade {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .professional-grade {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .commercial-grade {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .informational-grade {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        footer {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            color: var(--light-text-color);
            font-size: 0.875rem;
        }

        .topics {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .topic {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        /* Technical Analysis specific styles */
        .technical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .indicator {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .indicator-name {
            font-weight: 600;
            width: 100px;
            color: var(--accent-color);
        }

        .indicator-value {
            flex: 1;
        }

        /* Risk meter styles */
        .risk-meter {
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            margin: 1rem 0;
            position: relative;
        }

        .risk-level {
            height: 100%;
            border-radius: 4px;
            position: absolute;
            left: 0;
        }

        .risk-low {
            background-color: var(--success-color);
            width: 33%;
        }

        .risk-medium {
            background-color: var(--warning-color);
            width: 66%;
        }

        .risk-high {
            background-color: var(--danger-color);
            width: 100%;
        }

        /* Improved market card styles */
        .market-card {
            transition: all 0.2s ease-in-out;
        }

        .market-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="{{ strtolower($json['content_metadata']['target_audience']) }}">
<div class="container">
    <header>
        <h1>{{ $json['formatted_content']['title'] }}</h1>
        <div class="metadata">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    {{ $json['content_metadata']['reading_time'] }}
                </span>
            <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
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
        <div class="market-grid">
            @foreach (['Bitcoin' => 'bitcoin_analysis', 'Ethereum' => 'altcoin_analysis'] as $title => $key)
                <div class="market-card">
                    <h3>{{ $title }}</h3>
                    <div class="trend bearish">Bearish</div>
                    <div class="outlook">
                        <strong>Short-term:</strong> {{ explode('.', $json['formatted_content'][$key])[0] }}.
                    </div>
                    <p>{{ $json['formatted_content'][$key] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section>
        <h2>Global Market Comparison</h2>
        <div class="market-grid">
            <div class="market-card">
                <h3>US Markets</h3>
                <p>{{ $json['formatted_content']['market_comparison']['us_markets'] }}</p>
            </div>
            <div class="market-card">
                <h3>Asian Markets</h3>
                <p>{{ $json['formatted_content']['market_comparison']['asian_markets'] }}</p>
            </div>
        </div>
        <div class="market-card">
            <h3>Regional Outlook Comparison</h3>
            <p>{{ $json['formatted_content']['market_comparison']['comparative_outlook'] }}</p>
        </div>
    </section>

    <section>
        <h2>Market Sentiment</h2>
        <div class="market-card">
            <h3>Fear &amp; Greed Index</h3>
            <div class="trend bearish">Extreme Fear</div>
            <p>{{ $json['formatted_content']['sentiment_outlook'] }}</p>
        </div>
    </section>

    <section>
        <h2>Technical Analysis</h2>
        <div class="market-card">
            <h3>Technical Indicators</h3>
            <p>{{ $json['formatted_content']['technical_section'] }}</p>
        </div>
    </section>

    <section>
        <h2>Volume &amp; Trading Patterns</h2>
        <div class="market-card">
            <h3>Volume Insights</h3>
            <p>{{ $json['formatted_content']['volume_insights'] }}</p>
        </div>
    </section>

    <section>
        <h2>Market Correlations</h2>
        <div class="market-card">
            <h3>Asset Relationships</h3>
            <p>{{ $json['formatted_content']['correlation_analysis'] }}</p>
        </div>
    </section>

    <section>
        <h2>Risk Assessment</h2>
        <div class="market-card">
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
            <div class="market-card">
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
                <div class="criteria-grid">
                    @foreach ($json['academic_evaluation']['criteria'] as $key => $criterion)
                        <div class="criterion-card">
                            <h4>{{ ucwords(str_replace('_', ' ', $key)) }}</h4>
                            <div class="score-bar">
                                @php
                                    $score = $criterion['score'];
                                    $fillColor = $score >= 85 ? 'var(--success-color)' : ($score >= 70 ? 'var(--primary-color)' : ($score >= 60 ? 'var(--warning-color)' : 'var(--danger-color)'));
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
