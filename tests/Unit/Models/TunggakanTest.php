<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Tunggakan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TunggakanTest extends TestCase
{
    use RefreshDatabase;

    protected function createTestData()
    {
        $tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true,
        ]);

        $orangTua = OrangTua::create([
            'nama_wali' => 'Bapak Ahmad Santoso',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'PNS',
            'hubungan' => 'ayah',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => '7A',
            'tingkat' => '7',
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'is_active' => true,
        ]);

        return Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Santoso',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $siswa = $this->createTestData();

        $tunggakan = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'jenis_kelas' => 'reguler',
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false,
            'keterangan' => 'Tunggakan SPP bulan Januari 2024',
        ]);

        $this->assertInstanceOf(Tunggakan::class, $tunggakan);
        $this->assertEquals($siswa->id, $tunggakan->siswa_id);
        $this->assertEquals('reguler', $tunggakan->jenis_kelas);
        $this->assertEquals('Januari', $tunggakan->bulan_tunggakan);
        $this->assertEquals('2024/2025', $tunggakan->tahun_ajaran);
        $this->assertEquals(500000, $tunggakan->nominal);
        $this->assertEquals('belum_bayar', $tunggakan->status);
        $this->assertEquals('Tunggakan SPP bulan Januari 2024', $tunggakan->keterangan);
    }

    /** @test */
    public function it_belongs_to_siswa()
    {
        $siswa = $this->createTestData();

        $tunggakan = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
        ]);

        $this->assertInstanceOf(Siswa::class, $tunggakan->siswa);
        $this->assertEquals($siswa->id, $tunggakan->siswa->id);
        $this->assertEquals('Ahmad Santoso', $tunggakan->siswa->nama_lengkap);
    }

    /** @test */
    public function it_has_belum_bayar_scope()
    {
        $siswa = $this->createTestData();

        $t1 = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
            'is_lunas' => false,
        ]);

        $t2 = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Februari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-02-10',
            'status' => 'lunas',
            'is_lunas' => true,
        ]);

        $collection = Tunggakan::belumLunas()->get();

        $this->assertTrue($collection->contains($t1));
        $this->assertFalse($collection->contains($t2));
    }

    /** @test */
    public function it_validates_status_values()
    {
        $validStatuses = ['belum_bayar', 'lunas', 'sebagian'];
        $siswa = $this->createTestData();

        foreach ($validStatuses as $status) {
            $tunggakan = Tunggakan::create([
                'siswa_id' => $siswa->id,
                'jenis_kelas' => 'SPP',
                'bulan_tunggakan' => 'Januari',
                'tahun_ajaran' => '2024/2025',
                'nominal' => 500000,
                'nominal_kelas' => 500000,
                'tanggal_jatuh_tempo' => '2024-01-10',
                'status' => $status,
            ]);

            $this->assertEquals($status, $tunggakan->status);
        }
    }

    /** @test */
    public function it_can_filter_by_tahun_and_bulan()
    {
        $siswa = $this->createTestData();

        $t1 = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Januari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-01-10',
            'status' => 'belum_bayar',
        ]);

        $t2 = Tunggakan::create([
            'siswa_id' => $siswa->id,
            'jenis_kelas' => 'SPP',
            'bulan_tunggakan' => 'Februari',
            'tahun_ajaran' => '2024/2025',
            'nominal' => 500000,
            'nominal_kelas' => 500000,
            'tanggal_jatuh_tempo' => '2024-02-10',
            'status' => 'belum_bayar',
        ]);

        $collection = Tunggakan::where('bulan_tunggakan', 'Januari')
            ->where('tahun_ajaran', '2024/2025')
            ->get();

        $this->assertTrue($collection->contains($t1));
        $this->assertFalse($collection->contains($t2));
    }
}
