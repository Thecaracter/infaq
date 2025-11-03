<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\RiwayatController;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use App\Models\TransaksiInfaq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class RiwayatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $tuUser;
    protected $tahunAjaran;
    protected $kelas;
    protected $orangTua;
    protected $siswa;
    protected $transaksi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->tuUser = User::create([
            'name' => 'TU User',
            'email' => 'tu@test.com',
            'password' => bcrypt('password'),
            'role' => 'tu',
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

        $this->transaksi = TransaksiInfaq::create([
            'kode_transaksi' => 'INF-001',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->tuUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Januari',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);
    }

    /** @test */
    public function admin_can_view_all_riwayat()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
        $response->assertViewHas(['transaksi', 'kelas', 'users', 'stats']);
    }

    /** @test */
    public function tu_can_only_view_own_riwayat()
    {
        // Create another transaction by admin
        TransaksiInfaq::create([
            'kode_transaksi' => 'INF-002',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Februari',
            'nominal' => 300000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->tuUser);

        $response = $this->get(route('riwayat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');

        // Should only see own transaction
        $transaksi = $response->viewData('transaksi');
        $this->assertEquals(1, $transaksi->total());
        $this->assertEquals($this->tuUser->id, $transaksi->first()->user_id);
    }

    /** @test */
    public function can_filter_riwayat_by_date_range()
    {
        $this->actingAs($this->adminUser);

        $startDate = Carbon::now()->subDays(1)->toDateString();
        $endDate = Carbon::now()->addDays(1)->toDateString();

        $response = $this->get(route('riwayat.index', ['start_date' => $startDate, 'end_date' => $endDate]));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
    }

    /** @test */
    public function can_filter_riwayat_by_siswa_search()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.index', ['siswa_search' => 'Ahmad']));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
    }

    /** @test */
    public function can_filter_riwayat_by_kelas()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.index', ['kelas_id' => $this->kelas->id]));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
    }

    /** @test */
    public function admin_can_filter_by_user()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.index', ['user_id' => $this->tuUser->id]));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
    }

    /** @test */
    public function show_redirects_to_index()
    {
        $this->actingAs($this->tuUser);

        $response = $this->get(route('riwayat.show', $this->transaksi->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('riwayat.index'));
    }

    /** @test */
    public function tu_user_show_redirects_to_index()
    {
        // Create transaction by admin
        $adminTransaksi = TransaksiInfaq::create([
            'kode_transaksi' => 'INF-003',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Maret',
            'nominal' => 400000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->tuUser);

        $response = $this->get(route('riwayat.show', $adminTransaksi->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('riwayat.index'));
    }

    /** @test */
    public function export_returns_excel_file()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post(route('riwayat.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel');
    }

    /** @test */
    public function print_returns_print_view()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.print', $this->transaksi->id));

        $response->assertStatus(200);
        $response->assertViewIs('print.bukti-pembayaran');
        $response->assertViewHas('transaksi');
    }

    /** @test */
    public function tu_cannot_print_other_user_transaksi()
    {
        // Create transaction by admin
        $adminTransaksi = TransaksiInfaq::create([
            'kode_transaksi' => 'INF-004',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'April',
            'nominal' => 350000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->tuUser);

        $response = $this->get(route('riwayat.print', $adminTransaksi->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function resend_whatsapp_returns_success()
    {
        $this->actingAs($this->tuUser);

        $response = $this->post(route('riwayat.resend-whatsapp', $this->transaksi->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Notifikasi WhatsApp berhasil dikirim ulang.'
        ]);
    }

    /** @test */
    public function tu_cannot_resend_whatsapp_for_other_user_transaksi()
    {
        // Create transaction by admin
        $adminTransaksi = TransaksiInfaq::create([
            'kode_transaksi' => 'INF-005',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Mei',
            'nominal' => 250000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->tuUser);

        $response = $this->post(route('riwayat.resend-whatsapp', $adminTransaksi->id));

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda tidak memiliki akses ke transaksi ini.'
        ]);
    }

    /** @test */
    public function index_requires_authentication()
    {
        $response = $this->get(route('riwayat.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function index_shows_correct_statistics()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('riwayat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.riwayat');
        $response->assertViewHas('stats');
    }
}