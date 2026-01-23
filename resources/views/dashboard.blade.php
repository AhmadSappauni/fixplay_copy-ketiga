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

  /* ====== STAT CARDS ====== */
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

  /* ====== CARD GRAPH & TABLE ====== */
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
    background: rgba(30, 41, 59, 0.4); 
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

  /* ====== TABEL NEON ====== */
  .table-neon { width: 100%; margin-bottom: 0; color: #cbd5e1; }
  .table-neon thead th {
    background: rgba(15, 23, 42, 0.8); color: #94a3b8; font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 1rem 1.5rem; white-space: nowrap;
  }
  .table-neon tbody td {
    background: transparent; border-bottom: 1px solid rgba(148, 163, 184, 0.1); padding: 1rem 1.5rem;
    vertical-align: middle; font-size: 0.9rem; color: #f8fafc;
  }
  .table-neon tbody tr:hover td { background: rgba(99, 102, 241, 0.08); }

  .mono { font-family: 'Consolas', 'Monaco', monospace; color: #818cf8; font-weight: 600; }

  /* Tombol Aksi */
  .btn-action-group { display: inline-flex; align-items: center; gap: .25rem; }
  .btn-action-group .btn { padding: 0.25rem 0.7rem; font-size: 0.7rem; border-radius: 999px; font-weight: 600; }

  .btn-action-detail{ color: #e2e8f0; border-color: #475569; }
  .btn-action-detail:hover{ background:#475569; color:#fff; }

  .btn-action-edit{ color:#fcd34d; border-color:#b45309; }
  .btn-action-edit:hover{ background:#b45309; color:#fff; }

  .btn-action-delete{ color:#fca5a5; border-color:#991b1b; }
  .btn-action-delete:hover{ background:#991b1b; color:#fff; }

  /* Tombol Request Hapus (Kuning) */
  .btn-action-request{ color:#fde047; border-color:#ca8a04; }
  .btn-action-request:hover{ background:#ca8a04; color:#fff; }

  .badge-count {
    background: rgba(255,255,255,0.1); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.2);
    font-size: 0.7rem; padding: 0.35rem 0.7rem; border-radius: 99px;
  }

  /* MODAL GLASS STYLE (Untuk Request Hapus) */
  .modal-glass .modal-content {
    background: radial-gradient(circle at top left, #1e1e2f, #0f1020);
    border: 1px solid rgba(124,58,237,.3);
    box-shadow: 0 0 30px rgba(0,0,0,.8); color: #e5e7eb; border-radius: 1.25rem;
  }
  .modal-glass .modal-header { border-bottom: 1px solid rgba(255,255,255,.08); }
  .modal-glass .modal-footer { border-top: 1px solid rgba(255,255,255,.08); }
  .modal-glass .form-control {
    background: rgba(2, 6, 23, 0.8); border: 1px solid rgba(148,163,184,.2); color: #f1f5f9;
  }
  .modal-glass .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

  @media (max-width: 992px){ .dash-shell{ padding:1.35rem 1.1rem 2rem; border-radius:1.1rem; } }
  @media (max-width: 768px){
    .dash-header-stack{ flex-direction:column; align-items:flex-start !important; gap:.7rem; }
    .dash-header-actions{ width:100%; justify-content:flex-end; }
    .stat-card{ margin-bottom:.5rem; }
    .chart-wrapper{ min-height:200px; max-height:260px; }
    .btn-action-group .btn span.label-text { display: none; }
  }
</style>
@endpush

@section('page_content')
<div class="dash-shell">

  {{-- ALERT SUCCESS --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show bg-success-subtle border-success text-success-emphasis mb-4" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- [BARU] NOTIFIKASI REQUEST HAPUS (KHUSUS BOSS) --}}
  @if(isset($pendingRequests) && count($pendingRequests) > 0)
    <div class="alert alert-warning border-warning border-opacity-50 mb-4" role="alert" style="background: rgba(234, 179, 8, 0.1);">
        <h5 class="alert-heading fw-bold text-warning fs-6 mb-3">
            <i class="bi bi-bell-fill me-2"></i> Permintaan Penghapusan Data ({{ count($pendingRequests) }})
        </h5>
        
        <div class="d-flex flex-column gap-2">
            @foreach($pendingRequests as $req)
                <div class="p-3 rounded border border-warning border-opacity-25" style="background: rgba(0,0,0,0.2);">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-warning text-dark me-2">{{ $req->user->name ?? 'Karyawan' }}</span>
                            <span class="text-secondary small">{{ $req->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 0.65rem;">
                            {{ strtoupper($req->target_table) }} #{{ $req->target_id }}
                        </span>
                    </div>
                    
                    <div class="text-white fw-semibold mb-1">{{ $req->description }}</div>
                    <div class="text-warning small fst-italic mb-3">"{{ $req->reason }}"</div>

                    <div class="d-flex gap-2">
                        {{-- Approve --}}
                        <form action="{{ route('deletion.handle', $req->id) }}" method="POST">
                            @csrf 
                            <input type="hidden" name="action" value="approve">
                            <button class="btn btn-sm btn-success px-3" onclick="return confirm('Yakin setujui hapus data ini?')">
                                <i class="bi bi-check-lg me-1"></i> Setujui
                            </button>
                        </form>

                        {{-- Reject --}}
                        <form action="{{ route('deletion.handle', $req->id) }}" method="POST">
                            @csrf 
                            <input type="hidden" name="action" value="reject">
                            <button class="btn btn-sm btn-danger px-3">
                                <i class="bi bi-x-lg me-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  @endif

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
        <div class="stat-value mono {{ $todayPs < 0 ? 'text-danger' : '' }}">
            Rp {{ number_format($todayPs,0,',','.') }}
        </div>
        <div class="stat-sub">Total sesi rental hari ini</div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card success h-100">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value mono {{ $todayTotal < 0 ? 'text-danger' : '' }}">
            Rp {{ number_format($todayTotal,0,',','.') }}
        </div>
        <div class="stat-sub">Gabungan PS + Produk</div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card warning h-100">
        <div class="stat-label">Pendapatan Produk</div>
        <div class="stat-value mono {{ $todayProd < 0 ? 'text-danger' : '' }}">
            Rp {{ number_format($todayProd,0,',','.') }}
        </div>
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
          <div id="chartPayload" data-labels='@json($chartLabels)' data-series='@json($chartData)' hidden></div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 1: RIWAYAT RENTAL PS --}}
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="card card-trans">
        {{-- HEADER TABEL RENTAL --}}
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(99, 102, 241, 0.2);">
          <span class="text-white"><i class="bi bi-controller me-2"></i>Riwayat Rental PS</span>
          
          <div class="d-flex align-items-center gap-2">
              <span class="badge-count d-print-none">{{ count($rentalTx) }} DATA</span>
              
              {{-- [BARU] TOMBOL MODE HAPUS --}}
              <button type="button" class="btn btn-sm btn-outline-light d-print-none" 
                      onclick="toggleDeleteMode(this)" 
                      style="font-size: 0.65rem; border-radius: 99px; padding: 2px 10px; opacity: 0.8;">
                  <i class="bi bi-trash"></i> Hapus
              </button>
          </div>
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
                      
                      {{-- LOGIKA TOMBOL HAPUS (SEMBUNYI DEFAULT) --}}
                      @if(auth()->user()->role === 'boss')
                        {{-- BOSS: Hapus Langsung --}}
                        <form class="d-inline confirm-delete btn-mode-hapus d-none" method="POST" action="{{ route('sales.destroy', $t['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-action-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                      @else
                        {{-- KARYAWAN: Request Hapus --}}
                        <button type="button" class="btn btn-action-request btn-mode-hapus d-none" title="Request Hapus"
                                onclick="openRequestModal('sales', {{ $t['id'] }}, '{{ $t['title'] }}')">
                            <i class="bi bi-exclamation-triangle"></i>
                        </button>
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

  {{-- CARD 2: RIWAYAT PENJUALAN PRODUK --}}
  <div class="row g-4 mt-2">
    <div class="col-12">
      <div class="card card-trans">
        {{-- HEADER TABEL PRODUK --}}
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(34, 197, 94, 0.15);">
          <div class="d-flex align-items-center gap-2">
             <span class="text-white"><i class="bi bi-cup-straw me-2"></i>Penjualan Produk (F&B)</span>
             <span class="badge-count d-print-none">{{ count($productTx) }} DATA</span>
          </div>

          <div class="d-flex align-items-center gap-2 d-print-none">
             {{-- [BARU] TOMBOL MODE HAPUS --}}
             <button type="button" class="btn btn-sm btn-outline-light me-2" 
                     onclick="toggleDeleteMode(this)" 
                     style="font-size: 0.65rem; border-radius: 99px; padding: 2px 10px; opacity: 0.8;">
                 <i class="bi bi-trash"></i> Hapus
             </button>

             <input type="text" id="dashSearchInput" 
                    class="form-control form-control-sm text-white" 
                    style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); width: 150px; font-size: 0.75rem;" 
                    placeholder="Cari produk...">
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-sm align-middle table-neon mb-0" id="dashProductTable">
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
                <tr class="dash-prod-row">
                  <td class="text-secondary fw-semibold" style="font-size: 0.85rem;">{{ $t['date'] }}</td>
                  <td class="text-white searchable-dash">{{ $t['title'] }}</td>
                  <td class="text-end mono text-success">Rp {{ number_format($t['total'],0,',','.') }}</td>
                  <td class="text-end d-print-none">
                    <div class="btn-action-group justify-content-end">
                      <a href="{{ route('sales.show', $t['id']) }}" class="btn btn-action-detail" title="Detail"><i class="bi bi-info-circle"></i></a>
                      <a href="{{ route('sales.edit', $t['id']) }}" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                      
                      {{-- LOGIKA TOMBOL HAPUS (SEMBUNYI DEFAULT) --}}
                      @if(auth()->user()->role === 'boss')
                        {{-- BOSS: Hapus Langsung --}}
                        <form class="d-inline confirm-delete btn-mode-hapus d-none" method="POST" action="{{ route('sales.destroy', $t['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-action-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                      @else
                        {{-- KARYAWAN: Request Hapus --}}
                        <button type="button" class="btn btn-action-request btn-mode-hapus d-none" title="Request Hapus"
                                onclick="openRequestModal('sales', {{ $t['id'] }}, '{{ $t['title'] }}')">
                            <i class="bi bi-exclamation-triangle"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-secondary p-4">Belum ada penjualan produk.</td></tr>
              @endforelse
              
              <tr id="dashNoResult" style="display: none;">
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

</div>

{{-- MODAL REQUEST HAPUS (KHUSUS KARYAWAN) --}}
<div class="modal fade modal-glass" id="requestDeletionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>Request Hapus Data
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <form action="{{ route('deletion.store') }}" method="POST">
          @csrf
          <input type="hidden" name="target_table" id="reqTargetTable">
          <input type="hidden" name="target_id" id="reqTargetId">

          <p class="text-secondary small mb-3">
            Anda tidak memiliki akses untuk menghapus data ini secara langsung. 
            Silakan kirim permintaan ke Boss.
          </p>

          <div class="mb-3">
            <label class="form-label small text-muted">Data yang akan dihapus:</label>
            <input type="text" id="reqDataTitle" class="form-control text-white" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Alasan Penghapusan (Wajib)</label>
            <textarea name="reason" class="form-control text-white" rows="3" required placeholder="Contoh: Salah input nominal / Pelanggan cancel"></textarea>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning btn-sm fw-bold">Kirim Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

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
        backgroundColor: 'rgba(99, 102, 241, 0.85)',
        hoverBackgroundColor: 'rgba(168, 85, 247, 1)',
        barThickness: 24
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, 
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { display: false } },
        y: { beginAtZero: true, ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { color: 'rgba(51, 65, 85, 0.2)', borderDash: [4, 4] }, border: { display: false } }
      }
    }
  });
})();

// Script Pencarian Real-time Dashboard
document.getElementById('dashSearchInput')?.addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#dashProductTable .dash-prod-row');
    let hasVisible = false;
    rows.forEach(function(row) {
        let text = row.querySelector('.searchable-dash').textContent.toLowerCase();
        if (text.includes(filter)) { row.style.display = ''; hasVisible = true; } else { row.style.display = 'none'; }
    });
    let noRes = document.getElementById('dashNoResult');
    if (noRes) noRes.style.display = hasVisible ? 'none' : 'table-row';
});

// Function Buka Modal Request Hapus
function openRequestModal(table, id, title) {
    document.getElementById('reqTargetTable').value = table;
    document.getElementById('reqTargetId').value = id;
    document.getElementById('reqDataTitle').value = title;
    
    new bootstrap.Modal(document.getElementById('requestDeletionModal')).show();
}

// [BARU] Fungsi Toggle Mode Hapus
function toggleDeleteMode(btn) {
    // 1. Cari Card terdekat
    let card = btn.closest('.card');
    
    // 2. Cari semua tombol hapus/request di dalam card itu
    let targets = card.querySelectorAll('.btn-mode-hapus');
    
    // 3. Toggle visibility (muncul/sembunyi)
    targets.forEach(el => {
        el.classList.toggle('d-none');
    });

    // 4. Ubah tampilan tombol toggle
    if (btn.classList.contains('btn-outline-light')) {
        // Mode Aktif (Tombol jadi merah)
        btn.classList.remove('btn-outline-light');
        btn.classList.add('btn-danger');
        btn.innerHTML = '<i class="bi bi-x-circle me-1"></i> Batal';
    } else {
        // Mode Non-Aktif (Kembali normal)
        btn.classList.add('btn-outline-light');
        btn.classList.remove('btn-danger');
        btn.innerHTML = '<i class="bi bi-trash me-1"></i> Hapus';
    }
}
</script>
@endpush