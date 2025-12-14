@extends('layouts.fixplay')

@section('page_title','Kasir Fixplay - Laporan')

@push('styles')
<style>
  /* ====== SHELL UTAMA (DARK GRADIENT) ====== */
  .fp-header-sub{ font-size:.82rem; color:#cbd5f5; white-space:normal; opacity: 60%; }
  
  .report-shell{
    position: relative; padding: 1.75rem 1.75rem 2.25rem; border-radius: 1.5rem;
    background: radial-gradient(circle at top left,#4f46e533,#0f172a), radial-gradient(circle at bottom right,#22c55e22,#020617 70%);
    box-shadow: 0 22px 50px rgba(15,23,42,0.75); overflow: hidden; color:#e5e7eb;
  }
  .report-shell::before{
    content:""; position:absolute; inset:-40%;
    background: radial-gradient(circle at 10% 0,#ffffff33,transparent 55%), radial-gradient(circle at 90% 100%,#22c55e33,transparent 60%);
    opacity:.7; filter: blur(40px); pointer-events:none;
  }
  .report-shell > *{ position:relative; z-index:1; }

  .report-chip-icon{
    width:32px; height:32px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7); color:#fff; box-shadow:0 8px 18px rgba(79,70,229,.65); font-size:15px;
  }

  /* ====== INPUT & BUTTONS ====== */
  .report-filter .input-glass{
    border-radius:999px; background:rgba(15,23,42,.9); border:1px solid rgba(148,163,184,.5);
    padding:2px 10px; box-shadow:0 10px 25px rgba(15,23,42,.65); backdrop-filter:blur(18px);
  }
  .report-filter .form-control, .report-filter .form-select{
    background: transparent; border: none; color:#e5e7eb; font-size: 0.9rem;
  }
  .report-filter .form-control:focus, .report-filter .form-select:focus{ box-shadow:none; }
  .report-filter option { background: #0f172a; color: #e5e7eb; }

  .btn-light-soft{
    border-radius:999px; border:1px solid rgba(148,163,184,.4); background:rgba(15,23,42,.85); color:#e5e7eb; font-size: 0.85rem; padding: 0.4rem 1rem;
  }
  .btn-light-soft:hover{ background:rgba(31,41,55,.95); color: #fff; }
  
  .btn-outline-primary { border-radius: 999px; padding: 0.4rem 1rem; font-size: 0.85rem; }

  .bg-dark-soft{ background:rgba(15,23,42,.9); }

  /* ====== METRIC CARDS ====== */
  .metric-card{
    position:relative; border-radius:1.2rem; padding:1rem 1.1rem 1.1rem;
    background:linear-gradient(145deg,#020617,#0f172a); border:1px solid rgba(148,163,184,.3);
    box-shadow:0 18px 38px rgba(0,0,0,.6); backdrop-filter:blur(22px); overflow:hidden; color:#e5e7eb; height: 100%;
  }
  .metric-card::after{ content:""; position:absolute; inset:auto -40% -40% auto; width:120px; height:120px; border-radius:999px; opacity:.4; filter:blur(30px); }
  
  .metric-card .metric-icon{
    width:34px; height:34px; border-radius:999px; display:flex; align-items:center; justify-content:center;
    color:#0f172a; background:#e0e7ff; margin-bottom:.5rem; font-size:18px;
  }
  .metric-card .metric-label{ font-size:.7rem; letter-spacing:.08em; text-transform:uppercase; color:#94a3b8; margin-bottom:.2rem; font-weight:700; }
  .metric-card .metric-value{ font-size:1.35rem; font-weight:700; color:#f9fafb; letter-spacing: 0.5px; }
  .metric-card .metric-caption{ font-size:.7rem; color:#64748b; margin-top:.25rem; }
  
  .metric-blue::after   { background:radial-gradient(circle,#6366f1,#0ea5e9); }
  .metric-green::after  { background:radial-gradient(circle,#22c55e,#a3e635); }
  .metric-purple::after { background:radial-gradient(circle,#a855f7,#6366f1); }
  .metric-yellow::after { background:radial-gradient(circle,#facc15,#fb923c); }

  /* ====== CARD GLASS & TABLES ====== */
  .card-glass{
    border-radius:1.1rem; border:1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.85));
    box-shadow:0 18px 38px rgba(0,0,0,.6); backdrop-filter:blur(20px); color:#e5e7eb;
    overflow: hidden;
  }
  .card-glass .card-header{
    border-bottom:1px solid rgba(148,163,184,.2); 
    background: rgba(30, 41, 59, 0.4); 
    font-weight:700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color:#cbd5e1;
    padding: 0.8rem 1.2rem;
  }

  .table-modern{ width: 100%; margin-bottom: 0; color:#cbd5e1; }
  .table-modern thead th{
    background: rgba(15, 23, 42, 0.8);
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8;
    padding: 0.75rem 1rem; white-space: nowrap;
  }
  .table-modern tbody tr{ transition:background .15s ease; }
  .table-modern tbody tr:hover{ background:rgba(99, 102, 241, 0.08); }
  .table-modern td{
    background: transparent;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 0.75rem 1rem; vertical-align: middle; font-size: 0.85rem;
  }

  .amount-mono{ font-family: 'Consolas', 'Monaco', monospace; font-weight: 600; color: #818cf8; }
  
  .badge-soft-primary{ background:rgba(59,130,246,.2); color:#93c5fd; border: 1px solid rgba(59,130,246,.3); border-radius:99px; font-size: 0.65rem; padding: 0.3rem 0.6rem; }
  .badge-soft-danger{ background:rgba(248,113,113,.2); color:#fca5a5; border: 1px solid rgba(248,113,113,.3); border-radius:99px; font-size: 0.65rem; padding: 0.3rem 0.6rem; }

  .btn-action-group .btn { padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: 6px; margin-right: 4px; }
  .btn-outline-secondary-soft { color: #cbd5e1; border: 1px solid #475569; background: rgba(71,85,105,0.1); }
  .btn-outline-secondary-soft:hover { background: #475569; color: #fff; }
  .btn-outline-danger-soft { color: #fca5a5; border: 1px solid #ef4444; background: rgba(239,68,68,0.1); }
  .btn-outline-danger-soft:hover { background: #ef4444; color: #fff; }

  .rng-day, .rng-week, .rng-month, .rng-custom { display:none; }
  #rekapTabs .nav-link{
    border-radius:999px; padding:.3rem .9rem; font-size:.75rem; border:1px solid transparent; color:#94a3b8; background:transparent;
  }
  #rekapTabs .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
  #rekapTabs .nav-link.active{
    background:linear-gradient(135deg,#6366f1,#a855f7); color:#fff; box-shadow:0 4px 12px rgba(124, 58, 237, 0.4);
  }
  .rekap-pane { display:none; }
  .rekap-pane.show { display:block; }

  .modal-glass .modal-content {
    background: radial-gradient(circle at top left, #1e1e2f, #0f1020);
    border: 1px solid rgba(124,58,237,.3);
    box-shadow: 0 0 30px rgba(0,0,0,.8); color: #e5e7eb; border-radius: 1.25rem;
  }
  .modal-glass .modal-header { border-bottom: 1px solid rgba(255,255,255,.08); }
  .modal-glass .modal-footer { border-top: 1px solid rgba(255,255,255,.08); }
  .modal-glass .form-control, .modal-glass .form-select {
    background: rgba(2, 6, 23, 0.8); border: 1px solid rgba(148,163,184,.2); color: #f1f5f9; border-radius: 0.6rem;
  }
  .modal-glass .form-control:focus, .modal-glass .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.25);
  }
  .modal-glass .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
  .btn-gradient-primary {
    background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none; color: white; font-weight: 600;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3); border-radius: 999px;
  }
  .btn-gradient-primary:hover {
    background: linear-gradient(135deg, #7c3aed, #2563eb); color: white; transform: translateY(-1px);
  }

  input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }

  @media (max-width: 768px){
    .report-shell{ padding:1.25rem 1rem 2rem; border-radius:1rem; }
    .metric-card{ padding:.9rem .95rem 1rem; margin-bottom:.5rem; }
    .d-flex.align-items-center.justify-content-between.mb-3{ flex-direction:column; align-items:flex-start !important; gap:.75rem; }
    .report-filter .col-6, .report-filter .col-12{ flex:0 0 100%; max-width:100%; }
  }
  @media print {
    .report-shell, .card-glass, .table-modern tbody tr, .table-modern tfoot tr{ background:#fff; box-shadow:none; color:#000; }
    .card-glass{ border-color:#e5e7eb; }
    .table-modern thead{ background:#f3f4f6; color:#111827; }
    .d-print-none { display: none !important; }
    .card, .table { break-inside: avoid; }
  }
</style>
@endpush

@section('page_content')
<div class="report-shell">

  {{-- Header + tombol cetak --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="report-chip-icon"><i class="bi bi-activity"></i></span>
        <h4 class="m-0 text-light fw-bold">Laporan Keuangan</h4>
      </div>
      <div class="fp-header-sub">Ringkasan pendapatan & pengeluaran usaha.</div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none">
      <button type="button" class="btn btn-light-soft" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise"></i>
      </button>
      <button class="btn btn-outline-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> PDF
      </button>
    </div>
  </div>

  {{-- Filter rentang tanggal --}}
  <form method="get" class="mb-4 row g-3 align-items-end report-filter d-print-none">
    <div class="col-md-3 col-6">
      <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Rentang</label>
      <div class="input-glass">
        <select name="range" class="form-select" id="rangeSel">
          <option value="day"    @selected($range=='day')>Harian</option>
          <option value="week"   @selected($range=='week')>Mingguan</option>
          <option value="month"  @selected($range=='month')>Bulanan</option>
          <option value="custom" @selected($range=='custom')>Kustom</option>
        </select>
      </div>
    </div>

    <div class="col-md-3 col-6 rng-day rng-week rng-month">
      <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Tanggal</label>
      <div class="input-glass">
        <input name="date" type="date" class="form-control" value="{{ request('date', $start_date->format('Y-m-d')) }}">
      </div>
    </div>

    <div class="col-md-3 col-6 rng-custom">
      <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Mulai</label>
      <div class="input-glass">
        <input name="start" type="date" class="form-control" value="{{ $start_date->format('Y-m-d') }}">
      </div>
    </div>

    <div class="col-md-3 col-6 rng-custom">
      <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Sampai</label>
      <div class="input-glass">
        <input name="end" type="date" class="form-control" value="{{ $end_date->format('Y-m-d') }}">
      </div>
    </div>

    <div class="col-md-2 ms-md-auto col-12">
      <label class="d-block mb-1 opacity-0">Go</label>
      <button class="btn btn-gradient-primary w-100 shadow-lg">Terapkan</button>
    </div>
  </form>

  {{-- Ringkasan metrik utama (SPLIT LABA) --}}
  <div class="row g-4 mb-4">
    {{-- CARD 1: LABA BERSIH PS --}}
    <div class="col-md-3 col-6">
      <div class="metric-card metric-blue h-100">
        <div class="d-flex justify-content-between">
            <div class="metric-icon"><i class="bi bi-controller"></i></div>
            <div class="text-end">
                <span class="badge bg-primary bg-opacity-25 text-primary" style="font-size:0.65rem;">NETTO PS</span>
            </div>
        </div>
        <div class="metric-label text-primary">Laba Bersih PS</div>
        
        {{-- Rumus: Pendapatan PS - Pengeluaran yg pakai uang PS --}}
        @php $netPs = $ps_total - $exp_from_ps; @endphp
        <div class="metric-value {{ $netPs < 0 ? 'text-danger' : '' }}">
            Rp {{ number_format($netPs, 0, ',', '.') }}
        </div>
        
        <div class="metric-caption d-flex flex-column mt-2">
            <span class="text-success"><i class="bi bi-arrow-up-short"></i> Masuk: Rp {{ number_format($ps_total,0,',','.') }}</span>
            <span class="text-danger"><i class="bi bi-arrow-down-short"></i> Keluar: Rp {{ number_format($exp_from_ps,0,',','.') }}</span>
        </div>
      </div>
    </div>

    {{-- CARD 2: LABA BERSIH PRODUK --}}
    <div class="col-md-3 col-6">
      <div class="metric-card metric-green h-100">
        <div class="d-flex justify-content-between">
            <div class="metric-icon"><i class="bi bi-basket3"></i></div>
            <div class="text-end">
                <span class="badge bg-success bg-opacity-25 text-success" style="font-size:0.65rem;">NETTO PRODUK</span>
            </div>
        </div>
        <div class="metric-label text-success">Laba Bersih Produk</div>
        
        {{-- Rumus: Pendapatan Produk - Pengeluaran yg pakai uang Produk --}}
        @php $netProd = $prod_total - $exp_from_prod; @endphp
        <div class="metric-value {{ $netProd < 0 ? 'text-danger' : '' }}">
            Rp {{ number_format($netProd, 0, ',', '.') }}
        </div>

        <div class="metric-caption d-flex flex-column mt-2">
            <span class="text-success"><i class="bi bi-arrow-up-short"></i> Masuk: Rp {{ number_format($prod_total,0,',','.') }}</span>
            <span class="text-danger"><i class="bi bi-arrow-down-short"></i> Keluar: Rp {{ number_format($exp_from_prod,0,',','.') }}</span>
        </div>
      </div>
    </div>

    {{-- CARD 3: PENGELUARAN LAINNYA --}}
    <div class="col-md-3 col-6">
      <div class="metric-card metric-purple h-100">
        <div class="metric-icon"><i class="bi bi-wallet2"></i></div>
        <div class="metric-label">Pengeluaran Lain</div>
        {{-- Pengeluaran selalu merah --}}
        <div class="metric-value text-danger">
            - Rp {{ number_format($exp_from_other, 0, ',', '.') }}
        </div>
        <div class="metric-caption">Dari Kas Umum / Modal</div>
      </div>
    </div>

    {{-- CARD 4: TOTAL SALDO AKHIR --}}
    <div class="col-md-3 col-6">
      <div class="metric-card metric-yellow h-100">
        <div class="metric-icon"><i class="bi bi-safe"></i></div>
        <div class="metric-label">Sisa Kas Total</div>
        
        {{-- Rumus: (Total Semua Masuk) - (Total Semua Keluar) --}}
        @php $netTotal = $sales_total - $expenses_total; @endphp
        {{-- Jika Minus Merah, Jika Plus Kuning (Warning/Gold) --}}
        <div class="metric-value {{ $netTotal < 0 ? 'text-danger' : 'text-warning' }}">
            Rp {{ number_format($netTotal, 0, ',', '.') }}
        </div>
        <div class="metric-caption">Laba Bersih Keseluruhan</div>
      </div>
    </div>
  </div>

  {{-- Detail transaksi --}}
  <div class="row g-4 mt-2">
    
    {{-- 1. TABEL RIWAYAT RENTAL --}}
    <div class="col-lg-6">
      <div class="card card-glass h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(99, 102, 241, 0.2);">
          <span class="text-white"><i class="bi bi-controller me-2"></i>Laporan Rental PS</span>
          <span class="badge badge-soft-primary d-print-none">{{ $rentalSales->count() }} Data</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern table-sm">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Detail Rental</th>
                  <th class="text-end">Total</th>
                  <th class="text-end d-print-none" style="width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($rentalSales as $s)
                  <tr>
                    <td>
                        <div class="d-flex flex-column small text-secondary">
                            <span>{{ \Carbon\Carbon::parse($s->sold_at)->format('d-m-y') }}</span>
                            <span class="text-light fw-bold">{{ \Carbon\Carbon::parse($s->sold_at)->format('H:i') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate text-light fw-bold" style="max-width: 180px;" title="{{ $s->display_note }}">
                            {{ $s->display_note }}
                        </div>
                    </td>
                    <td class="text-end amount-mono text-info">Rp {{ number_format($s->total ?? 0,0,',','.') }}</td>
                    <td class="text-end d-print-none">
                      <div class="btn-action-group justify-content-end">
                          <button type="button" class="btn btn-outline-secondary-soft" title="Edit"
                                  onclick='editSaleModal({{ $s->id }}, {!! json_encode($s->note) !!}, {!! json_encode($s->payment_method) !!}, {{ $s->paid_amount ?? 0 }}, {{ $s->total ?? 0 }}, {!! json_encode($s->sold_at->format("Y-m-d\TH:i")) !!})'>
                            <i class="bi bi-pencil"></i>
                          </button>

                          @if(auth()->user() && auth()->user()->role === 'boss')
                            <form class="d-inline confirm-delete" method="POST" 
                                  action="{{ route('sales.destroy', $s->id) }}" 
                                  data-confirm="Hapus data rental ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger-soft" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                          @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-light p-4 opacity-50">Tidak ada transaksi rental.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- 2. TABEL RIWAYAT PRODUK --}}
    <div class="col-lg-6">
      <div class="card card-glass h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(34, 197, 94, 0.15);">
          <div class="d-flex align-items-center gap-2">
             <span class="text-white"><i class="bi bi-basket3 me-2"></i>Laporan Produk</span>
             <span class="badge badge-soft-primary d-print-none">{{ $productSales->count() }} Data</span>
          </div>
          
          <div class="d-print-none">
             <input type="text" id="searchProductInput" 
                    class="form-control form-control-sm text-white" 
                    style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); width: 160px;" 
                    placeholder="Cari menu...">
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern table-sm" id="productTable">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Barang</th>
                  <th class="text-end">Total</th>
                  <th class="text-end d-print-none" style="width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($productSales as $s)
                  <tr class="product-row">
                    <td>
                        <div class="d-flex flex-column small text-secondary">
                            <span>{{ \Carbon\Carbon::parse($s->sold_at)->format('d-m-y') }}</span>
                            <span class="text-light fw-bold">{{ \Carbon\Carbon::parse($s->sold_at)->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="searchable-text">
                        <div class="text-light" style="max-width: 180px;">
                            {{ $s->display_note }}
                        </div>
                    </td>
                    <td class="text-end amount-mono text-success">Rp {{ number_format($s->total ?? 0,0,',','.') }}</td>
                    <td class="text-end d-print-none">
                      <div class="btn-action-group justify-content-end">
                          <button type="button" class="btn btn-outline-secondary-soft" title="Edit"
                                  onclick='editSaleModal({{ $s->id }}, {!! json_encode($s->note) !!}, {!! json_encode($s->payment_method) !!}, {{ $s->paid_amount ?? 0 }}, {{ $s->total ?? 0 }}, {!! json_encode($s->sold_at->format("Y-m-d\TH:i")) !!})'>
                            <i class="bi bi-pencil"></i>
                          </button>

                          @if(auth()->user() && auth()->user()->role === 'boss')
                            <form class="d-inline confirm-delete" method="POST" 
                                  action="{{ route('sales.destroy', $s->id) }}" 
                                  data-confirm="Hapus penjualan produk ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger-soft" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                          @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-light p-4 opacity-50">Tidak ada penjualan produk.</td></tr>
                @endforelse
                
                <tr id="noProductFound" style="display: none;">
                    <td colspan="4" class="text-center text-secondary py-3">
                        <i class="bi bi-search me-1"></i> Produk tidak ditemukan.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TABEL PENGELUARAN --}}
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="card card-glass h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(239, 68, 68, 0.1);">
          <span class="text-white"><i class="bi bi-wallet2 me-2"></i>Daftar Pengeluaran</span>
          <span class="badge badge-soft-danger d-print-none">{{ $expenses->count() }} Item</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern table-sm">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Kategori</th>
                  <th class="text-end">Jumlah</th>
                  <th class="text-end d-print-none" style="width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($expenses as $e)
                <tr>
                  <td>
                      <div class="d-flex flex-column small text-secondary">
                          <span>{{ $e->timestamp ? \Carbon\Carbon::parse($e->timestamp)->format('d-m-y') : '-' }}</span>
                      </div>
                  </td>
                  <td>
                      {{-- BADGE SUMBER DANA (BARU) --}}
                      @if($e->fund_source === 'ps')
                          <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 mb-1" style="font-size: 0.6rem;">BILLING PS</span>
                      @elseif($e->fund_source === 'product')
                          <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 mb-1" style="font-size: 0.6rem;">PRODUK</span>
                      @else
                          <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 mb-1" style="font-size: 0.6rem;">KAS LAIN</span>
                      @endif

                      <div class="text-light fw-semibold">{{ $e->category }}</div>
                      <div class="small text-secondary text-truncate" style="max-width: 350px;">{{ $e->description }}</div>
                  </td>
                  <td class="text-end amount-mono text-danger">Rp {{ number_format($e->amount ?? 0,0,',','.') }}</td>
                  <td class="text-end d-print-none">
                    <div class="btn-action-group justify-content-end">
                        <button type="button" class="btn btn-outline-secondary-soft" title="Edit"
                                onclick='editExpenseModal({{ $e->id }}, {!! json_encode($e->category) !!}, {!! json_encode($e->description ?? "") !!}, {{ (int)($e->amount ?? 0) }}, {!! json_encode(isset($e->timestamp) ? ($e->timestamp_fmt ?? $e->timestamp) : "") !!})'>
                          <i class="bi bi-pencil"></i>
                        </button>
                        <form class="d-inline" method="POST" action="{{ route('purchases.expenses.destroy', $e->id) }}" onsubmit="return confirm('Hapus pengeluaran ini?');">
                          @csrf @method('DELETE')
                          <button class="btn btn-outline-danger-soft" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                  </td>
                </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-white p-4">Belum ada data pengeluaran.</td></tr>
                @endforelse
              </tbody>
              <tfoot>
                <tr class="fw-bold bg-dark bg-opacity-50">
                  <td colspan="2" class="text-end text-secondary">TOTAL PENGELUARAN PERIODE</td>
                  <td class="text-end text-danger">Rp {{ number_format($expenses_total,0,',','.') }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Rekap per periode --}}
  <div class="card card-glass mt-4">
    <div class="card-header d-flex align-items-center gap-3 border-bottom border-secondary border-opacity-25 py-2">
      <span class="text-uppercase small text-secondary fw-bold"><i class="bi bi-table me-1"></i> Rekapitulasi</span>
      <ul class="nav nav-pills ms-auto d-print-none" id="rekapTabs">
        <li class="nav-item"><button class="nav-link active" data-target="#tabHarian">Harian</button></li>
        <li class="nav-item"><button class="nav-link" data-target="#tabMingguan">Mingguan</button></li>
        <li class="nav-item"><button class="nav-link" data-target="#tabBulanan">Bulanan</button></li>
      </ul>
    </div>
    <div class="card-body p-0">
      <div class="p-3">
        <div id="tabHarian" class="rekap-pane show">
          <div class="table-responsive">
            <table class="table table-modern table-sm">
              <thead><tr><th >Periode</th><th class="text-end text-primary">Pendapatan PS</th><th class="text-end text-success">Produk</th><th class="text-end text-light">Total</th></tr></thead>
              <tbody>
                @forelse($daily_rows as $r)
                  <tr>
                      <td class="text-white ">{{ $r->label }}</td>
                      <td class="text-end amount-mono text-white fw-bold">{{ number_format($r->ps ?? 0,0,',','.') }}</td>
                      <td class="text-end amount-mono text-white fw-bold">{{ number_format($r->prod ?? 0,0,',','.') }}</td>
                      <td class="text-end amount-mono text-white fw-bold">{{ number_format($r->total ?? 0,0,',','.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-light py-3">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        <div id="tabMingguan" class="rekap-pane">
            <div class="table-responsive">
                <table class="table table-modern table-sm">
                  <thead><tr><th>Periode</th><th class="text-end text-primary">PS</th><th class="text-end text-success">Produk</th><th class="text-end text-light">Total</th></tr></thead>
                  <tbody>
                    @forelse($weekly_rows as $r)
                      <tr><td class="text-white ">{{ $r->label }}</td><td class="text-end amount-mono text-white">{{ number_format($r->ps ?? 0,0,',','.') }}</td><td class="text-end amount-mono text-white">{{ number_format($r->prod ?? 0,0,',','.') }}</td><td class="text-end amount-mono text-white fw-bold">{{ number_format($r->total ?? 0,0,',','.') }}</td></tr>
                    @empty
                      <tr><td colspan="4" class="text-center text-light py-3">Tidak ada data.</td></tr>
                    @endforelse
                  </tbody>
                </table>
            </div>
        </div>
        <div id="tabBulanan" class="rekap-pane">
            <div class="table-responsive">
                <table class="table table-modern table-sm">
                  <thead><tr><th>Periode</th><th class="text-end text-primary">PS</th><th class="text-end text-success">Produk</th><th class="text-end text-light">Total</th></tr></thead>
                  <tbody>
                    @forelse($monthly_rows as $r)
                      <tr><td class="text-white ">{{ $r->label }}</td><td class="text-end amount-mono text-white">{{ number_format($r->ps ?? 0,0,',','.') }}</td><td class="text-end amount-mono text-white">{{ number_format($r->prod ?? 0,0,',','.') }}</td><td class="text-end amount-mono text-white fw-bold">{{ number_format($r->total ?? 0,0,',','.') }}</td></tr>
                    @empty
                      <tr><td colspan="4" class="text-center text-light py-3">Tidak ada data.</td></tr>
                    @endforelse
                  </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Top produk & stok rendah --}}
  <div class="row g-4 mt-2">
    <div class="col-md-6">
      <div class="card card-glass h-100">
        <div class="card-header">Top Produk (Qty)</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern table-sm">
              <thead><tr><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Omzet</th></tr></thead>
              <tbody>
                @forelse($top as $t)
                  <tr><td class="text-white fw-bold">{{ $t->name }}</td><td class="text-center text-warning fw-bold">{{ $t->qty }}</td><td class="text-end amount-mono text-white fw-bold">{{ number_format($t->amount,0,',','.') }}</td></tr>
                @empty
                  <tr><td colspan="3" class="text-center text-white py-3">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-glass h-100">
        <div class="card-header text-danger">Stok Menipis (≤ 5)</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern table-sm">
              <thead><tr><th>Produk</th><th>Stok</th></tr></thead>
              <tbody>
                @forelse($low_stock as $p)
                  <tr><td class="text-white fw-bold">{{ $p->name }}</td><td class="text-danger fw-bold">{{ $p->stock }} {{ $p->unit }}</td></tr>
                @empty
                  <tr><td colspan="2" class="text-center text-white py-3">Stok aman.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- MODAL EDIT PENJUALAN --}}
<div class="modal fade modal-glass" id="editSaleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Edit Transaksi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <form id="editSaleForm" method="POST">
          @csrf @method('PUT')
          
          <div class="mb-3">
            <label class="form-label small text-mono">Waktu Transaksi</label>
            <input type="datetime-local" name="created_at" id="saleDate" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label small text-mono">Total Tagihan</label>
            <input type="number" name="total_bill" id="saleTotal" class="form-control" min="0" required>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small text-mono">Metode Bayar</label>
              <select name="payment_method" id="saleMethod" class="form-select">
                <option value="Tunai">Tunai</option>
                <option value="QRIS">QRIS</option>
                <option value="Transfer">Transfer</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small text-mono">Dibayar</label>
              <input type="number" name="paid_amount" id="salePaid" class="form-control" min="0">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-mono">Catatan</label>
            <textarea name="note" id="saleNote" class="form-control bg-secondary bg-opacity-25" rows="2" readonly style="cursor: not-allowed; color: #94a3b8;"></textarea>
            <div class="form-text small text-light">Catatan transaksi tidak dapat diubah manual.</div>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary-soft btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-gradient-primary px-4">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- MODAL EDIT PENGELUARAN --}}
<div class="modal fade modal-glass" id="editExpenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Edit Pengeluaran</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <form id="editExpenseForm" method="POST">
          @csrf @method('PUT')
          
          <div class="mb-3">
            <label class="form-label small text-muted">Waktu</label>
            <input type="text" name="timestamp" id="expDate" class="form-control" placeholder="YYYY-MM-DD HH:MM" required>
            <div class="form-text text-muted small">Format: YYYY-MM-DD HH:MM</div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Kategori</label>
            <input type="text" name="category" id="expCat" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Jumlah (Rp)</label>
            <input type="number" name="amount" id="expAmount" class="form-control" min="0" required>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Deskripsi</label>
            <textarea name="description" id="expDesc" class="form-control" rows="2"></textarea>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary-soft btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-gradient-primary px-4">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const sel = document.getElementById('rangeSel');
  function applyRangeUI(){
    if (!sel) return;
    const v = sel.value;
    document.querySelectorAll('.rng-day,.rng-week,.rng-month,.rng-custom')
      .forEach(x => x.style.display = 'none');
    if (v === 'day')    document.querySelectorAll('.rng-day').forEach(x => x.style.display='block');
    if (v === 'week')   document.querySelectorAll('.rng-week').forEach(x => x.style.display='block');
    if (v === 'month')  document.querySelectorAll('.rng-month').forEach(x => x.style.display='block');
    if (v === 'custom') document.querySelectorAll('.rng-custom').forEach(x => x.style.display='block');
  }
  if (sel){ sel.onchange = applyRangeUI; applyRangeUI(); }
})();

// Tabs rekap
(function(){
  const tabs  = document.querySelectorAll('#rekapTabs .nav-link');
  const panes = document.querySelectorAll('.rekap-pane');
  function show(target){
    panes.forEach(p => p.classList.remove('show'));
    document.querySelector(target)?.classList.add('show');
    tabs.forEach(t => t.classList.remove('active'));
    this.classList.add('active');
  }
  tabs.forEach(t => t.addEventListener('click', function(e){
    e.preventDefault();
    show.call(this, this.dataset.target);
  }));
})();

// Edit Sale
function editSaleModal(id, note, method, paid, total, date) {
  const form = document.getElementById('editSaleForm');
  form.action = '/sales/' + id;
  document.getElementById('saleDate').value = date;
  document.getElementById('saleNote').value = note || '';
  document.getElementById('saleMethod').value = method || 'Tunai';
  document.getElementById('salePaid').value = paid || 0;
  document.getElementById('saleTotal').value = total || 0;
  const modal = new bootstrap.Modal(document.getElementById('editSaleModal'));
  modal.show();
}

// Edit Expense
function editExpenseModal(id, category, description, amount, ts) {
  const form = document.getElementById('editExpenseForm');
  form.action = '/purchases/expenses/' + id;
  document.getElementById('expDate').value = ts || '';
  document.getElementById('expCat').value = category || '';
  document.getElementById('expDesc').value = description || '';
  document.getElementById('expAmount').value = amount || 0;
  const modal = new bootstrap.Modal(document.getElementById('editExpenseModal'));
  modal.show();
}

// Search
document.getElementById('searchProductInput')?.addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable .product-row');
    let hasVisible = false;
    rows.forEach(function(row) {
        let text = row.querySelector('.searchable-text').textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
            hasVisible = true;
        } else {
            row.style.display = 'none';
        }
    });
    let noResultRow = document.getElementById('noProductFound');
    if (noResultRow) {
        noResultRow.style.display = hasVisible ? 'none' : 'table-row';
    }
});
</script>
@endpush