<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Analytics\BlockchainToken;
use App\Exceptions\Analytics\TokenNotFoundException;
use App\Models\BlockchainToken as TokenModel;

class EloquentBlockchainTokenRepository
{
    /**
     * @param BlockchainToken $token
     *
     * @return BlockchainToken
     */
    public function save(BlockchainToken $token): BlockchainToken
    {
        $tokenModel = TokenModel::updateOrCreate(
            [TokenModel::FIELD_ID => $token->getId()],
            [
                TokenModel::FIELD_NAME => $token->getName(),
                TokenModel::FIELD_SYMBOL => $token->getSymbol(),
                TokenModel::FIELD_CONTRACT_ADDRESS => $token->getContractAddress(),
            ]
        );

        return $this->toDomainEntity($tokenModel);
    }

    /**
     * @param int $id
     *
     * @return BlockchainToken
     * @throws TokenNotFoundException
     */
    public function findById(int $id): BlockchainToken
    {
        $tokenModel = TokenModel::find($id);

        if (!$tokenModel) {
            throw TokenNotFoundException::create("Token not found with ID: {$id}");
        }

        return $this->toDomainEntity($tokenModel);
    }

    /**
     * @param string $symbol
     *
     * @return BlockchainToken
     * @throws TokenNotFoundException
     */
    public function findBySymbol(string $symbol): BlockchainToken
    {
        $tokenModel = TokenModel::where(TokenModel::FIELD_SYMBOL, $symbol)->first();

        if (!$tokenModel) {
            throw TokenNotFoundException::create("Token not found with symbol: {$symbol}");
        }

        return $this->toDomainEntity($tokenModel);
    }

    /**
     * @param string $contractAddress
     *
     * @return BlockchainToken
     * @throws TokenNotFoundException
     */
    public function findByContractAddress(string $contractAddress): BlockchainToken
    {
        $tokenModel = TokenModel::where(TokenModel::FIELD_CONTRACT_ADDRESS, $contractAddress)->first();

        if (!$tokenModel) {
            throw TokenNotFoundException::create("Token not found with contract address: {$contractAddress}");
        }

        return $this->toDomainEntity($tokenModel);
    }

    private function toDomainEntity(TokenModel $tokenModel): BlockchainToken
    {
        return BlockchainToken::instance(
            id: data_get($tokenModel, TokenModel::FIELD_ID),
            name: data_get($tokenModel, TokenModel::FIELD_NAME),
            symbol: data_get($tokenModel, TokenModel::FIELD_SYMBOL),
            contractAddress: data_get($tokenModel, TokenModel::FIELD_CONTRACT_ADDRESS),
            network: data_get($tokenModel, TokenModel::FIELD_NETWORK),
        );
    }
}
