<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Permission;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission as PermissionModel;

class SeedPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permissions from the Permission enum to the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Seeding permissions...');

        $count = 0;

        // Create all permissions defined in the Permission enum
        foreach (Permission::cases() as $permissionCase) {
            $permissionName = $permissionCase->value;

            // Check if permission already exists to avoid duplicates
            if (!PermissionModel::where('name', $permissionName)->exists()) {
                PermissionModel::create(['name' => $permissionName, 'guard_name' => 'web']);
                $this->line("Created permission: {$permissionName}");
                $count++;
            }
        }

        $this->info("Seeded {$count} new permissions successfully!");

        return Command::SUCCESS;
    }
}