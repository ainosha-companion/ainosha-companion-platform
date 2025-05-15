<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateSuperAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the command creates a super-admin role and assigns it to the user.
     */
    public function test_command_creates_super_admin_role_and_user(): void
    {
        // Run the command
        $this->artisan('app:create-super-admin')
            ->expectsOutput('Creating super-admin role and user...')
            ->expectsOutput('Creating super-admin role...')
            ->expectsOutput('Super-admin role created successfully.')
            ->expectsOutput('Creating super admin user...')
            ->expectsOutput('Super admin user created successfully.')
            ->expectsOutput('Super-admin role assigned to user successfully.')
            ->expectsOutput('Super-admin setup completed successfully!')
            ->assertSuccessful();

        // Assert the role was created
        $this->assertDatabaseHas('roles', [
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        // Assert the user was created
        $this->assertDatabaseHas('users', [
            'email' => 'superadmin2902@test.test',
        ]);

        // Assert the user has the role
        $user = User::where('email', 'superadmin2902@test.test')->first();
        $this->assertTrue($user->hasRole('super-admin'));
    }

    /**
     * Test the command handles the case when the role and user already exist.
     */
    public function test_command_handles_existing_role_and_user(): void
    {
        // Create the role and user beforehand
        $role = Role::create(['name' => 'super-admin']);
        $user = User::factory()->create([
            'email' => 'superadmin2902@test.test',
        ]);
        $user->assignRole($role);

        // Run the command
        $this->artisan('app:create-super-admin')
            ->expectsOutput('Creating super-admin role and user...')
            ->expectsOutput('Super-admin role already exists.')
            ->expectsOutput('Super admin user already exists.')
            ->expectsOutput('User already has super-admin role.')
            ->expectsOutput('Super-admin setup completed successfully!')
            ->assertSuccessful();

        // Assert the user still has the role
        $user = User::where('email', 'superadmin2902@test.test')->first();
        $this->assertTrue($user->hasRole('super-admin'));
    }
}
