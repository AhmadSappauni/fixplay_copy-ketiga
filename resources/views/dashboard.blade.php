@extends('layouts.fixplay')

@section('title','Kasir Fixplay')
@section('page_title','Kasir Fixplay')

@push('styles')
<style>
  /* ====== DASHBOARD SHELL ====== */
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
    content:""; position:absolute; inset:-40%;
    background: radial-gradient(circle at 10% 0,#a855f755,transparent 52%), radial-gradient(circle at 90% 100%,#0ea5e955,transparent 60%);
    opacity:.7; filter: blur(40px); pointer-events:none;
  }
  .dash-shell > *{ position:relative; z-index:1; }

  .dash-chip-icon{
    width:36px; height:36px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7); color:#fff; box-shadow:0 10px 24px rgba(79,70,229,.7); font-size:18px;
  }

  .dash-title-text{ color:#f9fafb; }
  .dash-subtitle{ color:#9ca3af; font-size:.8rem; }

  .btn-soft-dark{
    border-radius:999px; border:1px solid rgba(148,163,184,.4); background:rgba(15,23,42,.75); color:#e5e7eb;
  }
  .btn-soft-dark:hover{ background:rgba(15,23,42,.95); color:#fff; }

  .btn-soft-primary{
    border-radius:999px; border:none; background:linear-gradient(135deg,#6366f1,#a855f7); color:#f9fafb;
    box-shadow:0 12px 24px rgba(79,70,229,.75);
  }
  .btn-soft-primary:hover{ filter:brightness(1.06); }

  /* ====== STAT CARDS (Kotak Ringkasan) ====== */
  .stat-card{
    position:relative; border-radius:1.25rem; padding:1.2rem;
    background: linear-gradient(145deg, rgba(30,41,59,0.8), rgba(15,23,42,0.9));
    border:1px solid rgba(148,163,184,.25);
    box-shadow:0 10px 30px rgba(0,0,0,.4); backdrop-filter:blur(10px); overflow:hidden;
  }
  .stat-card::after{
    content:""; position:absolute; inset:auto -45% -45% auto; width:120px; height:120px; border-radius:999px; opacity:.3; filter:blur(25px);
  }
  .stat-card .stat-label{ font-size:.75rem; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8; font-weight:700; }
  .stat-card .stat-sub{ font-size:.8rem; color:#cbd5e1; margin-top: 2px; }
  .stat-card .stat-value{ font-size:1.6rem; font-weight:800; margin-top:.6rem; color:#fff; letter-spacing: 0.5px; }
  
  .stat-card.primary::after{ background:radial-gradient(circle,#6366f1,#0ea5e9); }
  .stat-card.success::after{ background:radial-gradient(circle,#22c55e,#a3e635); }
  .stat-card.warning::after{ background:radial-gradient(circle,#f97316,#facc15); }

  /* ====== CARD GRAPH & TABLE (DARK THEME) ====== */
  /* Ini yang membuat background tabel jadi gelap, bukan putih */
  .card-trans {
    border-radius: 1.25rem;
    border: 1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.85));
    box-shadow: 0 20px 40px rgba(0,0,0,.6);
    backdrop-filter: blur(12px);
    overflow: hidden;
    color: #e5e7eb;
  }
  
  .card-trans .card-header {
    border-bottom: 1px solid rgba(148,163,184,.2);
    background: rgba(30, 41, 59, 0.4); /* Header sedikit lebih terang */
    color: #e5e7eb;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    font-size: .75rem;
    padding: 1rem 1.5rem;
  }

  .card-graph {
    border-radius: 1.25rem;
    border: 1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.85));
    color: #e5e7eb;
  }
  .card-graph .card-body { padding: 1.5rem; }

  .chart-wrapper{ position:relative; width:100%; min-height:240px; max-height:300px; }
  .chart-wrapper canvas{ width:100% !important; height:100% !important; }

  /* ====== TABEL NEON (GAYA SESI RENTAL) ====== */
  .table-neon {
    width: 100%;
    margin-bottom: 0;
    color: #cbd5e1;
  }
  /* Header Tabel */
  .table-neon thead th {
    background: rgba(15, 23, 42, 0.8);
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 1rem 1.5rem;
    white-space: nowrap;
  }
  /* Body Tabel */
  .table-neon tbody td {
    background: transparent; /* Transparan agar background card terlihat */
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 1rem 1.5rem;
    vertical-align: middle;
    font-size: 0.9rem;
    color: #f8fafc; /* Teks Putih Terang */
  }
  /* Hover Effect */
  .table-neon tbody tr:hover td {
    background: rgba(99, 102, 241, 0.08); /* Highlight ungu tipis */
  }

  .mono { font-family: 'Consolas', 'Monaco', monospace; color: #818cf8; font-weight: 600; }

    /* Tombol Aksi Kecil */
    /* Tombol Aksi Kecil – LEBIH RAPI DI TABEL */
  .btn-action-group {
    display: inline-flex;
    align-items: center;
    gap: .25rem;           /* jarak antar tombol */
  }

  .btn-action-group .btn {
    padding: 0.25rem 0.7rem;
    font-size: 0.7rem;
    border-radius: 999px;
    font-weight: 600;
  }

  /* warna disesuaikan biar konsisten */
  .btn-action-detail{
    color: #e2e8f0;
    border-color: #475569;
  }
  .btn-action-detail:hover{
    background:#475569;
    color:#fff;
  }

  .btn-action-edit{
    color:#fcd34d;
    border-color:#b45309;
  }
  .btn-action-edit:hover{
    background:#b45309;
    color:#fff;
  }

  .btn-action-delete{
    color:#fca5a5;
    border-color:#991b1b;
  }
  .btn-action-delete:hover{
    background:#991b1b;
    color:#fff;
  }

  /* Badge Hitungan */
  .badge-count {
    background: rgba(255,255,255,0.1);
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,0.2);
    font-size: 0.7rem;
    padding: 0.35rem 0.7rem;
    border-radius: 99px;
  }

  /* Responsive */
  @media (max-width: 992px){ .dash-shell{ padding:1.35rem 1.1rem 2rem; border-radius:1.1rem; } }
  @media (max-width: 768px){
    .dash-header-stack{ flex-direction:column; align-items:flex-start !important; gap:.7rem; }
    .dash-header-actions{ width:100%; justify-content:flex-end; }
    .stat-card{ margin-bottom:.5rem; }
    .chart-wrapper{ min-height:200px; max-height:260px; }
  }
  @media (max-width: 768px) {
    .btn-action-group .btn span.label-text {
      display: none;       /* sembunyikan tulisan, icon tetap */
    }
  }
  @media print{
    .dash-shell{ background:#fff; box-shadow:none; }
    .card-trans, .card-graph, .stat-card{ box-shadow:none; border-color:#e5e7eb; background:#fff; color:#111827; }
    .table-neon thead th{ background:#f3f4f6; color:#111827; }
    .table-neon tbody td{ color:#000; border-bottom:1px solid #e5e7eb; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="dash-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-4 dash-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="dash-chip-icon"><i class="bi bi-speedometer2"></i></span>
        <h4 class="m-0 fw-bold dash-title-text">Kasir Fixplay - Dashboard</h4>
      </div>
      <div class="dash-subtitle">Ringkasan performa pendapatan harian dan transaksi terakhir.</div>
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
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="stat-card primary h-100">
        <div class="stat-label">Pendapatan PS</div>
        <div class="stat-value mono">Rp {{ number_format($todayPs,0,',','.') }}</div>
        <div class="stat-sub">Total sesi rental hari ini</div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card success h-100">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value mono">Rp {{ number_format($todayTotal,0,',','.') }}</div>
        <div class="stat-sub">Gabungan PS + Produk</div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card warning h-100">
        <div class="stat-label">Pendapatan Produk</div>
        <div class="stat-value mono">Rp {{ number_format($todayProd,0,',','.') }}</div>
        <div class="stat-sub">Penjualan makanan & minuman</div>
      </div>
    </div>
  </div>

  {{-- GRAFIK PENDAPATAN --}}
  <div class="row g-4">
    <div class="col-12">
      <div class="card card-graph">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold text-white">Grafik Pendapatan</h5>
            <span class="badge-count">10 Hari Terakhir</span>
          </div>
          <div class="chart-wrapper">
            <canvas id="revChart"></canvas>
          </div>
          {{-- Data Chart Tersembunyi --}}
          <div id="chartPayload" data-labels='@json($chartLabels)' data-series='@json($chartData)' hidden></div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 1: RIWAYAT RENTAL PS --}}
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="card card-trans">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(99, 102, 241, 0.2);">
          <span class="text-white"><i class="bi bi-controller me-2"></i>Riwayat Rental PS</span>
          <span class="badge-count d-print-none">{{ count($rentalTx) }} DATA</span>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-sm align-middle table-neon mb-0">
              <thead style="position: sticky; top: 0; z-index: 10;">
                <tr>
                  <th style="width: 170px;">Tanggal</th>
                  <th>Detail Rental</th>
                  <th class="text-end" style="width: 150px;">Total</th>
                  <th class="text-end d-print-none" style="width: 180px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($rentalTx as $t)
                <tr>
                  <td class="text-secondary fw-semibold" style="font-size: 0.85rem;">{{ $t['date'] }}</td>
                  <td class="text-white fw-bold">{{ $t['title'] }}</td>
                  <td class="text-end mono text-info">Rp {{ number_format($t['total'],0,',','.') }}</td>
                  <td class="text-end d-print-none">
                    <div class="btn-action-group justify-content-end">
                      <a href="{{ route('sales.show', $t['id']) }}" class="btn btn-action-detail" title="Detail"><i class="bi bi-info-circle"></i></a>
                      <a href="{{ route('sales.edit', $t['id']) }}" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                      
                      @if(auth()->user() && auth()->user()->role === 'boss')
                        <form class="d-inline confirm-delete" method="POST" action="{{ route('sales.destroy', $t['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-action-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-secondary p-4">Belum ada transaksi rental.</td></tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 2: RIWAYAT PENJUALAN PRODUK (MAKANAN/MINUMAN) --}}
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="card card-trans">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(34, 197, 94, 0.15);">
          <span class="text-white"><i class="bi bi-cup-straw me-2"></i>Penjualan Produk (F&B)</span>
          <span class="badge-count d-print-none">{{ count($productTx) }} DATA</span>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-sm align-middle table-neon mb-0">
              <thead style="position: sticky; top: 0; z-index: 10;">
                <tr>
                  <th style="width: 170px;">Tanggal</th>
                  <th>Barang Terjual</th>
                  <th class="text-end" style="width: 150px;">Total</th>
                  <th class="text-end d-print-none" style="width: 180px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($productTx as $t)
                <tr>
                  <td class="text-secondary fw-semibold" style="font-size: 0.85rem;">{{ $t['date'] }}</td>
                  <td class="text-white">{{ $t['title'] }}</td>
                  <td class="text-end mono text-success">Rp {{ number_format($t['total'],0,',','.') }}</td>
                  <td class="text-end d-print-none">
                    <div class="btn-action-group justify-content-end">
                      <a href="{{ route('sales.show', $t['id']) }}" class="btn btn-action-detail" title="Detail"><i class="bi bi-info-circle"></i></a>
                      <a href="{{ route('sales.edit', $t['id']) }}" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                      
                      @if(auth()->user() && auth()->user()->role === 'boss')
                        <form class="d-inline confirm-delete" method="POST" action="{{ route('sales.destroy', $t['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-action-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-secondary p-4">Belum ada penjualan produk.</td></tr>
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

  let labels = [], series = [];
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
        borderWidth: 0,
        borderRadius: 4,
        backgroundColor: 'rgba(99, 102, 241, 0.85)', // Indigo
        hoverBackgroundColor: 'rgba(168, 85, 247, 1)', // Purple
        barThickness: 24
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, 
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#f8fafc',
          bodyColor: '#cbd5e1',
          borderColor: '#334155',
          borderWidth: 1,
          padding: 10,
          displayColors: false,
        }
      },
      scales: {
        x: {
          ticks: { color: '#94a3b8', font: { size: 11 } },
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: { color: '#94a3b8', font: { size: 11 } },
          grid: { color: 'rgba(51, 65, 85, 0.2)', borderDash: [4, 4] },
          border: { display: false }
        }
      }
    }
  });
})();
</script>
@endpush