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
            // Kelas X (10)
            [
                'nama_kelas' => 'X-IPA-1',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 150000
            ],
            [
                'nama_kelas' => 'X-IPA-2',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 150000
            ],
            [
                'nama_kelas' => 'X-IPS-1',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 150000
            ],
            [
                'nama_kelas' => 'X-IPS-2',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 150000
            ],
            [
                'nama_kelas' => 'X-BAHASA',
                'tingkat' => 10,
                'jenis_kelas' => 'reguler',
                'nominal_bulanan' => 150000
            ],

            // Kelas XI (11)
            [
                'nama_kelas' => 'XI-IPA-1',
                'tingkat' => 11,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 200000
            ],
            [
                'nama_kelas' => 'XI-IPA-2',
                'tingkat' => 11,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 200000
            ],
            [
                'nama_kelas' => 'XI-IPS-1',
                'tingkat' => 11,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 200000
            ],
            [
                'nama_kelas' => 'XI-IPS-2',
                'tingkat' => 11,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 200000
            ],
            [
                'nama_kelas' => 'XI-BAHASA',
                'tingkat' => 11,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 200000
            ],

            // Kelas XII (12)
            [
                'nama_kelas' => 'XII-IPA-1',
                'tingkat' => 12,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 250000
            ],
            [
                'nama_kelas' => 'XII-IPA-2',
                'tingkat' => 12,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 250000
            ],
            [
                'nama_kelas' => 'XII-IPS-1',
                'tingkat' => 12,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 250000
            ],
            [
                'nama_kelas' => 'XII-IPS-2',
                'tingkat' => 12,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 250000
            ],
            [
                'nama_kelas' => 'XII-BAHASA',
                'tingkat' => 12,
                'jenis_kelas' => 'peminatan',
                'nominal_bulanan' => 250000
            ],
        ];

        foreach ($kelasList as $kelas) {
            Kelas::create([
                'nama_kelas' => $kelas['nama_kelas'],
                'tingkat' => $kelas['tingkat'],
                'jenis_kelas' => $kelas['jenis_kelas'],
                'nominal_bulanan' => $kelas['nominal_bulanan'],
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'is_active' => true,
            ]);
        }
    }
}