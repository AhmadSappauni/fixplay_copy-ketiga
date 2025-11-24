<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Exports\PresensiReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    /**
     * Halaman presensi hari ini di kasir.
     */
    public function index(Request $request)
    {
        $today      = Carbon::today();
        $karyawans  = Karyawan::orderBy('nama')->get();
        $shifts     = Presensi::shiftOptions();

        $todayPresensis = Presensi::with('karyawan')
            ->whereDate('tanggal', $today)
            ->orderBy('check_in')
            ->get();

        return view('presensi.index', compact(
            'today',
            'karyawans',
            'shifts',
            'todayPresensis'
        ));
    }

    /**
     * Check-in.
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'karyawan_id' => ['required', 'exists:karyawans,id'],
            'shift'       => ['required', 'in:' . implode(',', array_keys(Presensi::shiftOptions()))],
        ]);

        $today      = Carbon::today();
        $now        = Carbon::now();
        $karyawanId = $request->input('karyawan_id');
        $shift      = $request->input('shift');

        $presensi = Presensi::firstOrNew([
            'karyawan_id' => $karyawanId,
            'tanggal'     => $today->toDateString(),
            'shift'       => $shift,
        ]);

        if (! $presensi->check_in) {
            $presensi->check_in = $now;
        }

        $presensi->status = Presensi::determineStatus($shift, $today, $presensi->check_in ?? $now);

        $presensi->save();

        return back()->with('success', 'Check-in berhasil untuk shift ' . ucfirst(str_replace('_', ' ', $shift)));
    }

    /**
     * Check-out.
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'karyawan_id' => ['required', 'exists:karyawans,id'],
            'shift'       => ['required', 'in:' . implode(',', array_keys(Presensi::shiftOptions()))],
        ]);

        $today      = Carbon::today();
        $now        = Carbon::now();
        $karyawanId = $request->input('karyawan_id');
        $shift      = $request->input('shift');

        $presensi = Presensi::firstOrNew([
            'karyawan_id' => $karyawanId,
            'tanggal'     => $today->toDateString(),
            'shift'       => $shift,
        ]);

        if (! $presensi->check_in) {
            // kalau belum pernah check-in tapi langsung check-out
            $presensi->check_in = $now;
            $presensi->status   = Presensi::determineStatus($shift, $today, $presensi->check_in);
        }

        $presensi->check_out = $now;
        $presensi->save();

        return back()->with('success', 'Check-out berhasil untuk shift ' . ucfirst(str_replace('_', ' ', $shift)));
    }

    /**
     * Riwayat presensi (bisa dipakai karyawan & bos).
     */
    public function riwayat(Request $request)
    {
        $shifts     = Presensi::shiftOptions();
        $karyawans  = Karyawan::orderBy('nama')->get();

        $from = $request->input('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::today()->startOfMonth();

        $to = $request->input('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $karyawanId = $request->input('karyawan_id');
        $shift      = $request->input('shift');

        $query = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->orderBy('tanggal', 'desc')
            ->orderBy('check_in', 'desc');

        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $presensis = $query->get();

        return view('presensi.riwayat', compact(
            'presensis',
            'karyawans',
            'shifts',
            'from',
            'to',
            'karyawanId',
            'shift'
        ));
    }

    /**
     * Form edit satu presensi (untuk koreksi jam / shift).
     */
    public function edit(Presensi $presensi)
    {
        $shifts    = Presensi::shiftOptions();
        $karyawans = Karyawan::orderBy('nama')->get();

        return view('presensi.edit', compact('presensi', 'shifts', 'karyawans'));
    }

    /**
     * Update presensi (bisa dipanggil karyawan maupun bos).
     */
    public function update(Request $request, Presensi $presensi)
    {
        $request->validate([
            'karyawan_id' => ['required', 'exists:karyawans,id'],
            'tanggal'     => ['required', 'date'],
            'shift'       => ['required', 'in:' . implode(',', array_keys(Presensi::shiftOptions()))],
            'check_in'    => ['nullable', 'date_format:H:i'],
            'check_out'   => ['nullable', 'date_format:H:i'],
            'status'      => ['nullable', 'string', 'max:20'],
            'catatan'     => ['nullable', 'string', 'max:255'],
        ]);

        $tanggal = Carbon::parse($request->input('tanggal'))->startOfDay();
        $shift   = $request->input('shift');

        $presensi->karyawan_id = $request->input('karyawan_id');
        $presensi->tanggal     = $tanggal->toDateString();
        $presensi->shift       = $shift;

        $checkIn  = $request->input('check_in');
        $checkOut = $request->input('check_out');

        $presensi->check_in = $checkIn
            ? Carbon::parse($presensi->tanggal . ' ' . $checkIn)
            : null;

        $presensi->check_out = $checkOut
            ? Carbon::parse($presensi->tanggal . ' ' . $checkOut)
            : null;

        // jika status tidak diisi, hitung otomatis
        if ($request->filled('status')) {
            $presensi->status = $request->input('status');
        } elseif ($presensi->check_in) {
            $presensi->status = Presensi::determineStatus($shift, $tanggal, $presensi->check_in);
        }

        $presensi->catatan = $request->input('catatan');

        $presensi->save();

        return redirect()
            ->route('presensi.riwayat', [
                'karyawan_id' => $presensi->karyawan_id,
                'from'        => $presensi->tanggal->format('Y-m-d'),
                'to'          => $presensi->tanggal->format('Y-m-d'),
            ])
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * Hapus presensi (biasanya dipakai bos).
     */
    public function destroy(Presensi $presensi)
    {
        $presensi->delete();

        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

        /**
     * Laporan agregat untuk bos (dengan filter).
     */
    public function report(Request $request)
    {
        $shifts    = Presensi::shiftOptions();
        $karyawans = Karyawan::orderBy('nama')->get();

        $from = $request->input('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::today()->startOfMonth();

        $to = $request->input('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $karyawanId = $request->input('karyawan_id');
        $shift      = $request->input('shift');

        $query = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()]);

        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $allPresensis = $query->get();

        // Range tanggal
        $period    = CarbonPeriod::create($from->toDateString(), $to->toDateString());
        $totalHari = iterator_count($period);

        $summary = [];

        foreach ($allPresensis->groupBy('karyawan_id') as $kId => $items) {
            $nama = optional($items->first()->karyawan)->nama ?? 'Tanpa nama';

            $hariKerja = $items
                ->filter(fn (Presensi $p) => in_array($p->shift, Presensi::workingShiftKeys(), true))
                ->pluck('tanggal')
                ->unique()
                ->count();

            $telat = $items->where('status', 'telat')->count();

            $hadirAtauEntry = $items->pluck('tanggal')->unique()->count();
            $tidakHadir     = max($totalHari - $hadirAtauEntry, 0);

            $summary[] = [
                'karyawan_id' => $kId,
                'nama'        => $nama,
                'hari_kerja'  => $hariKerja,
                'telat'       => $telat,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        // Data untuk grafik (label & data)
        $chartLabels = collect($summary)->pluck('nama');
        $chartHari   = collect($summary)->pluck('hari_kerja');
        $chartTelat  = collect($summary)->pluck('telat');

        return view('presensi.report', compact(
            'summary',
            'karyawans',
            'shifts',
            'from',
            'to',
            'karyawanId',
            'shift',
            'totalHari',
            'chartLabels',
            'chartHari',
            'chartTelat'
        ));
    }
        /**
     * Export laporan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        // Bangun data ringkasan yang sama seperti report
        $data = $this->buildSummary($request);

        $rows = collect($data['summary'])->map(function ($item) {
            return [
                $item['nama'],
                $item['hari_kerja'],
                $item['telat'],
                $item['tidak_hadir'],
            ];
        });

        // Download sebagai file Excel
        return Excel::download(
            new PresensiReportExport($rows),
            'laporan-presensi.xlsx'
        );
    }


    /**
     * Export laporan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->buildSummary($request);

        $pdf = Pdf::loadView('presensi.report_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-presensi.pdf');
    }

    /**
     * Helper privat untuk membangun summary agar tidak duplikasi.
     */
    protected function buildSummary(Request $request): array
    {
        $shifts    = Presensi::shiftOptions();
        $karyawans = Karyawan::orderBy('nama')->get();

        $from = $request->input('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::today()->startOfMonth();

        $to = $request->input('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $karyawanId = $request->input('karyawan_id');
        $shift      = $request->input('shift');

        $query = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()]);

        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $allPresensis = $query->get();

        $period    = CarbonPeriod::create($from->toDateString(), $to->toDateString());
        $totalHari = iterator_count($period);

        $summary = [];

        foreach ($allPresensis->groupBy('karyawan_id') as $kId => $items) {
            $nama = optional($items->first()->karyawan)->nama ?? 'Tanpa nama';

            $hariKerja = $items
                ->filter(fn (Presensi $p) => in_array($p->shift, Presensi::workingShiftKeys(), true))
                ->pluck('tanggal')
                ->unique()
                ->count();

            $telat = $items->where('status', 'telat')->count();

            $hadirAtauEntry = $items->pluck('tanggal')->unique()->count();
            $tidakHadir     = max($totalHari - $hadirAtauEntry, 0);

            $summary[] = [
                'karyawan_id' => $kId,
                'nama'        => $nama,
                'hari_kerja'  => $hariKerja,
                'telat'       => $telat,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        return compact(
            'summary',
            'karyawans',
            'shifts',
            'from',
            'to',
            'karyawanId',
            'shift',
            'totalHari'
        );
    }


}
