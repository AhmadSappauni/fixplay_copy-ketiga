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
  .session-shell > *{
    position:relative;
    z-index:1;
  }

  .session-header-stack{
    gap:.75rem;
  }

  .session-chip-icon{
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

  .session-title-text{
    color:#f9fafb;
  }
  .session-subtitle{
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

  /* ====== CARD GLASSMORPHIC (Untuk Form & Tabel) ====== */
  .session-card{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.25);
    /* Background Gelap Transparan */
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.8));
    color:#e5e7eb;
    box-shadow:0 20px 38px rgba(0,0,0,.6);
    backdrop-filter:blur(12px);
    overflow: hidden; /* Agar border radius tabel aman */
  }
  .session-card-header{
    border-bottom:1px solid rgba(148,163,184,.2);
    background: rgba(30, 41, 59, 0.4);
    color:#e5e7eb;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    font-size:.75rem;
    padding: 1rem 1.25rem;
  }

  /* ====== FORM INPUT STYLE ====== */
  .session-shell .form-label{
    font-size:.8rem;
    font-weight:600;
    color:#9ca3af;
  }
  .session-shell .form-select,
  .session-shell .form-control{
    background:rgba(15,23,42,.6);
    border-radius:.6rem;
    border:1px solid rgba(148,163,184,.3);
    color:#f3f4f6;
    font-size:.9rem;
  }
  .session-shell .form-control:focus,
  .session-shell .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 2px rgba(99,102,241,.25);
    background:rgba(2,6,23,.8);
    color:#fff;
  }
  .session-shell .form-control[readonly]{
    background:rgba(15,23,42,.4);
    color:#9ca3af;
    border-color:rgba(148,163,184,.15);
  }

  .calc-line{
    color:#9ca3af;
    font-size:.85rem;
    display: flex; justify-content: space-between;
    margin-bottom: 4px;
  }
  .calc-line span{
    font-weight:700;
    color:#e5e7eb;
  }

  .btn-main-submit{
    width: 100%;
    border-radius:.75rem;
    padding:.7rem;
    font-weight:700;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    border:none;
    color:#fff;
    box-shadow:0 4px 12px rgba(34,197,94,.4);
    transition: all 0.2s;
  }
  .btn-main-submit:hover{
    filter:brightness(1.1);
    transform: translateY(-1px);
    box-shadow:0 6px 15px rgba(34,197,94,.5);
  }

  /* ====== TABEL FUTURISTIK (DARK MODE) ====== */
  .table-neon {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: #cbd5e1; /* Text abu terang */
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
    padding: 0.85rem 1rem;
    white-space: nowrap;
  }

  /* Body Tabel */
  .table-neon tbody td {
    background: transparent; /* Agar warna card tembus */
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 0.85rem 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
  }

  /* Efek Hover Baris */
  .table-neon tbody tr:hover td {
    background: rgba(99, 102, 241, 0.08); /* Highlight Ungu Tipis */
  }

  /* Typo & Badge */
  .amount-mono {
    font-family: 'Consolas', 'Monaco', monospace;
    font-weight: 600;
    color: #818cf8; /* Ungu muda untuk harga */
  }
  
  /* Badge untuk Unit & Tipe */
  .unit-name {
    font-weight: 700;
    color: #fff;
    font-size: 0.95rem;
  }
  .badge-type {
    background: #fbbf24; /* Kuning/Emas */
    color: #1e293b; /* Teks Hitam */
    font-weight: 800;
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 6px;
    vertical-align: middle;
    text-transform: uppercase;
    box-shadow: 0 2px 5px rgba(251, 191, 36, 0.4);
  }

  .badge-addon {
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 99px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: #e2e8f0;
    margin-right: 4px;
  }

  .session-history-scroll{
    max-height: 580px;
    overflow-y:auto;
    /* Scrollbar custom tipis */
    scrollbar-width: thin;
    scrollbar-color: #475569 #1e293b;
  }
  
  /* Tombol Hapus Kecil */
  .btn-delete-xs {
    padding: 4px 10px;
    font-size: 0.75rem;
    border-radius: 6px;
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.3);
    transition: all 0.2s;
  }
  .btn-delete-xs:hover {
    background: rgba(239, 68, 68, 0.9);
    color: white;
    border-color: transparent;
  }

  /* Style Tombol Tambah Jam Kecil (Fitur Baru) */
  .btn-add-time {
    padding: 4px 8px;
    font-size: 0.7rem;
    border-radius: 6px;
    background: rgba(34, 197, 94, 0.15);
    color: #86efac;
    border: 1px solid rgba(34, 197, 94, 0.3);
    margin-right: 4px;
    transition: all 0.2s;
  }
  .btn-add-time:hover {
    background: rgba(34, 197, 94, 0.9);
    color: #022c22;
    border-color: transparent;
  }

  /* [BARU] Style untuk kotak estimasi harga di modal */
  .price-display-box {
    background: rgba(34, 197, 94, 0.1); 
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 15px;
  }

  /* MODAL DARK GLASS STYLE (Untuk Modal Tambah Jam) */
  .modal-glass .modal-content {
    background: radial-gradient(circle at top left, #1e1e2f, #0f1020);
    border: 1px solid rgba(124,58,237,.3);
    box-shadow: 0 0 30px rgba(0,0,0,.8);
    color: #e5e7eb;
    border-radius: 1.25rem;
  }
  .modal-glass .modal-header { border-bottom: 1px solid rgba(255,255,255,.08); }
  .modal-glass .modal-footer { border-top: 1px solid rgba(255,255,255,.08); }
  .modal-glass .form-control, .modal-glass .form-select {
    background: rgba(15,23,42,.6); border: 1px solid rgba(148,163,184,.3); color: #fff;
  }
  .modal-glass .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

  @media (max-width: 992px){
    .session-shell{ padding:1.35rem 1rem 1.8rem; border-radius:1.1rem; }
  }
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
        <h4 class="m-0 fw-semibold session-title-text">Sesi PS (Durasi Tetap)</h4>
      </div>
      <div class="session-subtitle">Buat sesi PS dengan durasi tetap, hitung tagihan otomatis, dan pantau riwayat sesi.</div>
    </div>
    <div class="d-flex align-items-center gap-2 d-print-none session-header-actions">
      <button type="button" class="btn btn-soft-dark" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i> Refresh</button>
    </div>
  </div>

  <div class="row g-3">
    {{-- FORM BUAT SESI (Kiri) --}}
    <div class="col-lg-6">
      <div class="card session-card h-100">
        <div class="card-body">
          <h6 class="mb-3 fw-bold text-uppercase small text-gray-300">Buat sesi &amp; tagih</h6>
          <form method="post" action="{{ route('sessions.fixed') }}" id="fixedForm">
            @csrf

            <label class="form-label">Pilih Unit PS</label>
            <select class="form-select" name="ps_unit_id" id="unitSel" required>
              <option value="">-- pilih --</option>
              @foreach($units as $u)
                <option value="{{ $u->id }}" 
                        data-rate="{{ $u->hourly_rate }}" 
                        data-type="{{ $u->type ?? 'PS4' }}">
                  {{ $u->name }} [{{ $u->type ?? 'PS4' }}] — Rp {{ number_format($u->hourly_rate,0,',','.') }}/jam
                </option>
              @endforeach
            </select>

            {{-- ARCADE DIHAPUS, TINGGAL STIK TAMBAHAN --}}
            <div class="mt-3">
              <label class="form-label">Tambahan Stik</label>
              <select class="form-select" id="extraSel" name="extra_controllers">
                @for($n=0;$n<=4;$n++)<option value="{{ $n }}">{{ $n }}</option>@endfor
              </select>
            </div>

            <label class="form-label mt-3">Waktu Mulai</label>
            <input type="datetime-local" class="form-control" name="start_time" id="startInput" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>

            <label class="form-label mt-3">Durasi</label>
            {{-- DURASI HANYA SAMPAI 6 JAM --}}
            <select class="form-select" id="hoursSel" name="hours">
              <option value="0.5">30 menit</option>
              @for($h=1; $h<=6; $h++) 
                <option value="{{ $h }}">{{ $h }} jam</option>
              @endfor
            </select>

            <div class="row mt-3 g-2">
              <div class="col-md-4">
                <label class="form-label">Metode Bayar</label>
                <select name="payment_method" id="payMethod" class="form-select">
                  <option value="Tunai">Tunai</option>
                  <option value="QRIS">QRIS</option>
                  <option value="Transfer">Transfer</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Dibayar</label>
                <input type="number" class="form-control" name="paid_amount" id="paidAmount" value="" min="0">
              </div>
              <div class="col-md-4">
                <label class="form-label">Kembalian</label>
                <input type="text" class="form-control" id="changeLbl" value="Rp 0" readonly>
              </div>
            </div>

            <div class="mt-3 small">
              <div class="calc-line">Selesai jam: <span id="endLbl">—:—</span></div>
              <div class="calc-line">Total tagihan: <span id="billLbl" class="text-success fs-5">Rp 0</span></div>
            </div>

            <button class="btn btn-main-submit mt-3"><i class="bi bi-play-circle me-1"></i> Buat &amp; Tagih</button>
          </form>
        </div>
      </div>
    </div>

    {{-- RIWAYAT SESI (Kanan) --}}
    <div class="col-lg-6">
      <div class="card session-card h-100">
        <div class="card-header session-card-header d-flex justify-content-between align-items-center">
          <span>Riwayat Sesi (Terakhir 20)</span>
          <span class="badge bg-secondary-subtle text-dark d-print-none">{{ $closed_sessions->count() }} sesi</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive session-history-scroll">
            <table class="table table-sm table-hover m-0 align-middle table-neon">
              <thead>
                <tr>
                  <th>Unit / Paket</th>
                  <th class="text-nowrap">Waktu</th>
                  <th class="text-center">Durasi</th>
                  <th class="text-end">Tagihan</th>
                  <th class="text-end d-print-none">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($closed_sessions as $s)
                  <tr>
                    <td class="text-white">
                      <div class="fw-bold">{{ $s->psUnit->name ?? '-' }}</div>
                      <span class="badge-type">{{ $s->psUnit->type ?? 'PS4' }}</span>

                      @if(!empty($s->extra_controllers) && $s->extra_controllers > 0)
                        <span class="badge badge-addon ms-1">+Stik {{ $s->extra_controllers }}</span>
                      @endif
                      {{-- Arcade tetap ditampilkan di history jika ada data lama, tapi inputnya sudah dihapus --}}
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
                        {{ intdiv($s->minutes ?? 0, 60) }} jam
                        @if(($s->minutes % 60) > 0) {{ $s->minutes % 60 }} m @endif
                      </span>
                    </td>
                    <td class="text-end mono text-info">Rp {{ number_format($s->bill ?? 0,0,',','.') }}</td>
                    <td class="text-end d-print-none">
                      {{-- [UPDATED] Mengirim parameter rate, extra stik, dan arcade ke fungsi JS --}}
                      <button class="btn-add-time" 
                              onclick="openAddTimeModal(
                                '{{ $s->id }}', 
                                '{{ $s->psUnit->name ?? 'Unit' }}',
                                {{ $s->psUnit->hourly_rate ?? 10000 }},
                                {{ $s->extra_controllers ?? 0 }},
                                {{ $s->arcade_controllers ?? 0 }}
                              )">
                        <i class="bi bi-plus-circle-fill"></i> Jam
                      </button>

                      {{-- TOMBOL HAPUS: HANYA UNTUK ADMIN/BOS --}}
                      @if(auth()->user() && auth()->user()->role === 'admin')
                          <form class="d-inline confirm-delete" method="post"
                                action="{{ route('sessions.delete', ['sid' => $s->id]) }}"
                                data-confirm="Hapus riwayat sesi ini? Pendapatan di laporan akan ikut terhapus.">
                            @csrf @method('DELETE')
                            <button class="btn-delete-xs">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                          </form>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted p-3">Belum ada sesi.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL TAMBAH JAM (UPDATED) --}}
<div class="modal fade modal-glass" id="addTimeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Tambah Waktu Main</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <p class="text-muted small mb-3">
            Menambahkan durasi pada sesi unit: <strong id="addTimeUnitName" class="text-white"></strong><br>
            <span id="rateInfo" class="small fst-italic text-secondary"></span>
        </p>
        
        <form id="addTimeForm" method="post" action="{{ route('sessions.add_time') }}">
          @csrf
          <input type="hidden" name="session_id" id="addTimeSessionId">
          {{-- [BARU] Input Hidden untuk simpan tarif agar bisa dihitung JS --}}
          <input type="hidden" id="rawHourlyRate" value="0">
          <input type="hidden" id="rawExtraCtrl" value="0">
          <input type="hidden" id="rawArcadeCtrl" value="0">
          {{-- [BARU] Input Hidden untuk menyimpan total biaya tambahan hasil kalkulasi --}}
          <input type="hidden" id="rawAddCost" value="0">
          
          <div class="mb-3">
            <label class="form-label small text-muted">Durasi Tambahan</label>
            <select name="hours" id="addTimeHours" class="form-select text-white" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
              <option value="0.5">30 Menit</option>
              @for($h=1; $h<=5; $h++)
                <option value="{{ $h }}">{{ $h }} Jam</option>
              @endfor
            </select>
          </div>

          {{-- [BARU] ESTIMASI BIAYA --}}
          <div class="price-display-box">
             <div class="d-flex justify-content-between align-items-center">
                 <span class="small text-success">Biaya Tambahan:</span>
                 <span class="fw-bold fs-5 text-success" id="addTimeCostDisplay">Rp 0</span>
             </div>
             <div class="small text-muted text-end mt-1" style="font-size: 0.7rem;">
                *Tarif paket berlaku (Misal 3 Jam = 25rb)
             </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Metode Bayar Tambahan</label>
            <select name="payment_method" class="form-select text-white" style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
               <option value="Tunai">Tunai</option>
               <option value="QRIS">QRIS</option>
               <option value="Transfer">Transfer</option>
            </select>
          </div>
          
          <div class="mb-3">
             <label class="form-label small text-muted">Uang Diterima (Wajib)</label>
             <input type="number" id="addTimePaid" name="paid_amount" class="form-control text-white" placeholder="0" required style="background: rgba(15,23,42,.6); border-color: rgba(148,163,184,.3);">
          </div>
          
          <div class="mb-3">
             <label class="form-label small text-muted">Kembalian</label>
             <input type="text" id="addTimeChange" class="form-control text-white" value="Rp 0" readonly style="background: rgba(15,23,42,.4); border-color: rgba(148,163,184,.15); color: #9ca3af;">
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-main-submit w-auto px-4 py-2">Simpan & Perbarui Tagihan</button>
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
  // KONSTANTA HARGA (Arcade Dihapus dari UI, tapi tetap saya simpan konstanta jika ada data lama)
  const EX_RATE = 10000; 
  const KEY_TIMERS = 'fixplay.rental.timers';
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }
  
  // Fungsi Ambil Data Input (Tanpa Arcade)
  function getNumbers(){
    const unit = document.getElementById('unitSel');
    return {
      base: parseFloat(unit?.selectedOptions[0]?.dataset?.rate || '0'),
      type: unit?.selectedOptions[0]?.dataset?.type || 'PS4',
      extra: parseInt(document.getElementById('extraSel').value || '0', 10),
      // arcade di-hardcode 0 karena inputnya sudah dihapus
      arcade: 0, 
      hours: parseFloat(document.getElementById('hoursSel').value || '0'),
    };
  }
  function getUnitName(){ return (document.getElementById('unitSel')?.selectedOptions?.[0]?.textContent || '').split(' [')[0].trim(); }
  function getStartDT(){ const v=document.getElementById('startInput').value; return v?new Date(v):null; }
  
  // LocalStorage logic (Timer)
  function loadTimers(){ try{return JSON.parse(localStorage.getItem(KEY_TIMERS)||'[]')}catch(e){return[]} }
  function saveTimers(arr){ localStorage.setItem(KEY_TIMERS, JSON.stringify(arr)); }
  function saveTimer(){
    const unitName = getUnitName(), start = getStartDT(), hours = parseFloat(document.getElementById('hoursSel').value || '0');
    if (!unitName || !start || !(hours>0)) return;
    const endAt = new Date(start.getTime() + hours*60*60*1000).toISOString();
    const timers = loadTimers();
    timers.push({id:Date.now()+'-'+Math.random().toString(36).slice(2),unit:unitName,endAt:endAt,notified:false});
    saveTimers(timers.filter(t=>(Date.now()-new Date(t.endAt).getTime())<24*3600*1000));
  }

  let currentBill = 0;
  function roundToThousandCeil(n){ return Math.ceil(n/1000)*1000; }

  function updateCalc(){
    const start = document.getElementById('startInput').value;
    const {base, type, extra, hours} = getNumbers();
    const endLbl = document.getElementById('endLbl');
    try{
      if(start && hours>0){
        const dt = new Date(document.getElementById('startInput').value);
        dt.setTime(dt.getTime() + Math.round(hours * 3600 * 1000));
        endLbl.textContent = String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0');
      }else{ endLbl.textContent='—:—'; }
    }catch(e){ endLbl.textContent='—:—'; }

    // LOGIKA HARGA BARU (Diskon Paket - Termasuk > 6 Jam)
    const extrasCost = (extra * EX_RATE); 
    const totalExtras = extrasCost * hours;
    let unitBill = 0;

    if (type === 'PS4' && base === 10000) { 
        if (hours === 3) unitBill = 25000;
        else if (hours === 4) unitBill = 35000;
        else if (hours === 5) unitBill = 45000;
        else if (hours === 6) unitBill = 50000;
        else if (hours > 6) {
            // Logika > 6 Jam: Paket 6 Jam + Sisa Jam Normal
            const extraHours = hours - 6;
            unitBill = 50000 + (extraHours * 10000);
        }
        else unitBill = base * hours;
    } else {
        unitBill = base * hours;
    }

    let rawBill = unitBill + totalExtras;
    if (hours === 0.5) rawBill = roundToThousandCeil(rawBill);
    else rawBill = Math.round(rawBill);

    currentBill = rawBill || 0;
    document.getElementById('billLbl').textContent = 'Rp ' + fmtIDR(currentBill);
    updateChange();
  }

  function updateChange(){
    const method = (document.getElementById('payMethod')?.value||'Tunai').toLowerCase();
    const paid = parseInt(document.getElementById('paidAmount')?.value||'0',10);
    const change = method==='tunai' ? Math.max(0, paid-(currentBill||0)) : 0;
    const out = document.getElementById('changeLbl');
    if(out) out.value = 'Rp ' + fmtIDR(change);
  }

  const formEl = document.getElementById('fixedForm');
  if(formEl){
    formEl.addEventListener('submit', function(e){
      const method = (document.getElementById('payMethod')?.value||'Tunai').toLowerCase();
      const paid = parseInt(document.getElementById('paidAmount')?.value||'0',10);
      // Ambil bill langsung dari kalkulasi
      const currentBill = parseInt(document.getElementById('billLbl').textContent.replace(/[^0-9]/g,''));
      
      if (method==='tunai' && paid<currentBill){ e.preventDefault(); alert('Pembayaran tunai kurang.'); return; }
      try { saveTimer(); } catch(err){}
    });
  }

  // Arcade dihapus dari listener
  ['unitSel','extraSel','hoursSel','startInput'].forEach(id=>{
    const el=document.getElementById(id);
    if(el){ el.addEventListener('change', updateCalc); el.addEventListener('input', updateCalc); }
  });
  document.getElementById('payMethod')?.addEventListener('change', updateChange);
  document.getElementById('paidAmount')?.addEventListener('input', updateChange);
  updateCalc();
})();

// === [UPDATED] LOGIKA BARU MODAL TAMBAH JAM ===
function fmtIDRModal(n){ return (n||0).toLocaleString('id-ID'); }

function openAddTimeModal(sessionId, unitName, hourlyRate, extraCtrl, arcadeCtrl) {
    document.getElementById('addTimeSessionId').value = sessionId;
    document.getElementById('addTimeUnitName').textContent = unitName;
    
    // Set variable hidden
    document.getElementById('rawHourlyRate').value = hourlyRate;
    document.getElementById('rawExtraCtrl').value = extraCtrl;
    document.getElementById('rawArcadeCtrl').value = arcadeCtrl;

    document.getElementById('rateInfo').textContent = `Rate: Rp ${fmtIDRModal(hourlyRate)}/jam`;

    // Kalkulasi Realtime
    const calcAddOn = () => {
        const h = parseFloat(document.getElementById('addTimeHours').value || 0);
        const rate = parseFloat(document.getElementById('rawHourlyRate').value || 0);
        const ex = parseInt(document.getElementById('rawExtraCtrl').value || 0);
        const arc = parseInt(document.getElementById('rawArcadeCtrl').value || 0);

        // --- LOGIKA PAKET KHUSUS TAMBAH WAKTU ---
        // Jika tarif unit 10.000 (Regular), gunakan skema paket untuk jam tambahan (mandiri)
        let unitCost = 0;
        
        if (rate === 10000) {
            if (h === 3) unitCost = 25000;
            else if (h === 4) unitCost = 35000;
            else if (h === 5) unitCost = 45000;
            else if (h === 6) unitCost = 50000;
            else if (h > 6) {
                // Paket 6 jam (50k) + sisa jam dikali tarif normal
                unitCost = 50000 + ((h - 6) * rate);
            } else {
                // Untuk durasi < 3 jam (0.5, 1, 2), tarif normal (flat)
                // 1 jam = 10k, 2 jam = 20k
                unitCost = h * rate;
            }
        } else {
            // Untuk unit non-reguler (misal VIP), gunakan tarif flat per jam
            unitCost = h * rate;
        }

        // Biaya tambahan alat (Stik/Arcade) selalu flat per jam
        const extrasCost = (ex * 10000 * h) + (arc * 15000 * h);
        
        // Total Biaya Tambahan
        let total = unitCost + extrasCost;

        // Pembulatan 30 menit (jika hasil pecahan)
        if(h === 0.5) total = Math.ceil(total/1000)*1000;
        else total = Math.round(total);

        // Update nilai ke input hidden untuk validasi
        document.getElementById('rawAddCost').value = total;
        document.getElementById('addTimeCostDisplay').textContent = 'Rp ' + fmtIDRModal(total);

        // Hitung ulang kembalian setiap kali biaya berubah
        calcChange();
    };

    // Fungsi Hitung Kembalian
    const calcChange = () => {
        const cost = parseFloat(document.getElementById('rawAddCost').value || 0);
        const paid = parseFloat(document.getElementById('addTimePaid').value || 0);
        
        // Hitung kembalian, minimal 0
        const change = Math.max(0, paid - cost);
        
        document.getElementById('addTimeChange').value = 'Rp ' + fmtIDRModal(change);
    }

    // Attach Listener
    const sel = document.getElementById('addTimeHours');
    sel.onchange = calcAddOn;
    
    // Attach Listener untuk input uang
    const paidInput = document.getElementById('addTimePaid');
    paidInput.oninput = calcChange;
    
    // Jalankan sekali saat buka
    calcAddOn();

    // VALIDASI PEMBAYARAN PADA SUBMIT
    const form = document.getElementById('addTimeForm');
    form.onsubmit = function(e) {
        const cost = parseFloat(document.getElementById('rawAddCost').value || 0);
        const paid = parseFloat(document.getElementById('addTimePaid').value || 0);

        if (paid < cost) {
            e.preventDefault();
            alert('Pembayaran gagal: Uang diterima kurang dari biaya tambahan sebesar Rp ' + fmtIDRModal(cost));
            return false;
        }
    };
    
    const modalEl = document.getElementById('addTimeModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}
</script>
@endpush