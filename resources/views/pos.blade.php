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
    content:""; position:absolute; inset:-40%;
    background: radial-gradient(circle at 10% 0,#a855f755,transparent 52%), radial-gradient(circle at 90% 100%,#0ea5e955,transparent 60%);
    opacity:.7; filter: blur(40px); pointer-events:none;
  }
  .pos-shell > *{ position:relative; z-index:1; }

  .pos-chip-icon{
    width:36px; height:36px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#4f46e5,#a855f7); color:#fff; box-shadow:0 10px 24px rgba(79,70,229,.7); font-size:18px;
  }
  .pos-title-text{ color:#f9fafb; }
  .pos-subtitle{ color:#9ca3af; font-size:.8rem; }

  .btn-soft-dark{
    border-radius:999px; border:1px solid rgba(148,163,184,.4); background:rgba(15,23,42,.75); color:#e5e7eb;
  }
  .btn-soft-dark:hover{ background:rgba(15,23,42,.95); color:#fff; }

  /* ====== CARD STYLE (DARK GLASS) ====== */
  .card-pos, .card-pos-alt {
    border-radius:1.25rem; border:1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.8));
    color:#e5e7eb; box-shadow:0 20px 38px rgba(0,0,0,.6); backdrop-filter:blur(12px); overflow: hidden; height: 100%;
  }
  .card-header {
    border-bottom:1px solid rgba(148,163,184,.2); background: rgba(30, 41, 59, 0.4);
    color:#e5e7eb; font-weight:700; letter-spacing:.06em; text-transform:uppercase; font-size:.75rem; padding: 1rem 1.25rem;
  }

  /* Form Inputs */
  .form-label { font-size:.8rem; color:#cbd5f5; font-weight: 600; }
  .form-control, .form-select {
    background:rgba(15,23,42,.6); border-radius:.6rem; border:1px solid rgba(148,163,184,.3); color:#f3f4f6; font-size:.9rem;
  }
  .form-control:focus, .form-select:focus {
    border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,.25); background:rgba(2,6,23,.8); color:#fff;
  }
  .form-control::placeholder { color:#6b7280; }
  .form-control[readonly], .form-control:disabled { background:rgba(15,23,42,.4); color:#9ca3af; border-color:rgba(148,163,184,.15); }

  /* Tombol */
  .btn-outline-primary { border-radius:999px; }
  .btn-success {
    border-radius: .75rem; background: linear-gradient(135deg,#22c55e,#16a34a); border: none;
    box-shadow: 0 4px 12px rgba(22,163,74,.4); font-weight: 700; padding: 0.7rem;
  }
  .btn-success:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(22,163,74,.5); }

  /* Total Pill */
  .pos-total-pill {
    padding:.35rem .9rem; border-radius:999px; background:rgba(15,23,42,.9); border:1px solid rgba(52,211,153,.6);
    color:#bbf7d0; font-size:.85rem; font-weight: 600;
  }

  /* ====== TABEL FUTURISTIK (DARK MODE) ====== */
  .table-pos { width: 100%; margin-bottom: 0; color: #cbd5e1; }
  .table-pos thead th {
    background: rgba(15, 23, 42, 0.8); color: #94a3b8; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.05em; border-bottom: 1px solid rgba(148, 163, 184, 0.2); padding: 0.85rem 1rem; white-space: nowrap;
  }
  .table-pos tbody td {
    background: transparent; border-bottom: 1px solid rgba(148, 163, 184, 0.1); padding: 0.85rem 1rem; vertical-align: middle; font-size: 0.9rem;
  }
  .table-pos tbody tr:hover td { background: rgba(99, 102, 241, 0.08); }
  .amount-mono { font-family: 'Consolas', 'Monaco', monospace; color: #818cf8; font-weight: 600; }

  /* Tombol Aksi Ikon */
  .btn-icon-group { display: flex; gap: 4px; justify-content: flex-end; }
  .btn-icon {
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    border-radius: 6px; border: 1px solid transparent; background: transparent; transition: all 0.2s; color: #94a3b8;
  }
  .btn-icon:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
  .btn-icon-view:hover { background: #475569; color: #fff; }
  .btn-icon-edit:hover { background: #fbbf24; color: #000; }
  .btn-icon-del:hover { background: #f87171; color: #fff; }

  /* POS MODAL (Alert) */
  .modal-glass .modal-content {
    background: radial-gradient(circle at top left, #1e1e2f, #0f1020);
    border: 1px solid rgba(124,58,237,.3); box-shadow: 0 0 30px rgba(0,0,0,.8); color: #e5e7eb; border-radius: 1.25rem;
  }
  .modal-glass .modal-header { border-bottom: 1px solid rgba(255,255,255,.08); }
  .modal-glass .modal-footer { border-top: 1px solid rgba(255,255,255,.08); }
  .modal-glass .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
  .modal-glass .btn-primary {
    background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none; color: #fff; font-weight: 600;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
  }

  @media (max-width: 992px){ .pos-shell{ padding:1.35rem 1.1rem 2rem; border-radius:1.1rem; } }
  @media (max-width: 768px){
    .pos-header-stack{ flex-direction:column; align-items:flex-start !important; gap:.7rem; }
    .pos-header-actions{ width:100%; justify-content:flex-end; }
    .card-pos, .card-pos-alt{ margin-bottom:.4rem; }
  }
  @media print{
    .pos-shell{ background:#fff; box-shadow:none; }
    .card-pos, .card-pos-alt{ box-shadow:none; border-color:#e5e7eb; background:#fff; color:#111827; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="pos-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-4 pos-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="pos-chip-icon"><i class="bi bi-basket3"></i></span>
        <h4 class="m-0 fw-bold text-white">Penjualan Produk</h4>
      </div>
      <div class="pos-subtitle">Buat transaksi makanan & minuman dengan cepat, cek riwayat dalam satu layar.</div>
    </div>
    <div class="d-flex align-items-center gap-2 d-print-none pos-header-actions">
      <button type="button" class="btn btn-soft-dark btn-sm px-3 py-2" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  {{-- Alert Session Sukses --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none bg-success-subtle border-success text-success-emphasis" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    {{-- KIRI: FORM POS --}}
    <div class="col-lg-6">
      <div class="card card-pos h-100">
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between">
            <span><i class="bi bi-cart-plus me-2"></i> Input Penjualan</span>
            <span class="badge bg-dark border border-secondary d-print-none" style="font-size:.7rem;">POS Produk</span>
          </div>
        </div>
        <div class="card-body p-4">
          
          <form method="post" action="{{ route('pos.checkout') }}" id="pos-form">
            @csrf
            <div id="rows"></div>

            <div class="d-flex gap-2 mt-3 align-items-center border-top border-secondary border-opacity-25 pt-3">
              <button class="btn btn-outline-primary btn-sm" type="button" onclick="addRow()">
                <i class="bi bi-plus-lg"></i> Tambah Item
              </button>
              <div class="ms-auto pos-total-pill">
                Total: <span id="totalLbl" class="amount-mono text-white">Rp 0</span>
              </div>
            </div>

            <div class="row mt-4 g-3">
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
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary" style="font-size: 0.8rem;">Rp</span>
                    <input type="number" class="form-control" name="paid_amount" id="paidAmount" value="" min="0" placeholder="0">
                </div>
              </div>
              <div class="col-md-4">
                <label class="form-label">Kembalian</label>
                <input type="text" class="form-control bg-dark text-info fw-bold border-secondary" id="changeLbl" value="Rp 0" disabled>
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label">Catatan (opsional)</label>
              <input name="note" class="form-control" placeholder="mis. diskon member, dll.">
            </div>

            <button class="btn btn-success mt-4 w-100">
              <i class="bi bi-check-lg me-2"></i> PROSES CHECKOUT
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- KANAN: RIWAYAT PEMBELIAN --}}
    <div class="col-lg-6">
      <div class="card card-pos-alt h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span><i class="bi bi-clock-history me-2"></i> Riwayat Pembelian</span>
          <span class="badge bg-dark border border-secondary">Terakhir 10</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height:580px; overflow-y:auto; scrollbar-width:thin; scrollbar-color:#475569 #1e293b;">
            <table class="table table-pos">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Detail Item</th>
                  <th class="text-end">Total</th>
                  <th class="text-end d-print-none" style="width: 120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentSales as $rs)
                  <tr>
                    <td>
                        <div class="d-flex flex-column small text-secondary">
                            <span>{{ \Carbon\Carbon::parse($rs->sold_at)->format('d-m-Y') }}</span>
                            <span class="text-light fw-bold">{{ \Carbon\Carbon::parse($rs->sold_at)->format('H:i') }}</span>
                        </div>
                    </td>
                    <td>
                      <ul class="list-unstyled m-0 small">
                        @foreach($rs->items as $item)
                          @if($item->product)
                            <li class="mb-1">
                              <span class="text-white">{{ $item->product->name }}</span> 
                              <span class="text-muted ms-1">x{{ $item->qty }}</span>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </td>
                    <td class="text-end amount-mono text-light">
                      @php
                        $displayTotal = $rs->total > 0 ? $rs->total : $rs->items->sum('subtotal');
                      @endphp
                      Rp {{ number_format($displayTotal, 0, ',', '.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <div class="btn-icon-group">
                        <a href="{{ route('sales.show', $rs->id) }}" class="btn-icon btn-icon-view" title="Lihat Struk"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('sales.edit', $rs->id) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('sales.destroy', $rs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan otomatis.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-del" title="Hapus"><i class="bi bi-trash3"></i></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted p-5">
                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
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
  </div> 
</div>

{{-- MODAL ALERT (DIPINDAHKAN KELUAR LOOP AGAR TIDAK TERTUTUP ELEMENT LAIN) --}}
<div class="modal fade modal-glass" id="posAlert" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-white">
          <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
          Transaksi Ditolak
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body pt-3 text-center" id="posAlertBody">Pesan</div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">OK, Mengerti</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script id="pos-data" type="application/json">@json($products)</script>
<script>
(function(){
  const posProducts = JSON.parse(document.getElementById('pos-data').textContent || '[]');
  function fmtIDR(n){ return (n||0).toLocaleString('id-ID'); }

  function rowTemplate(idx){
    return `
    <div class="row g-2 align-items-end pos-row mt-2 pb-3 border-bottom border-secondary border-opacity-25" data-idx="${idx}">
      <div class="col-md-6">
        <label class="form-label small">Produk</label>
        <select class="form-select form-select-sm prod" name="product_id[]">
          <option value="">-- Pilih Produk --</option>
          ${posProducts.map(p => `
            <option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">
              ${p.name} (Rp ${fmtIDR(p.price)}) — stok: ${p.stock}
            </option>`).join('')}
        </select>
      </div>
      <div class="col-md-2 col-4">
        <label class="form-label small">Qty</label>
        <input type="number" class="form-control form-control-sm qty" name="qty[]" value="1" min="1">
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label small">Subtotal</label>
        <input type="text" class="form-control form-control-sm subtotal bg-dark text-light border-0" value="Rp 0" disabled>
      </div>
      <div class="col-md-1 col-2 text-end">
        <button class="btn btn-sm btn-outline-danger remove mt-4 border-0" title="Hapus Item">
            <i class="bi bi-x-lg"></i>
        </button>
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
      
      // Hapus event listener lama agar tidak double
      prod.onchange = null; 
      qty.oninput = null;
      rm.onclick = null;

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
    
    const qtyInput = row.querySelector('.qty');
    if(invalid) {
        qtyInput.classList.add('is-invalid');
        qtyInput.classList.remove('text-light');
    } else {
        qtyInput.classList.remove('is-invalid');
        qtyInput.classList.add('text-light');
    }
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
    const method=(document.getElementById('payMethod')?.value||'Tunai').toLowerCase();
    
    if (method === 'tunai' && paid < (currentTotal||0)){
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