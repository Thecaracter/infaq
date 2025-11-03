<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\TagihanController;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use App\Models\Tunggakan;
use App\Models\TransaksiInfaq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $siswa;
    protected $kelas;
    protected $tahunAjaran;
    protected $orangTua;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create test data
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
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 1',
            'pekerjaan' => 'Wiraswasta',
            'hubungan' => 'ayah'
        ]);

        $this->siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Test',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Test No. 1',
            'jenis_kelamin' => 'L',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);
    }

    /** @test */
    public function index_returns_paginated_siswa_with_tunggakan()
    {
        // Create some test tunggakan
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->get(route('tagihan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.tagihan');
        $response->assertViewHas(['siswaList', 'statistik', 'kelasList', 'search', 'kelas', 'status']);
    }

    /** @test */
    public function show_siswa_returns_siswa_tunggakan_data()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->get(route('tagihan.siswa.show', $this->siswa->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'siswa',
                'tunggakan_belum',
                'tunggakan_lunas',
                'riwayat_pembayaran',
                'summary'
            ]
        ]);
    }

    /** @test */
    public function proses_pembayaran_processes_payment_successfully()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('tagihan.pembayaran'), [
            'tunggakan_id' => $tunggakan->id,
            'nominal' => 250000,
            'tanggal_bayar' => '2024-01-15',
            'keterangan' => 'Pembayaran sebagian SPP Januari 2024'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses dan konfirmasi WhatsApp dikirim'
        ]);

        // Check if transaksi infaq is created with partial payment
        $this->assertDatabaseHas('transaksi_infaqs', [
            'siswa_id' => $this->siswa->id,
            'bulan_bayar' => 'Januari',
            'nominal' => 250000.00,  // Partial payment amount
            'nominal_kelas' => 500000.00,
            'jenis_kelas' => 'reguler'
        ]);
    }

    /** @test */
    public function proses_pembayaran_requires_authentication()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $response = $this->post(route('tagihan.pembayaran'), [
            'tunggakan_id' => $tunggakan->id,
            'nominal' => 500000
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function proses_pembayaran_validates_required_fields()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('tagihan.pembayaran'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tunggakan_id', 'nominal', 'tanggal_bayar']);
    }

    /** @test */
    public function cannot_pay_already_paid_tunggakan()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'lunas',
            'is_lunas' => true
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('tagihan.pembayaran'), [
            'tunggakan_id' => $tunggakan->id,
            'nominal' => 500000,
            'tanggal_bayar' => '2024-01-15'
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Tunggakan sudah lunas'
        ]);
    }

    /** @test */
    public function search_filters_tunggakan_by_siswa_name()
    {
        // Create another siswa for testing  
        $otherOrangTua = OrangTua::create([
            'nama_wali' => 'Siti Fatimah',
            'no_hp' => '08987654322',
            'alamat' => 'Jl. Test No. 2',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'hubungan' => 'ibu'
        ]);

        $otherSiswa = Siswa::create([
            'nis' => '54321',
            'nama_lengkap' => 'Fatimah Other',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Test No. 2',
            'jenis_kelamin' => 'P',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $otherOrangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar'
        ]);

        Tunggakan::create([
            'siswa_id' => $otherSiswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/tagihan?search=Ahmad');

        $response->assertStatus(200);
        $response->assertSee('Ahmad Test');
        $response->assertDontSee('Fatimah Other');
    }

    /** @test */
    public function can_filter_by_status()
    {
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Februari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-02-10',
            'status' => 'lunas',
            'is_lunas' => true
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/tagihan?status=nunggak');

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.tagihan');
    }

    // Print test skipped - no print route in TagihanController

    /** @test */
    public function destroy_deletes_tunggakan()
    {
        $tunggakan = Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->user);

        $response = $this->delete("/tagihan/{$tunggakan->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tagihan berhasil dihapus'
        ]);

        $this->assertDatabaseMissing('tunggakans', [
            'id' => $tunggakan->id
        ]);
    }
}