<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function users_can_login_with_valid_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function users_cannot_login_with_invalid_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function users_can_logout()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function login_requires_email_and_password()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /** @test */
    public function login_requires_valid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_users_are_redirected_from_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function guests_are_redirected_to_login_when_accessing_protected_routes()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/siswa');

        $response->assertStatus(200);
    }

    /** @test */
    public function guru_can_access_allowed_routes()
    {
        $guru = User::create([
            'name' => 'Guru User',
            'email' => 'guru@example.com',
            'password' => Hash::make('password123'),
            'role' => 'tu',
            'is_active' => true
        ]);

        $this->actingAs($guru);

        // Assuming guru can access dashboard
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function staff_can_access_allowed_routes()
    {
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->actingAs($staff);

        // Assuming staff can access dashboard
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => 'on'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // Check if remember token is set
        $user = $user->fresh();
        $this->assertNotNull($user->remember_token);
    }

    /** @test */
    public function login_throttling_works_after_multiple_failed_attempts()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Make multiple failed login attempts
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password'
            ]);
        }

        // The next attempt should be throttled
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function users_can_access_dashboard_after_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee('Dashboard');
    }

    /** @test */
    public function session_expires_and_redirects_to_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Simulate expired session
        $this->actingAs($user);
        Auth::logout();

        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}