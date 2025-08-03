<?php

namespace Database\Seeders;

use App\Models\OrangTua;
use Illuminate\Database\Seeder;

class OrangTuaSeeder extends Seeder
{
    public function run(): void
    {
        $orangTuaData = [
            [
                'nama_wali' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'pekerjaan' => 'PNS',
                'hubungan' => 'ayah',
            ],
            [
                'nama_wali' => 'Siti Aminah',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Proklamasi No. 2, Jakarta',
                'pekerjaan' => 'Guru',
                'hubungan' => 'ibu',
            ],
            [
                'nama_wali' => 'Ahmad Rahman',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Pahlawan No. 3, Jakarta',
                'pekerjaan' => 'Wiraswasta',
                'hubungan' => 'ayah',
            ],
            [
                'nama_wali' => 'Fatimah Zahra',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Diponegoro No. 4, Jakarta',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'hubungan' => 'ibu',
            ],
            [
                'nama_wali' => 'Dedi Kurniawan',
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Sudirman No. 5, Jakarta',
                'pekerjaan' => 'Karyawan Swasta',
                'hubungan' => 'wali',
            ],
        ];

        foreach ($orangTuaData as $data) {
            OrangTua::create($data);
        }
    }
}