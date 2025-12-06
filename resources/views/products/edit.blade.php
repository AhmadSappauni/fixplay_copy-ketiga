@extends('layouts.fixplay')

@section('title','Edit Produk')
@section('page_title','Edit Produk')

@push('styles')
<style>
  .fp-edit-shell{
    max-width:900px;
    margin:0 auto;
  }
  .fp-edit-card{
    border-radius:24px;
    padding:22px 22px 20px;
    color:#e5e7ff;
    background:
      radial-gradient(130% 150% at 0% 0%, rgba(129,140,248,.22), transparent 45%),
      radial-gradient(130% 150% at 100% 0%, rgba(56,189,248,.18), transparent 50%),
      linear-gradient(180deg,#020617,#02051b 45%,#020617 100%);
    box-shadow:
      0 28px 60px rgba(15,23,42,.80),
      0 0 0 1px rgba(148,163,255,.25) inset;
  }
  .fp-edit-header{
    display:flex;
    align-items:flex-start;
    gap:14px;
    margin-bottom:18px;
  }
  .fp-chip-icon{
    width:40px;height:40px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#8b5cf6,#4f46e5);
    color:#fff;
    box-shadow:0 12px 28px rgba(79,70,229,.85);
    font-size:18px;
  }
  .fp-edit-title{
    font-size:1.5rem;
    font-weight:900;
  }
  .fp-edit-sub{
    font-size:.9rem;
    color:#cbd5f5;
  }
  .fp-back-btn{
    border-radius:999px;
    padding:6px 14px;
    font-size:.8rem;
    border:1px solid rgba(148,163,255,.55);
    background:rgba(15,23,42,.8);
    color:#e5e7ff;
  }
  .fp-back-btn:hover{
    background:rgba(15,23,42,1);
    color:#fff;
  }
  .fp-section-label{
    font-size:.8rem;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:#9ca3af;
    margin-bottom:4px;
  }
  .fp-form-card{
    border-radius:18px;
    padding:18px 18px 10px;
    background:rgba(15,23,42,.92);
    border:1px solid rgba(30,64,175,.8);
  }
  .fp-form-card .form-label{
    font-size:.85rem;
    color:#e5e7ff;
  }
  .fp-form-card .form-control,
  .fp-form-card .form-select{
    background:#020617;
    border-color:rgba(148,163,255,.5);
    color:#e5e7ff;
  }
  .fp-form-card .form-control:focus,
  .fp-form-card .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 1px rgba(129,140,248,.8);
  }
  .fp-badge-pill{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:4px 10px;
    border-radius:999px;
    font-size:.75rem;
    border:1px solid rgba(148,163,255,.55);
    background:rgba(15,23,42,.85);
    color:#c7d2fe;
  }
  .fp-save-btn{
    border-radius:999px;
    padding:10px 22px;
    border:none;
    font-weight:700;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    box-shadow:0 12px 26px rgba(34,197,94,.55);
  }
  .fp-save-btn:hover{
    filter:brightness(1.07);
  }
  .fp-error-list{
    border-radius:14px;
    padding:10px 14px;
    background:rgba(248,113,113,.08);
    border:1px solid rgba(248,113,113,.6);
    color:#fecaca;
    font-size:.85rem;
  }

  @media (max-width: 768px){
    .fp-edit-card{
      padding:18px 14px 16px;
    }
    .fp-edit-header{
      flex-direction:column;
    }
  }
</style>
@endpush

@section('page_content')
<div class="fp-edit-shell">
  <div class="fp-edit-card">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div class="fp-edit-header">
        <div class="fp-chip-icon">
          <i class="bi bi-basket2"></i>
        </div>
        <div>
          <div class="fp-edit-title">Edit Produk</div>
          <div class="fp-edit-sub">
            Perbarui informasi produk makanan &amp; minuman di kasir Fixplay.
          </div>
        </div>
      </div>
      <a href="{{ route('products.index') }}" class="fp-back-btn">
        <i class="bi bi-arrow-left-short me-1"></i> Kembali
      </a>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
      <div class="fp-error-list mb-3">
        <strong>Oops, ada yang perlu dicek:</strong>
        <ul class="mb-0 mt-1">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- FORM --}}
    <div class="fp-section-label">Detail produk</div>
    <div class="fp-form-card mb-3">
      <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Nama produk</label>
          <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $product->name) }}"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <input
            type="text"
            name="category"
            class="form-control"
            placeholder="Makanan / Minuman / Cemilan ..."
            value="{{ old('category', $product->category) }}"
          >
        </div>

        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Harga (Rp)</label>
            <input
              type="number"
              name="price"
              class="form-control"
              placeholder="0"
              value="{{ old('price', $product->price) }}"
              required
            >
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input
              type="number"
              name="stock"
              class="form-control"
              placeholder="0"
              value="{{ old('stock', $product->stock) }}"
              required
            >
          </div>
          <div class="col-md-4">
            <label class="form-label">Satuan</label>
            <input
              type="text"
              name="unit"
              class="form-control"
              placeholder="pcs / cup / botol ..."
              value="{{ old('unit', $product->unit ?? 'pcs') }}"
            >
          </div>
        </div>

        <div class="mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
          <label class="fp-badge-pill mb-0">
            <input
              type="checkbox"
              name="active"
              value="1"
              class="form-check-input me-2"
              {{ old('active', $product->active ?? true) ? 'checked' : '' }}
            >
            Aktif di daftar produk
          </label>

          <button type="submit" class="fp-save-btn">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
