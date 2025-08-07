<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiInfaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'siswa_id',
        'user_id',
        'tanggal_bayar',
        'bulan_bayar',
        'nominal',
        'nominal_kelas',
        'jenis_kelas',
        'keterangan',
        'bukti_pembayaran',
        'notifikasi_sent',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'nominal' => 'decimal:2',
            'nominal_kelas' => 'decimal:2',
            'notifikasi_sent' => 'boolean',
        ];
    }

    // Generate kode transaksi otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            $transaksi->kode_transaksi = 'INF' . date('Ymd') . str_pad(
                TransaksiInfaq::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Auto fill nominal_kelas dan jenis_kelas dari kelas siswa
            if ($transaksi->siswa_id) {
                $siswa = \App\Models\Siswa::find($transaksi->siswa_id);
                if ($siswa && $siswa->kelas) {
                    $transaksi->nominal_kelas = $siswa->kelas->nominal_bulanan;
                    $transaksi->jenis_kelas = $siswa->kelas->jenis_kelas;
                }
            }
        });
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke user (TU)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format nominal
    public function getNominalFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // Accessor untuk format nominal kelas
    public function getNominalKelasFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal_kelas, 0, ',', '.');
    }

    // Accessor untuk jenis kelas format
    public function getJenisKelasFormatAttribute()
    {
        return $this->jenis_kelas === 'reguler' ? 'Reguler' : 'Peminatan';
    }

    // Accessor untuk status pembayaran
    public function getStatusPembayaranAttribute()
    {
        if ($this->nominal >= $this->nominal_kelas) {
            return 'Lunas';
        } elseif ($this->nominal > 0) {
            return 'Kurang Bayar';
        } else {
            return 'Belum Bayar';
        }
    }

    // Accessor untuk sisa pembayaran
    public function getSisaPembayaranAttribute()
    {
        return max(0, $this->nominal_kelas - $this->nominal);
    }
}