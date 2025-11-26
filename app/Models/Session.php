<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // 1. Import Trait HasUuids

class Session extends Model
{
    use HasFactory, HasUuids; // 2. Gunakan Trait HasUuids di sini

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

    public function ps_unit()
    {
        return $this->belongsTo(PSUnit::class, 'ps_unit_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}