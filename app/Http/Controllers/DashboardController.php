<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tz = 'Asia/Makassar'; // Sesuaikan timezone

        // 1. === Hitung Pendapatan & Pengeluaran Hari Ini ===
        $start = Carbon::today($tz)->startOfDay();
        $end   = Carbon::today($tz)->endOfDay();

        // A. Hitung PENDAPATAN (Income)
        $income = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NULL THEN sale_items.subtotal ELSE 0 END), 0) AS ps,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NOT NULL THEN sale_items.subtotal ELSE 0 END), 0) AS prod
            ")
            ->whereBetween('sales.sold_at', [$start, $end])
            ->first();

        $incomePs   = (int) ($income->ps ?? 0);
        $incomeProd = (int) ($income->prod ?? 0);

        // B. Hitung PENGELUARAN (Expenses) - [LOGIKA BARU]
        $expenses = DB::table('expenses')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN fund_source = 'ps' THEN amount ELSE 0 END), 0) AS ps,
                COALESCE(SUM(CASE WHEN fund_source = 'product' THEN amount ELSE 0 END), 0) AS prod,
                COALESCE(SUM(amount), 0) AS total
            ")
            ->whereBetween('timestamp', [$start, $end])
            ->first();

        $expPs    = (int) ($expenses->ps ?? 0);
        $expProd  = (int) ($expenses->prod ?? 0);
        $expTotal = (int) ($expenses->total ?? 0);

        // C. Hitung LABA BERSIH (Netto) untuk Dashboard
        // Rumus: Pendapatan - Pengeluaran
        $todayPs    = $incomePs - $expPs;       // Netto PS
        $todayProd  = $incomeProd - $expProd;   // Netto Produk
        
        // Total Keseluruhan (Termasuk pengeluaran kas lain jika mau ditampilkan di total)
        // Jika ingin total dashboard = (Netto PS + Netto Produk - Pengeluaran Lain), gunakan logika ini:
        $todayTotal = ($incomePs + $incomeProd) - $expTotal;

        // 2. === Grafik Pendapatan (10 Hari Terakhir) ===
        // Catatan: Grafik biasanya tetap menampilkan OMZET (Pemasukan) agar terlihat tren penjualannya.
        $daysBack   = 10;
        $chartStart = Carbon::today($tz)->subDays($daysBack - 1)->startOfDay();
        $chartEnd   = Carbon::today($tz)->endOfDay();

        $rows = Sale::selectRaw('DATE(sales.sold_at) AS d, SUM(sale_items.subtotal) AS t')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.sold_at', [$chartStart, $chartEnd])
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $byDate = [];
        foreach ($rows as $r) {
            $byDate[$r->d] = (int) $r->t;
        }

        $chartLabels = [];
        $chartData   = [];

        for ($i = 0; $i < $daysBack; $i++) {
            $d   = $chartStart->copy()->addDays($i);
            $key = $d->toDateString();
            $chartLabels[] = $d->format('d M'); 
            $chartData[]   = $byDate[$key] ?? 0;
        }

        // 3. === Riwayat Transaksi ===
        $recentSales = Sale::with(['items.product'])
            ->orderBy('sold_at', 'desc')
            ->limit(50) 
            ->get(); 

        $rentalTx  = [];
        $productTx = [];

        foreach ($recentSales as $s) {
            $isRental = $s->items->contains(function ($item) {
                return $item->product_id === null;
            });

            $names = [];
            foreach ($s->items->take(3) as $it) {
                if ($it->product) {
                    $names[] = $it->product->name . ' (' . $it->qty . ')';
                } elseif (!empty($it->description)) {
                    $names[] = $it->description; 
                }
            }
            
            $title = !empty($names) ? implode(', ', $names) : ($s->note ?: 'Item');
            if ($s->items->count() > 3) {
                $title .= ' +' . ($s->items->count() - 3) . ' item lainnya';
            }

            $totalFix = $s->total ?? $s->total_amount ?? 0;
            if ($totalFix == 0) {
                $totalFix = (int) $s->items->sum('subtotal');
            }

            $dataTransaksi = [
                'id'    => $s->id,
                'date'  => Carbon::parse($s->sold_at)->setTimezone($tz)->format('d-m-Y H:i'),
                'title' => $title, 
                'total' => $totalFix,
            ];

            if ($isRental) {
                $rentalTx[] = $dataTransaksi;
            } else {
                $productTx[] = $dataTransaksi;
            }
        }

        // 4. === Produk Paling Laris ===
        $weekStart = Carbon::now($tz)->startOfWeek()->startOfDay();
        $weekEnd   = Carbon::now($tz)->endOfWeek()->endOfDay();

        $topRow = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw("
                products.id,
                products.name,
                SUM(sale_items.qty)      AS total_qty,
                SUM(sale_items.subtotal) AS total_amount
            ")
            ->whereNotNull('sale_items.product_id')
            ->whereBetween('sales.sold_at', [$weekStart, $weekEnd])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(1)
            ->first();

        $topProduct = null;
        if ($topRow) {
            $topProduct = [
                'id'           => $topRow->id,
                'name'         => $topRow->name,
                'total_qty'    => (int) $topRow->total_qty,
                'total_amount' => (int) $topRow->total_amount,
                'week_start'   => $weekStart,
                'week_end'     => $weekEnd,
            ];
        }

        return view('dashboard', compact(
            'todayPs',
            'todayProd',
            'todayTotal',
            'chartLabels',
            'chartData',
            'rentalTx',   
            'productTx', 
            'topProduct'
        ));
    }
}