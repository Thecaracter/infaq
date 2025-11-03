<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppLog;

class WhatsAppService
{
    private $baseUrl;
    private $apiKey;
    private $deviceId;

    public function __construct()
    {
        $this->baseUrl = config('whatsapp.fontee.base_url', 'https://api.fonnte.com');
        $this->apiKey = config('whatsapp.fontee.api_key');
        $this->deviceId = config('whatsapp.fontee.device_id');
    }

    public function sendMessage($phoneNumber, $message, $type = 'text')
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            // Format untuk Fonnte API
            $payload = [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62'
            ];

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey, // Fonnte pakai langsung API key
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/send', $payload);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $formattedPhone,
                    'message_preview' => substr($message, 0, 50) . '...'
                ]);

                return [
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $response->body(),
                'error_code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error sistem: ' . $e->getMessage()
            ];
        }
    }

    public function sendReminderTunggakan($siswa, $tunggakan)
    {
        if (!$siswa->orangTua || !$siswa->orangTua->no_hp) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp orang tua tidak tersedia'
            ];
        }

        $message = $this->generateReminderMessage($siswa, $tunggakan);
        $result = $this->sendMessage($siswa->orangTua->no_hp, $message);

        // Log ke database
        WhatsAppLog::logMessage([
            'siswa_id' => $siswa->id,
            'phone_number' => $siswa->orangTua->no_hp,
            'message' => $message,
            'message_type' => 'reminder',
            'status' => $result['success'] ? 'sent' : 'failed',
            'response_data' => $result['data'] ?? null,
            'error_message' => $result['success'] ? null : $result['message']
        ]);

        return $result;
    }

    public function sendKonfirmasiPembayaran($transaksi)
    {
        $siswa = $transaksi->siswa;

        if (!$siswa->orangTua || !$siswa->orangTua->no_hp) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp orang tua tidak tersedia'
            ];
        }

        $message = $this->generateKonfirmasiMessage($transaksi);
        $result = $this->sendMessage($siswa->orangTua->no_hp, $message);

        // Log ke database
        WhatsAppLog::logMessage([
            'siswa_id' => $siswa->id,
            'phone_number' => $siswa->orangTua->no_hp,
            'message' => $message,
            'message_type' => 'payment_confirmation',
            'status' => $result['success'] ? 'sent' : 'failed',
            'response_data' => $result['data'] ?? null,
            'error_message' => $result['success'] ? null : $result['message']
        ]);

        return $result;
    }

    private function generateReminderMessage($siswa, $tunggakan)
    {
        $namaSekolah = config('app.name', 'SIMANIIS');
        $namaOrangTua = $siswa->orangTua->nama_wali ?? 'Bapak/Ibu';

        // Fix perhitungan hari terlambat - perbaiki logika
        $tanggalJatuhTempo = \Carbon\Carbon::parse($tunggakan->tanggal_jatuh_tempo);

        // Jika tanggal sekarang > tanggal jatuh tempo, berarti terlambat
        if (now()->greaterThan($tanggalJatuhTempo)) {
            $hariTerlambat = (int) $tanggalJatuhTempo->diffInDays(now());
        } else {
            $hariTerlambat = 0; // Belum jatuh tempo
        }

        $bulanTerlambat = (int) floor($hariTerlambat / 30); // Convert ke integer

        // Cek apakah masih dalam periode normal atau sudah terlambat
        if ($hariTerlambat == 0) {
            // Reminder normal (belum terlambat)
            return "🏫 *{$namaSekolah}*\n" .
                "━━━━━━━━━━━━━━━━━━━━\n\n" .
                "Assalamu'alaikum Wr. Wb.\n" .
                "Yth. {$namaOrangTua}\n\n" .
                "📋 *REMINDER PEMBAYARAN INFAQ*\n\n" .
                "👤 Nama Siswa : *{$siswa->nama_lengkap}*\n" .
                "🎓 Kelas      : *" . ($siswa->kelas->nama_kelas ?? '-') . "*\n" .
                "📅 Periode    : *{$this->formatBulanIndonesia($tunggakan->bulan_tunggakan)}*\n" .
                "💰 Nominal    : *Rp " . number_format($tunggakan->nominal, 0, ',', '.') . "*\n" .
                "⏰ Jatuh Tempo: *" . $tanggalJatuhTempo->format('d M Y') . "*\n\n" .
                "Mohon untuk segera melakukan pembayaran sebelum tanggal jatuh tempo.\n\n" .
                "Jazakumullah khair atas perhatiannya.\n" .
                "Wassalamu'alaikum Wr. Wb.\n\n" .
                "━━━━━━━━━━━━━━━━━━━━\n" .
                "_Pesan otomatis dari {$namaSekolah}_";
        } else {
            // Reminder terlambat
            $totalTunggakan = $siswa->tunggakans()->where('is_lunas', false)->count();
            $totalNominal = $siswa->tunggakans()->where('is_lunas', false)->sum('nominal');

            $statusKeterlambatan = '';
            if ($hariTerlambat <= 30) {
                $statusKeterlambatan = "⚠️ TERLAMBAT {$hariTerlambat} HARI";
            } else {
                $statusKeterlambatan = "🚨 TERLAMBAT {$bulanTerlambat} BULAN";
            }

            return "🏫 *{$namaSekolah}*\n" .
                "━━━━━━━━━━━━━━━━━━━━\n\n" .
                "Assalamu'alaikum Wr. Wb.\n" .
                "Yth. {$namaOrangTua}\n\n" .
                "📋 *PEMBERITAHUAN TUNGGAKAN*\n" .
                "{$statusKeterlambatan}\n\n" .
                "👤 Nama Siswa : *{$siswa->nama_lengkap}*\n" .
                "🎓 Kelas      : *" . ($siswa->kelas->nama_kelas ?? '-') . "*\n\n" .
                "📊 *DETAIL TUNGGAKAN:*\n" .
                "• Total Bulan : *{$totalTunggakan} bulan*\n" .
                "• Total Nominal : *Rp " . number_format($totalNominal, 0, ',', '.') . "*\n\n" .
                "🙏 Mohon untuk segera melunasi tunggakan agar tidak mengganggu proses pembelajaran putra/putri Anda.\n\n" .
                "📞 Untuk informasi lebih lanjut, silakan hubungi sekolah.\n\n" .
                "Jazakumullah khair atas perhatiannya.\n" .
                "Wassalamu'alaikum Wr. Wb.\n\n" .
                "━━━━━━━━━━━━━━━━━━━━\n" .
                "_Pesan otomatis dari {$namaSekolah}_";
        }
    }

    private function formatBulanIndonesia($bulanTahun)
    {
        $bulanIndo = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $parts = explode('-', $bulanTahun);
        if (count($parts) == 2) {
            $tahun = $parts[0];
            $bulan = $parts[1];
            return ($bulanIndo[$bulan] ?? 'Bulan') . ' ' . $tahun;
        }

        return $bulanTahun;
    }

    private function generateKonfirmasiMessage($transaksi)
    {
        $namaSekolah = config('app.name', 'SIMANIIS');
        $siswa = $transaksi->siswa;
        $namaOrangTua = $siswa->orangTua->nama_wali ?? 'Bapak/Ibu';

        return "🏫 *{$namaSekolah}*\n" .
            "━━━━━━━━━━━━━━━━━━━━\n\n" .
            "Assalamu'alaikum Wr. Wb.\n" .
            "Yth. {$namaOrangTua}\n\n" .
            "✅ *KONFIRMASI PEMBAYARAN*\n\n" .
            "Alhamdulillah, pembayaran infaq telah kami terima dengan detail sebagai berikut:\n\n" .
            "👤 Nama Siswa : *{$siswa->nama_lengkap}*\n" .
            "🎓 Kelas      : *" . ($siswa->kelas->nama_kelas ?? '-') . "*\n" .
            "📅 Periode    : *{$this->formatBulanIndonesia($transaksi->bulan_bayar)}*\n" .
            "💰 Nominal    : *Rp " . number_format($transaksi->nominal, 0, ',', '.') . "*\n" .
            "📝 Kode Transaksi : *{$transaksi->kode_transaksi}*\n" .
            "📆 Tanggal    : *" . $transaksi->tanggal_bayar->format('d M Y H:i') . " WIB*\n\n" .
            "Jazakumullah khair atas pembayaran dan kepercayaan yang diberikan kepada sekolah.\n\n" .
            "Semoga Allah SWT membalas kebaikan Bapak/Ibu.\n" .
            "Wassalamu'alaikum Wr. Wb.\n\n" .
            "━━━━━━━━━━━━━━━━━━━━\n" .
            "_Pesan otomatis dari {$namaSekolah}_";
    }

    private function formatPhoneNumber($phoneNumber)
    {
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public function testConnection()
    {
        try {
            // Test dengan endpoint device info untuk Fonnte
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/device');

            $responseData = $response->json();

            Log::info('WhatsApp test connection', [
                'status_code' => $response->status(),
                'response' => $responseData,
                'api_key' => substr($this->apiKey, 0, 8) . '...',
                'base_url' => $this->baseUrl
            ]);

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'message' => $response->successful()
                    ? 'Koneksi berhasil - Device: ' . ($responseData['device']['name'] ?? 'Unknown')
                    : 'Koneksi gagal: ' . ($responseData['reason'] ?? $response->body()),
                'data' => $responseData,
                'config' => [
                    'base_url' => $this->baseUrl,
                    'api_key_preview' => substr($this->apiKey, 0, 8) . '...',
                    'device_id' => $this->deviceId
                ]
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp test connection error', [
                'error' => $e->getMessage(),
                'config' => [
                    'base_url' => $this->baseUrl,
                    'api_key_preview' => substr($this->apiKey ?? 'NULL', 0, 8) . '...'
                ]
            ]);

            return [
                'success' => false,
                'message' => 'Error koneksi: ' . $e->getMessage(),
                'config' => [
                    'base_url' => $this->baseUrl,
                    'api_key_preview' => substr($this->apiKey ?? 'NULL', 0, 8) . '...',
                    'device_id' => $this->deviceId
                ]
            ];
        }
    }
}