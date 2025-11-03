<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KelasTest extends TestCase
{
    use RefreshDatabase;

    protected $tahunAjaran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $this->assertInstanceOf(Kelas::class, $kelas);
        $this->assertEquals('X IPA 1', $kelas->nama_kelas);
        $this->assertEquals(10, $kelas->tingkat);
        $this->assertEquals('reguler', $kelas->jenis_kelas);
        $this->assertEquals(100000, $kelas->nominal_bulanan);
        $this->assertTrue($kelas->is_active);
    }

    /** @test */
    public function it_belongs_to_tahun_ajaran()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertInstanceOf(TahunAjaran::class, $kelas->tahunAjaran);
        $this->assertEquals($this->tahunAjaran->id, $kelas->tahunAjaran->id);
    }

    /** @test */
    public function it_has_many_siswas()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $orangTua = \App\Models\OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1',
            'pekerjaan' => 'Wiraswasta',
            'hubungan' => 'ayah'
        ]);

        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2008-01-01',
            'alamat' => 'Jl. Sudirman No. 2',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertTrue($kelas->siswas->contains($siswa));
    }

    /** @test */
    public function it_has_active_scope()
    {
        $activeKelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => true
        ]);

        $inactiveKelas = Kelas::create([
            'nama_kelas' => 'X IPA 2',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => false
        ]);

        $activeKelasCollection = Kelas::active()->get();

        $this->assertTrue($activeKelasCollection->contains($activeKelas));
        $this->assertFalse($activeKelasCollection->contains($inactiveKelas));
    }

    /** @test */
    public function it_validates_tingkat_values()
    {
        // Valid tingkat values: 10, 11, 12
        $validTingkats = [10, 11, 12];

        foreach ($validTingkats as $tingkat) {
            $kelas = Kelas::create([
                'nama_kelas' => "X IPA $tingkat",
                'tingkat' => $tingkat,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 100000,
                'tahun_ajaran_id' => $this->tahunAjaran->id
            ]);

            $this->assertEquals($tingkat, $kelas->tingkat);
        }
    }

    /** @test */
    public function it_validates_jenis_kelas_values()
    {
        // Valid jenis_kelas values: reguler, peminatan
        $validJenisKelas = ['reguler', 'peminatan'];

        foreach ($validJenisKelas as $jenis) {
            $kelas = Kelas::create([
                'nama_kelas' => "X $jenis 1",
                'tingkat' => 10,
                'jenis_kelas' => $jenis,
                'nominal_bulanan' => 100000,
                'tahun_ajaran_id' => $this->tahunAjaran->id
            ]);

            $this->assertEquals($jenis, $kelas->jenis_kelas);
        }
    }

    /** @test */
    public function it_uses_soft_deletes()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $kelas->delete();

        $this->assertSoftDeleted($kelas);

        // Can still be found with trashed
        $this->assertTrue(Kelas::withTrashed()->where('id', $kelas->id)->exists());

        // Not found in regular query
        $this->assertFalse(Kelas::where('id', $kelas->id)->exists());
    }

    /** @test */
    public function it_casts_nominal_bulanan_to_decimal()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => '100000.50',
            'tahun_ajaran_id' => $this->tahunAjaran->id
        ]);

        $this->assertIsString($kelas->nominal_bulanan);
        $this->assertEquals('100000.50', $kelas->nominal_bulanan);
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 100000,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'is_active' => 1
        ]);

        $this->assertIsBool($kelas->is_active);
        $this->assertTrue($kelas->is_active);
    }
}