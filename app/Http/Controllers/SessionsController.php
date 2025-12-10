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

        // 1. Ambil Sesi Aktif
        $active_sessions = GameSession::with('psUnit')
            ->whereNull('end_time')
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. Ambil Sesi Selesai
        $closed_sessions = GameSession::with('psUnit')
            ->whereNotNull('end_time')
            ->orderBy('start_time', 'desc')
            ->limit(100)
            ->get();

        return view('sessions', compact('units', 'active_sessions', 'closed_sessions'));
    }

    public function storeFixed(Request $request)
    {
        $request->validate([
            'ps_unit_id' => 'required|exists:ps_units,id',
            'start_time' => 'required|date',
            'hours'      => 'required',
        ]);

        // === KASUS 1: OPEN BILLING ===
        if ($request->hours === 'open') {
            $unit = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);

            $session = new GameSession();
            $session->ps_unit_id       = $unit->id;
            $session->start_time       = $start;
            $session->end_time         = null; 
            $session->minutes          = 0;
            $session->extra_controllers = (int) $request->extra_controllers;
            $session->arcade_controllers = 0;
            $session->bill             = 0;
            $session->status           = 'active';
            $session->save();

            return back()->with('success', "Open Billing dimulai untuk unit {$unit->name}.");
        }

        // === KASUS 2: PAKET DURASI TETAP ===
        $request->validate([
            'hours'          => 'numeric|min:0.5',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'bill'           => 'required|numeric|min:0',
        ]);

        // Variabel untuk menampung data timer agar bisa dikirim ke View
        $timerData = null;

        $saleId = DB::transaction(function () use ($request, &$timerData) {
            $unit  = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);
            $hours = (float) $request->hours;
            $end   = $start->copy()->addMinutes($hours * 60);

            // Simpan data timer untuk dikirim ke JS
            $timerData = [
                'unit'     => $unit->name,
                'end_time' => $end->toIso8601String() // Format waktu standar ISO
            ];

            $totalBill = (float) $request->bill;
            $extraControllers  = (int) ($request->extra_controllers ?? 0);
            $arcadeControllers = (int) ($request->arcade_controllers ?? 0);

            if ($request->payment_method === 'Tunai' && $request->paid_amount < $totalBill) {
                throw new \Exception('Pembayaran kurang dari total tagihan.');
            }

            // Create Sale
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

            // Sale Items
            $itemDesc = "Sewa {$unit->name} ({$hours} jam)";
            if ($extraControllers > 0) $itemDesc .= " + {$extraControllers} Stik";
            if ($arcadeControllers > 0) $itemDesc .= " + {$arcadeControllers} Arcade";

            DB::table('sale_items')->insert([
                'sale_id'     => $sale->id,
                'product_id'  => null,
                'description' => $itemDesc,
                'qty'         => 1,
                'unit_price'  => $totalBill,
                'subtotal'    => $totalBill,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

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

            return $sale->id;
        });

        // REDIRECT DENGAN DATA TIMER (PENTING!)
        return redirect()
            ->route('sales.show', $saleId)
            ->with('success', 'Sesi berhasil dibuat.')
            ->with('new_timer', $timerData); // <--- INI KUNCINYA
    }

    public function stopOpen(Request $request)
    {
        $request->validate([
            'session_id'     => 'required|exists:game_sessions,id',
            'final_bill'     => 'required|numeric|min:0',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $saleId = DB::transaction(function () use ($request) {
            $session = GameSession::with('psUnit')->findOrFail($request->session_id);
            
            $start = Carbon::parse($session->start_time);
            $end   = now();
            $minutes = $start->diffInMinutes($end);
            $hours   = round($minutes / 60, 1);

            $totalBill = (float) $request->final_bill;

            if ($request->payment_method === 'Tunai' && $request->paid_amount < $totalBill) {
                throw new \Exception('Pembayaran kurang.');
            }

            $sale = Sale::create([
                'sold_at'        => now(),
                'total'          => $totalBill,
                'total_amount'   => $totalBill,
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => max(0, $request->paid_amount - $totalBill),
                'payment_method' => $request->payment_method,
                'note'           => "Open Billing: {$session->psUnit->name} ({$hours} jam)",
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $desc = "Open Billing {$session->psUnit->name} (±{$hours} jam)";
            if ($session->extra_controllers > 0) $desc .= " + {$session->extra_controllers} Stik";

            DB::table('sale_items')->insert([
                'sale_id'     => $sale->id,
                'product_id'  => null,
                'description' => $desc,
                'qty'         => 1,
                'unit_price'  => $totalBill,
                'subtotal'    => $totalBill,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $session->sale_id        = $sale->id;
            $session->end_time       = $end;
            $session->minutes        = $minutes;
            $session->bill           = $totalBill;
            $session->payment_method = $request->payment_method;
            $session->paid_amount    = $request->paid_amount;
            $session->status         = 'closed';
            $session->save();

            return $sale->id;
        });

        return redirect()->route('sales.show', $saleId)->with('success', 'Open billing selesai.');
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

        return redirect()->route('sessions.index')->with('success', 'Riwayat sesi dihapus.');
    }

    public function addTime(Request $request)
    {
        $request->validate([
            'session_id'     => 'required|exists:game_sessions,id',
            'hours'          => 'required|numeric|min:0.5',
            'paid_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'add_bill'       => 'required|numeric|min:0',
        ]);

        $timerData = null;

        $saleId = DB::transaction(function () use ($request, &$timerData) {
            $session = GameSession::with('psUnit')->findOrFail($request->session_id);
            $unit = $session->psUnit;

            $addedHours = (float) $request->hours;
            $totalAddOnCost = (float) $request->add_bill;

            $currentEnd = Carbon::parse($session->end_time);
            $newEnd = $currentEnd->copy()->addMinutes($addedHours * 60);
            
            // Simpan data timer update
            $timerData = [
                'unit'     => $unit->name,
                'end_time' => $newEnd->toIso8601String()
            ];

            $session->end_time = $newEnd;
            $session->minutes += ($addedHours * 60);
            $session->bill += $totalAddOnCost;
            
            $addedPaid = $request->paid_amount ?? 0;
            $session->paid_amount += $addedPaid;
            $session->payment_method = $request->payment_method;
            $session->save();

            if ($session->sale_id) {
                $sale = Sale::find($session->sale_id);
                if ($sale) {
                    $sale->total += $totalAddOnCost;
                    if (isset($sale->total_amount)) $sale->total_amount += $totalAddOnCost;
                    $sale->paid_amount += $addedPaid;
                    $sale->change_amount = max(0, $sale->paid_amount - $sale->total);
                    $sale->note .= " [Add +{$addedHours}h]";
                    $sale->save();

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
            return redirect()
                ->route('sales.show', $saleId)
                ->with('success', 'Waktu berhasil ditambahkan.')
                ->with('new_timer', $timerData); // <--- KIRIM DATA TIMER JUGA
        }

        return back()->with('success', 'Waktu berhasil ditambahkan.');
    }
}