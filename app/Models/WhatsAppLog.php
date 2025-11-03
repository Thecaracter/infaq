<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'user_id',
        'phone_number',
        'message',
        'message_type',
        'status',
        'response_data',
        'error_message',
        'sent_at'
    ];

    protected function casts(): array
    {
        return [
            'response_data' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logMessage($data)
    {
        return self::create([
            'siswa_id' => $data['siswa_id'] ?? null,
            'user_id' => auth()->id(),
            'phone_number' => $data['phone_number'],
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'custom',
            'status' => $data['status'] ?? 'pending',
            'response_data' => $data['response_data'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'sent_at' => $data['status'] === 'sent' ? now() : null
        ]);
    }
}