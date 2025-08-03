<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

        $kelasList = [
            ['nama_kelas' => '1A', 'tingkat' => '1'],
            ['nama_kelas' => '1B', 'tingkat' => '1'],
            ['nama_kelas' => '2A', 'tingkat' => '2'],
            ['nama_kelas' => '2B', 'tingkat' => '2'],
            ['nama_kelas' => '3A', 'tingkat' => '3'],
            ['nama_kelas' => '3B', 'tingkat' => '3'],
            ['nama_kelas' => '4A', 'tingkat' => '4'],
            ['nama_kelas' => '4B', 'tingkat' => '4'],
            ['nama_kelas' => '5A', 'tingkat' => '5'],
            ['nama_kelas' => '5B', 'tingkat' => '5'],
            ['nama_kelas' => '6A', 'tingkat' => '6'],
            ['nama_kelas' => '6B', 'tingkat' => '6'],
        ];

        foreach ($kelasList as $kelas) {
            Kelas::create([
                'nama_kelas' => $kelas['nama_kelas'],
                'tingkat' => $kelas['tingkat'],
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'is_active' => true,
            ]);
        }
    }
}