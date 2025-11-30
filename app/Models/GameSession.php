<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // PENTING: Ini harus ada

class GameSession extends Model
{
    // PENTING: Ini harus ada agar sinkron dengan database yang pakai UUID
    use HasFactory, HasUuids; 

    // Pastikan nama tabel sesuai dengan database Anda
    protected $table = 'game_sessions';

    protected $fillable = [
        'ps_unit_id',
        'sale_id',
        'start_time',
        'end_time',
        'minutes',
        'extra_controllers',
        'arcade_controllers',
        'bill',
        'payment_method',
        'paid_amount',
        'status',
        'note',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    // Relasi ke PS Unit (perhatikan nama fungsi camelCase)
    public function psUnit()
    {
        return $this->belongsTo(PSUnit::class, 'ps_unit_id');
    }

    // Relasi ke Sale (Transaksi)
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}