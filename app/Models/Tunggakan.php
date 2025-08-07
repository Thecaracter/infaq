<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tunggakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'bulan_tunggakan',
        'tahun_ajaran',
        'nominal',
        'nominal_kelas',
        'jenis_kelas',
        'is_lunas',
        'status',
        'tanggal_jatuh_tempo',
        'notifikasi_sent',
        'reminder_count',
        'last_reminder',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'nominal_kelas' => 'decimal:2',
            'is_lunas' => 'boolean',
            'tanggal_jatuh_tempo' => 'date',
            'notifikasi_sent' => 'boolean',
            'last_reminder' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tunggakan) {
            if ($tunggakan->siswa_id) {
                $siswa = \App\Models\Siswa::find($tunggakan->siswa_id);
                if ($siswa && $siswa->kelas) {
                    $tunggakan->nominal_kelas = $siswa->kelas->nominal_bulanan;
                    $tunggakan->jenis_kelas = $siswa->kelas->jenis_kelas;

                    if (!$tunggakan->nominal) {
                        $tunggakan->nominal = $siswa->kelas->nominal_bulanan;
                    }
                }
            }

            if (!$tunggakan->tahun_ajaran) {
                $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();
                $tunggakan->tahun_ajaran = $tahunAjaran->nama_tahun ?? date('Y') . '/' . (date('Y') + 1);
            }
        });
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('is_lunas', false);
    }

    public function scopeJatuhTempo($query)
    {
        return $query->where('tanggal_jatuh_tempo', '<', now()->toDateString());
    }

    public function scopeTahunAjaran($query, $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function getNominalFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getCanPayAttribute()
    {
        return !$this->is_lunas;
    }

    public function isTerlambat()
    {
        return !$this->is_lunas && $this->tanggal_jatuh_tempo < now();
    }

    public function perluReminderBulanan()
    {
        if ($this->is_lunas) {
            return false;
        }

        $bulanTerlambat = $this->getBulanTerlambat();
        $reminderCount = $this->reminder_count;

        $sudahKirimBulanIni = $this->last_reminder &&
            $this->last_reminder->format('Y-m') === now()->format('Y-m');

        if ($bulanTerlambat <= 0 || $sudahKirimBulanIni) {
            return false;
        }

        return match ($bulanTerlambat) {
            1 => $reminderCount == 0,
            2 => $reminderCount == 1,
            3 => $reminderCount == 2,
            default => $reminderCount < $bulanTerlambat
        };
    }

    public function getBulanTerlambat()
    {
        if ($this->is_lunas) {
            return 0;
        }

        $tanggalJatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
        $sekarang = Carbon::now();

        if ($sekarang <= $tanggalJatuhTempo) {
            return 0;
        }

        return $sekarang->diffInMonths($tanggalJatuhTempo);
    }

    public function getTotalTunggakanSiswa()
    {
        return self::where('siswa_id', $this->siswa_id)
            ->where('is_lunas', false)
            ->count();
    }

    public function getTotalNominalTunggakanSiswa()
    {
        return self::where('siswa_id', $this->siswa_id)
            ->where('is_lunas', false)
            ->sum('nominal');
    }

    public function markReminderSent()
    {
        $this->update([
            'reminder_count' => $this->reminder_count + 1,
            'last_reminder' => now(),
            'notifikasi_sent' => true
        ]);
    }

    public function getReminderMessage()
    {
        $siswa = $this->siswa;
        $bulanTerlambat = $this->getBulanTerlambat();
        $totalTunggakan = $this->getTotalTunggakanSiswa();
        $totalNominal = $this->getTotalNominalTunggakanSiswa();

        $namaOrangTua = $siswa->orangTua->nama_wali ?? 'Orang Tua';

        if ($bulanTerlambat == 0) {
            return "Yth. {$namaOrangTua}, reminder pembayaran infaq a.n {$siswa->nama_lengkap} bulan {$this->bulan_tunggakan} sebesar {$this->nominal_format}. Batas waktu sampai " . $this->tanggal_jatuh_tempo->format('d/m/Y') . ". Terima kasih.";
        }

        return "Yth. {$namaOrangTua}, pembayaran infaq a.n {$siswa->nama_lengkap} sudah terlambat {$bulanTerlambat} bulan dengan total tunggakan {$totalTunggakan} bulan sebesar Rp " . number_format($totalNominal, 0, ',', '.') . ". Mohon segera dilunasi. Terima kasih.";
    }

    public static function scopePerluReminderBatch($query, $limit = 50)
    {
        return $query->where('is_lunas', false)
            ->whereRaw('(
                        CASE 
                            WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) >= 30 AND reminder_count = 0 THEN 1
                            WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) >= 60 AND reminder_count = 1 THEN 1  
                            WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) >= 90 AND reminder_count = 2 THEN 1
                            WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) >= 120 AND reminder_count < 4 THEN 1
                            ELSE 0
                        END
                    ) = 1')
            ->where(function ($q) {
                $q->whereNull('last_reminder')
                    ->orWhere('last_reminder', '<', now()->subMonth());
            })
            ->limit($limit);
    }
}

?>