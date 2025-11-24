@extends('layouts.fixplay')

@section('title','Kasir Fixplay')
@section('page_title','Kasir Fixplay')

@push('styles')
<style>
  /* ====== DASHBOARD SHELL : FUTURISTIC PANEL ====== */
  .dash-shell{
    position: relative;
    padding: 1.75rem 1.75rem 2.25rem;
    border-radius: 1.5rem;
    background:
      radial-gradient(circle at top left,#312e81 0,#020617 55%),
      radial-gradient(circle at bottom right,#22c55e33 0,transparent 60%);
    box-shadow: 0 22px 55px rgba(15,23,42,0.75);
    overflow: hidden;
    color:#e5e7eb;
  }
  .dash-shell::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:
      radial-gradient(circle at 10% 0,#a855f755,transparent 52%),
      radial-gradient(circle at 90% 100%,#0ea5e955,transparent 60%);
    opacity:.7;
    filter: blur(40px);
    pointer-events:none;
  }
  .dash-shell > *{
    position:relative;
    z-index:1;
  }

  .dash-chip-icon{
    width:36px;
    height:36px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7);
    color:#fff;
    box-shadow:0 10px 24px rgba(79,70,229,.7);
    font-size:18px;
  }

  .dash-title-text{
    color:#f9fafb;
  }
  .dash-subtitle{
    color:#9ca3af;
    font-size:.8rem;
  }

  .btn-soft-dark{
    border-radius:999px;
    border:1px solid rgba(148,163,184,.4);
    background:rgba(15,23,42,.75);
    color:#e5e7eb;
  }
  .btn-soft-dark:hover{
    background:rgba(15,23,42,.95);
    color:#fff;
  }

  .btn-soft-primary{
    border-radius:999px;
    border:none;
    background:linear-gradient(135deg,#6366f1,#a855f7);
    color:#f9fafb;
    box-shadow:0 12px 24px rgba(79,70,229,.75);
  }
  .btn-soft-primary:hover{
    filter:brightness(1.06);
  }

  /* ====== SUMMARY CARDS ====== */
  .stat-card{
    position:relative;
    border-radius:1.25rem;
    padding:1.05rem 1.2rem 1.1rem;
    background:rgba(15,23,42,.92);
    border:1px solid rgba(148,163,184,.45);
    box-shadow:0 18px 36px rgba(15,23,42,.9);
    backdrop-filter:blur(20px);
    overflow:hidden;
  }
  .stat-card::after{
    content:"";
    position:absolute;
    inset:auto -45% -45% auto;
    width:140px;
    height:140px;
    border-radius:999px;
    opacity:.45;
    filter:blur(18px);
  }
  .stat-card .stat-label{
    font-size:.78rem;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:#9ca3af;
    font-weight:600;
  }
  .stat-card .stat-sub{
    font-size:.78rem;
    color:#6b7280;
  }
  .stat-card .stat-value{
    font-size:1.6rem;
    font-weight:800;
    margin-top:.45rem;
    color:#e5e7eb;
  }
  .stat-card.primary::after{
    background:radial-gradient(circle,#6366f1,#0ea5e9);
  }
  .stat-card.success::after{
    background:radial-gradient(circle,#22c55e,#a3e635);
  }
  .stat-card.warning::after{
    background:radial-gradient(circle,#f97316,#facc15);
  }

  /* ====== GRAPH CARD ====== */
  .card-graph{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.5);
    background:radial-gradient(circle at top,#020617,#030712 55%,#020617);
    color:#e5e7eb;
    box-shadow:0 20px 38px rgba(15,23,42,.9);
  }
  .card-graph .card-header,
  .card-graph .card-body{
    background:transparent;
    border-color:rgba(15,23,42,.6);
  }

  /* batasi tinggi grafik supaya tidak memanjang */
  .chart-wrapper{
    position:relative;
    width:100%;
    min-height:220px;
    max-height:280px;
  }
  .chart-wrapper canvas{
    width:100% !important;
    height:100% !important;
  }

  /* ====== TABLE CARD ====== */
  .card-trans{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.45);
    background:linear-gradient(145deg,#020617,#020617 40%,#0b1120 100%);
    color:#e5e7eb;
    box-shadow:0 20px 40px rgba(15,23,42,.9);
  }
  .card-trans .card-header{
    border-bottom:1px solid rgba(30,64,175,.8);
    background:linear-gradient(90deg,#111827,#020617);
    color:#e5e7eb;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    font-size:.75rem;
  }

  .table-neon{
    margin-bottom:0;
    color:#d1d5db;
  }
  .table-neon thead th{
    background:linear-gradient(90deg,#020617,#030712);
    border-bottom:1px solid rgba(55,65,81,.9);
    font-size:.78rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#9ca3af;
  }
  .table-neon tbody tr{
    border-color:rgba(31,41,55,.9);
    transition:background .14s ease, transform .06s ease;
  }
  .table-neon tbody tr:hover{
    background:rgba(79,70,229,.18);
    transform:translateY(-1px);
  }
  .table-neon td,
  .table-neon th{
    border-color:rgba(31,41,55,.9);
  }

  .mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  /* ====== RESPONSIVE ====== */
  @media (max-width: 992px){
    .dash-shell{
      padding:1.35rem 1.1rem 2rem;
      border-radius:1.1rem;
    }
  }
  @media (max-width: 768px){
    .dash-header-stack{
      flex-direction:column;
      align-items:flex-start !important;
      gap:.7rem;
    }
    .dash-header-actions{
      width:100%;
      justify-content:flex-end;
    }
    .stat-card{
      margin-bottom:.25rem;
    }
    .chart-wrapper{
      min-height:200px;
      max-height:260px;
    }
  }

  @media print{
    .dash-shell{
      background:#fff;
      box-shadow:none;
    }
    .card-trans,.card-graph,.stat-card{
      box-shadow:none;
      border-color:#e5e7eb;
      background:#fff;
      color:#111827;
    }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="dash-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-3 dash-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="dash-chip-icon">
          <i class="bi bi-speedometer2"></i>
        </span>
        <h4 class="m-0 fw-semibold dash-title-text">Kasir Fixplay - Dashboard</h4>
      </div>
      <div class="dash-subtitle">
        Ringkasan performa pendapatan harian dan transaksi terakhir.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none dash-header-actions">
      <button type="button" class="btn btn-soft-dark" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
      <button type="button" class="btn btn-soft-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Cetak / PDF
      </button>
    </div>
  </div>

  {{-- RINGKASAN HARI INI --}}
  <div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-6">
      <div class="stat-card primary">
        <div class="stat-label">Ringkasan hari ini</div>
        <div class="stat-sub">Pendapatan PS (harian)</div>
        <div class="stat-value mono">
          Rp {{ number_format($todayPs,0,',','.') }}
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card success">
        <div class="stat-label">Ringkasan hari ini</div>
        <div class="stat-sub">Total pendapatan (PS + Produk)</div>
        <div class="stat-value mono">
          Rp {{ number_format($todayTotal,0,',','.') }}
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card warning">
        <div class="stat-label">Ringkasan hari ini</div>
        <div class="stat-sub">Pendapatan Produk (harian)</div>
        <div class="stat-value mono">
          Rp {{ number_format($todayProd,0,',','.') }}
        </div>
      </div>
    </div>
  </div>

  {{-- GRAFIK PENDAPATAN --}}
  <div class="row g-4 mt-1">
    <div class="col-12">
      <div class="card card-graph h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0 fw-bold">Grafik Pendapatan</h5>
            <span class="text-xs text-muted small">Periode terakhir</span>
          </div>

          <div class="chart-wrapper">
            <canvas id="revChart"></canvas>
          </div>

          <div id="chartPayload"
               data-labels='@json($chartLabels)'
               data-series='@json($chartData)'
               hidden></div>
        </div>
      </div>
    </div>
  </div>

  {{-- TRANSAKSI TERAKHIR --}}
  <div class="row g-4 mt-3">
    <div class="col-12">
      <div class="card card-trans">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <span>Transaksi Terakhir</span>
          <span class="badge bg-secondary-subtle text-xs text-dark d-print-none">
            {{ count($lastTx) }} transaksi
          </span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle table-neon">
              <thead>
              <tr>
                <th style="width: 180px;">Tanggal</th>
                <th>Transaksi</th>
                <th class="text-end" style="width: 180px;">Total</th>
                <th class="text-end d-print-none" style="width: 260px;">Aksi</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($lastTx as $t)
                <tr>
                  <td>{{ $t['date'] }}</td>
                  <td>{{ $t['title'] }}</td>
                  <td class="text-end mono">
                    Rp {{ number_format($t['total'],0,',','.') }}
                  </td>
                  <td class="text-end d-print-none">
                    {{-- Detail --}}
                    <a href="{{ route('sales.show', $t['id']) }}"
                      class="btn btn-sm btn-outline-secondary text-dark me-1">
                      Detail
                    </a>

                    {{-- Edit --}}
                    <a href="{{ route('sales.edit', $t['id']) }}"
                      class="btn btn-sm btn-outline-warning text-dark me-1">
                      Edit
                    </a>


                    {{-- Hapus --}}
                    <form class="d-inline"
                          method="POST"
                          action="{{ route('sales.destroy', $t['id']) }}"
                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted p-3">
                    Belum ada transaksi.
                  </td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div> {{-- /.dash-shell --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const payload = document.getElementById('chartPayload');
  if (!payload) return;

  let labels = [];
  let series = [];
  try{
    labels = JSON.parse(payload.dataset.labels || '[]');
    series = JSON.parse(payload.dataset.series || '[]');
  }catch(e){ return; }

  const ctx = document.getElementById('revChart');
  if (!ctx) return;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Pendapatan',
        data: series,
        borderWidth: 1.6,
        borderRadius: 8,
        backgroundColor: 'rgba(129,140,248,0.85)',
        hoverBackgroundColor: 'rgba(251,191,36,0.95)',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, // biar ikut tinggi .chart-wrapper
      scales: {
        x: {
          ticks: { color: '#9ca3af' },
          grid: { display:false }
        },
        y: {
          beginAtZero: true,
          ticks: { color: '#9ca3af' },
          grid: { color: 'rgba(55,65,81,0.55)' }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: '#020617',
          borderColor: '#4f46e5',
          borderWidth: 1,
          titleColor: '#e5e7eb',
          bodyColor: '#e5e7eb',
        }
      }
    }
  });
})();
</script>
@endpush
