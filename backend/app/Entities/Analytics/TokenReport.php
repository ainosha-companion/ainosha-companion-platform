<?php

declare(strict_types=1);

namespace App\Entities\Analytics;

use App\Helpers\TokenReportExpirationHelper;
use App\Enums\Period;
use App\Entities\Analytics\BlockchainToken;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Config;

class TokenReport
{
    private function __construct(
        private ?int $id,
        private Period $type,
        private CarbonInterface $createdAt,
        private CarbonInterface $expiredAt,
        private BlockchainToken $token,
        private array $context,
    ) {
    }

    /**
     * Instantiate a new token report.
     *
     * @param int|null $id
     * @param BlockchainToken $token
     * @param Period $type
     * @param array $context
     * @param CarbonInterface $createdAt
     * @param CarbonInterface $expiredAt
     *
     * @return self
     */
    public static function instance(
        int|null $id,
        BlockchainToken $token,
        Period $type,
        array $context,
        CarbonInterface $createdAt,
        CarbonInterface $expiredAt
    ): self {
        return new self(
            id: $id,
            type: $type,
            context: $context,
            createdAt: $createdAt,
            expiredAt: $expiredAt,
            token: $token,
        );
    }

    /**
     * Preload a new token report.
     *
     * @param BlockchainToken $token
     * @param Period $type
     * @param array $context
     *
     * @return self
     */
    public static function preload(BlockchainToken $token, Period $type, array $context): self
    {
        $createdAt = Carbon::now();
        $expiredAt = TokenReportExpirationHelper::calculateExpiredAt($type, $createdAt);

        return new self(
            id: null,
            type: $type,
            context: $context,
            createdAt: $createdAt,
            expiredAt: $expiredAt,
            token: $token,
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): BlockchainToken
    {
        return $this->token;
    }

    public function getType(): Period
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