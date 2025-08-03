<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'nominal_infaq_bulanan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
            'nominal_infaq_bulanan' => 'decimal:2',
        ];
    }

    // Scope untuk tahun ajaran aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    // Relasi ke siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}