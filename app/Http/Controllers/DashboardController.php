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
        $tz = 'Asia/Makassar';

        // 1. === Ringkasan hari ini ===
        $start = Carbon::today($tz)->startOfDay();
        $end   = Carbon::today($tz)->endOfDay();

        $totals = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NULL THEN sale_items.subtotal ELSE 0 END), 0) AS ps,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NOT NULL THEN sale_items.subtotal ELSE 0 END), 0) AS prod
            ")
            ->whereBetween('sales.sold_at', [$start, $end])
            ->first();

        $todayPs    = (int) ($totals->ps   ?? 0);
        $todayProd  = (int) ($totals->prod ?? 0);
        $todayTotal = $todayPs + $todayProd;

        // 2. === Grafik 10 hari terakhir ===
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
            $chartLabels[] = $d->format('d-m');
            $chartData[]   = $byDate[$key] ?? 0;
        }

        // 3. === Riwayat Transaksi (DIPISAH RENTAL & PRODUK) ===
        // Ambil SEMUA data (tanpa limit)
        $allSales = Sale::with(['items.product'])
            ->orderBy('sold_at', 'desc')
            ->get(); 

        $rentalTx  = [];
        $productTx = [];

        foreach ($allSales as $s) {
            // Cek apakah ini transaksi rental (ada item yg product_id nya NULL)
            $isRental = $s->items->contains(function ($item) {
                return $item->product_id === null;
            });

            // Formatting Nama Item
            $names = [];
            foreach ($s->items->take(3) as $it) {
                if ($it->product) {
                    $names[] = $it->product->name;
                } elseif (!empty($it->description)) {
                    $names[] = $it->description;
                }
            }
            $title = !empty($names) ? implode(', ', $names) : ($s->note ?: 'Item');
            if ($s->items->count() > 3) {
                $title .= ' +' . ($s->items->count() - 3) . ' item';
            }

            // Hitung Total
            $totalFix = $s->total ?? $s->total_amount ?? 0;
            if ($totalFix == 0) {
                $totalFix = (int) $s->items->sum('subtotal');
            }

            $dataTransaksi = [
                'id'          => $s->id,
                'date'        => Carbon::parse($s->sold_at, $tz)->format('d-m-Y H:i'),
                'title'       => $title,
                'total'       => $totalFix,
            ];

            // Pisahkan ke array yang sesuai
            if ($isRental) {
                $rentalTx[] = $dataTransaksi;
            } else {
                $productTx[] = $dataTransaksi;
            }
        }

        // 4. === RESTORE: Produk Paling Laris (YANG HILANG TADI) ===
        $weekStart = Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->startOfDay();
        $weekEnd   = Carbon::now($tz)->endOfWeek(Carbon::SUNDAY)->endOfDay();

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

        // 5. === Return View ===
        return view('dashboard', compact(
            'todayPs',
            'todayProd',
            'todayTotal',
            'chartLabels',
            'chartData',
            'rentalTx',   // Data Rental
            'productTx',  // Data Produk
            'topProduct'  // Data Top Produk (Sudah dikembalikan)
        ));
    }
}