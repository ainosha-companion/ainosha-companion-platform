<?php

declare(strict_types=1);

namespace App\Entities\Content;

use App\Enums\ArticleRequestStatus;
use App\ValueObjects\Content\ArticleRequestId;
use App\ValueObjects\User\UserId;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Config;

class ArticleRequest
{
    private ArticleRequestId $id;
    private UserId $userId;
    private array $requestData;
    private ArticleRequestStatus $status;
    private ?CarbonInterface $expiredAt;
    private ?float $executionTime;
    private CarbonInterface $createdAt;
    private CarbonInterface $updatedAt;

    private function __construct(
        ArticleRequestId $id,
        UserId $userId,
        array $requestData,
        ArticleRequestStatus $status,
        ?CarbonInterface $expiredAt,
        ?float $executionTime,
        ?CarbonInterface $createdAt,
        ?CarbonInterface $updatedAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->requestData = $requestData;
        $this->status = $status;
        $this->expiredAt = $expiredAt;
        $this->executionTime = $executionTime;
        $this->createdAt = $createdAt ?? Carbon::now();
        $this->updatedAt = $updatedAt ?? Carbon::now();
    }

    /**
     * Create a new instance of ArticleRequest
     *
     * @param ArticleRequestId $id
     * @param UserId $userId
     * @param array $requestData
     * @param ArticleRequestStatus $status
     * @param CarbonInterface|null $expiredAt
     * @param float|null $executionTime
     * @param CarbonInterface|null $createdAt
     * @param CarbonInterface|null $updatedAt
     * @return self
     */
    public static function instance(
        ArticleRequestId $id,
        UserId $userId,
        array $requestData,
        ArticleRequestStatus $status = ArticleRequestStatus::PENDING,
        ?CarbonInterface $expiredAt = null,
        ?float $executionTime = null,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null
    ): self {
        return new self(
            $id,
            $userId,
            $requestData,
            $status,
            $expiredAt,
            $executionTime,
            $createdAt,
            $updatedAt
        );
    }

    /**
     * Create a new article request with default expiration date calculated from config
     *
     * @param ArticleRequestId $id
     * @param UserId $userId
     * @param array $requestData
     * @return self
     */
    public static function create(
        ArticleRequestId $id,
        UserId $userId,
        array $requestData
    ): self {
        // Get expiration days from config (default 5 days)
        $expirationDays = Config::get('content.article_request_expiration_days', 5);

        // Calculate expiry date
        $expiredAt = Carbon::now()->addDays($expirationDays);

        return self::instance(
            id: $id,
            userId: $userId,
            requestData: $requestData,
            status: ArticleRequestStatus::PENDING,
            expiredAt: $expiredAt
        );
    }

    public function getId(): ArticleRequestId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function getStatus(): ArticleRequestStatus
    {
        return $this->status;
    }

    public function getExpiredAt(): ?CarbonInterface
    {
        return $this->expiredAt;
    }

    public function getExecutionTime(): ?float
    {
        return $this->executionTime;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): CarbonInterface
    {
        return $this->updatedAt;
    }

    public function setStatus(ArticleRequestStatus $status): void
    {
        $this->status = $status;
        $this->updatedAt = Carbon::now();
    }

    public function setExecutionTime(float $executionTime): void
    {
        $this->executionTime = $executionTime;
        $this->updatedAt = Carbon::now();
    }

    public function markAsProcessing(): void
    {
        $this->status = ArticleRequestStatus::PROCESSING;
        $this->updatedAt = Carbon::now();
    }

    public function markAsSuccess(): void
    {
        $this->status = ArticleRequestStatus::SUCCESS;
        $this->updatedAt = Carbon::now();
    }

    public function markAsFailed(): void
    {
        $this->status = ArticleRequestStatus::FAILED;
        $this->updatedAt = Carbon::now();
    }

    public function markAsExpired(): void
    {
        $this->status = ArticleRequestStatus::EXPIRED;
        $this->updatedAt = Carbon::now();
    }

    public function markAsCancelled(): void
    {
        $this->status = ArticleRequestStatus::CANCELLED;
        $this->updatedAt = Carbon::now();
    }
}