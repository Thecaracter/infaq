<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TagihanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function generateTagihan(Request $request)
    {
        $secret = config('app.cron_secret', 'simaniis-secret-2024');

        if ($request->get('secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $tagihanService = new TagihanService();
            $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

            $result = $tagihanService->generateTagihanBulanan($bulan);

            Log::info('Cron generate tagihan executed', $result);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Cron generate tagihan failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kirimReminder(Request $request)
    {
        $secret = config('app.cron_secret', 'simaniis-secret-2024');

        if ($request->get('secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $tagihanService = new TagihanService();
            $limit = $request->get('limit', 50);

            $tunggakanList = $tagihanService->getTunggakanPerluReminder($limit);

            $berhasil = 0;
            $gagal = 0;

            foreach ($tunggakanList as $tunggakan) {
                try {
                    $message = $tunggakan->getReminderMessage();
                    $noHp = $tunggakan->siswa->orangTua->no_hp_wa ?? $tunggakan->siswa->orangTua->no_hp;

                    $tunggakan->markReminderSent();
                    $berhasil++;

                } catch (\Exception $e) {
                    Log::error("Gagal kirim reminder untuk tunggakan ID {$tunggakan->id}: " . $e->getMessage());
                    $gagal++;
                }
            }

            $result = [
                'success' => true,
                'message' => "Reminder berhasil dikirim. Berhasil: {$berhasil}, Gagal: {$gagal}",
                'data' => [
                    'berhasil' => $berhasil,
                    'gagal' => $gagal,
                    'total' => $tunggakanList->count()
                ]
            ];

            Log::info('Cron reminder executed', $result['data']);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Cron reminder failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $secret = config('app.cron_secret', 'simaniis-secret-2024');

        if ($request->get('secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $lastTagihan = \App\Models\Tunggakan::latest()->first();
        $totalTunggakanBulanIni = \App\Models\Tunggakan::where('bulan_tunggakan', Carbon::now()->format('Y-m'))->count();
        $totalReminderHariIni = \App\Models\Tunggakan::whereDate('last_reminder', Carbon::today())->count();

        return response()->json([
            'status' => 'OK',
            'server_time' => now()->toDateTimeString(),
            'timezone' => config('app.timezone'),
            'last_tagihan_created' => $lastTagihan?->created_at,
            'total_tunggakan_bulan_ini' => $totalTunggakanBulanIni,
            'total_reminder_hari_ini' => $totalReminderHariIni,
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB'
        ]);
    }
}