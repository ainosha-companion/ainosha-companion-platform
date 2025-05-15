<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Analytics\TokenReport;
use App\Enums\Period;
use App\Entities\Analytics\BlockchainToken;
use App\Exceptions\Analytics\TokenReportNotFoundException;
use App\Models\TokenReport as TokenReportModel;
use Carbon\Carbon;

class EloquentTokenReportRepository
{
    public function __construct(
        private readonly EloquentBlockchainTokenRepository $tokenRepository
    ) {
    }

    /**
     * @param TokenReport $report
     *
     * @return TokenReport
     */
    public function save(TokenReport $report): TokenReport
    {
        $model = TokenReportModel::updateOrCreate(
            [TokenReportModel::FIELD_ID => $report->getId()],
            [
                TokenReportModel::FIELD_TOKEN_ID => $report->getToken()->getId(),
                TokenReportModel::FIELD_TYPE => $report->getType()->value,
                TokenReportModel::FIELD_CONTEXT => $report->getContext(),
                TokenReportModel::FIELD_EXPIRED_AT => $report->getExpiredAt(),
            ]
        );
        $token = $this->tokenRepository->findById($model->token_id);

        return $this->toDomainEntity($model, $token);
    }

    /**
     * @param string $tokenSymbol
     * @param Period $type
     *
     * @return TokenReport
     * @throws TokenReportNotFoundException
     */
    public function getLatestForToken(string $tokenSymbol, Period $type): TokenReport
    {
        $token = $this->tokenRepository->findBySymbol($tokenSymbol);
        $model = TokenReportModel::where(TokenReportModel::FIELD_TOKEN_ID, $token->getId())
            ->where(TokenReportModel::FIELD_TYPE, $type->value)
            ->latest()
            ->first();

        if (!$model) {
            throw TokenReportNotFoundException::create("Report not found for token: {$tokenSymbol}");
        }

        return $this->toDomainEntity($model, $token);
    }

    private function toDomainEntity(TokenReportModel $model, BlockchainToken|null $token): TokenReport
    {
        return TokenReport::instance(
            id: data_get($model, TokenReportModel::FIELD_ID),
            token: $token,
            type: Period::tryFrom(data_get($model, TokenReportModel::FIELD_TYPE)),
            context: data_get($model, TokenReportModel::FIELD_CONTEXT),
            createdAt: Carbon::parse(data_get($model, TokenReportModel::FIELD_CREATED_AT)),
            expiredAt: Carbon::parse(data_get($model, TokenReportModel::FIELD_EXPIRED_AT))
        );
    }
}
