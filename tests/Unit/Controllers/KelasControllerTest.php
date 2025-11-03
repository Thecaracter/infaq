<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\KelasController;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class KelasControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $tahunAjaran;
    protected $kelas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true
        ]);

        $this->kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function index_returns_kelas_list_with_tahun_ajaran()
    {
        $this->actingAs($this->user);

        $response = $this->get('/admin/kelas');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.kelas');
        $response->assertViewHas(['kelas', 'tahunAjarans']);
    }

    /** @test */
    public function store_creates_new_kelas_successfully()
    {
        $this->actingAs($this->user);

        $response = $this->post('/admin/kelas', [
            'nama_kelas' => 'XI IPA 2',
            'tingkat' => 11,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 550000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan.'
        ]);

        $this->assertDatabaseHas('kelas', [
            'nama_kelas' => 'XI IPA 2',
            'tingkat' => 11,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 550000
        ]);
    }

    /** @test */
    public function store_validates_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/admin/kelas', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'nama_kelas',
            'tingkat',
            'jenis_kelas',
            'nominal_bulanan',
            'tahun_ajaran_id'
        ]);
    }

    /** @test */
    public function store_prevents_duplicate_kelas_in_same_tahun_ajaran()
    {
        $this->actingAs($this->user);

        $response = $this->post('/admin/kelas', [
            'nama_kelas' => 'X IPA 1', // Same as existing kelas
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'errors' => [
                'nama_kelas' => ['Nama kelas sudah ada di tahun ajaran ini.']
            ]
        ]);
    }

    /** @test */
    public function show_returns_kelas_data()
    {
        $this->actingAs($this->user);

        $response = $this->get("/admin/kelas/{$this->kelas->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $this->kelas->id,
                'nama_kelas' => 'X IPA 1',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 500000,
                'tahun_ajaran_id' => $this->tahunAjaran->id,
                'is_active' => true
            ]
        ]);
    }

    /** @test */
    public function update_modifies_kelas_successfully()
    {
        $this->actingAs($this->user);

        $response = $this->put("/admin/kelas/{$this->kelas->id}", [
            'nama_kelas' => 'X IPA 1 Updated',
            'tingkat' => 10,
            'jenis_kelas' => 'peminatan',
            'nominal_bulanan' => 600000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kelas berhasil diupdate.'
        ]);

        $this->assertDatabaseHas('kelas', [
            'id' => $this->kelas->id,
            'nama_kelas' => 'X IPA 1 Updated',
            'jenis_kelas' => 'peminatan',
            'nominal_bulanan' => 600000,
            'is_active' => false
        ]);
    }

    /** @test */
    public function destroy_soft_deletes_kelas()
    {
        $this->actingAs($this->user);

        $response = $this->delete("/admin/kelas/{$this->kelas->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.'
        ]);

        $this->assertSoftDeleted('kelas', [
            'id' => $this->kelas->id
        ]);
    }

    /** @test */
    public function restore_recovers_soft_deleted_kelas()
    {
        $this->kelas->delete(); // Soft delete first
        $this->actingAs($this->user);

        $response = $this->post("/admin/kelas/{$this->kelas->id}/restore");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kelas berhasil dipulihkan.'
        ]);

        $this->assertDatabaseHas('kelas', [
            'id' => $this->kelas->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function trashed_returns_soft_deleted_kelas()
    {
        $this->kelas->delete(); // Soft delete first
        $this->actingAs($this->user);

        $response = $this->get('/admin/kelas/trashed');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'nama_kelas',
                        'tingkat',
                        'jenis_kelas',
                        'nominal_bulanan',
                        'deleted_at'
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function toggle_active_changes_kelas_status()
    {
        $this->actingAs($this->user);

        $response = $this->post("/admin/kelas/{$this->kelas->id}/toggle-active");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kelas berhasil dinonaktifkan.',
            'is_active' => false
        ]);

        $this->assertDatabaseHas('kelas', [
            'id' => $this->kelas->id,
            'is_active' => false
        ]);
    }

    /** @test */
    public function get_options_returns_kelas_options()
    {
        $this->actingAs($this->user);

        // Test the method directly since route is not implemented
        $controller = new \App\Http\Controllers\KelasController();
        $response = $controller->getOptions();

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('tingkat_options', $data);
        $this->assertArrayHasKey('jenis_kelas_options', $data);
    }

    /** @test */
    public function index_requires_authentication()
    {
        $response = $this->get('/admin/kelas');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function store_validates_tingkat_values()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/admin/kelas', [
            'nama_kelas' => 'Test Kelas',
            'tingkat' => 9, // Invalid tingkat
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tingkat']);
    }

    /** @test */
    public function store_validates_jenis_kelas_values()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/admin/kelas', [
            'nama_kelas' => 'Test Kelas',
            'tingkat' => 10,
            'jenis_kelas' => 'invalid_type', // Invalid jenis_kelas
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['jenis_kelas']);
    }
}