<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\TransaksiInfaq;
use App\Models\Tunggakan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Admin dan TU
     */
    public function dashboard()
    {
        $user = Auth::user();
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $bulanIni = Carbon::now()->format('Y-m');

        // Data umum
        $data = [
            'user' => $user,
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'totalSiswa' => Siswa::active()->count(),
        ];

        if ($user->role === 'admin') {
            // Data khusus admin
            $data['totalKelas'] = Kelas::active()->count();
            $data['totalPembayaranBulanIni'] = TransaksiInfaq::whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])->sum('nominal');
            $data['totalTunggakan'] = Tunggakan::belumLunas()->sum('nominal');
            $data['pembayaranTerbaru'] = TransaksiInfaq::with(['siswa', 'user'])->latest()->take(5)->get();
            $data['tunggakanTerbanyak'] = Tunggakan::with('siswa')->belumLunas()->orderBy('nominal', 'desc')->take(5)->get();
            $data['chartData'] = $this->getChartDataPembayaran();
        } else {
            // Data khusus TU
            $data['pembayaranBulanIni'] = TransaksiInfaq::where('user_id', $user->id)
                ->whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])
                ->count();
            $data['totalNominalBulanIni'] = TransaksiInfaq::where('user_id', $user->id)
                ->whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])
                ->sum('nominal');
            $data['totalTunggakan'] = Tunggakan::belumLunas()->count();
            $data['pembayaranTerbaru'] = TransaksiInfaq::with('siswa')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            $data['siswaMenunggak'] = Siswa::whereHas('tunggakans', function ($query) {
                $query->belumLunas();
            })
                ->with([
                    'tunggakans' => function ($query) {
                        $query->belumLunas();
                    }
                ])
                ->take(10)
                ->get();
        }

        return view('dashboard', $data);
    }

    /**
     * Get chart data untuk pembayaran 6 bulan terakhir
     */
    private function getChartDataPembayaran()
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanFormat = $bulan->format('Y-m');

            $total = TransaksiInfaq::whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanFormat])
                ->sum('nominal');

            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => $total
            ];
        }

        return $data;
    }
}