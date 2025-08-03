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
        $kelas1A = Kelas::where('nama_kelas', '1A')->first();
        $kelas2A = Kelas::where('nama_kelas', '2A')->first();
        $kelas3A = Kelas::where('nama_kelas', '3A')->first();

        $orangTuas = OrangTua::all();

        $siswaData = [
            [
                'nis' => '2024001',
                'nama_lengkap' => 'Ahmad Fauzi',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2018-01-15',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'kelas_id' => $kelas1A->id,
                'orang_tua_id' => $orangTuas[0]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024002',
                'nama_lengkap' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2016-03-20',
                'alamat' => 'Jl. Proklamasi No. 2, Jakarta',
                'kelas_id' => $kelas2A->id,
                'orang_tua_id' => $orangTuas[1]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
            [
                'nis' => '2024003',
                'nama_lengkap' => 'Muhammad Rizki',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2015-05-10',
                'alamat' => 'Jl. Pahlawan No. 3, Jakarta',
                'kelas_id' => $kelas3A->id,
                'orang_tua_id' => $orangTuas[2]->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ],
        ];

        foreach ($siswaData as $data) {
            Siswa::create($data);
        }
    }
}