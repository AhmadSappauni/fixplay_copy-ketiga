@extends('layouts.fixplay')

@section('title','Produk & Stok')
@section('page_title','Produk & Stok')

@push('styles')
<style>
  .fp-card-shell{
    max-width:1300px;
    margin:0 auto;
  }
  .fp-card{
    border-radius:24px;
    padding:22px 22px 18px;
    color:#e5e7ff;
    background:
      radial-gradient(120% 140% at 0% 0%, rgba(129,140,248,.20), transparent 45%),
      radial-gradient(120% 160% at 100% 0%, rgba(59,130,246,.25), transparent 55%),
      linear-gradient(180deg,#020617,#02051b 45%,#020617 100%);
    box-shadow:
      0 28px 60px rgba(15,23,42,.80),
      0 0 0 1px rgba(148,163,255,.25) inset;
  }

  /* HEADER BAR SEPERTI SCREENSHOT */
  .fp-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1.2rem;
    flex-wrap:wrap;
  }
  .fp-header-main{
    display:flex;
    align-items:center;
    gap:.95rem;
    flex:1 1 auto;
    min-width:0;
  }
  .fp-header-icon{
    width:42px;
    height:42px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:radial-gradient(circle at 0 0,#a855f7,#4f46e5);
    box-shadow:0 14px 28px rgba(88,28,135,.7);
    color:#f9fafb;
    font-size:20px;
    flex-shrink:0;
  }
  .fp-header-title{
    font-size:1.25rem;
    font-weight:800;
    color:#f9fafb;
    line-height:1.3;
    white-space:nowrap;
  }
  .fp-header-sub{
    font-size:.82rem;
    color:#cbd5f5;
    white-space:normal;
    opacity: 60%;
  }
  .fp-header-text{
    min-width:0;
  }

  .fp-card-header-pill{
    display:inline-flex;
    align-items:center;
    padding:4px 14px;
    border-radius:999px;
    border:1px solid rgba(148,163,255,.55);
    font-size:.7rem;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:#c7d2fe;
    background:rgba(15,23,42,.75);
    margin-bottom:4px;
  }

  .fp-meta{
    font-size:.85rem;
    color:#9ca3af;
  }

  .fp-btn-primary{
    border-radius:999px;
    padding:10px 20px;
    font-weight:700;
    border:none;
    background:linear-gradient(135deg,#8b5cf6,#4f46e5);
    color:white;
    box-shadow:0 12px 26px rgba(129,140,248,.55);
    white-space:nowrap;
  }
  .fp-btn-primary:hover{
    filter:brightness(1.06);
  }

  .fp-search-wrap{
    margin-top:18px;
    margin-bottom:16px;
  }
  .fp-search-input{
    background:rgba(15,23,42,.85);
    border-radius:999px;
    border:1px solid rgba(148,163,255,.45);
    padding:12px 18px 12px 40px;
    color:#e5e7ff;
    outline:none;
    width:100%;
  }
  .fp-search-input::placeholder{ color:#64748b; }
  .fp-search-icon{
    position:absolute;
    left:15px; top:50%;
    transform:translateY(-50%);
    color:#64748b;
  }

  .fp-table-wrap{
    border-radius:18px;
    overflow:hidden;
    margin-top:10px;
    background:#020617;
    box-shadow:0 0 0 1px rgba(30,64,175,.7);
  }
  .fp-table thead th{
    background:rgba(15,23,42,.98);
    color:#c7d2fe;
    border-bottom:1px solid rgba(55,65,194,.8);
    font-size:.8rem;
    text-transform:uppercase;
    letter-spacing:.08em;
  }
  .fp-table tbody td{
    background:#020617;
    color:#e5e7ff;
    border-color:#020617;
    border-bottom:1px solid rgba(30,64,175,.5);
    font-size:.93rem;
  }

  .fp-status-pill{
    padding:4px 12px;
    border-radius:999px;
    font-size:.8rem;
    font-weight:700;
    background:rgba(34,197,94,.12);
    border:1px solid rgba(34,197,94,.6);
    color:#bbf7d0;
  }
  .fp-status-pill.inactive{
    background:rgba(248,113,113,.12);
    border-color:rgba(248,113,113,.7);
    color:#fecaca;
  }

  .fp-action-btn{
    width:32px;height:32px;
    border-radius:10px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
  }
  .fp-action-btn-edit{
    border:1px solid rgba(250,204,21,.8);
    background:rgba(250,204,21,.08);
    color:#facc15;
  }
  .fp-action-btn-del{
    border:1px solid rgba(248,113,113,.9);
    background:rgba(248,113,113,.08);
    color:#fecaca;
  }

  .fp-card-footer{
    margin-top:8px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:.75rem;
    font-size:.8rem;
    color:#9ca3af;
    flex-wrap:wrap;
  }

  /* RESPONSIVE */
  @media (max-width: 768px){
    .fp-card{
      padding:18px 14px 16px;
      border-radius:18px;
    }
    .fp-header{
      align-items:flex-start;
    }
    .fp-header-title{
      font-size:1.05rem;
      white-space:normal;
    }
    .fp-header-sub{
      font-size:.78rem;
    }
    .fp-card-shell{
      padding-inline:4px;
    }
    .fp-card-footer{
      flex-direction:column;
      align-items:flex-start;
    }
  }
</style>
@endpush

@section('page_content')
<div class="fp-card-shell">
  <div class="fp-card">

    {{-- HEADER SEPERTI GAMBAR --}}
    <div class="fp-header mb-2">
      <div class="fp-header-main">
        <div class="fp-header-icon">
          <i class="bi bi-basket"></i>
        </div>
        <div class="fp-header-text">
          <div class="fp-header-title">
            Kasir Fixplay - Penjualan Produk
          </div>
          <div class="fp-header-sub">
            Buat transaksi makanan &amp; minuman dengan cepat, cek riwayat dalam satu layar.
          </div>
          <div class="fp-card-header-pill mt-2">
            PRODUK &amp; STOK
          </div>
        </div>
      </div>

      <div class="text-end">
        <div class="fp-meta mb-2">
          Total produk:
          <span class="fw-bold text-white">{{ $items->total() }}</span>
        </div>
        <button type="button" class="fp-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
          <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </button>
      </div>
    </div>

    {{-- SEARCH BAR --}}
    <form method="GET" action="{{ route('products.index') }}" class="fp-search-wrap">
      <div class="position-relative">
        <span class="fp-search-icon"><i class="bi bi-search"></i></span>
        <input
          type="text"
          name="q"
          class="fp-search-input"
          placeholder="Cari nama produk atau kategori..."
          value="{{ request('q') }}"
        >
      </div>
    </form>

    {{-- TABEL PRODUK --}}
    <div class="fp-table-wrap mt-3">
      <div class="table-responsive">
        <table class="table table-hover table-sm mb-0 fp-table align-middle">
          <thead>
          <tr>
            <th style="width:60px;">#</th>
            <th>Nama</th>
            <th style="width:170px;">Kategori</th>
            <th style="width:160px;" class="text-end">Harga</th>
            <th style="width:110px;" class="text-end">Stok</th>
            <th style="width:110px;">Satuan</th>
            <th style="width:120px;">Status</th>
            <th style="width:130px;" class="text-end">Aksi</th>
          </tr>
          </thead>
          <tbody>
          @forelse($items as $idx => $p)
            <tr>
              <td>{{ $items->firstItem() + $idx }}</td>
              <td class="fw-semibold">{{ $p->name }}</td>
              <td>{{ $p->category ?: '-' }}</td>
              <td class="text-end">Rp {{ number_format($p->price,0,',','.') }}</td>
              <td class="text-end">{{ $p->stock }}</td>
              <td>{{ $p->unit ?: 'pcs' }}</td>
              <td>
                @php
                  $isActive = $p->active ?? true;
                @endphp
                <span class="fp-status-pill {{ $isActive ? '' : 'inactive' }}">
                  {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="text-end">
                <a href="{{ route('products.edit', $p->id) }}"
                   class="fp-action-btn fp-action-btn-edit me-1"
                   title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('products.destroy', $p->id) }}"
                      method="POST"
                      class="d-inline confirm-delete"
                      data-confirm="Yakin ingin menghapus produk ini?">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="fp-action-btn fp-action-btn-del"
                          title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                Belum ada produk yang terdaftar.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- FOOTER: INFO + PAGINATION --}}
    <div class="fp-card-footer">
      <div>
        Menampilkan
        <span class="text-white fw-semibold">
          {{ $items->count() ? $items->firstItem().' - '.$items->lastItem() : 0 }}
        </span>
        dari
        <span class="text-white fw-semibold">{{ $items->total() }}</span> produk
      </div>
      <div class="ms-auto">
        {{ $items->withQueryString()->links() }}
      </div>
    </div>

  </div>
</div>

{{-- MODAL TAMBAH PRODUK (INLINE, TANPA @include) --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('products.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama produk</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <input type="text" name="category" class="form-control" placeholder="Makanan / Minuman / Cemilan ...">
        </div>
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="price" class="form-control" min="0" value="0">
          </div>
          <div class="col-md-6">
            <label class="form-label">Stok awal</label>
            <input type="number" name="stock" class="form-control" min="0" value="0">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Satuan</label>
          <input type="text" name="unit" class="form-control" placeholder="pcs / cup / botol ..." value="pcs">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
