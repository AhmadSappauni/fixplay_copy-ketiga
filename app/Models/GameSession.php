<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    // Pakai tabel game_sessions (bukan sessions)
    protected $table = 'game_sessions';

    // Kolom yang boleh di-mass-assign
    protected $fillable = [
        'ps_unit_id',
        'sale_id',
        'started_at',
        'ended_at',
        'extra_stick',
        'extra_arcade',
        'bill',
        'note',
    ];

    public function psUnit()
    {
        // Tambahkan ->withTrashed() agar unit yang dihapus tetap terbaca di riwayat
        return $this->belongsTo(PSUnit::class, 'ps_unit_id')->withTrashed();
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
