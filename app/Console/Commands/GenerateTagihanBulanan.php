<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TagihanService;
use Carbon\Carbon;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate 
                           {--bulan= : Bulan dalam format YYYY-MM}
                           {--force : Force generate meskipun sudah ada}';

    protected $description = 'Generate tagihan bulanan untuk semua siswa aktif';

    public function handle()
    {
        $this->info('🚀 Memulai generate tagihan bulanan...');

        $bulan = $this->option('bulan') ?? Carbon::now()->format('Y-m');
        $this->info("📅 Bulan: {$bulan}");

        $tagihanService = new TagihanService();
        $result = $tagihanService->generateTagihanBulanan($bulan);

        if ($result['success']) {
            $this->info("✅ " . $result['message']);

            $this->table(
                ['Keterangan', 'Jumlah'],
                [
                    ['Berhasil dibuat', $result['data']['berhasil']],
                    ['Gagal', $result['data']['gagal']],
                    ['Sudah ada', $result['data']['sudah_ada']],
                    ['Total siswa', $result['data']['total_siswa']]
                ]
            );
        } else {
            $this->error("❌ " . $result['message']);
            return 1;
        }

        return 0;
    }
}