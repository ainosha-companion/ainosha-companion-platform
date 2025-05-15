<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Analytics\MarketSentiment;
use App\Models\MarketSentiment as MarketSentimentModel;
use Carbon\Carbon;

class EloquentMarketSentimentRepository
{
    /**
     * Retrieve the latest market sentiment from the database.
     *
     * @return MarketSentiment|null
     */
    public function getLatest(): ?MarketSentiment
    {
        $record = MarketSentimentModel::orderBy('created_at', 'desc')->first();

        if (!$record) {
            return null;
        }

        return MarketSentiment::instance(
            id: $record->id,
            type: $record->type,
            context: $record->context,
            createdAt: Carbon::parse($record->created_at),
            expiredAt: Carbon::parse($record->expired_at),
        );
    }

    /**
     * Insert or update the market sentiment in the database.
     *
     * @param MarketSentiment $marketSentiment
     *
     * @return void
     */
    public function save(MarketSentiment $marketSentiment): void
    {
        MarketSentimentModel::updateOrCreate(
            ['id' => $marketSentiment->getId()],
            [
                'type' => $marketSentiment->getType(),
                'context' => $marketSentiment->getContext(),
                'expired_at' => $marketSentiment->getExpiredAt()?->toDateTimeString(),
            ]
        );
    }
}