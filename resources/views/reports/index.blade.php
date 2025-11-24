@extends('layouts.fixplay')

@section('page_title','Kasir Fixplay - Laporan')

@section('page_content')
<div class="report-shell">

  {{-- Header + tombol cetak --}}
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="report-chip-icon">
          <i class="bi bi-activity"></i>
        </span>
        <h4 class="m-0 text-light fw-semibold">Kasir Fixplay - Laporan</h4>
      </div>
      <div class="text-muted small">
        Ringkasan pendapatan & pengeluaran usaha kamu
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none">
      <button type="button" class="btn btn-light-soft rounded-pill" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
      <button class="btn btn-outline-primary rounded-pill" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Cetak / PDF
      </button>
    </div>
  </div>

  {{-- Filter rentang tanggal --}}
  <form method="get" class="mb-3 row g-2 align-items-end report-filter d-print-none">
    <div class="col-md-3 col-6">
      <label class="form-label fw-semibold text-light small text-uppercase mb-1">Rentang</label>
      <div class="input-glass">
        <select name="range"
        class="form-select border-0 shadow-none text-light"
        id="rangeSel"
        style="background: rgba(15,23,42,.9);">
        <option value="day"    @selected($range=='day')>Harian</option>
        <option value="week"   @selected($range=='week')>Mingguan</option>
        <option value="month"  @selected($range=='month')>Bulanan</option>
        <option value="custom" @selected($range=='custom')>Kustom (Start–End)</option>
      </select>

      </div>
    </div>

    <div class="col-md-3 col-6 rng-day rng-week rng-month">
      <label class="form-label fw-semibold text-light small text-uppercase mb-1">Tanggal referensi</label>
      <div class="input-glass">
        <input name="date" type="date"
               class="form-control border-0 bg-transparent shadow-none text-light"
               value="{{ request('date', $start_date->format('Y-m-d')) }}">
      </div>
    </div>

    <div class="col-md-3 col-6 rng-custom">
      <label class="form-label fw-semibold text-light small text-uppercase mb-1">Start</label>
      <div class="input-glass">
        <input name="start" type="date"
               class="form-control border-0 bg-transparent shadow-none text-light"
               value="{{ $start_date->format('Y-m-d') }}">
      </div>
    </div>

    <div class="col-md-3 col-6 rng-custom">
      <label class="form-label fw-semibold text-light small text-uppercase mb-1">End</label>
      <div class="input-glass">
        <input name="end" type="date"
               class="form-control border-0 bg-transparent shadow-none text-light"
               value="{{ $end_date->format('Y-m-d') }}">
      </div>
    </div>

    <div class="col-md-3 ms-md-auto col-12">
      <label class="form-label d-block mb-1 opacity-0">Terapkan</label>
      <button class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-funnel me-1"></i> Terapkan
      </button>
    </div>
  </form>

  {{-- Ringkasan metrik utama --}}
  <div class="row g-3 mb-2">
    <div class="col-md-3 col-6">
      <div class="metric-card metric-blue">
        <div class="metric-icon">
          <i class="bi bi-controller"></i>
        </div>
        <div class="metric-label">Pendapatan PS</div>
        <div class="metric-value">Rp {{ number_format($ps_total,0,',','.') }}</div>
        <div class="metric-caption">Dari semua sesi rental PS</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="metric-card metric-green">
        <div class="metric-icon">
          <i class="bi bi-basket3"></i>
        </div>
        <div class="metric-label">Pendapatan Produk</div>
        <div class="metric-value">Rp {{ number_format($prod_total,0,',','.') }}</div>
        <div class="metric-caption">Makanan, minuman, dan produk lain</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="metric-card metric-purple">
        <div class="metric-icon">
          <i class="bi bi-receipt"></i>
        </div>
        <div class="metric-label">Total Penjualan</div>
        <div class="metric-value">Rp {{ number_format($sales_total,0,',','.') }}</div>
        <div class="metric-caption">PS + Produk</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="metric-card metric-yellow">
        <div class="metric-icon">
          <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="metric-label">Laba Bersih (setelah pengeluaran)</div>
        <div class="metric-value">
          Rp {{ number_format($sales_total - $expenses_total,0,',','.') }}
        </div>
        <div class="metric-caption">Pengeluaran: Rp {{ number_format($expenses_total,0,',','.') }}</div>
      </div>
    </div>
  </div>

  {{-- Detail transaksi & pengeluaran --}}
  <div class="row g-3 mt-1">
    <div class="col-lg-6">
      <div class="card card-glass">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Penjualan ({{ $start_date->format('d-m-Y') }} — {{ $end_date->format('d-m-Y') }})</span>
          <span class="badge badge-soft-primary d-print-none">
            {{ $sales->count() }} transaksi
          </span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm m-0 align-middle table-modern">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Catatan / Produk</th>
                  <th class="text-end">Total</th>
                  <th class="text-end d-print-none"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($sales as $s)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($s->sold_at)->format('d-m H:i') }}</td>
                    <td>{{ $s->display_note }}</td>
                    <td class="text-end amount-mono">
                      Rp {{ number_format($s->total ?? 0,0,',','.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <a class="btn btn-xs btn-outline-primary bg-dark-soft text-light px-2 py-1"
                         href="{{ url('/sales/'.$s->id) }}">
                        Lihat
                      </a>
                      <button type="button"
                              class="btn btn-xs btn-outline-secondary bg-dark-soft text-light px-2 py-1"
                              onclick='return editSale({{ $s->id }}, {!! json_encode($s->note) !!}, {!! json_encode($s->payment_method) !!}, {{ $s->paid_amount ?? 0 }}, {{ $s->total ?? 0 }})'>
                        Edit
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted p-3">Belum ada penjualan.</td>
                  </tr>
                @endforelse
              </tbody>
              <tfoot>
                <tr class="fw-semibold">
                  <td colspan="2">Total PS</td>
                  <td class="text-end">Rp {{ number_format($ps_total,0,',','.') }}</td>
                  <td class="d-print-none"></td>
                </tr>
                <tr class="fw-semibold">
                  <td colspan="2">Total Produk</td>
                  <td class="text-end">Rp {{ number_format($prod_total,0,',','.') }}</td>
                  <td class="d-print-none"></td>
                </tr>
                <tr class="fw-semibold">
                  <td colspan="2">Total Penjualan</td>
                  <td class="text-end">Rp {{ number_format($sales_total,0,',','.') }}</td>
                  <td class="d-print-none"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card card-glass">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Pengeluaran ({{ $start_date->format('d-m-Y') }} — {{ $end_date->format('d-m-Y') }})</span>
          <span class="badge badge-soft-danger d-print-none">
            {{ $expenses->count() }} item
          </span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm m-0 align-middle table-modern">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th class="text-end">Jumlah</th>
                  <th class="text-end d-print-none"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($expenses as $e)
                  <tr>
                    <td>
                      {{ $e->timestamp_fmt ?? (isset($e->timestamp) && $e->timestamp ? $e->timestamp->format('d-m H:i') : '') }}
                    </td>
                    <td>{{ $e->category }}</td>
                    <td>{{ $e->description }}</td>
                    <td class="text-end amount-mono">
                      Rp {{ number_format($e->amount ?? 0,0,',','.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <button type="button"
                              class="btn btn-xs btn-outline-secondary bg-dark-soft text-light px-2 py-1"
                              onclick='return editExpense({{ $e->id }}, {!! json_encode($e->category) !!}, {!! json_encode($e->description ?? "") !!}, {{ (int)($e->amount ?? 0) }}, {!! json_encode(isset($e->timestamp) ? ($e->timestamp_fmt ?? $e->timestamp) : "") !!})'>
                        Edit
                      </button>
                      <form class="d-inline" method="POST"
                            action="{{ route('purchases.expenses.destroy', $e->id) }}"
                            onsubmit="return confirm('Hapus pengeluaran ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger bg-dark-soft text-light px-2 py-1">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted p-3">Belum ada data.</td>
                  </tr>
                @endforelse
              </tbody>
              <tfoot>
                <tr class="fw-semibold">
                  <td colspan="3">Total Pengeluaran</td>
                  <td class="text-end">Rp {{ number_format($expenses_total,0,',','.') }}</td>
                  <td class="d-print-none"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Rekap per periode --}}
  <div class="card card-glass mt-3">
    <div class="card-header d-flex align-items-center gap-3">
      <span>Rekap per Periode (dalam rentang terpilih)</span>
      <ul class="nav nav-pills ms-auto d-print-none" id="rekapTabs">
        <li class="nav-item">
          <button class="nav-link active" data-target="#tabHarian">Harian</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-target="#tabMingguan">Mingguan</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-target="#tabBulanan">Bulanan</button>
        </li>
      </ul>
    </div>
    <div class="card-body p-0">
      <div class="p-3">
        <div id="tabHarian" class="rekap-pane show">
          <div class="table-responsive">
            <table class="table table-sm align-middle table-modern">
              <thead>
                <tr>
                  <th>Periode</th>
                  <th class="text-end">PS</th>
                  <th class="text-end">Produk</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($daily_rows as $r)
                  <tr>
                    <td>{{ $r->label }}</td>
                    <td class="text-end">Rp {{ number_format($r->ps ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->prod ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->total ?? 0,0,',','.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div id="tabMingguan" class="rekap-pane">
          <div class="table-responsive">
            <table class="table table-sm align-middle table-modern">
              <thead>
                <tr>
                  <th>Periode</th>
                  <th class="text-end">PS</th>
                  <th class="text-end">Produk</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($weekly_rows as $r)
                  <tr>
                    <td>{{ $r->label }}</td>
                    <td class="text-end">Rp {{ number_format($r->ps ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->prod ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->total ?? 0,0,',','.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div id="tabBulanan" class="rekap-pane">
          <div class="table-responsive">
            <table class="table table-sm align-middle table-modern">
              <thead>
                <tr>
                  <th>Periode</th>
                  <th class="text-end">PS</th>
                  <th class="text-end">Produk</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($monthly_rows as $r)
                  <tr>
                    <td>{{ $r->label }}</td>
                    <td class="text-end">Rp {{ number_format($r->ps ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->prod ?? 0,0,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format($r->total ?? 0,0,',','.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Top produk & stok rendah --}}
  <div class="row g-3 mt-1">
    <div class="col-md-6">
      <div class="card card-glass">
        <div class="card-header">Top Produk (Qty) — Rentang terpilih</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm m-0 align-middle table-modern">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th class="text-center">Qty</th>
                  <th class="text-end">Omzet</th>
                </tr>
              </thead>
              <tbody>
                @forelse($top as $t)
                  <tr>
                    <td>{{ $t->name }}</td>
                    <td class="text-center">{{ $t->qty }}</td>
                    <td class="text-end">Rp {{ number_format($t->amount,0,',','.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-glass">
        <div class="card-header">Stok Rendah (≤ 5)</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm m-0 align-middle table-modern">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Stok</th>
                </tr>
              </thead>
              <tbody>
                @forelse($low_stock as $p)
                  <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->stock }} {{ $p->unit }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted">Tidak ada.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div> {{-- /.report-shell --}}
@endsection

@push('styles')
<style>
  /* Shell utama: panel futuristik gelap */
  .report-shell{
    position: relative;
    padding: 1.75rem 1.75rem 2.25rem;
    border-radius: 1.5rem;
    background:
      radial-gradient(circle at top left,#4f46e533,#0f172a),
      radial-gradient(circle at bottom right,#22c55e22,#020617 70%);
    box-shadow: 0 22px 50px rgba(15,23,42,0.75);
    overflow: hidden;
    color:#e5e7eb;
  }
  .report-shell::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:
      radial-gradient(circle at 10% 0,#ffffff33,transparent 55%),
      radial-gradient(circle at 90% 100%,#22c55e33,transparent 60%);
    opacity:.7;
    filter: blur(40px);
    pointer-events:none;
  }
  .report-shell > *{
    position:relative;
    z-index:1;
  }

  .report-chip-icon{
    width:32px;
    height:32px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7);
    color:#fff;
    box-shadow:0 8px 18px rgba(79,70,229,.65);
    font-size:15px;
  }

  /* Input glass dark */
  .report-filter .input-glass{
    border-radius:999px;
    background:rgba(15,23,42,.9);
    border:1px solid rgba(148,163,184,.5);
    padding:2px 10px;
    box-shadow:0 10px 25px rgba(15,23,42,.65);
    backdrop-filter:blur(18px);
  }
  .report-filter .form-control,
  .report-filter .form-select{
    color:#e5e7eb;
  }
  .report-filter .form-control:focus,
  .report-filter .form-select:focus{
    box-shadow:none;
  }

  .btn-light-soft{
    border-radius:999px;
    border:1px solid rgba(148,163,184,.4);
    background:rgba(15,23,42,.85);
    color:#e5e7eb;
  }
  .btn-light-soft:hover{
    background:rgba(31,41,55,.95);
  }
  .bg-dark-soft{
    background:rgba(15,23,42,.9);
  }

  /* Metric cards - dark glassmorphism */
  .metric-card{
    position:relative;
    border-radius:1.2rem;
    padding:1rem 1.1rem 1.1rem;
    background:linear-gradient(145deg,#020617,#020617);
    border:1px solid rgba(148,163,184,.55);
    box-shadow:0 18px 38px rgba(15,23,42,.9);
    backdrop-filter:blur(22px);
    overflow:hidden;
    color:#e5e7eb;
  }
  .metric-card::after{
    content:"";
    position:absolute;
    inset:auto -40% -40% auto;
    width:120px;
    height:120px;
    border-radius:999px;
    opacity:.55;
    filter:blur(22px);
  }
  .metric-card .metric-icon{
    width:34px;
    height:34px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#0f172a;
    background:#e0e7ff;
    margin-bottom:.5rem;
    font-size:18px;
  }
  .metric-card .metric-label{
    font-size:.75rem;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:#9ca3af;
    margin-bottom:.15rem;
    font-weight:600;
  }
  .metric-card .metric-value{
    font-size:1.45rem;
    font-weight:700;
    color:#f9fafb;
  }
  .metric-card .metric-caption{
    font-size:.75rem;
    color:#9ca3af;
    margin-top:.15rem;
  }

  .metric-blue::after   { background:radial-gradient(circle,#6366f1,#0ea5e9); }
  .metric-green::after  { background:radial-gradient(circle,#22c55e,#a3e635); }
  .metric-purple::after { background:radial-gradient(circle,#a855f7,#6366f1); }
  .metric-yellow::after { background:radial-gradient(circle,#facc15,#fb923c); }

  /* Cards & table futuristik gelap */
  .card-glass{
    border-radius:1.1rem;
    border:1px solid rgba(148,163,184,.45);
    background:radial-gradient(circle at top,#020617 0,#020617 55%,#020617 100%);
    box-shadow:0 18px 38px rgba(15,23,42,.9);
    backdrop-filter:blur(20px);
    color:#e5e7eb;
  }
  .card-glass .card-header{
    border-bottom:1px solid rgba(148,163,184,.45);
    background:linear-gradient(90deg,rgba(15,23,42,.95),rgba(31,41,55,.9));
    font-weight:600;
    color:#f9fafb;
  }

  .table-modern{
    color:#e5e7eb;
  }
  .table-modern thead{
    background:linear-gradient(90deg,#020617,#020617);
    color:#e5e7eb;
  }
  .table-modern thead th{
    border-bottom:none;
    font-size:.78rem;
    text-transform:uppercase;
    letter-spacing:.08em;
  }
  .table-modern tbody tr{
    background:rgba(15,23,42,.92);
    transition:background .15s ease, transform .08s ease, box-shadow .08s ease;
  }
  .table-modern tbody tr:nth-child(even){
    background:rgba(15,23,42,.86);
  }
  .table-modern tbody tr:hover{
    background:rgba(79,70,229,.35);
    transform:translateY(-1px);
    box-shadow:0 8px 20px rgba(15,23,42,.7);
  }
  .table-modern td,
  .table-modern th{
    border-color:rgba(55,65,81,.8);
  }
  .table-modern tfoot tr{
    background:rgba(15,23,42,.9);
  }

  .amount-mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  .badge-soft-primary{
    background:rgba(59,130,246,.22);
    color:#bfdbfe;
    border-radius:999px;
  }
  .badge-soft-danger{
    background:rgba(248,113,113,.22);
    color:#fecaca;
    border-radius:999px;
  }

  /* Range visibility + tabs */
  .rng-day, .rng-week, .rng-month, .rng-custom { display:none; }

  #rekapTabs .nav-link{
    border-radius:999px;
    padding:.25rem .85rem;
    font-size:.8rem;
    border:1px solid transparent;
    color:#e5e7eb;
    background:rgba(15,23,42,.9);
  }
  #rekapTabs .nav-link.active{
    background:linear-gradient(135deg,#6366f1,#a855f7);
    color:#fff;
    box-shadow:0 8px 18px rgba(79,70,229,.8);
    border-color:transparent;
  }

  .rekap-pane { display:none; }
  .rekap-pane.show { display:block; }

  /* Mobile tweaks */
  @media (max-width: 768px){
    .report-shell{
      padding:1.25rem 1rem 2rem;
      border-radius:1rem;
    }
    .metric-card{
      padding:.9rem .95rem 1rem;
      margin-bottom:.25rem;
    }
    .d-flex.align-items-center.justify-content-between.mb-3{
      flex-direction:column;
      align-items:flex-start !important;
      gap:.75rem;
    }
    .report-filter .col-6,
    .report-filter .col-12{
      flex:0 0 100%;
      max-width:100%;
    }
  }

  /* Print mode: kembali simple dan putih */
  @media print {
    .report-shell{
      background:#fff;
      box-shadow:none;
      color:#000;
    }
    .card-glass{
      background:#fff;
      box-shadow:none;
      border-color:#e5e7eb;
      color:#000;
    }
    .table-modern thead{
      background:#f3f4f6;
      color:#111827;
    }
    .table-modern tbody tr,
    .table-modern tfoot tr{
      background:#fff;
      box-shadow:none;
    }
    .d-print-none { display: none !important; }
    .card, .table { break-inside: avoid; }
  }
</style>
@endpush

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

/* fungsi editSale & editExpense */
function editSale(id, note, method, paid, total) {
  const newNote = prompt("Catatan:", note || "");
  if (newNote === null) return false;

  const newMethod = prompt("Metode Bayar (Tunai/QRIS/Transfer/Lainnya):", method || "Tunai");
  if (newMethod === null) return false;

  let newPaid = paid;
  if ((newMethod || '').toLowerCase() === 'tunai') {
    const val = prompt("Dibayar (angka):", paid || total);
    if (val === null) return false;
    newPaid = parseInt(val || "0");
  }

  const f = document.createElement('form');
  f.method = 'post';
  f.action = '/sales/' + id;

  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if(token) {
    const _token = document.createElement('input');
    _token.type='hidden'; _token.name='_token'; _token.value=token; f.appendChild(_token);
  }
  const _method = document.createElement('input');
  _method.type='hidden'; _method.name='_method'; _method.value='PUT'; f.appendChild(_method);

  [['note', newNote], ['payment_method', newMethod], ['paid_amount', newPaid], ['total_bill', total]]
    .forEach(([k,v])=>{
      const i = document.createElement('input'); i.type='hidden'; i.name=k; i.value=v; f.appendChild(i);
    });

  const nowIso = new Date().toISOString().slice(0,16);
  const _created = document.createElement('input');
  _created.type='hidden'; _created.name='created_at'; _created.value=nowIso; f.appendChild(_created);

  document.body.appendChild(f); f.submit();
  return false;
}

function editExpense(id, category, description, amount, ts) {
  const newCat  = prompt("Kategori:", category || "");
  if (newCat === null) return false;

  const newDesc = prompt("Deskripsi:", description || "");
  if (newDesc === null) return false;

  const newAmt  = prompt("Jumlah (Rp):", amount);
  if (newAmt === null) return false;

  const newTs   = prompt("Waktu (YYYY-MM-DDTHH:MM):", ts || "");
  if (newTs === null) return false;

  const f = document.createElement('form');
  f.method = 'post';
  f.action = '/purchases/expenses/' + id;

  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if(token) {
    const _token = document.createElement('input');
    _token.type='hidden'; _token.name='_token'; _token.value=token; f.appendChild(_token);
  }
  const _method = document.createElement('input');
  _method.type='hidden'; _method.name='_method'; _method.value='PUT'; f.appendChild(_method);

  [['category', newCat], ['description', newDesc], ['amount', newAmt], ['timestamp', newTs]]
    .forEach(([k,v])=>{
      const i = document.createElement('input'); i.type='hidden'; i.name=k; i.value=v; f.appendChild(i);
    });

  document.body.appendChild(f); f.submit();
  return false;
}
</script>
@endpush
