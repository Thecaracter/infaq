<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jenis_kelas',
        'nominal_bulanan',
        'tahun_ajaran_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'nominal_bulanan' => 'decimal:2',
        ];
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function scopeReguler($query)
    {
        return $query->where('jenis_kelas', 'reguler');
    }


    public function scopePeminatan($query)
    {
        return $query->where('jenis_kelas', 'peminatan');
    }


    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }


    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }


    public function getNamaLengkapAttribute()
    {
        $jenis = $this->jenis_kelas === 'reguler' ? 'Reguler' : 'Peminatan';
        return "{$this->nama_kelas} ({$jenis})";
    }


    public function getTingkatSmaAttribute()
    {
        return match ($this->tingkat) {
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => $this->tingkat
        };
    }


    public function getNominalFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal_bulanan, 0, ',', '.');
    }


    public static function getTingkatOptions()
    {
        return [
            10 => 'Kelas X',
            11 => 'Kelas XI',
            12 => 'Kelas XII',
        ];
    }


    public static function getJenisKelasOptions()
    {
        return [
            'reguler' => 'Reguler',
            'peminatan' => 'Peminatan',
        ];
    }
}