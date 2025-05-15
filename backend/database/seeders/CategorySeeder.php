<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Application\Content\DTOs\CreateCategoryDTO;
use App\Application\Content\Handlers\CreateCategoryHandler;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function __construct(
        private readonly CreateCategoryHandler $createCategoryHandler,
    ) {}

    /**
     * Seed the categories table with predefined categories.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Markets',
                'description' => 'Cryptocurrency market analysis, price movements, and trading insights',
            ],
            [
                'name' => 'Technology',
                'description' => 'Blockchain technology, development updates, and technical innovations',
            ],
            [
                'name' => 'Regulation',
                'description' => 'Cryptocurrency regulations, legal frameworks, and policy updates',
            ],
            [
                'name' => 'Business',
                'description' => 'Corporate adoption, institutional investments, and business strategies',
            ],
            [
                'name' => 'Mining',
                'description' => 'Cryptocurrency mining operations, hardware, and network security',
            ],
            [
                'name' => 'DeFi',
                'description' => 'Decentralized finance protocols, yield farming, and liquidity mining',
            ],
            [
                'name' => 'NFTs',
                'description' => 'Non-fungible tokens, digital art, and blockchain collectibles',
            ],
            [
                'name' => 'Altcoins',
                'description' => 'Alternative cryptocurrencies, token launches, and ecosystem updates',
            ],
            [
                'name' => 'Security',
                'description' => 'Cybersecurity, hacks, scams, and best practices for protection',
            ],
            [
                'name' => 'Research',
                'description' => 'Academic studies, technical analysis, and market research',
            ],
        ];

        foreach ($categories as $category) {
            $this->createCategoryHandler->handle(
                new CreateCategoryDTO(
                    name: $category['name'],
                    description: $category['description'],
                ),
            );
        }
    }
}
