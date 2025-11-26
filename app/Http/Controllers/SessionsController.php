<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;   // Pastikan model Session sudah menggunakan trait HasUuids
use App\Models\PSUnit;
use App\Models\Sale;
use App\Models\SaleItem; // Tambahkan Model ini jika ada, atau pakai DB::table
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessionsController extends Controller
{
    public function index()
    {
        // Hanya tampilkan unit yang aktif
        $units = PSUnit::where('is_active', 1)->orderBy('name')->get();

        // Ambil sesi yang sudah selesai (closed)
        $closed_sessions = Session::with('ps_unit')
            ->where('status', 'closed')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('sessions', compact('units', 'closed_sessions'));
    }

    public function storeFixed(Request $request)
    {
        $request->validate([
            'ps_unit_id'     => 'required',
            'start_time'     => 'required|date',
            'hours'          => 'required|numeric',
            'paid_amount'    => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        // Gunakan Transaction agar data konsisten (Sale, Item, Session masuk semua atau tidak sama sekali)
        DB::transaction(function () use ($request) {
            $unit  = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);
            $hours = (float) $request->hours;
            $end   = $start->copy()->addMinutes($hours * 60);

            // --- 1. LOGIKA HARGA & PAKET ---
            $baseRate   = $unit->hourly_rate;
            $extraRate  = 10000;  // Tarif stik tambahan/jam
            $arcadeRate = 15000;  // Tarif arcade/jam

            $extraControllers  = (int) ($request->extra_controllers ?? 0);
            $arcadeControllers = (int) ($request->arcade_controllers ?? 0);

            // A. Hitung Biaya Unit (Cek Paket PS4 Reguler)
            $unitBill = 0;
            // Cek jika tipe PS4 (Reguler) dan tarif dasar 10.000
            // Kita gunakan pengecekan tipe (jika kolom type ada) atau harga (10000) sebagai fallback
            $isReguler = ($unit->type ?? '') === 'PS4' || $baseRate == 10000;

            if ($isReguler) {
                if ($hours == 3) $unitBill = 25000;
                elseif ($hours == 4) $unitBill = 35000;
                elseif ($hours == 5) $unitBill = 45000;
                elseif ($hours == 6) $unitBill = 50000;
                else $unitBill = $baseRate * $hours; // 1 jam, 2 jam, atau 0.5 jam
            } else {
                // Unit lain (PS5 / VVIP) hitung linear
                $unitBill = $baseRate * $hours;
            }

            // B. Hitung Biaya Tambahan
            $extraTotal  = $extraControllers * $extraRate * $hours;
            $arcadeTotal = $arcadeControllers * $arcadeRate * $hours;

            // C. Total Tagihan
            $totalBill = $unitBill + $extraTotal + $arcadeTotal;

            // Pembulatan khusus 30 menit (setengah jam) -> bulatkan ke ribuan terdekat/atas
            if ($hours == 0.5) {
                $totalBill = ceil($totalBill / 1000) * 1000;
            } else {
                $totalBill = round($totalBill);
            }

            // Validasi Pembayaran (Server Side)
            if ($request->payment_method === 'Tunai' && $request->paid_amount < $totalBill) {
                throw new \Exception('Pembayaran kurang dari total tagihan.');
            }

            // --- 2. BUAT DATA PENJUALAN (SALES) ---
            $sale = Sale::create([
                'sold_at'        => $end, // Pendapatan dicatat saat sesi selesai
                'total'          => $totalBill, // Pastikan nama kolom di DB 'total' (bukan total_amount) sesuai perbaikan dashboard
                'total_amount'   => $totalBill, // Backup jika kolom total_amount masih ada
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => max(0, $request->paid_amount - $totalBill),
                'payment_method' => $request->payment_method,
                'note'           => "Sesi PS: {$unit->name} ({$hours} jam)",
            ]);

            // --- 3. BUAT RINCIAN ITEM (SALE ITEMS) ---
            // Item 1: Sewa Unit
            DB::table('sale_items')->insert([
                'sale_id'     => $sale->id,
                'product_id'  => null,
                'description' => "Sewa {$unit->name} ({$hours} jam)",
                'qty'         => 1,
                'unit_price'  => $unitBill,
                'subtotal'    => $unitBill,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Item 2: Tambahan Stik (Jika ada)
            if ($extraTotal > 0) {
                DB::table('sale_items')->insert([
                    'sale_id'     => $sale->id,
                    'product_id'  => null,
                    'description' => "Tambahan Stik x{$extraControllers}",
                    'qty'         => 1,
                    'unit_price'  => $extraTotal,
                    'subtotal'    => $extraTotal,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            // Item 3: Arcade (Jika ada)
            if ($arcadeTotal > 0) {
                DB::table('sale_items')->insert([
                    'sale_id'     => $sale->id,
                    'product_id'  => null,
                    'description' => "Arcade Controller x{$arcadeControllers}",
                    'qty'         => 1,
                    'unit_price'  => $arcadeTotal,
                    'subtotal'    => $arcadeTotal,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            // --- 4. SIMPAN SESI (GAME SESSIONS) ---
            $session = new Session();
            $session->ps_unit_id       = $unit->id;
            $session->sale_id          = $sale->id;
            $session->start_time       = $start;
            $session->end_time         = $end;
            $session->minutes          = (int) ($hours * 60);
            $session->extra_controllers = $extraControllers;
            $session->arcade_controllers = $arcadeControllers;
            $session->bill             = $totalBill;
            $session->payment_method   = $request->payment_method;
            $session->paid_amount      = $request->paid_amount;
            $session->status           = 'closed';
            $session->note             = $request->note; // Jika ada input note tambahan
            
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

            // Hapus penjualan terkait agar laporan bersih
            if ($session->sale_id) {
                $sale = Sale::find($session->sale_id);
                if ($sale) {
                    // Hapus item rincian
                    DB::table('sale_items')
                        ->where('sale_id', $sale->id)
                        ->delete();

                    // Hapus header penjualan
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