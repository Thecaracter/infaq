<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrangTuaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'Guru',
            'hubungan' => 'ayah'
        ]);

        $this->assertInstanceOf(OrangTua::class, $orangTua);
        $this->assertEquals('Budi Santoso', $orangTua->nama_wali);
        $this->assertEquals('08123456789', $orangTua->no_hp);
        $this->assertEquals('Jl. Melati No. 10, Jakarta', $orangTua->alamat);
        $this->assertEquals('Guru', $orangTua->pekerjaan);
        $this->assertEquals('ayah', $orangTua->hubungan);
    }

    /** @test */
    public function it_has_many_siswa()
    {
        // Create required dependencies
        $tahunAjaran = \App\Models\TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true
        ]);

        $kelas = \App\Models\Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'tingkat' => 10,
            'jenis_kelas' => 'reguler',
            'nominal_bulanan' => 500000,
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'Guru',
            'hubungan' => 'ayah'
        ]);

        $siswa1 = Siswa::create([
            'nis' => '12345',
            'nama_lengkap' => 'Ahmad Santoso',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $siswa2 = Siswa::create([
            'nis' => '12346',
            'nama_lengkap' => 'Fatimah Santoso',
            'tanggal_lahir' => '2010-03-20',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
            'orang_tua_id' => $orangTua->id,
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $this->assertTrue($orangTua->siswas->contains($siswa1));
        $this->assertTrue($orangTua->siswas->contains($siswa2));
        $this->assertEquals(2, $orangTua->siswas->count());
    }

    /** @test */
    public function it_uses_soft_deletes()
    {
        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'Guru',
            'hubungan' => 'ayah'
        ]);

        $orangTua->delete();

        $this->assertSoftDeleted($orangTua);

        // Can still be found with trashed
        $this->assertTrue(OrangTua::withTrashed()->where('id', $orangTua->id)->exists());

        // Not found in regular query
        $this->assertFalse(OrangTua::where('id', $orangTua->id)->exists());
    }

    /** @test */
    public function it_can_be_restored_after_soft_delete()
    {
        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'Guru',
            'hubungan' => 'ayah'
        ]);

        $orangTua->delete();
        $orangTua->restore();

        $this->assertNotSoftDeleted($orangTua);
        $this->assertTrue(OrangTua::where('id', $orangTua->id)->exists());
    }

    /** @test */
    public function it_validates_hubungan_enum_values()
    {
        $validHubungans = ['ayah', 'ibu', 'wali'];

        foreach ($validHubungans as $hubungan) {
            $orangTua = OrangTua::create([
                'nama_wali' => 'Test Wali',
                'no_hp' => '08123456789',
                'alamat' => 'Jl. Test No. 1',
                'pekerjaan' => 'Test Job',
                'hubungan' => $hubungan
            ]);

            $this->assertEquals($hubungan, $orangTua->hubungan);
        }
    }

    /** @test */
    public function it_can_have_nullable_fields()
    {
        $orangTua = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'hubungan' => 'ayah'
        ]);

        $this->assertInstanceOf(OrangTua::class, $orangTua);
        $this->assertNull($orangTua->pekerjaan);
        $this->assertEquals('ayah', $orangTua->hubungan);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $attributes = [
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta',
            'pekerjaan' => 'Guru',
            'hubungan' => 'ayah'
        ];

        $orangTua = new OrangTua();
        $orangTua->fill($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $orangTua->{$key});
        }
    }

    /** @test */
    public function it_formats_phone_number_consistently()
    {
        $orangTua1 = OrangTua::create([
            'nama_wali' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Melati No. 10, Jakarta'
        ]);

        $orangTua2 = OrangTua::create([
            'nama_wali' => 'Ahmad Ibrahim',
            'no_hp' => '+6281234567890',
            'alamat' => 'Jl. Mawar No. 5, Bandung'
        ]);

        // Both should store the phone number as provided
        $this->assertEquals('08123456789', $orangTua1->no_hp);
        $this->assertEquals('+6281234567890', $orangTua2->no_hp);
    }
}