<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        // --- 1. SETUP TANGGAL UTAMA (Sesuai Filter User) ---
        $range = $r->query('range', 'day'); 
        $refDate = $r->query('date') ? Carbon::parse($r->query('date')) : Carbon::now();

        if ($range === 'day') {
            $start = $refDate->copy()->startOfDay();
            $end   = $refDate->copy()->endOfDay();
        } elseif ($range === 'week') {
            $start = $refDate->copy()->startOfWeek();
            $end   = $refDate->copy()->endOfWeek();
        } elseif ($range === 'month') {
            $start = $refDate->copy()->startOfMonth();
            $end   = $refDate->copy()->endOfMonth();
        } else { // custom
            $s = $r->query('start');
            $e = $r->query('end');
            $start = $s ? Carbon::parse($s)->startOfDay() : Carbon::now()->startOfDay();
            $end   = $e ? Carbon::parse($e)->endOfDay() : Carbon::now()->endOfDay();
        }

        // --- 2. SETUP TANGGAL UNTUK REKAPITULASI (Agar Data Lebih Luas) ---
        // Rekap Harian: Kita ambil data 1 Bulan penuh dari tanggal yang dipilih
        $recapDailyStart = $start->copy()->startOfMonth();
        $recapDailyEnd   = $start->copy()->endOfMonth();

        // Rekap Mingguan & Bulanan: Kita ambil data 1 Tahun penuh (Jan - Des)
        $recapYearStart  = $start->copy()->startOfYear();
        $recapYearEnd    = $start->copy()->endOfYear();


        // --- QUERY TOTAL PENDAPATAN (Tetap pakai $start & $end sesuai filter) ---
        $ps_total = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','sales.id')
            ->whereBetween('sales.sold_at', [$start, $end])
            ->whereNull('sale_items.product_id')
            ->selectRaw('COALESCE(SUM(sale_items.subtotal),0) as total')
            ->value('total');

        $prod_total = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','sales.id')
            ->whereBetween('sales.sold_at', [$start, $end])
            ->whereNotNull('sale_items.product_id')
            ->selectRaw('COALESCE(SUM(sale_items.subtotal),0) as total')
            ->value('total');

        $sales_total = ($ps_total ?? 0) + ($prod_total ?? 0);

        // --- [BARU] QUERY TOTAL PENGELUARAN (DIPISAH SUMBER DANA) ---
        $expenses_query = DB::table('expenses')->whereBetween('timestamp', [$start, $end]);

        // 1. Pengeluaran dari Uang PS
        $exp_from_ps = (clone $expenses_query)
            ->where('fund_source', 'ps')
            ->sum('amount');

        // 2. Pengeluaran dari Uang Produk
        $exp_from_prod = (clone $expenses_query)
            ->where('fund_source', 'product')
            ->sum('amount');

        // 3. Pengeluaran Lainnya (Kas Umum/Modal)
        // Ambil yang fund_source = 'other' ATAU NULL (untuk data lama)
        $exp_from_other = (clone $expenses_query)
            ->where(function($q) {
                $q->where('fund_source', 'other')->orWhereNull('fund_source');
            })
            ->sum('amount');

        // Total Semua Pengeluaran
        $expenses_total = $exp_from_ps + $exp_from_prod + $exp_from_other;


        // --- LIST PENJUALAN (Tetap pakai $start & $end) ---
        $sales = Sale::with(['items.product']) 
            ->whereBetween('sold_at', [$start, $end])
            ->orderBy('sold_at','desc')
            ->get()
            ->map(function($s){
                $totalFix = $s->total ?? $s->total_amount ?? 0;
                if ($totalFix == 0) {
                    $totalFix = $s->items->sum('subtotal');
                }
                $s->total = $totalFix;

                $names = [];
                foreach($s->items as $it) {
                    if($it->product) {
                        $names[] = $it->product->name . ' (' . $it->qty . ')';
                    }
                }
                $displayTitle = !empty($names) ? implode(', ', $names) : $s->note;
                $s->display_note = $displayTitle ?: 'Item'; 

                return $s;
            });

        // Split Rental & Produk
        $rentalSales = $sales->filter(function ($s) {
            return $s->items->contains(function ($item) {
                return $item->product_id === null;
            });
        });
        $productSales = $sales->diff($rentalSales);

        // Expenses List
        $expenses = DB::table('expenses')
            ->whereBetween('timestamp', [$start, $end])
            ->orderBy('timestamp','desc')
            ->get()
            ->map(function($e){
                $e->timestamp = isset($e->timestamp) ? Carbon::parse($e->timestamp) : null;
                $e->timestamp_fmt = $e->timestamp ? $e->timestamp->format('d-m H:i') : '';
                return $e;
            });

        // --- REKAPITULASI (MENGGUNAKAN RENTANG WAKTU LUAS) ---
        
        // 1. Daily Rows (Tampilkan data sebulan penuh)
        $daily_rows = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw("
                DATE(sales.sold_at) as d, 
                SUM(sale_items.subtotal) as total,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NULL THEN sale_items.subtotal ELSE 0 END),0) as ps_amount,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NOT NULL THEN sale_items.subtotal ELSE 0 END),0) as prod_amount
            ")
            ->whereBetween('sales.sold_at', [$recapDailyStart, $recapDailyEnd]) 
            ->groupBy('d')
            ->orderBy('d','asc')
            ->get()
            ->map(function($row){
                return (object)[
                    'label' => Carbon::parse($row->d)->format('d/m'), 
                    'ps'    => (int)$row->ps_amount,
                    'prod'  => (int)$row->prod_amount,
                    'total' => (int)$row->total
                ];
            });

        // 2. Weekly Rows (Tampilkan data setahun penuh)
        $weekly_rows = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw("
                YEAR(sales.sold_at) as y,
                WEEK(sales.sold_at,1) as w,
                SUM(sale_items.subtotal) as total,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NULL THEN sale_items.subtotal ELSE 0 END),0) as ps_amount,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NOT NULL THEN sale_items.subtotal ELSE 0 END),0) as prod_amount
            ")
            ->whereBetween('sales.sold_at', [$recapYearStart, $recapYearEnd]) 
            ->groupBy('y','w')
            ->orderBy('y','asc')
            ->orderBy('w','asc')
            ->get()
            ->map(function($r){
                $weekStart = Carbon::now()->setISODate($r->y, $r->w)->startOfWeek();
                $weekOfMonth = $weekStart->weekOfMonth;
                $label = 'Mg ' . $weekOfMonth . ' ' . $weekStart->translatedFormat('M');

                return (object)[
                    'label' => $label,
                    'ps'    => (int)$r->ps_amount,
                    'prod'  => (int)$r->prod_amount,
                    'total' => (int)$r->total,
                ];
            });

        // 3. Monthly Rows (Tampilkan data setahun penuh)
        $monthly_rows = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw("
                DATE_FORMAT(sales.sold_at,'%Y-%m') as m, 
                SUM(sale_items.subtotal) as total,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NULL THEN sale_items.subtotal ELSE 0 END),0) as ps_amount,
                COALESCE(SUM(CASE WHEN sale_items.product_id IS NOT NULL THEN sale_items.subtotal ELSE 0 END),0) as prod_amount
            ")
            ->whereBetween('sales.sold_at', [$recapYearStart, $recapYearEnd]) 
            ->groupBy('m')
            ->orderBy('m','asc')
            ->get()
            ->map(function($r){
                return (object)[
                    'label' => Carbon::parse($r->m . '-01')->translatedFormat('F'), 
                    'ps'    => (int)$r->ps_amount,
                    'prod'  => (int)$r->prod_amount,
                    'total' => (int)$r->total
                ];
            });

        // --- Top products ---
        $top = DB::table('sale_items')
            ->join('sales','sale_items.sale_id','sales.id')
            ->join('products','sale_items.product_id','products.id')
            ->whereBetween('sales.sold_at', [$start, $end])
            ->selectRaw('products.name as name, SUM(sale_items.qty) as qty, SUM(sale_items.subtotal) as amount')
            ->groupBy('products.id','products.name')
            ->orderByRaw('SUM(sale_items.qty) desc')
            ->limit(10)
            ->get();

        $low_stock = Product::where('stock','<=',5)->orderBy('stock','asc')->get();

        return view('reports.index', [
            'start_date'     => $start,
            'end_date'       => $end,
            'start_date_str' => $start->format('Y-m-d'),
            'end_date_str'   => $end->format('Y-m-d'),
            
            // TOTAL PENDAPATAN
            'ps_total'       => (int)$ps_total,
            'prod_total'     => (int)$prod_total,
            'sales_total'    => (int)$sales_total,
            
            // TOTAL PENGELUARAN (GLOBAL & SPLIT)
            'expenses_total' => (int)$expenses_total,
            'exp_from_ps'    => (int)$exp_from_ps,    // <--- Dikirim ke View
            'exp_from_prod'  => (int)$exp_from_prod,  // <--- Dikirim ke View
            'exp_from_other' => (int)$exp_from_other, // <--- Dikirim ke View
            
            'sales'          => $sales, 
            'rentalSales'    => $rentalSales,
            'productSales'   => $productSales,
            
            'expenses'       => $expenses,
            'daily_rows'     => $daily_rows,
            'weekly_rows'    => $weekly_rows,
            'monthly_rows'   => $monthly_rows,
            'top'            => $top,
            'low_stock'      => $low_stock,
            'range'          => $range,
            'ref_date'       => $refDate,
            'd'              => Carbon::now(),
        ]);
    }
}