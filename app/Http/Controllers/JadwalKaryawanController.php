<?php

namespace App\Http\Controllers;

use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalKaryawanController extends Controller
{
    /**
     * Tampilkan jadwal mingguan dan form edit untuk 1 karyawan.
     */
    public function index(Request $request)
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        $shifts    = Presensi::shiftOptions();

        // karyawan terpilih (default: karyawan pertama jika ada)
        $selectedKaryawanId = $request->input('karyawan_id') ?: ($karyawans->first()->id ?? null);

        // tanggal acuan minggu (default: hari ini)
        $weekDate = $request->input('week_date')
            ? Carbon::parse($request->input('week_date'))
            : Carbon::today();

        // start minggu = Senin, end = Minggu
        $startOfWeek = $weekDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = $weekDate->copy()->endOfWeek(Carbon::SUNDAY);

        // ambil jadwal yang sudah ada untuk minggu ini
        $jadwal = JadwalKaryawan::where('karyawan_id', $selectedKaryawanId)
            ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get()
            ->keyBy(fn ($item) => $item->tanggal->toDateString());

        // susun 7 hari
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'date'   => $date,
                'record' => $jadwal->get($date->toDateString()),
            ];
        }

        return view('jadwal.index', compact(
            'karyawans',
            'shifts',
            'selectedKaryawanId',
            'weekDate',
            'startOfWeek',
            'endOfWeek',
            'days'
        ));
    }

    /**
     * Simpan / update jadwal mingguan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => ['required', 'exists:karyawans,id'],
            'week_start'  => ['required', 'date'],
            'shifts'      => ['array'],
        ]);

        $karyawanId = $request->input('karyawan_id');
        $weekStart  = Carbon::parse($request->input('week_start'));

        $shiftsOptions = Presensi::shiftOptions(); // validasi value

        foreach ($request->input('shifts', []) as $tanggal => $shift) {
            $tanggal = Carbon::parse($tanggal)->toDateString();
            $shift   = trim($shift);

            // kalau kosong: hapus jadwal jika ada
            if ($shift === '') {
                JadwalKaryawan::where('karyawan_id', $karyawanId)
                    ->where('tanggal', $tanggal)
                    ->delete();
                continue;
            }

            // kalau bukan salah satu shift yg dikenal, skip saja biar aman
            if (! array_key_exists($shift, $shiftsOptions)) {
                continue;
            }

            JadwalKaryawan::updateOrCreate(
                [
                    'karyawan_id' => $karyawanId,
                    'tanggal'     => $tanggal,
                ],
                [
                    'shift'   => $shift,
                    'catatan' => null, // nanti bisa dikembangkan
                ]
            );
        }

        return redirect()
            ->route('jadwal.index', [
                'karyawan_id' => $karyawanId,
                'week_date'   => $weekStart->format('Y-m-d'),
            ])
            ->with('success', 'Jadwal minggu ini berhasil disimpan.');
    }
}
