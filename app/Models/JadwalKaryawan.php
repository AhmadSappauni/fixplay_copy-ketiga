<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalKaryawan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_karyawans';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'shift',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
