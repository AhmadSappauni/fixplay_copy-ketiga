@extends('layouts.fixplay')

@section('page_title', 'Edit Transaksi')

@push('styles')
<style>
  /* ====== EDIT SHELL (FUTURISTIC) ====== */
  .card-edit {
    border-radius: 1.25rem;
    border: 1px solid rgba(148,163,184,.25);
    /* Background Gelap Transparan */
    background: linear-gradient(145deg, rgba(2,6,23,0.95), rgba(15,23,42,0.9));
    color: #e5e7eb;
    box-shadow: 0 20px 40px rgba(0,0,0,.7);
    backdrop-filter: blur(12px);
    overflow: hidden;
  }

  .card-header-edit {
    border-bottom: 1px solid rgba(148,163,184,.2);
    background: rgba(30, 41, 59, 0.4);
    padding: 1rem 1.5rem;
  }

  /* Form Input Style */
  .form-label {
    font-size: 0.8rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .form-control, .form-select {
    background: rgba(15,23,42,.6);
    border-radius: .6rem;
    border: 1px solid rgba(148,163,184,.3);
    color: #f3f4f6;
    font-size: .9rem;
    padding: 0.6rem 0.8rem;
  }

  .form-control:focus, .form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.25);
    background: rgba(2,6,23,.8);
    color: #fff;
  }

  /* Input Readonly tapi tetap terbaca jelas */
  .form-control[readonly] {
    background: rgba(30, 41, 59, 0.3);
    color: #cbd5e1;
    border-color: rgba(148,163,184,.15);
    cursor: not-allowed;
  }

  .input-group-text {
    background: rgba(15,23,42,.8);
    border-color: rgba(148,163,184,.3);
    color: #94a3b8;
  }

  /* Tombol */
  .btn-primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none;
    border-radius: 999px;
    box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
    font-weight: 600;
    padding: 0.6rem 1.5rem;
  }
  .btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }
  
  .btn-outline-secondary {
    color: #cbd5e1; border-color: #475569; border-radius: 999px;
  }
  .btn-outline-secondary:hover { background: #475569; color: #fff; }

  .alert-info-soft {
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.3);
    color: #bae6fd;
    font-size: 0.85rem;
    border-radius: 0.75rem;
  }

  input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
  }
</style>
@endpush

@section('page_content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    
    <div class="card card-edit">
      
      <div class="card-header-edit d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0 fw-bold text-white">Edit Transaksi</h5>
          <small class="text-secondary">ID Transaksi: #{{ $sale->id }}</small>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>

      <div class="card-body p-4">
        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="alert alert-info-soft d-flex align-items-center mb-4">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>
              Mengubah <b>Total Tagihan</b> akan otomatis memperbarui harga item & sesi terkait.
            </div>
          </div>

          <div class="row g-3 mb-3">
            {{-- WAKTU TRANSAKSI --}}
            <div class="col-md-6">
              <label class="form-label">Waktu Transaksi</label>
              <input type="datetime-local" name="created_at" class="form-control" 
                     value="{{ \Carbon\Carbon::parse($sale->sold_at ?? $sale->created_at)->format('Y-m-d\TH:i') }}" required>
            </div>

            {{-- TOTAL TAGIHAN --}}
            <div class="col-md-6">
              <label class="form-label text-warning">Total Tagihan (Baru)</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="total_bill" class="form-control fw-bold text-white" 
                       value="{{ $sale->total > 0 ? $sale->total : $sale->items->sum('subtotal') }}" required>
              </div>
            </div>
          </div>

          <hr class="border-secondary border-opacity-25 my-4">

          <div class="row g-3 mb-3">
            {{-- METODE PEMBAYARAN --}}
            <div class="col-md-4">
              <label class="form-label">Metode Bayar</label>
              <select name="payment_method" class="form-select">
                <option value="Tunai" {{ $sale->payment_method == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="QRIS" {{ $sale->payment_method == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                <option value="Transfer" {{ $sale->payment_method == 'Transfer' ? 'selected' : '' }}>Transfer</option>
              </select>
            </div>

            {{-- DIBAYAR --}}
            <div class="col-md-4">
              <label class="form-label">Uang Diterima</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="paid_amount" class="form-control" 
                       value="{{ $sale->paid_amount }}">
              </div>
            </div>
            
            {{-- KEMBALIAN (Hanya Info) --}}
            <div class="col-md-4">
              <label class="form-label text-white">Kembalian (Info)</label>
              <input type="text" class="form-control" readonly 
                     value="Rp {{ number_format($sale->change_amount, 0, ',', '.') }}">
            </div>
          </div>

          {{-- CATATAN --}}
          <div class="mb-4">
            <label class="form-label">Catatan Transaksi</label>
            <input type="text" name="note" class="form-control" 
                   value="{{ $sale->note }}" placeholder="Tambahkan catatan jika perlu...">
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4 py-2">
              <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection