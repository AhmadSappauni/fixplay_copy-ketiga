<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Models\PSUnit;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessionsController extends Controller
{
    public function index()
    {
        $units = PSUnit::orderBy('name')->get();
        // Mengambil sesi yang sudah selesai (closed) untuk riwayat
        $closed_sessions = GameSession::with('psUnit')
            ->whereNotNull('end_time')      
            ->orderBy('start_time', 'desc') 
            ->limit(20)
            ->get();

        return view('sessions', compact('units', 'closed_sessions'));
    }

    public function storeFixed(Request $request)
    {
        $request->validate([
            'ps_unit_id'     => 'required|exists:ps_units,id',
            'start_time'     => 'required|date',
            'hours'          => 'required|numeric|min:0.5',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        // Menggunakan DB transaction dan me-return ID Sale untuk redirect
        $saleId = DB::transaction(function () use ($request) {
            $unit  = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);
            $hours = (float) $request->hours;
            $end   = $start->copy()->addMinutes($hours * 60);

            $baseRate   = $unit->hourly_rate;
            $extraRate  = 10000; 
            $arcadeRate = 15000; 

            $extraControllers  = (int) ($request->extra_controllers ?? 0);
            $arcadeControllers = (int) ($request->arcade_controllers ?? 0);

            $isReguler = ($unit->type ?? '') === 'PS4' || $baseRate == 10000;
            
            $unitBill = 0;
            if ($isReguler) {
                if ($hours == 3) $unitBill = 25000;
                elseif ($hours == 4) $unitBill = 35000;
                elseif ($hours == 5) $unitBill = 45000;
                elseif ($hours == 6) $unitBill = 50000;
                else $unitBill = $baseRate * $hours;
            } else {
                $unitBill = $baseRate * $hours;
            }

            $extraTotal  = $extraControllers * $extraRate * $hours;
            $arcadeTotal = $arcadeControllers * $arcadeRate * $hours;
            $totalBill = $unitBill + $extraTotal + $arcadeTotal;

            if ($hours == 0.5) {
                $totalBill = ceil($totalBill / 1000) * 1000;
            } else {
                $totalBill = round($totalBill);
            }

            if ($request->payment_method === 'Tunai' && $request->paid_amount < $totalBill) {
                throw new \Exception('Pembayaran kurang dari total tagihan.');
            }

            // Create Sale Header
            $sale = Sale::create([
                'sold_at'        => now(), 
                'total'          => $totalBill, 
                'total_amount'   => $totalBill, 
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => max(0, $request->paid_amount - $totalBill),
                'payment_method' => $request->payment_method,
                'note'           => "Sesi PS: {$unit->name} ({$hours} jam)",
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Create Sale Items
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

            if ($extraControllers > 0) {
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

            if ($arcadeControllers > 0) {
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

            // Create Session
            $session = new GameSession();
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
            $session->save(); 

            return $sale->id; // Return ID untuk redirect
        });

        // REDIRECT KE HALAMAN STRUK (RECEIPT)
        return redirect()
            ->route('sales.show', $saleId)
            ->with('success', 'Sesi berhasil dibuat. Silakan cetak struk.');
    }

    public function destroy($sid)
    {
        DB::transaction(function () use ($sid) {
            $session = GameSession::findOrFail($sid);
            if ($session->sale_id) {
                $sale = Sale::find($session->sale_id);
                if ($sale) {
                    DB::table('sale_items')->where('sale_id', $sale->id)->delete();
                    $sale->delete();
                }
            }
            $session->delete();
        });

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Riwayat sesi dan laporan pendapatan terkait berhasil dihapus.');
    }

    /**
     * FITUR TAMBAH JAM (ADD TIME)
     * Menggunakan logika paket mandiri dan redirect ke struk.
     */
    public function addTime(Request $request)
    {
        $request->validate([
            'session_id'   => 'required|exists:game_sessions,id',
            'hours'        => 'required|numeric|min:0.5',
            'paid_amount'  => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $saleId = DB::transaction(function () use ($request) {
            $session = GameSession::with('psUnit')->findOrFail($request->session_id);
            $unit = $session->psUnit;
            
            $addedHours = (float) $request->hours;

            // 1. HITUNG BIAYA TAMBAHAN (LOGIKA PAKET MANDIRI)
            $rate = $unit->hourly_rate; 
            $unitCost = 0;

            if ($rate == 10000) {
                // Logika Paket Regular untuk Jam Tambahan
                if ($addedHours == 3) $unitCost = 25000;
                elseif ($addedHours == 4) $unitCost = 35000;
                elseif ($addedHours == 5) $unitCost = 45000;
                elseif ($addedHours == 6) $unitCost = 50000;
                elseif ($addedHours > 6) {
                    $unitCost = 50000 + (($addedHours - 6) * 10000);
                } else {
                    $unitCost = $addedHours * 10000;
                }
            } else {
                // Non-reguler (Flat)
                $unitCost = $addedHours * $rate;
            }

            // Tambahan Alat (Stik/Arcade) Flat
            $extraCtrlCost = ($session->extra_controllers * 10000 * $addedHours);
            $arcadeCtrlCost = ($session->arcade_controllers * 15000 * $addedHours);

            // Total Biaya Tambahan
            $totalAddOnCost = $unitCost + $extraCtrlCost + $arcadeCtrlCost;

            // Pembulatan jika 30 menit
            if ($addedHours == 0.5) {
                $totalAddOnCost = ceil($totalAddOnCost / 1000) * 1000;
            } else {
                $totalAddOnCost = round($totalAddOnCost);
            }

            // 2. Update Waktu Selesai & Durasi Total
            $currentEnd = Carbon::parse($session->end_time);
            $newEnd = $currentEnd->copy()->addMinutes($addedHours * 60);
            $session->end_time = $newEnd;
            $session->minutes += ($addedHours * 60);
            $totalHours = $session->minutes / 60; 

            // 3. Update Tagihan Sesi (Tambahkan biaya baru ke total lama)
            $session->bill += $totalAddOnCost;
            
            // Update Pembayaran
            $addedPaid = $request->paid_amount ?? 0;
            $session->paid_amount += $addedPaid;
            $session->payment_method = $request->payment_method; 

            $session->save();

            // 4. Update Laporan Penjualan (Sale)
            if ($session->sale_id) {
                $sale = Sale::find($session->sale_id);
                if ($sale) {
                    // Update Header
                    $sale->total += $totalAddOnCost;
                    if(isset($sale->total_amount)) $sale->total_amount += $totalAddOnCost;
                    
                    $sale->paid_amount += $addedPaid;
                    $sale->change_amount = max(0, $sale->paid_amount - $sale->total);
                    
                    // Update Note agar kasir tahu ada penambahan
                    $sale->note = "Sesi: {$unit->name} ({$totalHours} jam) [Add +{$addedHours}h]";
                    $sale->save();

                    // Tambahkan Item Baru di Struk untuk "Tambahan Waktu"
                    DB::table('sale_items')->insert([
                        'sale_id'     => $sale->id,
                        'product_id'  => null,
                        'description' => "Tambahan Waktu (+{$addedHours} jam)",
                        'qty'         => 1,
                        'unit_price'  => $totalAddOnCost,
                        'subtotal'    => $totalAddOnCost,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
                return $session->sale_id;
            }
            return null;
        });

        if ($saleId) {
            // REDIRECT KE HALAMAN STRUK (RECEIPT)
            return redirect()
                ->route('sales.show', $saleId)
                ->with('success', 'Waktu berhasil ditambahkan. Struk telah diperbarui.');
        }

        return back()->with('success', 'Waktu berhasil ditambahkan, namun data penjualan tidak ditemukan.');
    }
}