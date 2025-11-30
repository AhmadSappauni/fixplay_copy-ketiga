<?php

namespace App\Http\Controllers;

use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan package barryvdh/laravel-dompdf terinstall

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

        // --- BAGIAN 1: Jadwal Individu (Untuk Form Input) ---
        $jadwal = JadwalKaryawan::where('karyawan_id', $selectedKaryawanId)
            ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get()
            ->keyBy(fn ($item) => $item->tanggal->toDateString());

        // susun 7 hari untuk form input
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'date'   => $date,
                'record' => $jadwal->get($date->toDateString()),
            ];
        }

        // --- BAGIAN 2: Rekapitulasi Semua Karyawan (Untuk Card Bawah & Export) ---
        // Kita gunakan fungsi helper getRecapData agar seragam
        $recap = $this->getRecapData($startOfWeek, $endOfWeek);

        return view('jadwal.index', compact(
            'karyawans',
            'shifts',
            'selectedKaryawanId',
            'weekDate',
            'startOfWeek',
            'endOfWeek',
            'days',
            'recap' // Kirim data rekap ke view
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

            // --- VALIDASI MAX 2 KARYAWAN ---
            // Cek berapa orang yang sudah ada di shift & tanggal ini (kecuali diri sendiri)
            $existingCount = JadwalKaryawan::where('tanggal', $tanggal)
                ->where('shift', $shift)
                ->where('karyawan_id', '!=', $karyawanId) // Jangan hitung diri sendiri jika sedang update
                ->count();

            if ($existingCount >= 2) {
                // Jika sudah penuh (2 orang), kembalikan error
                $tglIndo = Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['msg' => "Gagal menyimpan! Jadwal pada <b>{$tglIndo}</b> untuk shift <b>{$shiftsOptions[$shift]}</b> sudah penuh (2 orang)."]);
            }

            JadwalKaryawan::updateOrCreate(
                [
                    'karyawan_id' => $karyawanId,
                    'tanggal'     => $tanggal,
                ],
                [
                    'shift'   => $shift,
                    'catatan' => $request->input("catatan.{$tanggal}"),
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

    // --- FITUR EXPORT ---

    public function exportExcel(Request $request)
    {
        $data = $this->prepareExportData($request);
        
        $filename = 'Jadwal_Mingguan_' . $data['startOfWeek']->format('d-M') . '_sd_' . $data['endOfWeek']->format('d-M-Y') . '.xls';

        return response()->streamDownload(function() use ($data) {
            echo view('jadwal.export_excel', $data);
        }, $filename);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->prepareExportData($request);
        
        $pdf = Pdf::loadView('jadwal.export_pdf', $data)
                  ->setPaper('a4', 'landscape'); // Landscape agar tabel muat

        $filename = 'Jadwal_Mingguan_' . $data['startOfWeek']->format('d-M') . '_sd_' . $data['endOfWeek']->format('d-M-Y') . '.pdf';
        
        return $pdf->stream($filename);
    }

    // --- HELPER METHODS ---

    private function prepareExportData(Request $request)
    {
        $weekDate = $request->input('week_date')
            ? Carbon::parse($request->input('week_date'))
            : Carbon::today();

        $startOfWeek = $weekDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = $weekDate->copy()->endOfWeek(Carbon::SUNDAY);

        $recap = $this->getRecapData($startOfWeek, $endOfWeek);

        // Generate array tanggal untuk looping di view export
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->copy()->addDays($i);
        }

        return compact('startOfWeek', 'endOfWeek', 'recap', 'dates');
    }

    private function getRecapData($start, $end)
    {
        // Ambil semua jadwal di rentang minggu ini beserta data karyawannya
        $allJadwal = JadwalKaryawan::with('karyawan')
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->get();

        // Kelompokkan data: [Tanggal] => [Shift] => [Array Nama Karyawan]
        $recap = [];
        foreach ($allJadwal as $item) {
            $tgl = $item->tanggal->toDateString();
            $shf = $item->shift; // key shift (pagi, sore, sakit, izin)
            
            // Simpan nama karyawan ke dalam array
            $recap[$tgl][$shf][] = $item->karyawan->nama;
        }
        return $recap;
    }
}