<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_wali',
        'no_hp',
        'alamat',
        'pekerjaan',
        'hubungan',
    ];

    // Relasi ke siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    // Accessor untuk format nomor HP WhatsApp
    public function getNoHpWaAttribute()
    {
        // Format nomor HP untuk WhatsApp (hapus karakter non-digit, tambah 62)
        $nomor = preg_replace('/[^0-9]/', '', $this->no_hp);

        // Jika diawali 0, ganti dengan 62
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        // Jika belum diawali 62, tambahkan
        if (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }
}