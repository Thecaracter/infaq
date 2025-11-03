<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class UpdatePeminatanSeeder extends Seeder
{
    public function run(): void
    {
        // Update kelas IPA
        Kelas::where('nama_kelas', 'LIKE', '%IPA%')
            ->where('jenis_kelas', 'peminatan')
            ->update(['peminatan' => 'IPA']);

        // Update kelas IPS  
        Kelas::where('nama_kelas', 'LIKE', '%IPS%')
            ->where('jenis_kelas', 'peminatan')
            ->update(['peminatan' => 'IPS']);

        // Update kelas BAHASA
        Kelas::where('nama_kelas', 'LIKE', '%BAHASA%')
            ->where('jenis_kelas', 'peminatan')
            ->update(['peminatan' => 'BAHASA']);

        $this->command->info('Data peminatan berhasil diupdate!');
    }
}