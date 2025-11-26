<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import ini

class PSUnit extends Model
{
    use HasFactory, SoftDeletes; // 2. Pasang Trait ini

    protected $table = 'ps_units';

    protected $fillable = [
        'name',
        'type',
        'hourly_rate',
        'is_active',
        'is_vip'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_vip' => 'boolean',
        'hourly_rate' => 'integer',
    ];
}