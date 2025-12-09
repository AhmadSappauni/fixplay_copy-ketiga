@extends('layouts.fixplay')

@section('page_title', 'Kasir Fixplay')

@push('styles')
<style>
  /* ====== SHELL UTAMA FUTURISTIK ====== */
  .session-shell{
    position: relative;
    padding: 1.6rem 1.6rem 2.1rem;
    border-radius: 1.5rem;
    background:
      radial-gradient(circle at top left,#312e81 0,#020617 55%),
      radial-gradient(circle at bottom right,#22c55e33 0,transparent 60%);
    box-shadow: 0 22px 55px rgba(15,23,42,0.75);
    overflow: hidden;
    color:#e5e7eb;
  }
  .session-shell::before{
    content:""; position:absolute; inset:-40%;
    background: radial-gradient(circle at 10% 0,#a855f755,transparent 52%), radial-gradient(circle at 90% 100%,#0ea5e955,transparent 60%);
    opacity:.7; filter: blur(40px); pointer-events:none;
  }
  .session-shell > *{ position:relative; z-index:1; }

  .session-header-stack{ gap:.75rem; }
  .session-chip-icon{
    width:36px; height:36px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7); color:#fff; box-shadow:0 10px 24px rgba(79,70,229,.7); font-size:18px;
  }
  .session-title-text{ color:#f9fafb; }
  .session-subtitle{ color:#9ca3af; font-size:.8rem; }

  .btn-soft-dark{
    border-radius:999px; border:1px solid rgba(148,163,184,.4); background:rgba(15,23,42,.75); color:#e5e7eb;
  }
  .btn-soft-dark:hover{ background:rgba(15,23,42,.95); color:#fff; }

  /* ====== CARD GLASSMORPHIC ====== */
  .session-card{
    border-radius:1.25rem; border:1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.8));
    color:#e5e7eb; box-shadow:0 20px 38px rgba(0,0,0,.6); backdrop-filter:blur(12px); overflow: hidden;
  }
  .session-card-header{
    border-bottom:1px solid rgba(148,163,184,.2); background: rgba(30, 41, 59, 0.4);
    color:#e5e7eb; font-weight:700; letter-spacing:.06em; text-transform:uppercase; font-size:.75rem; padding: 1rem 1.25rem;
  }

  /* ====== FORM INPUT STYLE ====== */
  .session-shell .form-label{ font-size:.8rem; font-weight:600; color:#9ca3af; }
  .session-shell .form-select, .session-shell .form-control{
    background:rgba(15,23,42,.6); border-radius:.6rem; border:1px solid rgba(148,163,184,.3); color:#f3f4f6; font-size:.9rem;
  }
  .session-shell .form-control:focus, .session-shell .form-select:focus{
    border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,.25); background:rgba(2,6,23,.8); color:#fff;
  }
  .session-shell .form-control[readonly]{
    background:rgba(15,23,42,.4); color:#9ca3af; border-color:rgba(148,163,184,.15);
  }

  .btn-main-submit{
    width: 100%; border-radius:.75rem; padding:.7rem; font-weight:700;
    background:linear-gradient(135deg,#22c55e,#16a34a); border:none; color:#fff;
    box-shadow:0 4px 12px rgba(34,197,94,.4); transition: all 0.2s;
  }
  .btn-main-submit:hover{ filter:brightness(1.1); transform: translateY(-1px); box-shadow:0 6px 15px rgba(34,197,94,.5); }

  /* ====== TABEL FUTURISTIK ====== */
  .table-neon { width: 100%; margin-bottom: 0; border-collapse: separate; border-spacing: 0; color: #cbd5e1; }
  .table-neon thead th {
    background: rgba(15, 23, 42, 0.8); color: #94a3b8; font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 0.85rem 1rem; white-space: nowrap;
  }
  .table-neon tbody td {
    background: transparent; border-bottom: 1px solid rgba(148, 163, 184, 0.1); padding: 0.85rem 1rem;
    vertical-align: middle; font-size: 0.9rem;
  }
  .table-neon tbody tr:hover td { background: rgba(99, 102, 241, 0.08); }

  .amount-mono{ font-family:'Consolas','Monaco',monospace; font-weight:600; color:#818cf8; }
  .unit-name{ font-weight:700; color:#fff; font-size:.95rem; }
  .badge-type{
    background:#fbbf24; color:#1e293b; font-weight:800; font-size:.65rem; padding:2px 6px;
    border-radius:4px; margin-left:6px; vertical-align:middle; text-transform:uppercase;
    box-shadow:0 2px 5px rgba(251,191,36,.4);
  }
  .badge-addon{
    font-size:0.7rem; padding:2px 8px; border-radius:99px;
    background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#e2e8f0; margin-right:4px;
  }

  .session-history-scroll{ max-height:580px; overflow-y:auto; scrollbar-width:thin; scrollbar-color:#475569 #1e293b; }

  .btn-delete-xs{
    padding:4px 10px; font-size:0.75rem; border-radius:6px;
    background:rgba(239,68,68,0.15); color:#fca5a5; border:1px solid rgba(239,68,68,0.3); transition:all 0.2s;
  }
  .btn-delete-xs:hover{ background:rgba(239,68,68,0.9); color:#fff; border-color:transparent; }

  .btn-add-time{
    padding:4px 8px; font-size:0.7rem; border-radius:6px;
    background:rgba(34,197,94,0.15); color:#86efac; border:1px solid rgba(34,197,94,0.3); margin-right:4px; transition:all 0.2s;
  }
  .btn-add-time:hover{ background:rgba(34,197,94,0.9); color:#022c22; border-color:transparent; }

  /* Modal */
  .modal-glass .modal-content{
    background:radial-gradient(circle at top left,#1e1e2f,#0f1020); border:1px solid rgba(124,58,237,.3);
    box-shadow:0 0 30px rgba(0,0,0,.8); color:#e5e7eb; border-radius:1.25rem;
  }
  .modal-glass .modal-header{border-bottom:1px solid rgba(255,255,255,.08);}
  .modal-glass .modal-footer{border-top:1px solid rgba(255,255,255,.08);}
  .modal-glass .form-control, .modal-glass .form-select{
    background:rgba(15,23,42,.6); border:1px solid rgba(148,163,184,.3); color:#fff;
  }
  .modal-glass .btn-close-white{filter:invert(1) grayscale(100%) brightness(200%);}

  input[type="datetime-local"]::-webkit-calendar-picker-indicator{ filter:invert(1); }

  /* Animasi Badge Open */
  .badge-pulse { animation: pulse-animation 2s infinite; }
  @keyframes pulse-animation {
    0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
  }

  @media (max-width: 992px){ .session-shell{ padding:1.35rem 1rem 1.8rem; border-radius:1.1rem; } }
  @media (max-width: 768px){
    .session-header-stack{ flex-direction:column; align-items:flex-start !important; }
    .session-header-actions{ width:100%; justify-content:flex-end; }
    .session-card{ margin-bottom:.25rem; }
  }
  @media print{
    .session-shell{ background:#fff; box-shadow:none; }
    .session-card{ box-shadow:none; border:1px solid #ccc; background:#fff; color:#000; }
    .table-neon thead th{ background:#eee; color:#000; }
    .table-neon tbody td{ color:#000; border-bottom:1px solid #ccc; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')

<div class="session-shell">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none bg-success-subtle border-success text-success-emphasis" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-3 session-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="session-chip-icon"><i class="bi bi-controller"></i></span>
        <h4 class="m-0 fw-semibold session-title-text">Sesi PS</h4>
      </div>
      <div class="session-subtitle">Kelola sesi durasi tetap atau open billing (Bayar Nanti).</div>
    </div>
    <div class="d-flex align-items-center gap-2 d-print-none session-header-actions">
      <button type="button" class="btn btn-soft-dark" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i> Refresh</button>
    </div>
  </div>

  <div class="row g-3">
    {{-- FORM BUAT SESI (Kiri) --}}
    <div class="col-lg-5">
      <div class="card session-card h-100">
        <div class="card-body">
          <h6 class="mb-3 fw-bold text-uppercase small text-gray-300">Buat Sesi Baru</h6>
          <form method="post" action="{{ route('sessions.fixed') }}" id="fixedForm">
            @csrf

            <label class="form-label">Pilih Unit PS</label>
            <select class="form-select" name="ps_unit_id" id="unitSel" required>
              <option value="">-- pilih --</option>
              @foreach($units as $u)
                <option value="{{ $u->id }}">
                  {{ $u->name }} [{{ $u->type ?? 'PS4' }}]
                </option>
              @endforeach
            </select>

            <div class="mt-3">
              <label class="form-label">Tambahan Stik</label>
              <select class="form-select" id="extraSel" name="extra_controllers">
                @for($n=0;$n<=4;$n++)
                <option value="{{ $n }}">{{ $n }}</option>
                @endfor
              </select>
            </div>

            <label class="form-label mt-3">Waktu Mulai</label>
            <input type="datetime-local" class="form-control" name="start_time" id="startInput" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>

            <label class="form-label mt-3">Durasi Main</label>
            <select class="form-select" id="hoursSel" name="hours">
              <option value="open" class="fw-bold text-warning">★ OPEN BILLING (Bayar Nanti)</option>
              <option disabled>──────────────</option>
              
              {{-- 30 Menit Awal --}}
              <option value="0.5">30 menit</option>

              {{-- Loop dari 1 sampai 10 --}}
              @for($h = 1; $h <= 10; $h++)
                {{-- Jam Pas (Contoh: 1 jam) --}}
                <option value="{{ $h }}">{{ $h }} jam</option>
                
                {{-- Jam Setengah (Contoh: 1 jam 30 menit), value jadi 1.5 --}}
                @if($h < 10) {{-- Batasi agar tidak muncul 10 jam 30 menit --}}
                    <option value="{{ $h + 0.5 }}">{{ $h }} jam 30 menit</option>
                @endif
              @endfor
            </select>

            {{-- AREA PEMBAYARAN (Hanya muncul jika Fixed Duration) --}}
            <div id="paymentArea">
                <div class="mt-1 small text-end text-secondary">
                   Selesai jam: <span id="endLbl" class="fw-bold text-light">—:—</span>
                </div>

                <div class="mt-4 p-3 rounded-3" style="background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(148, 163, 184, 0.2);">
                    <label class="form-label text-success small text-uppercase fw-bold mb-1">Total Tagihan (Manual)</label>
                    <div class="input-group">
                       <span class="input-group-text bg-success border-success text-white">Rp</span>
                       <input type="number" name="bill" id="manualBillInput" class="form-control fs-5 fw-bold text-white" 
                              style="background: rgba(22, 163, 74, 0.1); border-color: #16a34a;" placeholder="0">
                    </div>
                    <div class="form-text text-secondary fst-italic" style="font-size: 0.75rem;">
                       *Masukkan harga borongan.
                    </div>
                </div>

                <div class="row mt-3 g-2">
                  <div class="col-md-4">
                    <label class="form-label">Metode</label>
                    <select name="payment_method" id="payMethod" class="form-select">
                      <option value="Tunai">Tunai</option>
                      <option value="QRIS">QRIS</option>
                      <option value="Transfer">Transfer</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Dibayar</label>
                    <input type="number" class="form-control" name="paid_amount" id="paidAmount" min="0">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Kembalian</label>
                    <input type="text" class="form-control" id="changeLbl" value="Rp 0" readonly>
                  </div>
                </div>
            </div>

            <button class="btn btn-main-submit mt-4" id="submitBtn">
              <i class="bi bi-play-circle me-1"></i> Mulai Sesi
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- DAFTAR SESI (Kanan) --}}
    <div class="col-lg-7">
      <div class="card session-card h-100">
        <div class="card-header session-card-header d-flex justify-content-between align-items-center">
          <span>Daftar Sesi</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive session-history-scroll">
            <table class="table table-sm table-hover m-0 align-middle table-neon">
              <thead>
                <tr>
                  <th>Unit</th>
                  <th>Mulai</th>
                  <th class="text-center">Status</th>
                  <th class="text-end">Tagihan</th>
                  <th class="text-end d-print-none">Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- 1. SESI OPEN BILLING (Running) --}}
                @foreach($active_sessions as $as)
                  <tr style="background: rgba(34, 197, 94, 0.05);">
                    <td class="text-white">
                      <div class="fw-bold">{{ $as->psUnit->name }}</div>
                      <span class="badge-type">{{ $as->psUnit->type ?? 'PS' }}</span>
                      @if($as->extra_controllers > 0)
                        <span class="badge badge-addon ms-1">+{{ $as->extra_controllers }} Stik</span>
                      @endif
                    </td>
                    <td class="small text-secondary">
                      <div>{{ \Carbon\Carbon::parse($as->start_time)->format('H:i') }}</div>
                      <div class="text-xs text-info update-timer" data-start="{{ $as->start_time }}">Running...</div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-success badge-pulse">OPEN</span>
                    </td>
                    <td class="text-end text-secondary small fst-italic">Bayar nanti</td>
                    <td class="text-end d-print-none">
                      <button class="btn btn-sm btn-danger fw-bold shadow-sm" style="font-size: 0.7rem;"
                              onclick="openStopModal('{{ $as->id }}', '{{ $as->psUnit->name }}', '{{ $as->start_time }}')">
                          <i class="bi bi-stop-circle-fill me-1"></i> STOP
                      </button>
                    </td>
                  </tr>
                @endforeach

                {{-- 2. SESI SELESAI (Riwayat) --}}
                @foreach($closed_sessions as $s)
                  <tr>
                    <td class="text-white">
                      <div class="fw-bold">{{ $s->psUnit->name ?? '-' }}</div>
                      <span class="badge-type">{{ $s->psUnit->type ?? 'PS4' }}</span>
                      @if(!empty($s->extra_controllers) && $s->extra_controllers > 0)
                        <span class="badge badge-addon ms-1">+Stik {{ $s->extra_controllers }}</span>
                      @endif
                      @if(!empty($s->arcade_controllers) && $s->arcade_controllers > 0)
                        <span class="badge badge-addon ms-1">Arcade {{ $s->arcade_controllers }}</span>
                      @endif
                    </td>
                    <td class="text-nowrap small text-secondary">
                      <div><i class="bi bi-play-fill me-1"></i> {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</div>
                      <div><i class="bi bi-stop-fill me-1"></i> {{ $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '-' }}</div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-25">
                        {{ intdiv($s->minutes ?? 0, 60) }}h @if(($s->minutes % 60) > 0) {{ $s->minutes % 60 }}m @endif
                      </span>
                    </td>
                    <td class="text-end mono text-info">Rp {{ number_format($s->bill ?? 0,0,',','.') }}</td>
                    <td class="text-end d-print-none">
                      <button class="btn-add-time"
                              onclick="openAddTimeModal(
                                '{{ $s->id }}',
                                '{{ $s->psUnit->name ?? 'Unit' }}'
                              )">
                        <i class="bi bi-plus-circle-fill"></i> Add
                      </button>

                      @if(auth()->user() && auth()->user()->role === 'admin')
                        <form class="d-inline confirm-delete" method="post"
                              action="{{ route('sessions.delete', ['sid' => $s->id]) }}"
                              data-confirm="Hapus riwayat sesi ini? Pendapatan di laporan akan ikut terhapus.">
                          @csrf @method('DELETE')
                          <button class="btn-delete-xs"><i class="bi bi-trash3-fill"></i></button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL STOP OPEN BILLING (BARU) --}}
<div class="modal fade modal-glass" id="stopOpenModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Stop & Bayar Sesi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-3">
        <p class="text-mono small mb-3">Unit: <strong id="stopUnitName" class="text-white"></strong></p>
        <div class="alert alert-dark border-secondary d-flex justify-content-between">
            <span>Durasi berjalan:</span>
            <strong id="stopDurationTxt" class="text-dark">...</strong>
        </div>

        <form id="stopOpenForm" method="post" action="{{ route('sessions.stop_open') }}">
          @csrf
          <input type="hidden" name="session_id" id="stopSessionId">

          <div class="mb-3">
             <label class="form-label text-success small text-uppercase fw-bold">Total Tagihan (Manual)</label>
             <div class="input-group">
                <span class="input-group-text bg-success border-success text-white">Rp</span>
                <input type="number" name="final_bill" id="stopBillInput" class="form-control fs-5 fw-bold text-white" 
                       style="background: rgba(22, 163, 74, 0.1); border-color: #16a34a;" placeholder="0" required>
             </div>
          </div>

          <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small text-secondary">Metode Bayar</label>
                <select name="payment_method" class="form-select text-white" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
                   <option value="Tunai">Tunai</option>
                   <option value="QRIS">QRIS</option>
                   <option value="Transfer">Transfer</option>
                </select>
              </div>
              <div class="col-6">
                 <label class="form-label small text-secondary">Uang Diterima</label>
                 <input type="number" id="stopPaid" name="paid_amount" class="form-control text-white" placeholder="0" required style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
              </div>
          </div>
          
          <div class="mb-3">
             <label class="form-label small text-secondary">Kembalian</label>
             <input type="text" id="stopChange" class="form-control text-white" value="Rp 0" readonly style="background: rgba(15,23,42,.4); border-color: rgba(148,163,184,.15); color: #9ca3af;">
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-main-submit w-auto px-4 py-2 bg-danger border-danger shadow-none">
                Stop & Cetak Struk
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- MODAL TAMBAH JAM (MANUAL) --}}
<div class="modal fade modal-glass" id="addTimeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Tambah Waktu Main</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <p class="text-mono small mb-3">
          Unit: <strong id="addTimeUnitName" class="text-white"></strong>
        </p>

        <form id="addTimeForm" method="post" action="{{ route('sessions.add_time') }}">
          @csrf
          <input type="hidden" name="session_id" id="addTimeSessionId">

          <div class="mb-3">
            <label class="form-label small text-secondary">Durasi Tambahan</label>
            <select name="hours" id="addTimeHours" class="form-select text-white" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
              <option value="0.5">30 Menit</option>
              @for($h=1; $h<=5; $h++)
                <option value="{{ $h }}">{{ $h }} Jam</option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
             <label class="form-label text-success small text-uppercase fw-bold">Biaya Tambahan (Manual)</label>
             <div class="input-group">
                <span class="input-group-text bg-success border-success text-white">Rp</span>
                <input type="number" name="add_bill" id="addTimeBillInput" 
                       class="form-control text-white fw-bold" 
                       style="background: rgba(22, 163, 74, 0.1); border-color: #16a34a;" 
                       placeholder="0" required>
             </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-secondary">Metode Bayar Tambahan</label>
            <select name="payment_method" class="form-select text-white" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
               <option value="Tunai">Tunai</option>
               <option value="QRIS">QRIS</option>
               <option value="Transfer">Transfer</option>
            </select>
          </div>
          
          <div class="mb-3">
             <label class="form-label small text-secondary">Uang Diterima</label>
             <input type="number" id="addTimePaid" name="paid_amount" class="form-control text-white" placeholder="0" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
          </div>
          
          <div class="mb-3">
             <label class="form-label small text-secondary">Kembalian</label>
             <input type="text" id="addTimeChange" class="form-control text-white" value="Rp 0" readonly style="background: rgba(15,23,42,.4); border-color: rgba(148,163,184,.15); color: #9ca3af;">
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-main-submit w-auto px-4 py-2">Simpan Tambahan</button>
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
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }

  // 1. TAMPILAN FORM (OPEN VS FIXED)
  const hoursSel = document.getElementById('hoursSel');
  const paymentArea = document.getElementById('paymentArea');
  const manualBillInput = document.getElementById('manualBillInput');
  const submitBtn = document.getElementById('submitBtn');

  function toggleForm(){
      if(hoursSel.value === 'open'){
          paymentArea.style.display = 'none';
          manualBillInput.required = false; // Tidak wajib bayar sekarang
          submitBtn.innerHTML = '<i class="bi bi-play-circle me-1"></i> Mulai Open Billing';
          submitBtn.classList.replace('btn-main-submit', 'btn-success'); 
      } else {
          paymentArea.style.display = 'block';
          manualBillInput.required = true; // Wajib bayar
          submitBtn.innerHTML = '<i class="bi bi-play-circle me-1"></i> Simpan & Cetak Struk';
          submitBtn.classList.replace('btn-success', 'btn-main-submit');
          updateEndTime();
      }
  }
  hoursSel.addEventListener('change', toggleForm);
  toggleForm(); // init

  // 2. JAM SELESAI (Hanya jika fixed duration)
  function updateEndTime(){
    const startVal = document.getElementById('startInput').value;
    const hours = parseFloat(hoursSel.value || '0');
    const endLbl = document.getElementById('endLbl');
    if(hoursSel.value === 'open') return;

    try{
      if(startVal && hours > 0){
        const dt = new Date(startVal);
        dt.setTime(dt.getTime() + Math.round(hours * 3600 * 1000));
        endLbl.textContent = String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0');
      } else {
        endLbl.textContent='—:—';
      }
    } catch(e){ endLbl.textContent='—:—'; }
  }
  document.getElementById('startInput').addEventListener('change', updateEndTime);

  // 3. KEMBALIAN (Fixed)
  function updateChange(){
    const method = (document.getElementById('payMethod')?.value||'Tunai').toLowerCase();
    const bill = parseInt(manualBillInput.value || '0', 10);
    const paid = parseInt(document.getElementById('paidAmount')?.value || '0', 10);
    const change = method==='tunai' ? Math.max(0, paid - bill) : 0;
    document.getElementById('changeLbl').value = 'Rp ' + fmtIDR(change);
  }
  manualBillInput.addEventListener('input', updateChange);
  document.getElementById('paidAmount').addEventListener('input', updateChange);
  document.getElementById('payMethod')?.addEventListener('change', updateChange);

  // 4. TIMER BERJALAN (Untuk sesi Open di tabel)
  setInterval(() => {
      document.querySelectorAll('.update-timer').forEach(el => {
          const start = new Date(el.dataset.start);
          const now = new Date();
          const diffMs = now - start;
          const diffHrs = Math.floor(diffMs / 3600000);
          const diffMins = Math.floor((diffMs % 3600000) / 60000);
          el.textContent = `${diffHrs}j ${diffMins}m berjalan`;
      });
  }, 60000); // Update tiap menit

})();

// === LOGIKA MODAL STOP OPEN BILLING ===
function openStopModal(id, unitName, startTime){
    document.getElementById('stopSessionId').value = id;
    document.getElementById('stopUnitName').textContent = unitName;
    
    // Hitung durasi kasar
    const start = new Date(startTime);
    const now = new Date();
    const diffMs = now - start;
    const diffHrs = (diffMs / 3600000).toFixed(1);
    document.getElementById('stopDurationTxt').textContent = `± ${diffHrs} Jam`;

    // Reset Form
    document.getElementById('stopBillInput').value = '';
    document.getElementById('stopPaid').value = '';
    document.getElementById('stopChange').value = 'Rp 0';

    const modal = new bootstrap.Modal(document.getElementById('stopOpenModal'));
    modal.show();
}

// Logic Kembalian di Modal Stop
const stopBillInput = document.getElementById('stopBillInput');
const stopPaidInput = document.getElementById('stopPaid');
function calcStopChange(){
    const bill = parseInt(stopBillInput.value || 0);
    const paid = parseInt(stopPaidInput.value || 0);
    const change = Math.max(0, paid - bill);
    document.getElementById('stopChange').value = 'Rp ' + (change).toLocaleString('id-ID');
}
stopBillInput.addEventListener('input', calcStopChange);
stopPaidInput.addEventListener('input', calcStopChange);

// === LOGIKA MODAL TAMBAH JAM (MANUAL) ===
function fmtIDRModal(n){ return (n||0).toLocaleString('id-ID'); }

function openAddTimeModal(sessionId, unitName) {
  document.getElementById('addTimeSessionId').value = sessionId;
  document.getElementById('addTimeUnitName').textContent = unitName;
  
  // Reset
  document.getElementById('addTimeBillInput').value = '';
  document.getElementById('addTimePaid').value = '';
  document.getElementById('addTimeChange').value = 'Rp 0';

  const calcChangeAdd = () => {
    const cost = parseFloat(document.getElementById('addTimeBillInput').value || 0);
    const paid = parseFloat(document.getElementById('addTimePaid').value || 0);
    const change = Math.max(0, paid - cost);
    document.getElementById('addTimeChange').value = 'Rp ' + fmtIDRModal(change);
  }

  document.getElementById('addTimeBillInput').oninput = calcChangeAdd;
  document.getElementById('addTimePaid').oninput = calcChangeAdd;

  const form = document.getElementById('addTimeForm');
  form.onsubmit = function(e) {
    const cost = parseFloat(document.getElementById('addTimeBillInput').value || 0);
    const paid = parseFloat(document.getElementById('addTimePaid').value || 0);

    if (cost <= 0) {
        e.preventDefault();
        alert("Isi biaya tambahan.");
        return false;
    }
    if (paid < cost) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Pembayaran kurang',
        background: '#0f172a', color: '#e5e7eb'
      });
      return false;
    }
  };

  const modalEl = document.getElementById('addTimeModal');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}
</script>
@endpush