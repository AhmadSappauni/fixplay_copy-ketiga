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
  <div class="d-flex align-items-center justify-content-between mb-4 session-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="session-chip-icon"><i class="bi bi-controller"></i></span>
        <h4 class="m-0 fw-bold text-white">Sesi Rental</h4>
      </div>
      <div class="session-subtitle">Management durasi sewa &amp; tagihan otomatis.</div>
    </div>
    <div class="d-flex align-items-center gap-2 d-print-none session-header-actions">
      <button type="button" class="btn btn-soft-dark btn-sm px-3 py-2" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  <div class="row g-4">
    
    {{-- KIRI: FORM BUAT SESI --}}
    <div class="col-lg-5">
      <div class="card session-card h-100">
        <div class="card-body p-4">
          <h6 class="mb-4 fw-bold text-uppercase small text-secondary border-bottom border-secondary pb-2">
            <i class="bi bi-plus-circle me-2"></i> Buat Sesi Baru
          </h6>

          <form method="post" action="{{ route('sessions.fixed') }}" id="fixedForm">
            @csrf

            <div class="mb-3">
              <label class="form-label">Pilih Unit PS</label>
              <select class="form-select" name="ps_unit_id" id="unitSel" required>
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $u)
                  <option value="{{ $u->id }}" 
                          data-rate="{{ $u->hourly_rate }}" 
                          data-type="{{ $u->type ?? 'PS4' }}">
                    {{ $u->name }} [{{ $u->type ?? 'PS4' }}]
                  </option>
                @endforeach
              </select>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label">Stik Tambahan</label>
                <select class="form-select" id="extraSel" name="extra_controllers">
                  @for($n=0;$n<=4;$n++)<option value="{{ $n }}">{{ $n }}</option>@endfor
                </select>
              </div>
              <div class="col-6">
                <label class="form-label">Arcade Stick</label>
                <select class="form-select" id="arcadeSel" name="arcade_controllers">
                  @for($n=0;$n<=2;$n++)<option value="{{ $n }}">{{ $n }}</option>@endfor
                </select>
              </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Durasi Main</label>
                <div class="input-group">
                    <select class="form-select" id="hoursSel" name="hours">
                      <option value="0.5">30 Menit</option>
                      @for($h=1;$h<=12;$h++)<option value="{{ $h }}">{{ $h }} Jam</option>@endfor
                    </select>
                    <span class="input-group-text bg-dark border-secondary text-light">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Waktu Mulai</label>
                <input type="datetime-local" class="form-control" name="start_time" id="startInput" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="p-3 rounded-3 mb-3" style="background: rgba(0,0,0,0.2);">
                <div class="calc-line">
                    <span>Estimasi Selesai</span>
                    <span id="endLbl" class="text-warning">—:—</span>
                </div>
                <div class="calc-line border-top border-secondary pt-2 mt-2">
                    <span class="fs-5">Total Tagihan</span>
                    <span id="billLbl" class="fs-4 text-success">Rp 0</span>
                </div>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-6">
                <label class="form-label small">Metode Bayar</label>
                <select name="payment_method" id="payMethod" class="form-select form-select-sm">
                  <option value="Tunai">Tunai</option>
                  <option value="QRIS">QRIS</option>
                  <option value="Transfer">Transfer</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label small">Uang Diterima</label>
                <input type="number" class="form-control form-control-sm" name="paid_amount" id="paidAmount" placeholder="0">
              </div>
              <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center small text-muted">
                      <span>Kembalian:</span>
                      <input type="text" id="changeLbl" class="form-control form-control-sm text-end fw-bold text-info" style="width: 120px; border:none; background:transparent;" value="Rp 0" readonly>
                  </div>
              </div>
            </div>

            <button class="btn-main-submit">
              <i class="bi bi-check-lg me-2"></i> PROSES & TAGIH
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- KANAN: RIWAYAT SESI --}}
    <div class="col-lg-7">
      <div class="card session-card h-100">
        <div class="card-header session-card-header d-flex justify-content-between align-items-center">
          <span><i class="bi bi-clock-history me-2"></i> Riwayat Sesi</span>
          <span class="badge bg-dark border border-secondary">{{ $closed_sessions->count() }} Data</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive session-history-scroll">
            <table class="table table-neon">
              <thead>
                <tr>
                  <th style="width: 30%;">Unit / Paket</th>
                  <th>Waktu</th>
                  <th class="text-center">Durasi</th>
                  <th class="text-end">Tagihan</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($closed_sessions as $s)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center mb-1">
                          <span class="unit-name">{{ $s->psUnit->name ?? '-' }}</span>
                          {{-- Badge Tipe Unit Keren --}}
                          <span class="badge-type">{{ $s->psUnit->type ?? 'PS4' }}</span>
                      </div>
                      
                      <div class="d-flex flex-wrap gap-1">
                          @if(!empty($s->extra_controllers) && $s->extra_controllers > 0)
                            <span class="badge-addon"><i class="bi bi-controller"></i> +{{ $s->extra_controllers }}</span>
                          @endif
                          @if(!empty($s->arcade_controllers) && $s->arcade_controllers > 0)
                            <span class="badge-addon"><i class="bi bi-joystick"></i> +{{ $s->arcade_controllers }}</span>
                          @endif
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column small text-secondary">
                          <span><i class="bi bi-play-fill me-1"></i> {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</span>
                          <span><i class="bi bi-stop-fill me-1"></i> {{ $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '-' }}</span>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-25">
                        {{ intdiv($s->minutes ?? 0, 60) }} Jam
                        @if(($s->minutes % 60) > 0) {{ $s->minutes % 60 }} m @endif
                      </span>
                    </td>
                    <td class="text-end amount-mono">
                      Rp {{ number_format($s->bill ?? 0,0,',','.') }}
                    </td>
                    <td class="text-end">
                      <form class="d-inline confirm-delete" method="post"
                            action="{{ route('sessions.delete', ['sid' => $s->id]) }}"
                            data-confirm="Hapus riwayat sesi ini? Laporan keuangan terkait juga akan dihapus.">
                        @csrf @method('DELETE')
                        <button class="btn-delete-xs">
                          <i class="bi bi-trash3-fill"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                      <i class="bi bi-inbox display-6 d-block mb-3 opacity-25"></i>
                      Belum ada riwayat sesi hari ini.
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
</div>
@endsection

@push('scripts')
<script>
(function(){
  // Konstanta harga tambahan
  const EX_RATE = 10000; 
  const ARC_RATE = 15000;
  
  // Helper format rupiah
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }

  // Ambil nilai dari form
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

  function roundToThousandCeil(n){ return Math.ceil(n/1000)*1000; }

  // Fungsi kalkulasi utama
  function updateCalc(){
    const startInput = document.getElementById('startInput').value;
    const {base, type, extra, arcade, hours} = getNumbers();
    const endLbl = document.getElementById('endLbl');

    // Hitung Jam Selesai
    try{
      if(startInput && hours>0){
        const dt = new Date(startInput);
        dt.setTime(dt.getTime() + Math.round(hours * 3600 * 1000));
        endLbl.textContent = String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0');
      }else{ endLbl.textContent='—:—'; }
    }catch(e){ endLbl.textContent='—:—'; }

    // Hitung Harga
    const extrasCost = (extra * EX_RATE) + (arcade * ARC_RATE);
    const totalExtras = extrasCost * hours;
    let unitBill = 0;

    // LOGIKA HARGA PAKET
    if (type === 'PS4' && base === 10000) { 
        if (hours === 3) unitBill = 25000;
        else if (hours === 4) unitBill = 35000;
        else if (hours === 5) unitBill = 45000;
        else if (hours === 6) unitBill = 50000;
        else unitBill = base * hours;
    } else {
        unitBill = base * hours;
    }

    let rawBill = unitBill + totalExtras;
    
    // Pembulatan khusus 30 menit
    if (hours === 0.5) rawBill = roundToThousandCeil(rawBill);
    else rawBill = Math.round(rawBill);

    // Update UI
    document.getElementById('billLbl').textContent = 'Rp ' + fmtIDR(rawBill);
    
    // Update Kembalian
    const paidInput = document.getElementById('paidAmount');
    const paid = parseInt(paidInput.value || '0', 10);
    const change = Math.max(0, paid - rawBill);
    document.getElementById('changeLbl').value = 'Rp ' + fmtIDR(change);
    
    // Simpan tagihan saat ini untuk validasi submit
    window.currentTotalBill = rawBill;
  }

  // Event Listeners
  const inputs = ['unitSel','extraSel','arcadeSel','hoursSel','startInput','paidAmount'];
  inputs.forEach(id => {
    const el = document.getElementById(id);
    if(el){
      el.addEventListener('change', updateCalc);
      el.addEventListener('input', updateCalc);
    }
  });

  // Validasi Submit
  const formEl = document.getElementById('fixedForm');
  if(formEl){
    formEl.addEventListener('submit', function(e){
      const method = document.getElementById('payMethod').value;
      const paid = parseInt(document.getElementById('paidAmount').value||'0', 10);
      
      if (method === 'Tunai' && paid < window.currentTotalBill){
        e.preventDefault();
        alert('Uang pembayaran kurang dari total tagihan!');
      }
      // Tidak perlu blok try-catch saveTimer() jika tidak menggunakan fitur timer local storage
    });
  }

  // Init
  updateCalc();
})();
</script>
@endpush