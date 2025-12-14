@extends('layouts.fixplay')

@section('page_title','Kasir Fixplay - Pengeluaran')

@push('styles')
<style>
  /* ====== SHELL FUTURISTIK ====== */
  .fx-shell{
    position:relative;
    padding:1.6rem 1.6rem 2rem;
    border-radius:1.4rem;
    background:
      radial-gradient(circle at top left,#312e81 0,#020617 55%),
      radial-gradient(circle at bottom right,#22c55e33 0,transparent 60%);
    box-shadow:0 22px 55px rgba(15,23,42,.8);
    color:#e5e7eb;
    overflow:hidden;
  }
  .fx-shell::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:
      radial-gradient(circle at 12% 0,#a855f755,transparent 52%),
      radial-gradient(circle at 90% 100%,#0ea5e955,transparent 60%);
    opacity:.6;
    filter:blur(40px);
    pointer-events:none;
  }
  .fx-shell > *{ position:relative; z-index:1; }

  .fx-header-icon{
    width:38px;height:38px;border-radius:999px;
    display:inline-flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,#f97316,#facc15);
    color:#111827;box-shadow:0 10px 24px rgba(248,250,252,.2);font-size:18px;
  }
  .fx-title{ color:#f9fafb; }
  .fx-subtitle{ color:#9ca3af;font-size:.8rem; }

  .btn-soft-dark{
    border-radius:999px;border:1px solid rgba(148,163,184,.5);
    background:rgba(15,23,42,.85);color:#e5e7eb;
  }
  .btn-soft-dark:hover{ background:rgba(15,23,42,1);color:#fff; }

  /* ====== CARD FUTURISTIK ====== */
  .fx-card{
    border-radius:1.2rem;
    border:1px solid rgba(148,163,184,.25);
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.8));
    box-shadow:0 20px 38px rgba(0,0,0,.6);
    backdrop-filter:blur(12px);
    color:#e5e7eb;
    height: 100%;
    overflow: hidden;
  }
  .fx-card-header{
    border-bottom:1px solid rgba(148,163,184,.2);
    background: rgba(30, 41, 59, 0.4);
    font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;
    font-weight:700;padding: 1rem 1.25rem;color: #94a3b8;
  }

  .fx-form-label{
    font-size:.8rem;font-weight:600;color:#cbd5f5;
  }

  /* Input Style */
  .form-control, .form-select{
    background:rgba(15,23,42,.6);
    border-radius:.6rem;
    border:1px solid rgba(148,163,184,.3);
    color:#f3f4f6;font-size:.9rem;
  }
  .form-control:focus, .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 2px rgba(99,102,241,.25);
    background:rgba(2,6,23,.8);color:#fff;
  }
  .form-control::placeholder { color:#6b7280; }

  /* Tombol Simpan */
  .btn-main-submit{
    width: 100%;border-radius:.75rem;padding:.7rem;font-weight:700;
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    border:none;color:#fff;
    box-shadow:0 4px 12px rgba(59, 130, 246, 0.4);
    transition: all 0.2s;
  }
  .btn-main-submit:hover{
    filter:brightness(1.1);
    transform: translateY(-1px);
    box-shadow:0 6px 15px rgba(59, 130, 246, 0.5);
  }

  /* ====== TABEL RIWAYAT (DARK MODE) ====== */
  .table-neon{
    margin-bottom:0;color:#cbd5e1;width: 100%;
  }
  .table-neon thead th{
    background:rgba(15, 23, 42, 0.8);
    border-bottom:1px solid rgba(55,65,81,.9);
    color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;
    font-size:.75rem;padding: 0.85rem 1rem;
  }
  .table-neon tbody tr{
    border-color:rgba(31,41,55,.9);
    transition:background .14s ease, transform .06s ease;
  }
  .table-neon tbody tr:hover{ background:rgba(79,70,229,.15); }
  .table-neon td{
    background: transparent;
    border-bottom:1px solid rgba(31,41,55,.9);
    padding: 0.85rem 1rem;
    vertical-align: middle;
  }

  .amount-mono{
    font-family: 'Consolas', 'Monaco', monospace;
    color: #f87171;
    font-weight: 600;
  }

  /* Tombol Aksi Ikon */
  .btn-icon-group { display: flex; gap: 4px; justify-content: flex-end; }
  .btn-icon {
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    border-radius: 6px; border: 1px solid transparent; background: transparent;
    transition: all 0.2s; color: #94a3b8;
  }
  .btn-icon:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }

  .btn-icon-edit { border-color: #fbbf24; color: #fbbf24; }
  .btn-icon-edit:hover { background: #fbbf24; color: #000; }

  .btn-icon-del { border-color: #f87171; color: #f87171; }
  .btn-icon-del:hover { background: #f87171; color: #fff; }

  .table-wrap-scroll{
    max-height:580px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #475569 #1e293b;
  }

  /* ====== BADGE KOSONG / STATUS ====== */
  .badge-soft{
    border-radius:999px;
    padding:.25rem .7rem;
    font-size:.7rem;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(148, 163, 184, 0.2);
    color: #cbd5e1;
  }
  
  .badge-fund {
      font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;
      font-weight: 700; margin-bottom: 2px; display: inline-block;
  }
  .bg-fund-ps { background: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); }
  .bg-fund-product { background: rgba(34, 197, 94, 0.2); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.3); }
  .bg-fund-other { background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.3); }

  input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
  }


  /* ====== RESPONSIVE ====== */
  @media (max-width: 992px){
    .fx-shell{ padding:1.3rem 1rem 1.8rem; border-radius:1.1rem; }
  }
  @media (max-width: 768px){
    .fx-header-stack{ flex-direction:column; align-items:flex-start !important; gap:.6rem; }
    .fx-header-actions{ width:100%; justify-content:flex-end; }
    .fx-card{ margin-bottom:.35rem; }
    .table-wrap-scroll{ max-height:380px; }
  }

  @media print{
    .fx-shell{ background:#fff; box-shadow:none; }
    .fx-card{ box-shadow:none; border-color:#e5e7eb; background:#fff; color:#111827; }
    .fx-card-header{ background:#f3f4f6; color:#111827; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="fx-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-4 fx-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="fx-header-icon">
          <i class="bi bi-cash-coin"></i>
        </span>
        <h4 class="m-0 fw-bold fx-title">Pengeluaran</h4>
      </div>
      <div class="fx-subtitle">
        Catat biaya operasional & lihat riwayat pengeluaran dengan tampilan ringkas.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none fx-header-actions">
      <button type="button" class="btn btn-soft-dark btn-sm px-3 py-2" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  {{-- ALERT SUCCESS --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none bg-success-subtle border-success text-success-emphasis" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    {{-- KIRI: FORM PENGELUARAN --}}
    <div class="col-lg-5 col-md-6">
      <div class="card fx-card h-100">
        <div class="card-header fx-card-header">
          <div class="d-flex justify-content-between align-items-center">
            <span><i class="bi bi-pencil-square me-2"></i> Catat Pengeluaran</span>
            <span class="badge-soft">INPUT</span>
          </div>
        </div>
        <div class="card-body p-4">
          <form method="post" action="{{ route('purchases.expenses.store') }}">
            @csrf
            
            {{-- [BARU] PILIH SUMBER DANA --}}
            <div class="mb-3">
                <label class="form-label fx-form-label text-warning">Pakai Uang Dari?</label>
                <select name="fund_source" class="form-select text-white" style="border-color: #f59e0b;">
                    <option value="ps">💰 Pendapatan Billing PS</option>
                    <option value="product">🍔 Pendapatan Jual Produk</option>
                    <option value="other">🏦 Kas Lainnya / Modal</option>
                </select>
                <div class="form-text text-secondary small" style="opacity: 0.7;">Pilih saldo yang akan dikurangi.</div>
            </div>

            <div class="mb-3">
              <label class="form-label fx-form-label">Kategori</label>
              <input name="category" class="form-control" placeholder="Listrik / Belanja Stok / Sewa / dll." required value="{{ old('category') }}">
            </div>
            <div class="mb-3">
              <label class="form-label fx-form-label">Deskripsi</label>
              <input name="description" class="form-control" placeholder="Opsional, mis. bulan November" value="{{ old('description') }}">
            </div>
            <div class="mb-3">
              <label class="form-label fx-form-label">Total Harga (Rp)</label>
              <div class="input-group">
                  <span class="input-group-text bg-dark border-secondary text-secondary" style="font-size: 0.8rem;">Rp</span>
                  <input name="amount" type="number" class="form-control mono" value="{{ old('amount') }}" required placeholder="0">
              </div>
            </div>
            <div class="mb-1">
              <label class="form-label fx-form-label">Tanggal &amp; Waktu (opsional)</label>
              <input name="timestamp" type="datetime-local" class="form-control" value="{{ old('timestamp') }}">
            </div>
            <small class="text-grey d-block mt-1 mb-4" style="font-size: 0.75rem;">
              * Jika dikosongkan, sistem akan memakai waktu saat ini.
            </small>

            <button class="btn-main-submit">
              <i class="bi bi-plus-circle me-2"></i> SIMPAN PENGELUARAN
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- KANAN: RIWAYAT PENGELUARAN --}}
    <div class="col-lg-7 col-md-6">
      <div class="card fx-card h-100">
        <div class="card-header fx-card-header d-flex justify-content-between align-items-center">
          <span><i class="bi bi-clock-history me-2"></i> Riwayat Pengeluaran</span>
          <span class="badge-soft text-info fw-bold">
            {{ $items->total() }} DATA
          </span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive table-wrap-scroll">
            <table class="table table-sm m-0 align-middle table-neon">
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th class="text-end">Jumlah</th>
                  <th class="text-end d-print-none" style="width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($items as $e)
                  <tr>
                    <td>
                        <div class="d-flex flex-column small text-secondary">
                            <span>{{ optional($e->timestamp)->format('d-m-Y') ?? '-' }}</span>
                            <span class="text-light fw-bold">{{ optional($e->timestamp)->format('H:i') ?? '' }}</span>
                        </div>
                    </td>
                    <td>
                        {{-- Badge Sumber Dana --}}
                        @if($e->fund_source === 'ps')
                            <span class="badge-fund bg-fund-ps" style="border-color: #3b82f6; color: #93c5fd;">DARI BILLING PS</span>
                        @elseif($e->fund_source === 'product')
                            <span class="badge-fund bg-fund-product" style="border-color: #22c55e; color: #86efac;">DARI PRODUK</span>
                        @else
                            {{-- Default jika null atau 'other' --}}
                            <span class="badge-fund bg-fund-other">DARI KAS LAIN</span>
                        @endif
                        
                        <div class="text-white mt-1">{{ $e->category }}</div>
                    </td>
                    <td>
                        <div class="text-truncate text-secondary" style="max-width:180px;">
                            {{ $e->description ?: '-' }}
                        </div>
                    </td>
                    <td class="text-end amount-mono text-white fw-bold">
                      Rp {{ number_format($e->amount,0,',','.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <div class="btn-icon-group">
                        {{-- TOMBOL EDIT --}}
                        <button type="button"
                                class="btn-icon btn-icon-edit btn-edit-expense"
                                title="Edit"
                                data-id="{{ $e->id }}"
                                data-fund="{{ $e->fund_source ?? 'other' }}"
                                data-category="{{ $e->category }}"
                                data-description="{{ $e->description }}"
                                data-amount="{{ $e->amount }}"
                                data-timestamp="{{ optional($e->timestamp)->format('Y-m-d\TH:i') }}">
                          <i class="bi bi-pencil"></i>
                        </button>

                        {{-- HAPUS --}}
                        <form class="d-inline confirm-delete"
                              method="post"
                              action="{{ route('purchases.expenses.destroy', $e->id) }}">
                          @csrf
                          @method('DELETE')
                          <button class="btn-icon btn-icon-del" title="Hapus">
                            <i class="bi bi-trash3"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-white p-5">
                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                        Belum ada data pengeluaran.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="p-3 border-top border-secondary border-opacity-25 bg-black bg-opacity-20">
            {{ $items->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL EDIT PENGELUARAN --}}
<div class="modal fade" id="expenseEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content fx-card">
      <div class="fx-card-header">
        <span><i class="bi bi-pencil-square me-2"></i> Edit Pengeluaran</span>
      </div>
      <form id="expenseEditForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          
          {{-- [BARU] EDIT SUMBER DANA --}}
          <div class="mb-3">
             <label class="form-label fx-form-label text-warning">Sumber Dana</label>
             <select name="fund_source" id="editFund" class="form-select text-white" style="border-color: #f59e0b;">
                 <option value="ps">💰 Pendapatan Billing PS</option>
                 <option value="product">🍔 Pendapatan Jual Produk</option>
                 <option value="other">🏦 Kas Lainnya / Modal</option>
             </select>
          </div>

          <div class="mb-3">
            <label class="form-label fx-form-label">Kategori</label>
            <input id="editCategory" name="category" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fx-form-label">Deskripsi</label>
            <input id="editDescription" name="description" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fx-form-label">Total Harga (Rp)</label>
            <div class="input-group">
              <span class="input-group-text bg-dark border-secondary text-secondary" style="font-size: 0.8rem;">Rp</span>
              <input id="editAmount" name="amount" type="number" class="form-control mono" required>
            </div>
          </div>
          <div class="mb-0">
            <label class="form-label fx-form-label">Tanggal &amp; Waktu</label>
            <input id="editTimestamp" name="timestamp" type="datetime-local" class="form-control">
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between px-4 pb-4 pt-2 border-0">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-main-submit btn-sm px-4" style="width:auto;">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const baseUrl   = "{{ url('/purchases/expenses') }}";
  const modalEl   = document.getElementById('expenseEditModal');
  const editModal = modalEl ? new bootstrap.Modal(modalEl) : null;

  const form      = document.getElementById('expenseEditForm');
  const inFund    = document.getElementById('editFund'); // Baru
  const inCat     = document.getElementById('editCategory');
  const inDesc    = document.getElementById('editDescription');
  const inAmount  = document.getElementById('editAmount');
  const inTs      = document.getElementById('editTimestamp');

  if (!editModal || !form) return;

  document.querySelectorAll('.btn-edit-expense').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id    = this.dataset.id;
      const fund  = this.dataset.fund || 'other'; // Baru
      const cat   = this.dataset.category || '';
      const desc  = this.dataset.description || '';
      const amt   = this.dataset.amount || '';
      const ts    = this.dataset.timestamp || '';

      inFund.value   = fund; // Set dropdown modal
      inCat.value    = cat;
      inDesc.value   = desc;
      inAmount.value = amt;
      inTs.value     = ts;

      form.action = baseUrl + '/' + id;

      editModal.show();
    });
  });
});
</script>
@endpush