<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
            'is_active' => true,
        ]);

        TahunAjaran::create([
            'nama_tahun' => '2023/2024',
            'tanggal_mulai' => '2023-07-01',
            'tanggal_selesai' => '2024-06-30',
            'is_active' => false,
        ]);

        TahunAjaran::create([
            'nama_tahun' => '2022/2023',
            'tanggal_mulai' => '2022-07-01',
            'tanggal_selesai' => '2023-06-30',
            'is_active' => false,
        ]);
    }
}