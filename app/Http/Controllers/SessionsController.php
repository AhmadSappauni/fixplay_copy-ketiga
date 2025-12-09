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
            // ->limit(20)
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
            // WAJIB: Input manual total tagihan dari form
            'bill'           => 'required|numeric|min:0', 
        ]);

        // Menggunakan DB transaction dan me-return ID Sale untuk redirect
        $saleId = DB::transaction(function () use ($request) {
            $unit  = PSUnit::findOrFail($request->ps_unit_id);
            $start = Carbon::parse($request->start_time);
            $hours = (float) $request->hours;
            $end   = $start->copy()->addMinutes($hours * 60);

            // --- LOGIKA UTAMA: TARIF MURNI MANUAL ---
            // Kita abaikan rate unit, extra rate, dll. Kita percaya penuh pada input 'bill'.
            $totalBill = (float) $request->bill;

            // Ambil data stik/arcade hanya untuk catatan, bukan untuk hitung harga
            $extraControllers  = (int) ($request->extra_controllers ?? 0);
            $arcadeControllers = (int) ($request->arcade_controllers ?? 0);

            // Validasi pembayaran (harus lunas/lebih karena tunai)
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

            // --- SALE ITEMS (STRUK) ---
            // Karena harga manual (global), kita jadikan 1 item saja agar tidak bingung membagi harganya.
            // Contoh Deskripsi: "Sewa PS3 (2 jam) + 2 Stik"
            
            $itemDesc = "Sewa {$unit->name} ({$hours} jam)";
            if ($extraControllers > 0) {
                $itemDesc .= " + {$extraControllers} Stik";
            }
            if ($arcadeControllers > 0) {
                $itemDesc .= " + {$arcadeControllers} Arcade";
            }

            DB::table('sale_items')->insert([
                'sale_id'     => $sale->id,
                'product_id'  => null,
                'description' => $itemDesc,
                'qty'         => 1,
                'unit_price'  => $totalBill, // Harga sesuai input manual
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
            $session->bill             = $totalBill; // Simpan tagihan manual
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
     * FITUR TAMBAH JAM (ADD TIME) - MANUAL
     */
    public function addTime(Request $request)
    {
        $request->validate([
            'session_id'     => 'required|exists:game_sessions,id',
            'hours'          => 'required|numeric|min:0.5',
            'paid_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            // WAJIB: Input manual biaya tambahan dari form modal
            'add_bill'       => 'required|numeric|min:0',
        ]);

        $saleId = DB::transaction(function () use ($request) {
            $session = GameSession::with('psUnit')->findOrFail($request->session_id);
            $unit = $session->psUnit;

            $addedHours = (float) $request->hours;

            // --- LOGIKA UTAMA: BIAYA TAMBAHAN MURNI MANUAL ---
            $totalAddOnCost = (float) $request->add_bill;

            // 2. Update Waktu Selesai & Durasi Total (Tetap dihitung untuk record)
            $currentEnd = Carbon::parse($session->end_time);
            $newEnd = $currentEnd->copy()->addMinutes($addedHours * 60);
            $session->end_time = $newEnd;
            $session->minutes += ($addedHours * 60);
            $totalHours = $session->minutes / 60;

            // 3. Update Tagihan Sesi (Tambahkan biaya manual ke total lama)
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
                    if (isset($sale->total_amount)) {
                        $sale->total_amount += $totalAddOnCost;
                    }

                    $sale->paid_amount += $addedPaid;
                    $sale->change_amount = max(0, $sale->paid_amount - $sale->total);

                    // Update Note agar kasir tahu ada penambahan durasi
                    $sale->note = "Sesi: {$unit->name} ({$totalHours} jam) [Add +{$addedHours}h]";
                    $sale->save();

                    // Tambahkan Item Baru di Struk untuk "Tambahan Waktu"
                    DB::table('sale_items')->insert([
                        'sale_id'     => $sale->id,
                        'product_id'  => null,
                        'description' => "Tambahan Waktu (+{$addedHours} jam)",
                        'qty'         => 1,
                        'unit_price'  => $totalAddOnCost, // Harga Manual
                        'subtotal'    => $totalAddOnCost, // Subtotal Manual
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
                ->with('success', 'Waktu berhasil ditambahkan. Struk telah diperbarui.');
        }

        return back()->with('success', 'Waktu berhasil ditambahkan, namun data penjualan tidak ditemukan.');
    }
}