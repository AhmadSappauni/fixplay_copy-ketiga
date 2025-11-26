@extends('layouts.fixplay')

@section('page_title', 'Kasir Fixplay - Unit PS')

@push('styles')
<style>
  /* ====== SHELL FUTURISTIK ====== */
  .unit-shell{
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
  .unit-shell::before{
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
  .unit-shell > *{
    position:relative;
    z-index:1;
  }

  .unit-chip-icon{
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
  .unit-title-text{
    color:#f9fafb;
  }
  .unit-subtitle{
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
    background:linear-gradient(135deg,#6366f1,#a855f7);
    color:#f9fafb;
    box-shadow:0 12px 24px rgba(79,70,229,.75);
  }
  .btn-soft-primary:hover{
    filter:brightness(1.06);
  }

  /* ====== CARD FORM & LIST ====== */
  .unit-card{
    position:relative;
    border-radius:1.25rem;
    padding:1.1rem 1.25rem 1.25rem;
    background:radial-gradient(circle at top,#020617,#030712 55%,#020617);
    border:1px solid rgba(148,163,184,.5);
    box-shadow:0 20px 40px rgba(15,23,42,.9);
    color:#e5e7eb;
    overflow:hidden;
  }
  .unit-card::after{
    content:"";
    position:absolute;
    inset:auto -45% -45% auto;
    width:140px;
    height:140px;
    border-radius:999px;
    opacity:.35;
    filter:blur(18px);
    background:radial-gradient(circle,#6366f1,#0ea5e9);
  }
  .unit-card-header{
    font-size:.8rem;
    text-transform:uppercase;
    letter-spacing:.12em;
    color:#9ca3af;
    font-weight:700;
    margin-bottom:.6rem;
  }
  .unit-card label.form-label{
    font-size:.8rem;
    color:#cbd5f5;
  }

  .unit-input, .unit-shell .form-select{
    border-radius:.75rem;
    border:1px solid rgba(148,163,184,.5);
    background:rgba(15,23,42,.92);
    color:#e5e7eb;
  }
  .unit-input:focus,
  .unit-shell .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 1px rgba(99,102,241,.55);
    background:rgba(15,23,42,.98);
    color:#e5e7eb;
  }

  /* ====== TABEL UNIT ====== */
  .unit-table-wrapper{
    max-height:520px;
  }

  .table-unit{
    margin-bottom:0;
    color:#d1d5db;
  }
  .table-unit thead th{
    background:linear-gradient(90deg,#020617,#030712);
    border-bottom:1px solid rgba(55,65,81,.9);
    font-size:.78rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#9ca3af;
  }
  .table-unit tbody tr{
    border-color:rgba(31,41,55,.9);
    transition:background .14s ease, transform .06s ease;
  }
  .table-unit tbody tr:hover{
    background:rgba(79,70,229,.18);
    transform:translateY(-1px);
  }
  .table-unit td,
  .table-unit th{
    border-color:rgba(31,41,55,.9);
  }

  .mono{
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,"Liberation Mono","Courier New", monospace;
  }

  .badge-addon{
    border-radius:999px;
    padding:.1rem .45rem;
    font-size:.7rem;
  }
  .badge-glow{
    background:rgba(129,140,248,.12);
    color:#c7d2fe;
    box-shadow:0 0 14px rgba(129,140,248,.4);
    border:1px solid rgba(129,140,248,.4);
  }

  .btn-xs-ghost{
    border-radius:999px;
    padding:.15rem .55rem;
    font-size:.72rem;
  }

  /* ====== RESPONSIVE ====== */
  @media (max-width: 992px){
    .unit-shell{
      padding:1.35rem 1.1rem 2rem;
      border-radius:1.1rem;
    }
  }
  @media (max-width: 768px){
    .unit-header-stack{
      flex-direction:column;
      align-items:flex-start !important;
      gap:.7rem;
    }
    .unit-header-actions{
      width:100%;
      justify-content:flex-end;
    }
    .unit-card{
      margin-bottom:.25rem;
      padding:1rem 1rem 1.1rem;
    }
    .unit-table-wrapper{
      max-height:360px;
    }
  }

  @media print{
    .unit-shell{
      background:#fff;
      box-shadow:none;
    }
    .unit-card{
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
<div class="unit-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-3 unit-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="unit-chip-icon">
          <i class="bi bi-controller"></i>
        </span>
        <h4 class="m-0 fw-semibold unit-title-text">Unit PS</h4>
      </div>
      <div class="unit-subtitle">
        Kelola daftar unit PlayStation dan tarif per jam untuk sesi rental.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none unit-header-actions">
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
    <!-- Kiri: form tambah unit PS -->
    <div class="col-lg-5 col-md-6">
      <div class="unit-card h-100">
        <div class="unit-card-header">Tambah Unit PS</div>
        <div class="small mb-2 text-gray-400">
          Buat unit baru dan tentukan tipenya (PS4/PS5/VVIP) untuk perhitungan tarif otomatis.
        </div>

        <form method="post" action="{{ route('ps_units.store') }}" class="mt-2">
          @csrf
          <div class="mb-2">
            <label class="form-label">Nama Unit (mis. PS 4 - A)</label>
            <input name="name"
                   class="form-control unit-input"
                   required
                   value="{{ old('name') }}">
          </div>

          {{-- DROPDOWN TIPE UNIT (BARU) --}}
          <div class="mb-2">
            <label class="form-label">Tipe / Kategori</label>
            <select name="type" class="form-select unit-input" required>
              <option value="PS4" {{ old('type') == 'PS4' ? 'selected' : '' }}>PS4 (Reguler)</option>
              <option value="PS5" {{ old('type') == 'PS5' ? 'selected' : '' }}>PS5</option>
              <option value="VVIP" {{ old('type') == 'VVIP' ? 'selected' : '' }}>VVIP</option>
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Tarif per Jam (Rp)</label>
            <input name="hourly_rate"
                   type="number"
                   class="form-control unit-input mono"
                   value="{{ old('hourly_rate') }}"
                   min="0"
                   required>
          </div>
          <div class="mb-2 form-check">
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   id="activeCheck"
                   class="form-check-input"
                   {{ old('is_active',1) ? 'checked' : '' }}>
            <label for="activeCheck" class="form-check-label small">Aktif</label>
          </div>
          <button class="btn btn-soft-primary mt-1">
            <i class="bi bi-save me-1"></i> Simpan Unit
          </button>
        </form>
      </div>
    </div>

    <!-- Kanan: daftar unit -->
    <div class="col-lg-7 col-md-6">
      <div class="unit-card h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="unit-card-header mb-0">Daftar Unit PS</div>
          <span class="badge bg-secondary-subtle text-dark d-print-none">
            {{ $units->count() }} unit
          </span>
        </div>

        <div class="unit-table-wrapper table-responsive mt-2">
          <table class="table table-sm table-hover m-0 align-middle table-unit">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Tipe</th> {{-- KOLOM BARU --}}
                <th>Tarif / Jam</th>
                <th class="text-end d-print-none">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($units as $u)
                <tr>
                  <td>
                    {{ $u->name }}
                    @if(!$u->is_active)
                      <span class="badge badge-addon badge-glow ms-2">Nonaktif</span>
                    @endif
                  </td>
                  <td>
                    {{-- Menampilkan Tipe dengan Badge --}}
                    <span class="badge badge-addon" style="background:rgba(99,102,241,0.2); color:#a5b4fc;">
                        {{ $u->type ?? 'PS4' }}
                    </span>
                  </td>
                  <td class="mono">
                    Rp {{ number_format($u->hourly_rate ?? 0,0,',','.') }}
                  </td>
                  <td class="text-end d-print-none">
                    {{-- Update parameter fungsi editUnit --}}
                    <button class="btn btn-xs-ghost btn-outline-secondary me-1"
                            onclick="return editUnit({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->type ?? 'PS4' }}', {{ $u->hourly_rate ?? 0 }})">
                      Edit
                    </button>

                    <form class="d-inline" method="post" action="{{ route('ps_units.toggle', $u->id) }}">
                      @csrf
                      <button class="btn btn-xs-ghost {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                      </button>
                    </form>

                    <form class="d-inline confirm-delete"
                          method="post"
                          action="{{ route('ps_units.destroy', $u->id) }}"
                          onsubmit="return confirm('Hapus unit PS?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs-ghost btn-outline-danger">
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted p-3">
                    Belum ada unit.
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
@endsection

@push('scripts')
<script>
function editUnit(id, name, type, rate){
  const newName = prompt("Nama Unit:", name);
  if (newName === null) return false;

  // Prompt untuk edit Tipe
  let newType = prompt("Tipe (PS4 / PS5 / VVIP):", type);
  if (newType === null) return false;
  newType = newType.toUpperCase(); // Pastikan format huruf besar

  const newRate = prompt("Tarif/Jam (Rp):", rate);
  if (newRate === null) return false;

  // buat form update dinamis (PUT)
  const form = document.createElement("form");
  form.method = "post";
  form.action = "/ps-units/" + id;

  // csrf token
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (!tokenMeta) {
    alert('CSRF token tidak ditemukan di <meta>.');
    return false;
  }
  const token = tokenMeta.getAttribute('content');

  const _token = document.createElement("input");
  _token.type  = "hidden";
  _token.name  = "_token";
  _token.value = token;
  form.appendChild(_token);

  // spoof PUT
  const _method = document.createElement("input");
  _method.type  = "hidden";
  _method.name  = "_method";
  _method.value = "PUT";
  form.appendChild(_method);

  // Kirim data termasuk tipe
  [["name", newName], ["type", newType], ["hourly_rate", newRate]].forEach(([k,v])=>{
    const i = document.createElement("input");
    i.type  = "hidden";
    i.name  = k;
    i.value = v;
    form.appendChild(i);
  });

  document.body.appendChild(form);
  form.submit();
  return false;
}
</script>
@endpush