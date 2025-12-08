@extends('layouts.fixplay')

@section('page_title','Struk Penjualan')

@push('styles')
<style>
  /* ====== STRUK SHELL (FUTURISTIC) ====== */
  .card-receipt {
    border-radius: 1.25rem;
    border: 1px solid rgba(148,163,184,.25);
    /* Background Gelap Transparan */
    background: linear-gradient(145deg, rgba(2,6,23,0.95), rgba(15,23,42,0.9));
    color: #e5e7eb;
    box-shadow: 0 20px 40px rgba(0,0,0,.7);
    backdrop-filter: blur(12px);
    overflow: hidden;
  }

  /* Typography */
  .text-soft { color: #94a3b8; font-size: 0.85rem; }
  .text-highlight { color: #f9fafb; font-weight: 600; }
  .amount-mono { font-family: 'Consolas', 'Monaco', monospace; color: #818cf8; font-weight: 600; }
  
  /* Separator Garis Putus-Putus */
  .dashed-line {
    border-top: 1px dashed rgba(148, 163, 184, 0.3);
    margin: 1rem 0;
  }

  /* ====== TABEL ITEM (NEON STYLE) ====== */
  .table-receipt { width: 100%; margin-bottom: 0; color: #cbd5e1; }
  .table-receipt thead th {
    background: rgba(30, 41, 59, 0.4);
    color: #94a3b8;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 0.75rem 1rem;
  }
  .table-receipt tbody td {
    background: transparent;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 0.75rem 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
    color: #f1f5f9;
  }
  
  /* Tombol */
  .btn-outline-secondary { color: #cbd5e1; border-color: #475569; border-radius: 999px; }
  .btn-outline-secondary:hover { background: #475569; color: #fff; }
  .btn-primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; border-radius: 999px;
    box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4); font-weight: 600;
  }
  .btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }

  

  /* Print Mode */
  @media print {
    .card-receipt { box-shadow: none; border: 1px solid #000; background: #fff; color: #000; }
    .table-receipt thead th, .table-receipt tbody td { color: #000; border-color: #ccc; }
    .text-soft, .amount-mono { color: #000 !important; }
    .d-print-none { display: none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    
    <div class="card card-receipt">
      <div class="card-body p-4">
        
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h5 class="mb-1 fw-bold text-white"><i class="bi bi-receipt me-2 text-primary"></i>Struk Penjualan</h5>
            <div class="text-soft">
              @php $ts = $sale->timestamp ?? $sale->sold_at ?? $sale->created_at; @endphp
              <span class="d-block">No: <strong>#{{ $sale->id }}</strong></span>
              <span class="d-block">{{ \Carbon\Carbon::parse($ts)->format('d M Y, H:i') }}</span>
            </div>
          </div>
          
          @php $grandTotal = $sale->total ?? $sale->total_amount ?? 0; @endphp
          <div class="text-end">
            <div class="text-soft small text-uppercase mb-1">Total Tagihan</div>
            <div class="fs-4 amount-mono text-white">
              Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </div>
          </div>
        </div>

        <div class="dashed-line"></div>

        {{-- TABEL ITEM --}}
        <div class="table-responsive mb-3">
          <table class="table table-receipt">
            <thead>
              <tr>
                <th>Item / Deskripsi</th>
                <th class="text-center" style="width: 60px;">Qty</th>
                <th class="text-end" style="width: 100px;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @php
                $itemList = isset($items) ? $items : ($sale->items ?? []);
              @endphp

              @forelse($itemList as $it)
                @php
                  $itemName = $it->product_name ?? ($it->product->name ?? ($it->description ?? '-'));
                  if ($itemName === '-' && !empty($sale->note)) { $itemName = $sale->note; }
                  $qtyVal = (int) ($it->qty ?? 0);
                  $subVal = (int) ($it->subtotal ?? 0);
                @endphp
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $itemName }}</div>
                    @if(isset($it->unit_price))
                      <div class="small text-soft">@ Rp {{ number_format($it->unit_price, 0, ',', '.') }}</div>
                    @endif
                  </td>
                  <td class="text-center amount-mono">{{ $qtyVal }}</td>
                  <td class="text-end amount-mono">Rp {{ number_format($subVal, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-soft py-3">Tidak ada item.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- INFO PEMBAYARAN --}}
        @php
          $method       = $sale->payment_method ?? '-';
          $paidAmount   = (int) ($sale->paid_amount ?? 0);
          $changeAmount = (int) ($sale->change_amount ?? 0);
        @endphp

        <div class="p-3 rounded-3 mb-3" style="background: rgba(0,0,0,0.2);">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-soft">Metode Bayar</span>
            <span class="text-highlight text-uppercase">{{ $method }}</span>
          </div>
          
          @if(strtolower($method) === 'tunai')
            <div class="d-flex justify-content-between mb-1">
              <span class="text-soft">Uang Diterima</span>
              <span class="text-highlight">Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
            </div>
            <div class="dashed-line my-2" style="opacity: 0.5;"></div>
            <div class="d-flex justify-content-between">
              <span class="text-soft">Kembalian</span>
              <span class="amount-mono text-info">Rp {{ number_format($changeAmount, 0, ',', '.') }}</span>
            </div>
          @endif
        </div>

        {{-- CATATAN --}}
        @if(!empty($sale->note))
          <div class="alert alert-dark bg-transparent border-secondary text-soft small d-flex align-items-start">
            <i class="bi bi-info-circle me-2 mt-1"></i>
            <div>
              <strong>Catatan:</strong> {{ $sale->note }}
            </div>
          </div>
        @endif

        {{-- FOOTER AKSI --}}
        <div class="d-flex gap-2 mt-4 d-print-none">
          <a href="{{ $backUrl ?? route('dashboard') }}" class="btn btn-outline-secondary flex-grow-1">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <button onclick="window.print()" class="btn btn-primary flex-grow-1">
            <i class="bi bi-printer me-1"></i> Cetak Struk
          </button>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection