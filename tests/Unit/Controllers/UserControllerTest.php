<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\TransaksiInfaq;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $tuUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@test.com',
            'password' => Hash::make('password'),
            'role' => 'tu',
            'is_active' => true
        ]);
    }

    /** @test */
    public function index_returns_tu_users_only()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/admin/user');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.user');
        $response->assertViewHas('users');

        $users = $response->viewData('users');
        $this->assertEquals(1, $users->total()); // Only TU user should be shown
    }

    /** @test */
    public function store_creates_new_tu_user_successfully()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/user', [
            'name' => 'New TU User',
            'email' => 'newtu@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'tu',
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User TU berhasil ditambahkan.'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'New TU User',
            'email' => 'newtu@test.com',
            'role' => 'tu',
            'is_active' => true
        ]);
    }

    /** @test */
    public function store_validates_required_fields()
    {
        $this->actingAs($this->adminUser);

        $response = $this->postJson('/admin/user', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function store_validates_unique_email()
    {
        $this->actingAs($this->adminUser);

        $response = $this->postJson('/admin/user', [
            'name' => 'Test User',
            'email' => $this->adminUser->email, // Use existing email to trigger unique validation
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function store_validates_password_confirmation()
    {
        $this->actingAs($this->adminUser);

        $response = $this->postJson('/admin/user', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password' // Mismatched password confirmation
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function show_returns_tu_user_data()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get("/admin/user/{$this->tuUser->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $this->tuUser->id,
                'name' => 'TU User',
                'email' => 'tu@test.com',
                'role' => 'tu',
                'is_active' => true
            ]
        ]);
    }

    /** @test */
    public function show_returns_404_for_admin_user()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get("/admin/user/{$this->adminUser->id}");

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'User tidak ditemukan.'
        ]);
    }

    /** @test */
    public function update_modifies_tu_user_successfully()
    {
        $this->actingAs($this->adminUser);

        $response = $this->put("/admin/user/{$this->tuUser->id}", [
            'name' => 'Updated TU User',
            'email' => 'updatedtu@test.com',
            'role' => 'tu',
            'is_active' => false
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Data user TU berhasil diupdate.'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->tuUser->id,
            'name' => 'Updated TU User',
            'email' => 'updatedtu@test.com',
            'is_active' => false
        ]);
    }

    /** @test */
    public function update_can_change_password()
    {
        $this->actingAs($this->adminUser);

        $response = $this->put("/admin/user/{$this->tuUser->id}", [
            'name' => 'TU User',
            'email' => 'tu@test.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'role' => 'tu',
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Data user TU berhasil diupdate.'
        ]);

        $this->assertTrue(Hash::check('newpassword', $this->tuUser->fresh()->password));
    }

    /** @test */
    public function update_returns_403_for_admin_user()
    {
        $this->actingAs($this->adminUser);

        $response = $this->put("/admin/user/{$this->adminUser->id}", [
            'name' => 'Updated Admin',
            'email' => 'admin@test.com',
            'role' => 'tu'
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'User tidak dapat diedit.'
        ]);
    }

    /** @test */
    public function toggle_active_changes_tu_user_status()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post("/admin/user/{$this->tuUser->id}/toggle-active");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User TU berhasil dinonaktifkan.',
            'is_active' => false
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->tuUser->id,
            'is_active' => false
        ]);
    }

    /** @test */
    public function toggle_active_returns_403_for_admin_user()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post("/admin/user/{$this->adminUser->id}/toggle-active");

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Status user ini tidak dapat diubah.'
        ]);
    }

    /** @test */
    public function destroy_deletes_tu_user_without_transactions()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete("/admin/user/{$this->tuUser->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'User TU berhasil dihapus.'
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $this->tuUser->id
        ]);
    }

    /** @test */
    public function destroy_prevents_deleting_admin_user()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete("/admin/user/{$this->adminUser->id}");

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'User administrator tidak dapat dihapus.'
        ]);
    }

    /** @test */
    public function destroy_prevents_self_deletion()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete("/admin/user/{$this->adminUser->id}");

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'User administrator tidak dapat dihapus.'
        ]);
    }

    /** @test */
    public function destroy_prevents_deleting_user_with_transactions()
    {
        // Create test data for transaction
        $tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 1',
            'pekerjaan' => 'Wiraswasta',
            'hubungan' => 'ayah'
        ]);

        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Test',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Test No. 1',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        // Create transaction by TU user
        TransaksiInfaq::create([
            'kode_transaksi' => 'INF-001',
            'siswa_id' => $siswa->id,
            'user_id' => $this->tuUser->id,
            'tanggal_bayar' => now(),
            'bulan_bayar' => 'Januari',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->adminUser);

        $response = $this->delete("/admin/user/{$this->tuUser->id}");

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'User tidak dapat dihapus karena masih memiliki transaksi.'
        ]);
    }

    /** @test */
    public function reset_password_changes_user_password()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post("/admin/user/{$this->tuUser->id}/reset-password");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password berhasil direset.',
            'new_password' => 'password123'
        ]);

        $this->assertTrue(Hash::check('password123', $this->tuUser->fresh()->password));
    }

    /** @test */
    public function reset_password_prevents_self_reset()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post("/admin/user/{$this->adminUser->id}/reset-password");

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Gunakan menu ganti password untuk mengubah password Anda sendiri.'
        ]);
    }

    /** @test */
    public function index_requires_authentication()
    {
        $response = $this->get('/admin/user');

        $response->assertRedirect(route('login'));
    }
}