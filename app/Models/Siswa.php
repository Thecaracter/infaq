<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'kelas_id',
        'orang_tua_id',
        'tahun_ajaran_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Scope untuk siswa aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke orang tua
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }

    // Relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke transaksi infaq
    public function transaksiInfaqs()
    {
        return $this->hasMany(TransaksiInfaq::class);
    }

    // Relasi ke tunggakan
    public function tunggakans()
    {
        return $this->hasMany(Tunggakan::class);
    }

    // Accessor untuk nama lengkap dengan NIS
    public function getNamaLengkapNisAttribute()
    {
        return $this->nama_lengkap . ' (' . $this->nis . ')';
    }
}