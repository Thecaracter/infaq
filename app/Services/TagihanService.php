<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Tunggakan;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagihanService
{
    public function generateTagihanBulanan($bulan = null, $tahun = null)
    {
        try {
            DB::beginTransaction();

            $bulan = $bulan ?? Carbon::now()->format('Y-m');
            $tahunAjaran = $this->getCurrentTahunAjaran();

            Log::info("Generate tagihan untuk bulan: {$bulan}, tahun ajaran: {$tahunAjaran}");

            $siswaAktif = Siswa::active()
                ->with(['kelas', 'tahunAjaran'])
                ->get();

            $berhasil = 0;
            $gagal = 0;
            $sudahAda = 0;

            foreach ($siswaAktif as $siswa) {
                $existingTagihan = Tunggakan::where('siswa_id', $siswa->id)
                    ->where('bulan_tunggakan', $bulan)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->first();

                if ($existingTagihan) {
                    $sudahAda++;
                    continue;
                }

                try {
                    $tunggakan = new Tunggakan([
                        'siswa_id' => $siswa->id,
                        'bulan_tunggakan' => $bulan,
                        'tahun_ajaran' => $tahunAjaran,
                        'nominal' => $siswa->kelas->nominal_bulanan ?? 0,
                        'tanggal_jatuh_tempo' => Carbon::createFromFormat('Y-m', $bulan)->addMonth()->day(15),
                        'is_lunas' => false,
                        'status' => 'belum_bayar',
                        'keterangan' => "Tagihan infaq bulan " . Carbon::createFromFormat('Y-m', $bulan)->format('F Y')
                    ]);

                    $tunggakan->save();
                    $berhasil++;

                } catch (\Exception $e) {
                    Log::error("Gagal generate tagihan untuk siswa {$siswa->nama_lengkap}: " . $e->getMessage());
                    $gagal++;
                }
            }

            DB::commit();

            $result = [
                'success' => true,
                'message' => "Generate tagihan selesai. Berhasil: {$berhasil}, Gagal: {$gagal}, Sudah ada: {$sudahAda}",
                'data' => [
                    'berhasil' => $berhasil,
                    'gagal' => $gagal,
                    'sudah_ada' => $sudahAda,
                    'total_siswa' => $siswaAktif->count()
                ]
            ];

            Log::info("Generate tagihan selesai", $result['data']);
            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error generate tagihan bulanan: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal generate tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getTunggakanPerluReminder($limit = 50)
    {
        return Tunggakan::perluReminderBatch($limit)->with(['siswa.orangTua'])->get();
    }

    public function updateStatusTunggakan($siswaId, $bulan, $nominalBayar)
    {
        $tunggakan = Tunggakan::where('siswa_id', $siswaId)
            ->where('bulan_tunggakan', $bulan)
            ->first();

        if (!$tunggakan) {
            return false;
        }

        $totalBayar = \App\Models\TransaksiInfaq::where('siswa_id', $siswaId)
            ->where('bulan_bayar', $bulan)
            ->sum('nominal');

        if ($totalBayar >= $tunggakan->nominal) {
            $tunggakan->update([
                'is_lunas' => true,
                'status' => 'lunas'
            ]);
        } elseif ($totalBayar > 0) {
            $tunggakan->update([
                'is_lunas' => false,
                'status' => 'sebagian'
            ]);
        } else {
            $tunggakan->update([
                'is_lunas' => false,
                'status' => 'belum_bayar'
            ]);
        }

        return $tunggakan;
    }

    public function prosesPembayaran($siswaId, $bulan, $nominalBayar, $userId, $keterangan = null)
    {
        try {
            DB::beginTransaction();

            $tunggakan = Tunggakan::where('siswa_id', $siswaId)
                ->where('bulan_tunggakan', $bulan)
                ->first();

            if (!$tunggakan) {
                throw new \Exception('Tunggakan tidak ditemukan');
            }

            if ($tunggakan->is_lunas) {
                throw new \Exception('Tunggakan sudah lunas');
            }

            $transaksi = \App\Models\TransaksiInfaq::create([
                'siswa_id' => $siswaId,
                'user_id' => $userId,
                'tanggal_bayar' => now(),
                'bulan_bayar' => $bulan,
                'nominal' => $nominalBayar,
                'keterangan' => $keterangan ?? 'Pembayaran infaq bulan ' . $bulan
            ]);

            $this->updateStatusTunggakan($siswaId, $bulan, $nominalBayar);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'data' => [
                    'transaksi' => $transaksi,
                    'tunggakan' => $tunggakan->fresh()
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ];
        }
    }

    private function getCurrentTahunAjaran()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        return $tahunAjaran->nama_tahun ?? date('Y') . '/' . (date('Y') + 1);
    }

    public function getStatistikTagihan($bulan = null)
    {
        $bulan = $bulan ?? Carbon::now()->format('Y-m');

        $total = Tunggakan::where('bulan_tunggakan', $bulan)->count();
        $lunas = Tunggakan::where('bulan_tunggakan', $bulan)->where('is_lunas', true)->count();
        $belumLunas = $total - $lunas;
        $totalNominal = Tunggakan::where('bulan_tunggakan', $bulan)->sum('nominal');
        $nominalLunas = Tunggakan::where('bulan_tunggakan', $bulan)->where('is_lunas', true)->sum('nominal');

        return [
            'total_tagihan' => $total,
            'lunas' => $lunas,
            'belum_lunas' => $belumLunas,
            'persentase_lunas' => $total > 0 ? round(($lunas / $total) * 100, 2) : 0,
            'total_nominal' => $totalNominal,
            'nominal_lunas' => $nominalLunas,
            'nominal_outstanding' => $totalNominal - $nominalLunas
        ];
    }
}