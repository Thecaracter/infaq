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
        'keterangan',
        'bukti_pembayaran',
        'notifikasi_sent',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'nominal' => 'decimal:2',
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
}