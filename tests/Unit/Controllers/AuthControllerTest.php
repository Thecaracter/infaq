<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);
    }

    /** @test */
    public function show_login_form_returns_login_view_when_not_authenticated()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function show_login_form_redirects_to_dashboard_when_authenticated()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function login_with_valid_credentials_authenticates_user()
    {
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function login_with_invalid_email_returns_error()
    {
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Email tidak ditemukan.']);
        $this->assertGuest();
    }

    /** @test */
    public function login_with_inactive_user_returns_error()
    {
        $this->user->update(['is_active' => false]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        $this->assertGuest();
    }

    /** @test */
    public function login_with_wrong_password_returns_error()
    {
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        $this->assertGuest();
    }

    /** @test */
    public function logout_logs_out_user_and_redirects()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Anda berhasil logout.');
        $this->assertGuest();
    }

    /** @test */
    public function show_change_password_form_returns_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('change-password'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.change-password');
    }

    /** @test */
    public function change_password_with_valid_data_updates_password()
    {
        $this->actingAs($this->user);

        $response = $this->post('/change-password', [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password berhasil diubah.');
        $this->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));
    }

    /** @test */
    public function change_password_with_wrong_current_password_returns_error()
    {
        $this->actingAs($this->user);

        $response = $this->post('/change-password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['current_password' => 'Password lama salah.']);
    }

    /** @test */
    public function change_password_validates_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->post('/change-password', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    /** @test */
    public function login_with_tu_role_authenticates_successfully()
    {
        $tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@example.com',
            'password' => Hash::make('password'),
            'role' => 'tu',
            'is_active' => true
        ]);

        $response = $this->post(route('login'), [
            'email' => 'tu@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($tuUser);
    }
}