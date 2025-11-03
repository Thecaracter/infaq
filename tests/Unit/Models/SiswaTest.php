<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\TahunAjaran;
use App\Models\TransaksiInfaq;
use App\Models\Tunggakan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $tahunAjaran;
    protected $kelas;
    protected $orangTua;

    protected function setUp(): void
    {
        parent::setUp();

        // Create required data
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
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $this->orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1'
        ]);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $this->assertInstanceOf(Siswa::class, $siswa);
        $this->assertEquals('12345', $siswa->nis);
        $this->assertEquals('Ahmad Rizki', $siswa->nama_lengkap);
        $this->assertTrue($siswa->is_active);
    }

    /** @test */
    public function it_belongs_to_kelas()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertInstanceOf(Kelas::class, $siswa->kelas);
        $this->assertEquals($this->kelas->id, $siswa->kelas->id);
    }

    /** @test */
    public function it_belongs_to_orang_tua()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertInstanceOf(OrangTua::class, $siswa->orangTua);
        $this->assertEquals($this->orangTua->id, $siswa->orangTua->id);
    }

    /** @test */
    public function it_belongs_to_tahun_ajaran()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertInstanceOf(TahunAjaran::class, $siswa->tahunAjaran);
        $this->assertEquals($this->tahunAjaran->id, $siswa->tahunAjaran->id);
    }

    /** @test */
    public function it_has_many_transaksi_infaqs()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        // Create a user for the transaction
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $transaksi = TransaksiInfaq::create([
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'bulan_bayar' => 'Januari',
            'nominal' => 100000,
            'nominal_kelas' => 100000,
            'jenis_kelas' => 'reguler',
            'tanggal_bayar' => now(),
            'kode_transaksi' => 'INF202510270001'
        ]);

        $this->assertTrue($siswa->transaksiInfaqs->contains($transaksi));
    }

    /** @test */
    public function it_has_many_tunggakans()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $tunggakan = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 100000,
            'is_lunas' => false,
            'tanggal_jatuh_tempo' => now()->addDays(30)
        ]);

        $this->assertTrue($siswa->tunggakans->contains($tunggakan));
    }

    /** @test */
    public function it_has_active_scope()
    {
        $activeSiswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $inactiveSiswa = Siswa::create([
            'nis' => '54321',
            'nama_lengkap' => 'Budi Pratama',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-02-01',
            'alamat' => 'Jl. Gatot Subroto No. 3',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        $activeSiswaCollection = Siswa::active()->get();

        $this->assertTrue($activeSiswaCollection->contains($activeSiswa));
        $this->assertFalse($activeSiswaCollection->contains($inactiveSiswa));
    }

    /** @test */
    public function it_has_nama_lengkap_nis_accessor()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $expected = 'Ahmad Rizki (12345)';
        $this->assertEquals($expected, $siswa->nama_lengkap_nis);
    }

    /** @test */
    public function it_uses_soft_deletes()
    {
        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $this->kelas->id,
            'orang_tua_id' => $this->orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $siswa->delete();

        $this->assertSoftDeleted($siswa);

        // Can still be found with trashed
        $this->assertTrue(Siswa::withTrashed()->where('id', $siswa->id)->exists());

        // Not found in regular query
        $this->assertFalse(Siswa::where('id', $siswa->id)->exists());
    }
}