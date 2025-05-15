<?php

declare(strict_types=1);

namespace App\Entities\Analytics;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class MarketSentiment
{
    private function __construct(
        private ?int $id,
        private string $type,
        private array $context,
        private CarbonInterface $createdAt,
        private CarbonInterface $expiredAt,
    ) {
    }

    /**
     * Instantiate a new MarketSentiment
     *
     * @param int|null $id
     * @param string $type
     * @param array $context
     * @param CarbonInterface $createdAt
     * @param CarbonInterface $expiredAt
     *
     * @return self
     */
    public static function instance(
        int|null $id,
        string $type,
        array $context,
        CarbonInterface $createdAt,
        CarbonInterface $expiredAt
    ): self {
        return new self(id: $id, type: $type, context: $context, createdAt: $createdAt, expiredAt: $expiredAt);
    }

    /**
     * Preload a new MarketSentiment
     *
     * @param string $type
     * @param array $context
     *
     * @return self
     */
    public static function preload(string $type, array $context): self
    {
        $createdAt = Carbon::now();
        $reloadTimes = config('analytics.market_sentiment.reload_times') ?? [];
        $expiredAt = null;

        foreach ($reloadTimes as $time) {
            // Create candidate time for today using the H:i format.
            $candidate = Carbon::createFromFormat('H:i', $time)
                ->setDate($createdAt->year, $createdAt->month, $createdAt->day);

            // If the candidate has already passed, move to the next time.
            if ($candidate->lt($createdAt)) {
                continue;
            }

            // Set the candidate time as the expired time.
            $expiredAt = $candidate;
        }

        return new self(null, $type, $context, $createdAt, $expiredAt);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getExpiredAt(): CarbonInterface
    {
        return $this->expiredAt;
    }

    public function isExpired(): bool
    {
        return $this->getExpiredAt()->isPast();
    }
}