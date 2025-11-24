<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'shift',
        'check_in',
        'check_out',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
    ];

    /*
     * Definisi shift: label + jam mulai/selesai.
     * Jam masih dummy, nanti bisa kamu revisi.
     */
    public static function shiftDefinitions(): array
    {
        return [
            'pagi' => [
                'label' => 'Pagi',
                'start' => '09:00',
                'end'   => '17:00',
            ],
            'sore' => [
                'label' => 'Sore',
                'start' => '17:00',
                'end'   => '24:00',
            ],
            'sakit' => [
                'label' => 'Sakit',
                'start' => null,
                'end'   => null,
            ],
            'izin' => [
                'label' => 'Izin',
                'start' => null,
                'end'   => null,
            ],
        ];
    }

    public static function shiftOptions(): array
    {
        return collect(self::shiftDefinitions())
            ->mapWithKeys(fn ($def, $key) => [$key => $def['label']])
            ->toArray();
    }

    /**
     * Shift yang dianggap "kerja" (dipakai menghitung telat / hadir).
     */
    public static function workingShiftKeys(): array
    {
        return ['pagi', 'sore'];
    }

    /**
     * Hitung status hadir/telat berdasarkan shift & jam check-in.
     */
    public static function determineStatus(string $shift, Carbon $tanggal, Carbon $checkIn): string
    {
        // kalau sakit / izin => status langsung itu
        if (in_array($shift, ['sakit', 'izin'], true)) {
            return $shift;
        }

        $defs = self::shiftDefinitions();
        if (! isset($defs[$shift]) || ! $defs[$shift]['start']) {
            return 'hadir';
        }

        $startTime = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $defs[$shift]['start']);

        // telat kalau > 10 menit dari jam mulai. 
        $deadline = $startTime->copy()->addMinutes(15);

        return $checkIn->greaterThan($deadline) ? 'telat' : 'hadir';
    }

    // RELASI
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
