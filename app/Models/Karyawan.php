<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'phone',
        'foto',
        // nanti bisa tambah 'shift_default' jika dibutuhkan
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
