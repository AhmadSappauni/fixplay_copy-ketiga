<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    protected $table = 'deletion_requests';

    protected $fillable = [
        'user_id',
        'target_table', // 'sales' atau 'products'
        'target_id',
        'description',
        'reason',
        'status', // 'pending', 'approved', 'rejected'
    ];

    // Relasi ke User (Karyawan yg request)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}