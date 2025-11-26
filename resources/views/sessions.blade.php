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

  /* ====== CARD GLASSMORPHIC ====== */
  .session-card{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.45);
    background:radial-gradient(circle at top,#020617,#030712 55%,#020617);
    color:#e5e7eb;
    box-shadow:0 20px 38px rgba(15,23,42,.9);
    backdrop-filter:blur(18px);
  }
  .session-card-header{
    border-bottom:1px solid rgba(30,64,175,.8);
    background:linear-gradient(90deg,rgba(15,23,42,.9),rgba(15,23,42,.6));
    color:#e5e7eb;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    font-size:.75rem;
  }

  /* ====== FORM STYLE DI DALAM SHELL ====== */
  .session-shell .form-label{
    font-size:.8rem;
    font-weight:600;
    color:#e5e7eb;
  }
  .session-shell .form-select,
  .session-shell .form-control{
    background:rgba(15,23,42,.9);
    border-radius:.8rem;
    border:1px solid rgba(148,163,184,.6);
    color:#e5e7eb;
    font-size:.85rem;
  }
  .session-shell .form-control:focus,
  .session-shell .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 1px rgba(99,102,241,.65);
    background:rgba(15,23,42,.95);
    color:#f9fafb;
  }
  .session-shell .form-control[readonly],
  .session-shell .form-control:disabled{
    background:rgba(15,23,42,.7) !important;
    color:#e5e7eb !important;
  }

  .calc-line{
    color:#9ca3af;
    font-size:.8rem;
  }
  .calc-line span{
    font-weight:600;
    color:#e5e7eb;
  }

  .btn-main-submit{
    border-radius:999px;
    padding:.55rem 1.1rem;
    font-weight:600;
    background:linear-gradient(135deg,#22c55e,#a3e635);
    border:none;
    color:#022c22;
    box-shadow:0 12px 28px rgba(34,197,94,.55);
  }
  .btn-main-submit:hover{
    filter:brightness(1.04);
  }

  /* ====== TABEL RIWAYAT ====== */
  .table-neon{
    margin-bottom:0;
    color:#d1d5db;
    font-size:.8rem;
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
    background:rgba(79,70,229,.22);
    transform:translateY(-1px);
  }
  .table-neon td,
  .table-neon th{
    border-color:rgba(31,41,55,.9);
  }

  .amount-mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  .badge-addon{
    border-radius:999px;
    border:1px solid rgba(129,140,248,.6);
    background:rgba(79,70,229,.14);
    color:#e5e7eb;
    font-size:.7rem;
    padding:.15rem .45rem;
  }
  .badge-glow{
    box-shadow:0 0 14px rgba(129,140,248,.6);
  }

  /* MAX HEIGHT TABEL */
  .session-history-scroll{
    max-height: 520px;
    overflow-y:auto;
  }

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
    .session-card{ box-shadow:none; border-color:#e5e7eb; background:#fff; color:#111827; }
    .table-neon thead th{ background:#f3f4f6; color:#111827; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')

<div class="session-shell">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

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
    {{-- FORM BUAT SESI --}}
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
                {{-- Menambahkan data-type agar JS bisa baca tipe unitnya --}}
                <option value="{{ $u->id }}" 
                        data-rate="{{ $u->hourly_rate }}" 
                        data-type="{{ $u->type ?? 'PS4' }}">
                  {{ $u->name }} [{{ $u->type ?? 'PS4' }}] — Rp {{ number_format($u->hourly_rate,0,',','.') }}/jam
                </option>
              @endforeach
            </select>

            <div class="row mt-3 g-2">
              <div class="col-md-6">
                <label class="form-label">Tambahan Stik</label>
                <select class="form-select" id="extraSel" name="extra_controllers">
                  @for($n=0;$n<=4;$n++)<option value="{{ $n }}">{{ $n }}</option>@endfor
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Arcade Controller</label>
                <select class="form-select" id="arcadeSel" name="arcade_controllers">
                  @for($n=0;$n<=2;$n++)<option value="{{ $n }}">{{ $n }}</option>@endfor
                </select>
              </div>
            </div>

            <label class="form-label mt-3">Waktu Mulai</label>
            <input type="datetime-local" class="form-control" name="start_time" id="startInput" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>

            <label class="form-label mt-3">Durasi</label>
            <select class="form-select" id="hoursSel" name="hours">
              <option value="0.5">30 menit</option>
              @for($h=1;$h<=6;$h++)<option value="{{ $h }}">{{ $h }} jam</option>@endfor
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
              <div class="calc-line">Total tagihan: <span id="billLbl">Rp 0</span></div>
            </div>

            <button class="btn btn-main-submit mt-3"><i class="bi bi-play-circle me-1"></i> Buat &amp; Tagih</button>
          </form>
        </div>
      </div>
    </div>

    {{-- RIWAYAT SESI --}}
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
                  <th>Unit</th>
                  <th class="text-nowrap">Mulai</th>
                  <th class="text-nowrap">Selesai</th>
                  <th class="text-center">Durasi</th>
                  <th class="text-end">Tagihan</th>
                  <th class="text-end d-print-none">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($closed_sessions as $s)
                  <tr>
                    {{-- FIX: Menggunakan $s->psUnit (CamelCase) bukan $s->ps_unit --}}
                    <td class="text-dark">
                      <div class="fw-bold">{{ $s->psUnit->name ?? '-' }}</div>
                      
                      {{-- Badge Tipe Unit --}}
                      <span class="badge bg-light text-dark fw-bold border border-secondary mt-1" style="font-size: 0.7rem;">
                        {{ $s->psUnit->type ?? 'PS4' }}
                      </span>

                      @if(!empty($s->extra_controllers) && $s->extra_controllers > 0)
                        <span class="badge badge-addon badge-glow ms-1">+Stik {{ $s->extra_controllers }}</span>
                      @endif
                      @if(!empty($s->arcade_controllers) && $s->arcade_controllers > 0)
                        <span class="badge badge-addon badge-glow ms-1">Arcade {{ $s->arcade_controllers }}</span>
                      @endif
                    </td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($s->start_time)->format('d-m H:i') }}</td>
                    <td class="text-nowrap">{{ $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('d-m H:i') : '-' }}</td>
                    <td class="text-center">
                      <span class="badge bg-secondary-subtle text-dark fw-semibold">{{ intdiv($s->minutes ?? 0, 60) }} jam</span>
                    </td>
                    <td class="text-end amount-mono">Rp {{ number_format($s->bill ?? 0,0,',','.') }}</td>
                    <td class="text-end d-print-none">
                      <form class="d-inline confirm-delete" method="post"
                            action="{{ route('sessions.delete', ['sid' => $s->id]) }}"
                            data-confirm="Hapus riwayat sesi ini? Pendapatan di laporan akan ikut terhapus.">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center text-muted p-3">Belum ada sesi.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const EX_RATE = 10000; const ARC_RATE = 15000;
  const KEY_TIMERS = 'fixplay.rental.timers';
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }
  function getNumbers(){
    const unit = document.getElementById('unitSel');
    return {
      base: parseFloat(unit?.selectedOptions[0]?.dataset?.rate || '0'),
      type: unit?.selectedOptions[0]?.dataset?.type || 'PS4',
      extra: parseInt(document.getElementById('extraSel').value || '0', 10),
      arcade: parseInt(document.getElementById('arcadeSel').value || '0', 10),
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
    const {base, type, extra, arcade, hours} = getNumbers();
    const endLbl = document.getElementById('endLbl');
    try{
      if(start && hours>0){
        const dt = new Date(document.getElementById('startInput').value);
        dt.setTime(dt.getTime() + Math.round(hours * 3600 * 1000));
        endLbl.textContent = String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0');
      }else{ endLbl.textContent='—:—'; }
    }catch(e){ endLbl.textContent='—:—'; }

    // LOGIKA HARGA BARU (Frontend)
    const extrasCost = (extra * EX_RATE) + (arcade * ARC_RATE);
    const totalExtras = extrasCost * hours;
    let unitBill = 0;

    if (type === 'PS4' && base === 10000) { 
        // Paket Khusus PS4 Reguler
        if (hours === 3) unitBill = 25000;
        else if (hours === 4) unitBill = 35000;
        else if (hours === 5) unitBill = 45000;
        else if (hours === 6) unitBill = 50000;
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
      if (method==='tunai' && paid<currentBill){ e.preventDefault(); alert('Pembayaran tunai kurang.'); return; }
      try { saveTimer(); } catch(err){}
    });
  }

  ['unitSel','extraSel','arcadeSel','hoursSel','startInput'].forEach(id=>{
    const el=document.getElementById(id);
    if(el){ el.addEventListener('change', updateCalc); el.addEventListener('input', updateCalc); }
  });
  document.getElementById('payMethod')?.addEventListener('change', updateChange);
  document.getElementById('paidAmount')?.addEventListener('input', updateChange);
  updateCalc();
})();
</script>
@endpush