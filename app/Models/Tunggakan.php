<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunggakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'bulan_tunggakan',
        'nominal',
        'is_lunas',
        'tanggal_jatuh_tempo',
        'notifikasi_sent',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'is_lunas' => 'boolean',
            'tanggal_jatuh_tempo' => 'date',
            'notifikasi_sent' => 'boolean',
        ];
    }

    // Scope untuk tunggakan belum lunas
    public function scopeBelumLunas($query)
    {
        return $query->where('is_lunas', false);
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}