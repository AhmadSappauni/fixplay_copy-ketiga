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
  .fx-shell > *{
    position:relative;
    z-index:1;
  }

  .fx-header-icon{
    width:38px;
    height:38px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#f97316,#facc15);
    color:#111827;
    box-shadow:0 10px 24px rgba(248,250,252,.2);
    font-size:18px;
  }
  .fx-title{
    color:#f9fafb;
  }
  .fx-subtitle{
    color:#9ca3af;
    font-size:.8rem;
  }

  .btn-soft-dark{
    border-radius:999px;
    border:1px solid rgba(148,163,184,.5);
    background:rgba(15,23,42,.85);
    color:#e5e7eb;
  }
  .btn-soft-dark:hover{
    background:rgba(15,23,42,1);
    color:#fff;
  }

  /* ====== CARD FUTURISTIK ====== */
  .fx-card{
    border-radius:1.2rem;
    border:1px solid rgba(148,163,184,.45);
    background:linear-gradient(145deg,#020617,#020617 45%,#0b1120 100%);
    box-shadow:0 18px 40px rgba(15,23,42,.95);
    color:#e5e7eb;
  }
  .fx-card-header{
    border-bottom:1px solid rgba(55,65,81,.9);
    background:linear-gradient(90deg,rgba(15,23,42,1),rgba(15,23,42,.8));
    font-size:.78rem;
    letter-spacing:.08em;
    text-transform:uppercase;
    font-weight:700;
  }

  .fx-form-label{
    font-size:.8rem;
    font-weight:600;
    color:#cbd5f5;
  }

  /* ====== TABEL RIWAYAT ====== */
  .table-neon{
    margin-bottom:0;
    color:#d1d5db;
    font-size:.82rem;
  }
  .table-neon thead th{
    background:linear-gradient(90deg,#020617,#030712);
    border-bottom:1px solid rgba(55,65,81,.9);
    color:#9ca3af;
    text-transform:uppercase;
    letter-spacing:.07em;
    font-size:.75rem;
  }
  .table-neon tbody tr{
    border-color:rgba(31,41,55,.9);
    transition:background .14s ease, transform .06s ease;
  }
  .table-neon tbody tr:hover{
    background:rgba(79,70,229,.18);
    transform:translateY(-1px);
  }
  .table-neon td,
  .table-neon th{
    border-color:rgba(31,41,55,.9);
  }

  .amount-mono{
    font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  /* ====== BADGE KOSONG / STATUS ====== */
  .badge-soft{
    border-radius:999px;
    padding:.25rem .7rem;
    font-size:.7rem;
  }

  /* ====== RESPONSIVE ====== */
  @media (max-width: 992px){
    .fx-shell{
      padding:1.3rem 1rem 1.8rem;
      border-radius:1.1rem;
    }
  }
  @media (max-width: 768px){
    .fx-header-stack{
      flex-direction:column;
      align-items:flex-start !important;
      gap:.6rem;
    }
    .fx-header-actions{
      width:100%;
      justify-content:flex-end;
    }
    .fx-card{
      margin-bottom:.35rem;
    }
    .table-wrap-scroll{
      max-height:380px;
    }
  }

  @media print{
    .fx-shell{
      background:#fff;
      box-shadow:none;
    }
    .fx-card{
      box-shadow:none;
      border-color:#e5e7eb;
      background:#fff;
      color:#111827;
    }
    .fx-card-header{
      background:#f3f4f6;
      color:#111827;
    }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="fx-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-3 fx-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="fx-header-icon">
          <i class="bi bi-cash-coin"></i>
        </span>
        <h4 class="m-0 fw-semibold fx-title">Pengeluaran</h4>
      </div>
      <div class="fx-subtitle">
        Catat biaya operasional & lihat riwayat pengeluaran dengan tampilan ringkas.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none fx-header-actions">
      <button type="button" class="btn btn-soft-dark" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  {{-- ALERT SUCCESS --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-3">
    {{-- KIRI: FORM PENGELUARAN --}}
    <div class="col-lg-5 col-md-6">
      <div class="card fx-card h-100">
        <div class="card-header fx-card-header">
          <div class="d-flex justify-content-between align-items-center">
            <span>Catat Pengeluaran</span>
            <span class="badge-soft bg-dark text-warning text-uppercase">Input</span>
          </div>
        </div>
        <div class="card-body">
          <form method="post" action="{{ route('purchases.expenses.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fx-form-label">Kategori</label>
              <input name="category"
                     class="form-control"
                     placeholder="Listrik / Belanja Stok / Sewa / dll."
                     required
                     value="{{ old('category') }}">
            </div>
            <div class="mb-3">
              <label class="form-label fx-form-label">Deskripsi</label>
              <input name="description"
                     class="form-control"
                     placeholder="Opsional, mis. bulan November / pembelian stok minuman"
                     value="{{ old('description') }}">
            </div>
            <div class="mb-3">
              <label class="form-label fx-form-label">Jumlah (Rp)</label>
              <input name="amount"
                     type="number"
                     class="form-control"
                     value="{{ old('amount') }}"
                     required>
            </div>
            <div class="mb-1">
              <label class="form-label fx-form-label">Tanggal &amp; Waktu (opsional)</label>
              <input name="timestamp"
                     type="datetime-local"
                     class="form-control"
                     value="{{ old('timestamp') }}">
            </div>
            <small class="text-secondary d-block mt-1">
              Jika dikosongkan, sistem akan memakai waktu saat ini.
            </small>

            <button class="btn btn-primary mt-3 w-100">
              <i class="bi bi-plus-circle me-1"></i> Simpan Pengeluaran
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- KANAN: RIWAYAT PENGELUARAN --}}
    <div class="col-lg-7 col-md-6">
      <div class="card fx-card h-100">
        <div class="card-header fx-card-header d-flex justify-content-between align-items-center">
          <span>Riwayat Pengeluaran</span>
          <span class="badge-soft bg-dark text-info text-uppercase">
            {{ $items->total() }} Data
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
                  <th class="text-end d-print-none"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($items as $e)
                  <tr>
                    <td class="text-nowrap">
                      {{ optional($e->timestamp)->format('d-m-Y H:i') ?? '-' }}
                    </td>
                    <td>{{ $e->category }}</td>
                    <td class="text-truncate" style="max-width:220px;">
                      {{ $e->description ?: '-' }}
                    </td>
                    <td class="text-end amount-mono">
                      Rp {{ number_format($e->amount,0,',','.') }}
                    </td>
                    <td class="text-end d-print-none">
                      <div class="btn-group btn-group-sm" role="group">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                title="Edit"
                                onclick="return editExpense(
                                  {{ $e->id }},
                                  '{{ addslashes($e->category) }}',
                                  '{{ addslashes($e->description ?: '') }}',
                                  {{ $e->amount }},
                                  '{{ $e->timestamp ? $e->timestamp->format('Y-m-d\\TH:i') : '' }}'
                                )">
                          <i class="bi bi-pencil"></i>
                        </button>

                        <form class="d-inline confirm-delete"
                              method="post"
                              action="{{ route('purchases.expenses.destroy', $e->id) }}"
                              onsubmit="return confirm('Hapus pengeluaran ini?');">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-outline-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted p-3">
                      Belum ada data pengeluaran.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="p-3 border-top border-dark-subtle bg-black bg-opacity-25">
            {{ $items->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function editExpense(id, category, description, amount, ts) {
  const newCat  = prompt("Kategori:", category || "");
  if (newCat === null) return false;

  const newDesc = prompt("Deskripsi:", description || "");
  if (newDesc === null) return false;

  const newAmt  = prompt("Jumlah (Rp):", amount);
  if (newAmt === null) return false;

  const newTs   = prompt("Waktu (YYYY-MM-DDTHH:MM):", ts || "");
  if (newTs === null) return false;

  const form = document.createElement('form');
  form.method = 'post';
  form.action = '/purchases/expenses/' + id;

  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (!tokenMeta) {
    alert('CSRF token tidak ditemukan di halaman.');
    return false;
  }

  const token = tokenMeta.getAttribute('content');
  const _token = document.createElement("input");
  _token.type="hidden";
  _token.name="_token";
  _token.value=token;
  form.appendChild(_token);

  const _method = document.createElement("input");
  _method.type="hidden";
  _method.name="_method";
  _method.value="PUT";
  form.appendChild(_method);

  [['category', newCat], ['description', newDesc], ['amount', newAmt], ['timestamp', newTs]].forEach(function(pair){
    const k = pair[0], v = pair[1];
    const i = document.createElement('input');
    i.type='hidden';
    i.name=k;
    i.value=v;
    form.appendChild(i);
  });

  document.body.appendChild(form);
  form.submit();
  return false;
}
</script>
@endpush
