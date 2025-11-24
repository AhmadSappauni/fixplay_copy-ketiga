<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;   // ini sekarang mengarah ke tabel game_sessions
use App\Models\PSUnit;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessionsController extends Controller
{
    public function index()
    {
        $units = PSUnit::all();

        // Ambil sesi yang sudah selesai (ended_at != null)
        $closed_sessions = Session::with('ps_unit')
            ->whereNotNull('end_time')      // kolom di DB
            ->orderBy('start_time', 'desc') // kolom di DB
            ->limit(20)
            ->get();


        return view('sessions', compact('units', 'closed_sessions'));
    }

    public function storeFixed(Request $request)
    {
        $request->validate([
            'ps_unit_id'   => 'required',
            'start_time'   => 'required|date',
            'hours'        => 'required|numeric',
            'paid_amount'  => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $unit  = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);
            $hours = (float) $request->hours;
            $end   = $start->copy()->addMinutes($hours * 60);

            // 1. Hitung Tagihan
            $baseRate    = $unit->hourly_rate;
            $extraRate   = 10000;  // stik tambahan
            $arcadeRate  = 15000;  // arcade

            $extraControllers  = (int) ($request->extra_controllers ?? 0);
            $arcadeControllers = (int) ($request->arcade_controllers ?? 0);

            $baseTotal   = $baseRate * $hours;
            $extraTotal  = $extraControllers * $extraRate * $hours;
            $arcadeTotal = $arcadeControllers * $arcadeRate * $hours;

            $totalBill = $baseTotal + $extraTotal + $arcadeTotal;

            // Optional: pembulatan khusus 30 menit
            if ($hours == 0.5) {
                $totalBill = ceil($totalBill / 1000) * 1000;
            }

            // 2. Buat Data Penjualan
            $sale = Sale::create([
                'sold_at'        => $end, // pendapatan dicatat saat sesi selesai
                'total_amount'   => $totalBill,
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => max(0, $request->paid_amount - $totalBill),
                'payment_method' => $request->payment_method,
                'note'           => "Sesi PS: {$unit->name} ({$hours} jam)",
            ]);

            // Item penjualan (jasa sewa PS)
            DB::table('sale_items')->insert([
                'sale_id'    => $sale->id,
                'product_id' => null,      // bukan produk fisik
                'qty'        => 1,
                'unit_price' => $totalBill,
                'subtotal'   => $totalBill,
            ]);

            // 3. Simpan Data Sesi ke game_sessions
            $session = new Session();
            $session->ps_unit_id          = $unit->id;
            $session->sale_id             = $sale->id;
            $session->start_time          = $start;                 // <-- pakai start_time
            $session->end_time            = $end;                   // <-- pakai end_time
            $session->minutes             = (int) ($hours * 60);    // simpan durasi
            $session->extra_controllers   = $extraControllers;      // <-- nama kolom di DB
            $session->arcade_controllers  = $arcadeControllers;     // <-- nama kolom di DB
            $session->bill                = $totalBill;
            $session->payment_method      = $request->payment_method;
            $session->paid_amount         = $request->paid_amount;
            $session->status              = 'closed';
            $session->note                = null;

            $session->save();

        });

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Sesi berhasil dibuat dan ditagihkan.');
    }

    public function destroy($sid)
    {
        DB::transaction(function () use ($sid) {
            $session = Session::findOrFail($sid);

            // Hapus penjualan terkait
            if ($session->sale_id) {
                $sale = Sale::find($session->sale_id);
                if ($sale) {
                    DB::table('sale_items')
                        ->where('sale_id', $sale->id)
                        ->delete();

                    $sale->delete();
                }
            }

            // Hapus sesi
            $session->delete();
        });

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Riwayat sesi dan laporan pendapatan terkait berhasil dihapus.');
    }
}
