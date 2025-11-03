<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\CronController;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use App\Models\Tunggakan;
use App\Services\TagihanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CronControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $secret;
    protected $siswa;
    protected $kelas;
    protected $tahunAjaran;
    protected $orangTua;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secret = 'test-secret';
        Config::set('app.cron_secret', $this->secret);

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
    public function generate_tagihan_requires_valid_secret()
    {
        $response = $this->get('/cron/generate-tagihan?secret=invalid-secret');

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    /** @test */
    public function generate_tagihan_works_with_valid_secret()
    {
        $response = $this->get("/cron/generate-tagihan?secret={$this->secret}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /** @test */
    public function generate_tagihan_accepts_custom_bulan_parameter()
    {
        $bulan = '2024-01';

        $response = $this->get("/cron/generate-tagihan?secret={$this->secret}&bulan={$bulan}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /** @test */
    public function generate_tagihan_logs_execution()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Cron generate tagihan executed', \Mockery::type('array'));

        Log::shouldReceive('error')
            ->zeroOrMoreTimes()
            ->with(\Mockery::any());

        $response = $this->get("/cron/generate-tagihan?secret={$this->secret}");

        $response->assertStatus(200);
    }

    /** @test */
    public function generate_tagihan_logs_errors()
    {
        // Mock TagihanService to throw exception
        $this->mock(TagihanService::class, function ($mock) {
            $mock->shouldReceive('generateTagihanBulanan')
                ->andThrow(new \Exception('Test error'));
        });

        Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Cron generate tagihan failed:/'));

        $response = $this->get("/cron/generate-tagihan?secret={$this->secret}");

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false
        ]);
    }

    /** @test */
    public function kirim_reminder_requires_valid_secret()
    {
        $response = $this->get('/cron/kirim-reminder?secret=invalid-secret');

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    /** @test */
    public function kirim_reminder_works_with_valid_secret()
    {
        // Create some tunggakan that need reminder
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(5),
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        $response = $this->get("/cron/kirim-reminder?secret={$this->secret}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'berhasil',
                'gagal',
                'total'
            ]
        ]);
    }

    /** @test */
    public function kirim_reminder_accepts_custom_limit_parameter()
    {
        $limit = 10;

        $response = $this->get("/cron/kirim-reminder?secret={$this->secret}&limit={$limit}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'berhasil',
                'gagal',
                'total'
            ]
        ]);
    }

    /** @test */
    public function kirim_reminder_logs_execution()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Cron reminder executed', \Mockery::type('array'));

        $response = $this->get("/cron/kirim-reminder?secret={$this->secret}");

        $response->assertStatus(200);
    }

    /** @test */
    public function kirim_reminder_logs_errors_for_individual_reminders()
    {
        // Create tunggakan with invalid phone number to trigger error
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(5),
            'status' => 'belum_bayar',
            'is_lunas' => false
        ]);

        // Mock to expect error logging
        Log::shouldReceive('error')
            ->with(\Mockery::pattern('/Gagal kirim reminder untuk tunggakan ID/'));

        Log::shouldReceive('info')
            ->once()
            ->with('Cron reminder executed', \Mockery::type('array'));

        $response = $this->get("/cron/kirim-reminder?secret={$this->secret}");

        $response->assertStatus(200);
    }

    /** @test */
    public function kirim_reminder_handles_service_exception()
    {
        // Mock TagihanService to throw exception
        $this->mock(TagihanService::class, function ($mock) {
            $mock->shouldReceive('getTunggakanPerluReminder')
                ->andThrow(new \Exception('Service error'));
        });

        Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Cron reminder failed:/'));

        $response = $this->get("/cron/kirim-reminder?secret={$this->secret}");

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false
        ]);
    }

    /** @test */
    public function status_requires_valid_secret()
    {
        $response = $this->get('/cron/status?secret=invalid-secret');

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    /** @test */
    public function status_returns_system_information()
    {
        // Create some test data
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => Carbon::now()->format('F'),
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(10),
            'status' => 'belum_bayar',
            'is_lunas' => false,
            'last_reminder' => Carbon::today()
        ]);

        $response = $this->get("/cron/status?secret={$this->secret}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'server_time',
            'timezone',
            'last_tagihan_created',
            'total_tunggakan_bulan_ini',
            'total_reminder_hari_ini',
            'memory_usage'
        ]);

        $response->assertJson([
            'status' => 'OK',
            'timezone' => config('app.timezone')
        ]);
    }

    /** @test */
    public function status_shows_correct_statistics()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // Create tunggakan for current month
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => $bulanIni,
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(10),
            'status' => 'belum_bayar',
            'is_lunas' => false,
            'last_reminder' => Carbon::today()
        ]);

        // Create tunggakan for different month  
        Tunggakan::create([
            'siswa_id' => $this->siswa->id,
            'jenis_kelas' => 'reguler',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => Carbon::now()->subMonths(2),
            'status' => 'belum_bayar',
            'is_lunas' => false,
            'last_reminder' => Carbon::yesterday()
        ]);

        $response = $this->get("/cron/status?secret={$this->secret}");

        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertEquals(1, $responseData['total_tunggakan_bulan_ini']);
        $this->assertEquals(1, $responseData['total_reminder_hari_ini']);
    }
}