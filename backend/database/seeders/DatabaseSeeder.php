<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquents\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed categories
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
