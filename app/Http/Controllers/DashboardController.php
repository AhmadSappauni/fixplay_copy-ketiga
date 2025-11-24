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

        // === Ringkasan hari ini (zona WITA Makassar) ===
        $start = Carbon::today($tz)->startOfDay();
        $end   = Carbon::today($tz)->endOfDay();

        // === Kartu ringkasan hari ini (PS vs Produk) ===
        $totals = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN sale_items.product_id IS NULL
                                THEN sale_items.subtotal
                            ELSE 0
                        END
                    ), 0
                ) AS ps,
                COALESCE(
                    SUM(
                        CASE
                            WHEN sale_items.product_id IS NOT NULL
                                THEN sale_items.subtotal
                            ELSE 0
                        END
                    ), 0
                ) AS prod
            ")
            ->whereBetween('sales.sold_at', [$start, $end])
            ->first();

        $todayPs    = (int) ($totals->ps   ?? 0);
        $todayProd  = (int) ($totals->prod ?? 0);
        $todayTotal = $todayPs + $todayProd;

        // === Grafik 10 hari terakhir (total penjualan per hari) ===
        $daysBack   = 10;
        $chartStart = Carbon::today($tz)->subDays($daysBack - 1)->startOfDay();
        $chartEnd   = Carbon::today($tz)->endOfDay();

        // Pakai subtotal dari sale_items karena tabel sales tidak menyimpan total yang fix
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

        // === Riwayat transaksi terakhir (10) ===
        $last = Sale::with(['items.product'])
            ->orderBy('sold_at', 'desc')
            ->limit(10)
            ->get();

        $lastTx = [];

        foreach ($last as $s) {
            // Ambil sampai 3 nama produk fisik (atau description jika ada)
            $names = [];
            foreach ($s->items->take(3) as $it) {
                if ($it->product) {
                    $names[] = $it->product->name;
                } elseif (!empty($it->description)) {
                    $names[] = $it->description;
                }
            }

            // Judul transaksi (fallback ke note kalau tidak ada nama item)
            $title = !empty($names) ? implode(', ', $names) : ($s->note ?: 'Item');

            if ($s->items->count() > 3) {
                $title .= ' +' . ($s->items->count() - 3) . ' item';
            }

            // Total: pakai kolom total/total_amount, kalau 0 hitung dari subtotal item
            $totalFix = $s->total ?? $s->total_amount ?? 0;
            if ($totalFix == 0) {
                $totalFix = (int) $s->items->sum('subtotal');
            }

            $lastTx[] = [
                'id'          => $s->id,
                'date'        => Carbon::parse($s->sold_at, $tz)->format('d-m-Y H:i'),
                'date_iso'    => Carbon::parse($s->sold_at, $tz)->toDateString(),
                'title'       => $title,
                'total'       => $totalFix,
                'method'      => $s->payment_method ?? 'Tunai',
                'paid_amount' => (int) ($s->paid_amount ?? 0),
                'note'        => $s->note ?? '',
            ];
        }

        // === Produk paling laris minggu ini (berdasarkan qty terjual) ===
        // Minggu ini = Senin–Minggu di zona Asia/Makassar
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
                'id'          => $topRow->id,
                'name'        => $topRow->name,
                'total_qty'   => (int) $topRow->total_qty,
                'total_amount'=> (int) $topRow->total_amount,
                'week_start'  => $weekStart,
                'week_end'    => $weekEnd,
            ];
        }

        return view('dashboard', compact(
            'todayPs',
            'todayProd',
            'todayTotal',
            'chartLabels',
            'chartData',
            'lastTx',
            'topProduct'   // <-- dipakai di dashboard.blade.php untuk kartu "Produk paling laris minggu ini"
        ));
    }
}
