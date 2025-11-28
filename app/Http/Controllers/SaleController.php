<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function show($id)
    {
        $sale = Sale::find($id);
        
        if (!$sale) {
            abort(404);
        }

        $items = DB::table('sale_items')
            ->where('sale_id', $sale->id)
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->select('sale_items.*', 'products.name as product_name')
            ->get();

        // LOGIKA TOMBOL KEMBALI DINAMIS
        $backUrl = url()->previous();
        if ($backUrl == url()->current() || empty($backUrl)) {
            $backUrl = route('dashboard');
        }

        return view('sales.receipt', [
            'sale' => $sale,
            'items' => $items,
            'backUrl' => $backUrl
        ]);
    }

    public function edit($id)
    {
        $sale = Sale::with('items')->findOrFail($id);

        return view('sales.edit', [
            'sale' => $sale
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'created_at'     => 'required|date',
            'total_bill'     => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_amount'    => 'required|numeric|min:0',
            'note'           => 'nullable|string|max:255',
        ]);

        $sale = Sale::with('items')->findOrFail($id);
        $newTotal = $request->total_bill;

        if ($request->paid_amount < $newTotal) {
            return back()->withErrors(['paid_amount' => 'Nominal dibayar kurang dari total tagihan baru!']);
        }

        DB::transaction(function () use ($sale, $request, $newTotal) {
            // 1. Update Waktu Transaksi
            $sale->sold_at = $request->created_at;
            $sale->created_at = $request->created_at;

            // 2. Update Harga Item
            if ($sale->items->isNotEmpty()) {
                $item = $sale->items->first();
                $item->subtotal = $newTotal;
                $qty = max($item->qty, 1);
                $item->unit_price = $newTotal / $qty;
                $item->save();
            }

            // 3. Update Header Sales
            $sale->total = $newTotal; 
            
            // 4. Update Pembayaran & Kembalian
            $sale->payment_method = $request->payment_method;
            $sale->paid_amount    = $request->paid_amount;
            $sale->change_amount  = $request->paid_amount - $newTotal;
            $sale->note           = $request->note;

            $sale->save();

            // 5. SINKRONISASI UPDATE SESSION
            // Cari sesi yang terhubung dengan ID penjualan ini, lalu update kolom 'bill'
            DB::table('game_sessions') // Pastikan nama tabelnya game_sessions sesuai database Anda
                ->where('sale_id', $sale->id)
                ->update(['bill' => $newTotal]);
        });

        return back()->with('success', 'Data transaksi berhasil diperbarui (Waktu & Harga).');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            
            // 1. Kembalikan Stok Produk (Jika ada produk fisik)
            $items = DB::table('sale_items')->where('sale_id', $sale->id)->get();
            foreach ($items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->qty);
                }
            }

            // 2. Hapus Sesi Rental Terkait (FITUR BARU)
            // Agar saat dihapus dari dashboard, di halaman Rental juga hilang
            DB::table('game_sessions')
                ->where('sale_id', $sale->id)
                ->delete();

            // 3. Hapus Item Penjualan
            DB::table('sale_items')->where('sale_id', $sale->id)->delete();
            
            // 4. Hapus Header Penjualan
            $sale->delete();
        });

        return back()->with('success', 'Transaksi berhasil dihapus (Sesi terkait juga dihapus).');
    }
}