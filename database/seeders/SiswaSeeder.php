<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $kelasXIPA1 = Kelas::where('nama_kelas', 'X-IPA-1')->first();
        $kelasXIIPA1 = Kelas::where('nama_kelas', 'XI-IPA-1')->first();
        $kelasXIIIPA1 = Kelas::where('nama_kelas', 'XII-IPA-1')->first();

        $orangTuas = OrangTua::all();

        $siswaData = [
            [
                'nis' => '2024001',
                'nama_lengkap' => 'Ahmad Fauzi Rahman',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2009-01-15',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta Selatan',
                'kelas_id' => $kelasXIPA1->id,
                'orang_tua_id' => $orangTuas[0]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024002',
                'nama_lengkap' => 'Siti Nurhaliza Putri',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-03-20',
                'alamat' => 'Jl. Proklamasi No. 2, Jakarta Pusat',
                'kelas_id' => $kelasXIIPA1->id,
                'orang_tua_id' => $orangTuas[1]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024003',
                'nama_lengkap' => 'Muhammad Rizki Pratama',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2007-05-10',
                'alamat' => 'Jl. Pahlawan No. 3, Jakarta Timur',
                'kelas_id' => $kelasXIIIPA1->id,
                'orang_tua_id' => $orangTuas[2]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024004',
                'nama_lengkap' => 'Dewi Sartika Maharani',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2009-08-22',
                'alamat' => 'Jl. Diponegoro No. 4, Jakarta Barat',
                'kelas_id' => $kelasXIPA1->id,
                'orang_tua_id' => $orangTuas[3]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024005',
                'nama_lengkap' => 'Bayu Adi Saputra',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-12-05',
                'alamat' => 'Jl. Sudirman No. 5, Jakarta Utara',
                'kelas_id' => $kelasXIIPA1->id,
                'orang_tua_id' => $orangTuas[4]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
        ];

        foreach ($siswaData as $data) {
            Siswa::create($data);
        }
    }
}