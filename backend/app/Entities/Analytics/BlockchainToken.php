<?php

declare(strict_types=1);

namespace App\Entities\Analytics;

class BlockchainToken
{
    private function __construct(
        private ?int $id,
        private string $name,
        private string $symbol,
        private string $contractAddress,
        private string $network,
    ) {
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param string $symbol
     * @param string $contractAddress
     * @param string $network
     *
     * @return self
     */
    public static function instance(
        int|null $id,
        string $name,
        string $symbol,
        string $contractAddress,
        string $network,
    ): self {
        return new self(
            id: $id,
            name: $name,
            symbol: $symbol,
            contractAddress: $contractAddress,
            network: $network,
        );
    }

    /**
     * @param string $name
     * @param string $symbol
     * @param string $contractAddress
     *
     * @return self
     */
    public static function preload(
        string $name,
        string $symbol,
        string $contractAddress,
        string $network,
    ): self {
        return new self(
            id: null,
            name: $name,
            symbol: $symbol,
            contractAddress: $contractAddress,
            network: $network,
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getContractAddress(): string
    {
        return $this->contractAddress;
    }

    public  function getNetwork(): string
    {
        return $this->network;
    }
}