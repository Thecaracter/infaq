<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TransaksiInfaq;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransaksiInfaqTest extends TestCase
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

        $siswa = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Santoso',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        return [$siswa, $user];
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        [$siswa, $user] = $this->createTestData();

        $transaksi = TransaksiInfaq::create([
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'bulan_bayar' => 'Januari',
            'nominal' => 100000,
            'nominal_kelas' => 100000,
            'jenis_kelas' => 'reguler',
            'tanggal_bayar' => '2024-01-15',
            'kode_transaksi' => 'INF202401001',
            'keterangan' => 'Pembayaran infaq bulan Januari 2024',
        ]);

        $this->assertInstanceOf(TransaksiInfaq::class, $transaksi);
        $this->assertEquals($siswa->id, $transaksi->siswa_id);
        $this->assertEquals('reguler', $transaksi->jenis_kelas);
        $this->assertEquals('Januari', $transaksi->bulan_bayar);
        $this->assertEquals(100000, $transaksi->nominal);
        $this->assertEquals('2024-01-15', \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('Y-m-d'));
        $this->assertEquals('Pembayaran infaq bulan Januari 2024', $transaksi->keterangan);
    }

    /** @test */
    public function it_belongs_to_siswa()
    {
        [$siswa, $user] = $this->createTestData();

        $transaksi = TransaksiInfaq::create([
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'bulan_bayar' => 'Januari',
            'nominal' => 100000,
            'nominal_kelas' => 100000,
            'jenis_kelas' => 'reguler',
            'tanggal_bayar' => '2024-01-15',
            'kode_transaksi' => 'INF202401002',
            'keterangan' => 'Test transaksi',
        ]);

        $this->assertInstanceOf(Siswa::class, $transaksi->siswa);
        $this->assertEquals($siswa->id, $transaksi->siswa->id);
        $this->assertEquals('Ahmad Santoso', $transaksi->siswa->nama_lengkap);
    }

    /** @test */
    public function it_can_filter_by_bulan()
    {
        [$siswa, $user] = $this->createTestData();

        $t1 = TransaksiInfaq::create([
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'bulan_bayar' => 'Januari',
            'nominal' => 100000,
            'nominal_kelas' => 100000,
            'jenis_kelas' => 'reguler',
            'tanggal_bayar' => '2024-01-15',
            'kode_transaksi' => 'INF202401003',
        ]);

        $t2 = TransaksiInfaq::create([
            'siswa_id' => $siswa->id,
            'user_id' => $user->id,
            'bulan_bayar' => 'Februari',
            'nominal' => 100000,
            'nominal_kelas' => 100000,
            'jenis_kelas' => 'reguler',
            'tanggal_bayar' => '2024-02-15',
            'kode_transaksi' => 'INF202402003',
        ]);

        $collection = TransaksiInfaq::where('bulan_bayar', 'Januari')->get();

        $this->assertTrue($collection->contains($t1));
        $this->assertFalse($collection->contains($t2));
    }

}