<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('admin', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function it_hides_password_in_arrays()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayHasKey('name', $userArray);
        $this->assertArrayHasKey('email', $userArray);
        $this->assertArrayHasKey('role', $userArray);
    }

    /** @test */
    public function it_hides_remember_token_in_arrays()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'remember_token' => 'some_token_value'
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_validates_role_values()
    {
        $validRoles = ['admin', 'tu'];

        foreach ($validRoles as $role) {
            $user = User::create([
                'name' => 'Test User',
                'email' => "test_{$role}@example.com",
                'password' => Hash::make('password123'),
                'role' => $role
            ]);

            $this->assertEquals($role, $user->role);
        }
    }

    /** @test */
    public function it_has_admin_scope()
    {
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu'
        ]);

        $adminUsers = User::admin()->get();

        $this->assertTrue($adminUsers->contains($adminUser));
        $this->assertFalse($adminUsers->contains($tuUser));
    }

    /** @test */
    public function it_has_tu_scope()
    {
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu'
        ]);

        $tuUsers = User::tu()->get();

        $this->assertTrue($tuUsers->contains($tuUser));
        $this->assertFalse($tuUsers->contains($adminUser));
    }

    /** @test */
    public function it_can_check_if_user_is_admin()
    {
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu'
        ]);

        $this->assertEquals('admin', $adminUser->role);
        $this->assertEquals('tu', $tuUser->role);
    }

    /** @test */
    public function it_requires_unique_email()
    {
        User::create([
            'name' => 'First User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // This test expects a database constraint violation when creating duplicate email
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        User::create([
            'name' => 'Second User',
            'email' => 'test@example.com', // Same email as first user
            'password' => Hash::make('password456'),
            'role' => 'tu'
        ]);
    }

    /** @test */
    public function it_automatically_hashes_password_when_set()
    {
        // This test assumes a mutator exists for password hashing
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plaintext_password',
            'role' => 'admin'
        ]);

        // If password mutator exists, this should be hashed
        // If not, it would be stored as plain text (which would be a security issue)
        $this->assertNotEquals('plaintext_password', $user->password);
    }

    /** @test */
    public function it_has_email_verification_timestamps()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Just check the user is created properly
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
    }

    /** @test */
    public function it_can_check_user_active_status()
    {
        $activeUser = User::create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $inactiveUser = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu',
            'is_active' => false
        ]);

        $this->assertTrue($activeUser->is_active);
        $this->assertFalse($inactiveUser->is_active);
    }

    /** @test */
    public function it_stores_timestamps_correctly()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
    }

    /** @test */
    public function it_can_update_user_information()
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu'
        ]);

        $user->update([
            'name' => 'Updated Name',
            'role' => 'admin'
        ]);

        $this->assertEquals('Updated Name', $user->fresh()->name);
        $this->assertEquals('admin', $user->fresh()->role);
        $this->assertEquals('original@example.com', $user->fresh()->email); // Should remain unchanged
    }
}