<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use App\Models\TransaksiInfaq;
use App\Models\Tunggakan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $tuUser;
    protected $tahunAjaran;
    protected $kelas;
    protected $orangTua;
    protected $siswa;

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
    }

    /** @test */
    public function admin_dashboard_returns_admin_data()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas([
            'user',
            'tahunAjaranAktif',
            'totalSiswa',
            'totalKelas',
            'totalPembayaranBulanIni',
            'totalTunggakan',
            'pembayaranTerbaru',
            'tunggakanTerbanyak',
            'chartData'
        ]);
    }

    /** @test */
    public function tu_dashboard_returns_tu_data()
    {
        $this->actingAs($this->tuUser);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas([
            'user',
            'tahunAjaranAktif',
            'totalSiswa',
            'pembayaranBulanIni',
            'totalNominalBulanIni',
            'totalTunggakan',
            'pembayaranTerbaru',
            'siswaMenunggak'
        ]);
    }

    /** @test */
    public function dashboard_requires_authentication()
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function get_tunggakan_data_returns_siswa_with_tunggakan()
    {
        // Create tunggakan
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

        $this->actingAs($this->adminUser);

        $response = $this->get('/dashboard/tunggakan-data');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'nama_lengkap',
                    'nis',
                    'kelas',
                    'total_tunggakan',
                    'jumlah_tunggakan'
                ]
            ]
        ]);
    }

    /** @test */
    public function admin_dashboard_shows_correct_statistics()
    {
        // Create test data
        $bulanIni = Carbon::now()->format('Y-m');

        TransaksiInfaq::create([
            'kode_transaksi' => 'INF-001',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Januari',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => 'Februari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-02-10',
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $this->actingAs($this->adminUser);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalSiswa', 1);
        $response->assertViewHas('totalKelas', 1);
        $response->assertViewHas('totalPembayaranBulanIni', 500000);
        $response->assertViewHas('totalTunggakan', 500000);
    }

    /** @test */
    public function tu_dashboard_shows_user_specific_data()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // Create transaction by TU user
        TransaksiInfaq::create([
            'kode_transaksi' => 'INF-002',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->tuUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Januari',
            'nominal' => 300000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        // Create transaction by admin user (should not appear in TU dashboard)
        TransaksiInfaq::create([
            'kode_transaksi' => 'INF-003',
            'siswa_id' => $this->siswa->id,
            'user_id' => $this->adminUser->id,
            'tanggal_bayar' => Carbon::now(),
            'bulan_bayar' => 'Januari',
            'nominal' => 200000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler'
        ]);

        $this->actingAs($this->tuUser);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pembayaranBulanIni', 1); // Only 1 payment by TU user
        $response->assertViewHas('totalNominalBulanIni', 300000); // Only TU user's amount
    }
}