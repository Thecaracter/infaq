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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
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

    // Accessor untuk durasi tahun ajaran dalam hari
    public function getDurasiHariAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    // Accessor untuk status aktif dengan teks
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Accessor untuk format periode
    public function getPeriodeAttribute()
    {
        return $this->tanggal_mulai->format('d M Y') . ' - ' . $this->tanggal_selesai->format('d M Y');
    }
}