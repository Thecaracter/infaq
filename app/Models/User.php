<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Scope untuk admin
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Scope untuk TU
    public function scopeTu($query)
    {
        return $query->where('role', 'tu');
    }

    // Relasi ke transaksi
    public function transaksiInfaqs()
    {
        return $this->hasMany(TransaksiInfaq::class);
    }
}