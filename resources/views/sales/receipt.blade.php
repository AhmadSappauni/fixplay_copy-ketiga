@extends('layouts.fixplay')

@section('page_title','Struk Penjualan')

@section('page_content')
<div class="card card-dark">
  <div class="card-body">
    {{-- HEADER STRUK --}}
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h5 class="mb-0">Struk Penjualan</h5>
        {{-- Tanggal: cek timestamp, sold_at, lalu created_at --}}
        <div class="text-soft">
          @php
            $ts = $sale->timestamp ?? $sale->sold_at ?? $sale->created_at;
          @endphp
          #{{ $sale->id }} • {{ \Carbon\Carbon::parse($ts)->format('d-m-Y H:i') }}
        </div>
      </div>

      {{-- Total: cek total, lalu total_amount --}}
      @php
        $grandTotal = $sale->total ?? $sale->total_amount ?? 0;
      @endphp
      <div class="fw-bold fs-5 amount-mono">
        Rp {{ number_format($grandTotal, 0, ',', '.') }}
      </div>
    </div>

    <hr class="my-3">

    {{-- DETAIL ITEM --}}
    <div class="table-responsive">
      <table class="table table-sm align-middle m-0 table-neon">
        <thead>
          <tr>
            <th>Item</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Harga</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @php
            // Gunakan $items (jika dikirim dari controller via query builder),
            // kalau tidak ada, fallback ke relasi Eloquent $sale->items
            $itemList = isset($items) ? $items : ($sale->items ?? []);
          @endphp

          @forelse($itemList as $it)
            @php
              // Ambil nama product dari berbagai sumber
              $itemName = $it->product_name
                ?? ($it->product->name ?? ($it->description ?? '-'));

              // Jika masih '-' tapi sale punya catatan (kasus sesi PS),
              // pakai catatan sebagai nama item supaya tidak kosong.
              if ($itemName === '-' && !empty($sale->note)) {
                  $itemName = $sale->note;
              }

              $qtyVal    = (int) ($it->qty ?? 0);
              $subVal    = (int) ($it->subtotal ?? 0);
              $unitPrice = $it->unit_price ?? ($qtyVal > 0 ? (int) round($subVal / $qtyVal) : 0);
            @endphp

            <tr>
              <td>{{ $itemName }}</td>
              <td class="text-center amount-mono">{{ $qtyVal }}</td>
              <td class="text-end amount-mono">
                Rp {{ number_format($unitPrice, 0, ',', '.') }}
              </td>
              <td class="text-end amount-mono">
                Rp {{ number_format($subVal, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-3">
                Tidak ada item pada transaksi ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- INFORMASI PEMBAYARAN --}}
    @php
      $method       = $sale->payment_method ?? null;
      $paidAmount   = (int) ($sale->paid_amount ?? 0);
      $changeAmount = (int) ($sale->change_amount ?? 0);
    @endphp

    @if($method)
      <div class="mt-3">
        <span class="text-soft">Metode:</span>
        <strong>{{ $method }}</strong>

        @if(strtolower($method) === 'tunai')
          • <span class="text-soft">Dibayar:</span>
          <span class="amount-mono">
            Rp {{ number_format($paidAmount, 0, ',', '.') }}
          </span>
          • <span class="text-soft">Kembalian:</span>
          <span class="amount-mono">
            Rp {{ number_format($changeAmount, 0, ',', '.') }}
          </span>
        @endif
      </div>
    @endif

    {{-- CATATAN --}}
    @if(!empty($sale->note))
      <div class="text-soft mt-2">
        Catatan: {{ $sale->note }}
      </div>
    @endif

    {{-- TOMBOL AKSI --}}
    <div class="mt-3 d-print-none">
      <a href="{{ $backUrl ?? route('dashboard') }}" class="btn btn-outline-secondary">
        Kembali
      </a>
      <button onclick="window.print()" class="btn btn-primary">
        Cetak
      </button>
    </div>
  </div>
</div>
@endsection
