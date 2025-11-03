<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\TahunAjaranController;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TahunAjaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $tahunAjaran;

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
    }

    /** @test */
    public function index_returns_tahun_ajaran_list()
    {
        $this->actingAs($this->user);

        $response = $this->get('/admin/tahun-ajaran');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.tahun-ajaran');
        $response->assertViewHas('tahunAjarans');
    }

    /** @test */
    public function store_creates_new_tahun_ajaran_successfully()
    {
        $this->actingAs($this->user);

        $response = $this->post('/admin/tahun-ajaran', [
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan.'
        ]);

        $this->assertDatabaseHas('tahun_ajarans', [
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false
        ]);
    }

    /** @test */
    public function store_deactivates_other_tahun_ajaran_when_creating_active_one()
    {
        $this->actingAs($this->user);

        $response = $this->post('/admin/tahun-ajaran', [
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan.'
        ]);

        // Check that old tahun ajaran is deactivated
        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        // Check that new tahun ajaran is active
        $this->assertDatabaseHas('tahun_ajarans', [
            'nama_tahun' => '2025/2026',
            'is_active' => true
        ]);
    }

    /** @test */
    public function store_validates_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('admin.tahun-ajaran.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nama_tahun', 'tanggal_mulai', 'tanggal_selesai']);
    }

    /** @test */
    public function store_validates_unique_nama_tahun()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('admin.tahun-ajaran.store'), [
            'nama_tahun' => '2024/2025', // Already exists
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nama_tahun']);
    }

    /** @test */
    public function store_validates_tanggal_selesai_after_tanggal_mulai()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('admin.tahun-ajaran.store'), [
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-06-30' // Before tanggal_mulai
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tanggal_selesai']);
    }

    /** @test */
    public function show_returns_tahun_ajaran_data()
    {
        $this->actingAs($this->user);

        $response = $this->get("/admin/tahun-ajaran/{$this->tahunAjaran->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $this->tahunAjaran->id,
                'nama_tahun' => '2024/2025',
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2025-06-30',
                'is_active' => true
            ]
        ]);
    }

    /** @test */
    public function update_modifies_tahun_ajaran_successfully()
    {
        $this->actingAs($this->user);

        $response = $this->put("/admin/tahun-ajaran/{$this->tahunAjaran->id}", [
            'nama_tahun' => '2024/2025 Updated',
            'tanggal_mulai' => '2024-08-01',
            'tanggal_selesai' => '2025-07-31',
            'is_active' => false
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil diupdate.'
        ]);

        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'nama_tahun' => '2024/2025 Updated',
            'tanggal_mulai' => '2024-08-01',
            'tanggal_selesai' => '2025-07-31',
            'is_active' => false
        ]);
    }

    /** @test */
    public function update_deactivates_other_tahun_ajaran_when_activating()
    {
        // Create another tahun ajaran
        $otherTahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->put("/admin/tahun-ajaran/{$otherTahunAjaran->id}", [
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true
        ]);

        $response->assertStatus(200);

        // Check that old active tahun ajaran is deactivated
        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        // Check that updated tahun ajaran is active
        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $otherTahunAjaran->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function destroy_soft_deletes_tahun_ajaran()
    {
        $this->actingAs($this->user);

        $response = $this->delete("/admin/tahun-ajaran/{$this->tahunAjaran->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dihapus.'
        ]);

        $this->assertSoftDeleted('tahun_ajarans', [
            'id' => $this->tahunAjaran->id
        ]);
    }

    /** @test */
    public function restore_recovers_soft_deleted_tahun_ajaran()
    {
        $this->tahunAjaran->delete(); // Soft delete first
        $this->actingAs($this->user);

        $response = $this->post("/admin/tahun-ajaran/{$this->tahunAjaran->id}/restore");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dipulihkan.'
        ]);

        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function trashed_returns_soft_deleted_tahun_ajaran()
    {
        $this->tahunAjaran->delete(); // Soft delete first
        $this->actingAs($this->user);

        $response = $this->get('/admin/tahun-ajaran/trashed');

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
                        'nama_tahun',
                        'tanggal_mulai',
                        'tanggal_selesai',
                        'deleted_at'
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function toggle_active_activates_inactive_tahun_ajaran()
    {
        $this->tahunAjaran->update(['is_active' => false]);
        $this->actingAs($this->user);

        $response = $this->post("/admin/tahun-ajaran/{$this->tahunAjaran->id}/toggle-active");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil diaktifkan.',
            'is_active' => true
        ]);

        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function toggle_active_deactivates_active_tahun_ajaran()
    {
        $this->actingAs($this->user);

        $response = $this->post("/admin/tahun-ajaran/{$this->tahunAjaran->id}/toggle-active");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dinonaktifkan.',
            'is_active' => false
        ]);

        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);
    }

    /** @test */
    public function toggle_active_deactivates_other_tahun_ajaran_when_activating()
    {
        // Create another inactive tahun ajaran
        $otherTahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->post("/admin/tahun-ajaran/{$otherTahunAjaran->id}/toggle-active");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tahun ajaran berhasil diaktifkan.',
            'is_active' => true
        ]);

        // Check that old active tahun ajaran is deactivated
        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        // Check that toggled tahun ajaran is active
        $this->assertDatabaseHas('tahun_ajarans', [
            'id' => $otherTahunAjaran->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function index_requires_authentication()
    {
        $response = $this->get('/admin/tahun-ajaran');

        $response->assertRedirect(route('login'));
    }
}