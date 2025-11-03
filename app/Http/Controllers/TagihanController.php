<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TagihanService;
use App\Services\WhatsAppService;
use App\Models\Tunggakan;
use App\Models\Siswa;
use App\Models\TransaksiInfaq;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    protected $tagihanService;
    protected $whatsAppService;

    public function __construct(TagihanService $tagihanService, WhatsAppService $whatsAppService)
    {
        $this->tagihanService = $tagihanService;
        $this->whatsAppService = $whatsAppService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $kelas = $request->get('kelas');
        $status = $request->get('status', 'all');

        $query = Siswa::active()
            ->with(['kelas', 'orangTua'])
            ->withCount([
                'tunggakans as total_tunggakan' => function ($q) {
                    $q->where('is_lunas', false);
                }
            ])
            ->withSum([
                'tunggakans as nominal_tunggakan' => function ($q) {
                    $q->where('is_lunas', false);
                }
            ], 'nominal');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($kelas) {
            $query->where('kelas_id', $kelas);
        }

        if ($status !== 'all') {
            if ($status === 'nunggak') {
                $query->having('total_tunggakan', '>', 0);
            } elseif ($status === 'lunas') {
                $query->having('total_tunggakan', '=', 0);
            }
        }

        $siswaList = $query->latest()->paginate(15);

        $bulanIni = Carbon::now()->format('Y-m');
        $statistik = $this->tagihanService->getStatistikTagihan($bulanIni);

        $kelasList = \App\Models\Kelas::active()->get();

        return view('pages.admin.tagihan', compact('siswaList', 'statistik', 'kelasList', 'search', 'kelas', 'status'));
    }

    public function showSiswa($id)
    {
        $siswa = Siswa::with(['kelas', 'orangTua', 'tahunAjaran'])->findOrFail($id);

        $tunggakan = Tunggakan::where('siswa_id', $id)
            ->with(['siswa'])
            ->orderBy('bulan_tunggakan', 'desc')
            ->get()
            ->groupBy('is_lunas');

        $riwayatPembayaran = TransaksiInfaq::where('siswa_id', $id)
            ->with(['user'])
            ->latest()
            ->take(10)
            ->get();

        $totalTunggakan = $tunggakan->get(0, collect())->sum('nominal');
        $totalLunas = $tunggakan->get(1, collect())->sum('nominal');

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => $siswa,
                'tunggakan_belum' => $tunggakan->get(0, collect()),
                'tunggakan_lunas' => $tunggakan->get(1, collect()),
                'riwayat_pembayaran' => $riwayatPembayaran,
                'summary' => [
                    'total_tunggakan' => $totalTunggakan,
                    'total_lunas' => $totalLunas,
                    'jumlah_bulan_nunggak' => $tunggakan->get(0, collect())->count(),
                    'bulan_terlambat' => $tunggakan->get(0, collect())->filter(function ($item) {
                        return $item->isTerlambat();
                    })->count()
                ]
            ]
        ]);
    }

    public function prosesPembayaran(Request $request)
    {
        $request->validate([
            'tunggakan_id' => 'required|exists:tunggakans,id',
            'nominal' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $tunggakan = Tunggakan::with(['siswa.orangTua', 'siswa.kelas'])->findOrFail($request->tunggakan_id);

            if ($tunggakan->is_lunas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tunggakan sudah lunas'
                ], 400);
            }

            $transaksi = TransaksiInfaq::create([
                'kode_transaksi' => 'INF-' . time(),
                'siswa_id' => $tunggakan->siswa_id,
                'user_id' => auth()->id(),
                'tanggal_bayar' => $request->tanggal_bayar,
                'bulan_bayar' => $tunggakan->bulan_tunggakan,
                'nominal' => $request->nominal,
                'nominal_kelas' => $tunggakan->nominal_kelas,
                'jenis_kelas' => $tunggakan->jenis_kelas,
                'keterangan' => $request->keterangan ?? 'Pembayaran infaq bulan ' . $tunggakan->bulan_tunggakan
            ]);

            $this->tagihanService->updateStatusTunggakan($tunggakan->siswa_id, $tunggakan->bulan_tunggakan, $request->nominal);

            DB::commit();

            $transaksi->load(['siswa.orangTua', 'siswa.kelas']);
            $this->sendPaymentConfirmation($transaksi);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses dan konfirmasi WhatsApp dikirim',
                'data' => [
                    'transaksi' => $transaksi,
                    'tunggakan' => $tunggakan->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendPaymentConfirmation($transaksi)
    {
        try {
            $result = $this->whatsAppService->sendKonfirmasiPembayaran($transaksi);
            $transaksi->update(['notifikasi_sent' => $result['success']]);
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation WhatsApp', [
                'transaksi_id' => $transaksi->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function generateViaWeb(Request $request)
    {
        try {
            $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

            Artisan::call('tagihan:generate', ['--bulan' => $bulan]);

            return back()->with('success', 'Tagihan berhasil di-generate untuk bulan ' . Carbon::createFromFormat('Y-m', $bulan)->format('F Y'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate tagihan: ' . $e->getMessage());
        }
    }

    public function generateAjax(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m'
        ]);

        $bulan = $request->bulan;
        $result = $this->tagihanService->generateTagihanBulanan($bulan);

        return response()->json($result);
    }

    public function kirimReminder($siswaId, $tunggakanId = null)
    {
        try {
            $siswa = Siswa::with(['orangTua'])->findOrFail($siswaId);

            if ($tunggakanId) {
                $tunggakan = Tunggakan::with(['siswa.orangTua', 'siswa.kelas'])->findOrFail($tunggakanId);

                $result = $this->whatsAppService->sendReminderTunggakan($tunggakan->siswa, $tunggakan);

                if ($result['success']) {
                    $tunggakan->markReminderSent();
                }

                return response()->json($result);
            } else {
                $tunggakanList = Tunggakan::where('siswa_id', $siswaId)
                    ->where('is_lunas', false)
                    ->with(['siswa.orangTua', 'siswa.kelas'])
                    ->get();

                if ($tunggakanList->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada tunggakan yang perlu reminder'
                    ], 400);
                }

                $berhasil = 0;
                $gagal = 0;

                foreach ($tunggakanList as $tunggakan) {
                    $result = $this->whatsAppService->sendReminderTunggakan($tunggakan->siswa, $tunggakan);

                    if ($result['success']) {
                        $tunggakan->markReminderSent();
                        $berhasil++;
                    } else {
                        $gagal++;
                    }

                    sleep(1);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Reminder selesai. Berhasil: {$berhasil}, Gagal: {$gagal}",
                    'data' => [
                        'berhasil' => $berhasil,
                        'gagal' => $gagal,
                        'total' => $tunggakanList->count()
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal kirim reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkReminder(Request $request)
    {
        try {
            $limit = $request->get('limit', 50);

            $tunggakanList = $this->tagihanService->getTunggakanPerluReminder($limit);

            $berhasil = 0;
            $gagal = 0;

            foreach ($tunggakanList as $tunggakan) {
                try {
                    $result = $this->whatsAppService->sendReminderTunggakan($tunggakan->siswa, $tunggakan);

                    if ($result['success']) {
                        $tunggakan->markReminderSent();
                        $berhasil++;
                    } else {
                        $gagal++;
                    }

                    sleep(1);

                } catch (\Exception $e) {
                    $gagal++;
                    \Log::error('Bulk reminder failed for tunggakan', [
                        'tunggakan_id' => $tunggakan->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk reminder selesai. Berhasil: {$berhasil}, Gagal: {$gagal}",
                'data' => [
                    'berhasil' => $berhasil,
                    'gagal' => $gagal,
                    'total' => $tunggakanList->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal bulk reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tunggakan = Tunggakan::findOrFail($id);

            $adaPembayaran = TransaksiInfaq::where('siswa_id', $tunggakan->siswa_id)
                ->where('bulan_bayar', $tunggakan->bulan_tunggakan)
                ->exists();

            if ($adaPembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan tidak bisa dihapus karena sudah ada pembayaran'
                ], 400);
            }

            $tunggakan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tagihan: ' . $e->getMessage()
            ], 500);
        }
    }
}