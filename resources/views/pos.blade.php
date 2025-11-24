@extends('layouts.fixplay')

@section('page_title','Kasir Fixplay - Penjualan')

@push('styles')
<style>
  /* ====== POS SHELL : FUTURISTIC PANEL ====== */
  .pos-shell{
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
  .pos-shell::before{
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
  .pos-shell > *{
    position:relative;
    z-index:1;
  }

  .pos-chip-icon{
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
  .pos-title-text{
    color:#f9fafb;
  }
  .pos-subtitle{
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
    background:linear-gradient(135deg,#22c55e,#a855f7);
    color:#f9fafb;
    box-shadow:0 12px 24px rgba(22,163,74,.75);
  }
  .btn-soft-primary:hover{
    filter:brightness(1.06);
  }

  /* ====== MAIN CARDS ====== */
  .card-pos{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.5);
    background:radial-gradient(circle at top,#020617,#030712 55%,#020617);
    color:#e5e7eb;
    box-shadow:0 20px 38px rgba(15,23,42,.9);
  }
  .card-pos .card-header{
    border-bottom:1px solid rgba(31,41,55,.85);
    background:linear-gradient(90deg,#020617,#030712);
    color:#e5e7eb;
    font-weight:600;
    letter-spacing:.06em;
    text-transform:uppercase;
    font-size:.75rem;
  }

  .card-pos-alt{
    border-radius:1.25rem;
    border:1px solid rgba(148,163,184,.45);
    background:linear-gradient(145deg,#020617,#020617 40%,#0b1120 100%);
    color:#e5e7eb;
    box-shadow:0 20px 40px rgba(15,23,42,.9);
  }

  /* Form labels & controls */
  .card-pos .form-label,
  .card-pos-alt .form-label{
    font-size:.8rem;
    color:#cbd5f5;
  }
  .card-pos .form-control,
  .card-pos .form-select{
    background:rgba(15,23,42,.9);
    border:1px solid rgba(148,163,184,.55);
    color:#e5e7eb;
  }
  .card-pos .form-control:focus,
  .card-pos .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 1px rgba(99,102,241,.6);
  }
  .card-pos .form-control::placeholder{
    color:#6b7280;
  }

  .btn-outline-primary{
    border-radius:999px;
  }
  .btn-success{
    border-radius:999px;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    border:none;
    box-shadow:0 10px 24px rgba(22,163,74,.6);
  }
  .btn-success:hover{
    filter:brightness(1.07);
  }

  /* TOTAL + CALC BADGE */
  .pos-total-pill{
    padding:.35rem .9rem;
    border-radius:999px;
    background:rgba(15,23,42,.9);
    border:1px solid rgba(52,211,153,.6);
    color:#bbf7d0;
    font-size:.8rem;
  }

  /* TABLE FUTURISTIC */
  .table-pos{
    margin-bottom:0;
    color:#d1d5db;
  }
  .table-pos thead th{
    background:linear-gradient(90deg,#020617,#030712);
    border-bottom:1px solid rgba(55,65,81,.9);
    font-size:.78rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#9ca3af;
  }
  .table-pos tbody tr{
    border-color:rgba(31,41,55,.9);
    transition:background .14s ease, transform .06s ease;
  }
  .table-pos tbody tr:hover{
    background:rgba(79,70,229,.18);
    transform:translateY(-1px);
  }
  .table-pos td,
  .table-pos th{
    border-color:rgba(31,41,55,.9);
  }

  .amount-mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  /* POS MODAL */
  .fixplay-alert .modal-content{
    background:
      radial-gradient(100% 140% at 0% 0%, rgba(124,58,237,.25), transparent 40%),
      radial-gradient(120% 120% at 100% 0%, rgba(59,130,246,.22), transparent 45%),
      linear-gradient(180deg, #151528, #0f1020);
    color:#e5e7eb;
    border:1px solid rgba(122,92,255,.45);
    box-shadow: 0 10px 30px rgba(0,0,0,.45), 0 0 24px rgba(124,58,237,.3);
    border-radius:14px;
  }
  .fixplay-alert .modal-header .modal-title{
    font-weight:800;
    text-shadow:0 0 12px rgba(124,58,237,.45);
  }
  .fixplay-alert .btn-primary{
    background: linear-gradient(90deg, #7a5cff, #38bdf8);
    border:none;
    color:#0a0a12;
    font-weight:700;
    box-shadow:0 6px 18px rgba(122,92,255,.35);
    border-radius:999px;
  }

  /* HEADER LAYOUT */
  .pos-header-stack{}
  .pos-header-actions{}

  /* RESPONSIVE */
  @media (max-width: 992px){
    .pos-shell{
      padding:1.35rem 1.1rem 2rem;
      border-radius:1.1rem;
    }
  }
  @media (max-width: 768px){
    .pos-header-stack{
      flex-direction:column;
      align-items:flex-start !important;
      gap:.7rem;
    }
    .pos-header-actions{
      width:100%;
      justify-content:flex-end;
    }
    .card-pos,
    .card-pos-alt{
      margin-bottom:.4rem;
    }
  }

  @media print{
    .pos-shell{
      background:#fff;
      box-shadow:none;
    }
    .card-pos,
    .card-pos-alt{
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
<div class="pos-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-3 pos-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="pos-chip-icon">
          <i class="bi bi-basket3"></i>
        </span>
        <h4 class="m-0 fw-semibold pos-title-text">Kasir Fixplay - Penjualan Produk</h4>
      </div>
      <div class="pos-subtitle">
        Buat transaksi makanan & minuman dengan cepat, cek riwayat dalam satu layar.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none pos-header-actions">
      <button type="button" class="btn btn-soft-dark" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  {{-- Alert Session Sukses --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-3">
    {{-- KIRI: FORM POS --}}
    <div class="col-lg-6">
      <div class="card card-pos h-100">
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between">
            <span>Input Penjualan</span>
            <span class="badge bg-secondary-subtle text-dark text-uppercase d-print-none" style="font-size:.7rem;">
              POS Produk
            </span>
          </div>
        </div>
        <div class="card-body">
          {{-- Alert Modal --}}
          <div class="modal fade fixplay-alert" id="posAlert" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header border-0">
                  <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Transaksi Ditolak
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="posAlertBody">Pesan</div>
                <div class="modal-footer border-0">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK, Mengerti</button>
                </div>
              </div>
            </div>
          </div>

          <form method="post" action="{{ route('pos.checkout') }}" id="pos-form">
            @csrf
            <div id="rows"></div>

            <div class="d-flex gap-2 mt-3 align-items-center">
              <button class="btn btn-outline-primary" type="button" onclick="addRow()">
                + Tambah Item
              </button>
              <div class="ms-auto pos-total-pill">
                Total: <span id="totalLbl" class="amount-mono">Rp 0</span>
              </div>
            </div>

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
                <input type="text" class="form-control bg-white text-dark" id="changeLbl" value="Rp 0" disabled>
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label">Catatan (opsional)</label>
              <input name="note" class="form-control" placeholder="mis. bayar tunai, diskon, dll.">
            </div>

            <button class="btn btn-success mt-3 w-100 w-md-auto">
              Checkout
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- KANAN: RIWAYAT PEMBELIAN --}}
    <div class="col-lg-6">
      <div class="card card-pos-alt h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Riwayat Pembelian Makanan & Minuman</span>
          <small class="text-muted d-print-none">Terakhir 10</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height:520px;">
            <table class="table table-sm m-0 align-middle table-pos">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Detail Item</th>
                  <th class="text-end">Total</th>
                  <th class="text-end d-print-none">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentSales as $rs)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($rs->sold_at)->format('d-m H:i') }}</td>
                    <td>
                      {{-- Loop item dalam satu sel agar mie & rosta menyatu --}}
                      <ul class="list-unstyled m-0 small">
                        @foreach($rs->items as $item)
                          @if($item->product)
                            <li>
                              {{ $item->product->name }}
                              <span class="text-muted fw-bold">x{{ $item->qty }}</span>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </td>
                    <td class="text-end amount-mono">
                      @php
                        $displayTotal = $rs->total > 0 ? $rs->total : $rs->items->sum('subtotal');
                      @endphp
                      Rp {{ number_format($displayTotal, 0, ',', '.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <div class="btn-group btn-group-sm" role="group">
                        {{-- Lihat struk --}}
                        <a href="{{ route('sales.show', $rs->id) }}"
                           class="btn btn-outline-secondary"
                           title="Lihat Struk">
                          <i class="bi bi-eye"></i>
                        </a>

                        {{-- Edit pembayaran --}}
                        <a href="{{ route('sales.edit', $rs->id) }}"
                           class="btn btn-outline-warning"
                           title="Edit Pembayaran">
                          <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Hapus transaksi --}}
                        <form action="{{ route('sales.destroy', $rs->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan otomatis.');">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                                  class="btn btn-outline-danger"
                                  title="Hapus Transaksi">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted p-3">
                      Belum ada pembelian produk.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div> {{-- /.row --}}
</div> {{-- /.pos-shell --}}
@endsection

@push('scripts')
<script id="pos-data" type="application/json">@json($products)</script>
<script>
(function(){
  const posProducts = JSON.parse(document.getElementById('pos-data').textContent || '[]');
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }

  function rowTemplate(idx){
    return `
    <div class="row g-2 align-items-end pos-row mt-2" data-idx="${idx}">
      <div class="col-md-6">
        <label class="form-label">Produk</label>
        <select class="form-select prod" name="product_id[]">
          <option value="">-- pilih --</option>
          ${posProducts.map(p => `
            <option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">
              ${p.name} (Rp ${fmtIDR(p.price)}) — stok: ${p.stock}
            </option>`).join('')}
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Jumlah Produk</label>
        <input type="number" class="form-control qty" name="qty[]" value="1" min="1">
      </div>
      <div class="col-md-2">
        <label class="form-label">Subtotal</label>
        <input type="text" class="form-control subtotal bg-white text-dark" value="Rp 0" disabled>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-danger w-100 mt-4 mt-md-0 remove">Hapus</button>
      </div>
    </div>`;
  }

  let idx=0, currentTotal=0;
  function addRow(){
    const c=document.getElementById('rows');
    c.insertAdjacentHTML('beforeend', rowTemplate(idx++));
    bind(); updateTotals();
  }
  function bind(){
    document.querySelectorAll('.pos-row').forEach(row=>{
      const prod=row.querySelector('.prod');
      const qty =row.querySelector('.qty');
      const rm  =row.querySelector('.remove');
      prod.onchange=()=>{ updateTotals(); validateRowStock(row); };
      qty.oninput  =()=>{ updateTotals(); validateRowStock(row); };
      rm.onclick=(e)=>{ e.preventDefault(); row.remove(); updateTotals(); };
    });
  }

  function validateRowStock(row){
    const sel = row.querySelector('.prod');
    const pid = sel.value;
    const qty = parseInt(row.querySelector('.qty').value||"0",10);
    const stock = parseInt(sel.selectedOptions[0]?.dataset?.stock || "0", 10);
    const invalid = pid && qty>stock;
    row.querySelector('.qty').classList.toggle('is-invalid', invalid);
    return invalid;
  }

  function findStockError(){
    let msg=null;
    document.querySelectorAll('.pos-row').forEach(row=>{
      if (msg) return;
      const sel=row.querySelector('.prod');
      const txt=sel.selectedOptions[0]?.text || '';
      const name=txt.split(' (')[0] || 'Produk';
      const qty =parseInt(row.querySelector('.qty').value||"0",10);
      const stock=parseInt(sel.selectedOptions[0]?.dataset?.stock || "0",10);
      if (sel.value && qty>stock){
        msg = `Stok ${name} tidak cukup.\nStok tersedia: ${stock.toLocaleString('id-ID')}, diminta: ${qty.toLocaleString('id-ID')}.\n\nTransaksi dibatalkan.`;
      }
    });
    return msg;
  }

  function updateTotals(){
    let total=0;
    document.querySelectorAll('.pos-row').forEach(row=>{
      const prod=row.querySelector('.prod');
      const qty =parseInt(row.querySelector('.qty').value||"0",10);
      const price=parseInt(prod.selectedOptions[0]?.dataset?.price||"0",10);
      const sub=price*(qty>0?qty:0);
      total+=sub;
      row.querySelector('.subtotal').value="Rp "+fmtIDR(sub);
    });
    currentTotal=total;
    document.getElementById('totalLbl').textContent="Rp "+fmtIDR(total);
    updateChange();
  }

  function updateChange(){
    const method=(document.getElementById('payMethod')?.value||'Tunai').toLowerCase();
    const paid  =parseInt(document.getElementById('paidAmount')?.value||"0",10);
    const change=(method==='tunai') ? Math.max(0, paid-(currentTotal||0)) : 0;
    document.getElementById('changeLbl').value="Rp "+fmtIDR(change);
  }

  document.addEventListener('input',(e)=>{ if(e.target?.id==='paidAmount') updateChange(); });
  document.addEventListener('change',(e)=>{ if(e.target?.id==='payMethod') updateChange(); });

  const posAlertEl = document.getElementById('posAlert');
  const posAlert   = posAlertEl ? new bootstrap.Modal(posAlertEl, {backdrop:'static'}) : null;
  function showAlert(msg){
    const body=document.getElementById('posAlertBody');
    if(body) body.textContent = msg;
    if(posAlert) posAlert.show(); else alert(msg);
  }

  const form=document.getElementById('pos-form');
  form.addEventListener('submit', function(e){
    const stockMsg = findStockError();
    if (stockMsg){
      e.preventDefault(); e.stopPropagation();
      showAlert(stockMsg);
      return false;
    }
    const paid  =parseInt(document.getElementById('paidAmount')?.value||"0",10);
    if (paid < (currentTotal||0)){
      e.preventDefault(); e.stopPropagation();
      showAlert(
        "Nominal dibayar kurang.\n" +
        "Total: Rp " + (currentTotal||0).toLocaleString('id-ID') + "\n" +
        "Dibayar: Rp " + (paid||0).toLocaleString('id-ID')
      );
      return false;
    }
  });

  window.addRow=addRow;
  addRow();
})();
</script>
@endpush
