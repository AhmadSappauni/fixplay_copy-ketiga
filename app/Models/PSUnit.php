<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PSUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ps_units';

    /**
     * Kolom yang boleh diisi secara massal (create/update).
     * PENTING: 'type' harus ada di sini agar dropdown PS4/PS5 tersimpan.
     */
    protected $fillable = [
        'name',
        'type',         // <--- Kunci utamanya ada di sini
        'hourly_rate',
        'is_active',
        'is_vip'        // Opsional (kalau masih dipakai untuk data lama)
    ];

    /**
     * Nilai default jika kolom kosong saat new PSUnit()
     */
    protected $attributes = [
        'type' => 'PS4',
        'is_active' => true,
    ];

    /**
     * Konversi tipe data otomatis
     */
    protected $casts = [
        'is_active'   => 'boolean',
        'is_vip'      => 'boolean',
        'hourly_rate' => 'integer',
    ];
}