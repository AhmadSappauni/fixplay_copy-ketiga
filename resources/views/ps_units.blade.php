@extends('layouts.fixplay')

@section('page_title', 'Kasir Fixplay - Unit PS')

@push('styles')
<style>
  /* ====== SHELL FUTURISTIK (Sama dengan Dashboard/Session) ====== */
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

  /* Tombol Soft Dark */
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

  /* ====== CARD STYLE (DARK GLASS) ====== */
  .unit-card{
    position:relative;
    border-radius:1.25rem;
    padding:1.5rem;
    background: linear-gradient(145deg, rgba(2,6,23,0.9), rgba(15,23,42,0.8));
    border:1px solid rgba(148,163,184,.25);
    box-shadow:0 20px 38px rgba(0,0,0,.6);
    backdrop-filter:blur(12px);
    color:#e5e7eb;
    overflow:hidden;
    height: 100%;
  }
  .unit-card::after{
    content:"";
    position:absolute;
    inset:auto -45% -45% auto;
    width:140px;
    height:140px;
    border-radius:999px;
    opacity:.15;
    filter:blur(25px);
    background:radial-gradient(circle,#6366f1,#0ea5e9);
    pointer-events: none;
  }
  
  .unit-card-header{
    font-size:.85rem;
    text-transform:uppercase;
    letter-spacing:.1em;
    color:#94a3b8;
    font-weight:700;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(148,163,184,.15);
    padding-bottom: 0.75rem;
  }

  /* Input Style */
  .unit-input, .unit-shell .form-select{
    background:rgba(15,23,42,.6);
    border-radius:.6rem;
    border:1px solid rgba(148,163,184,.3);
    color:#f3f4f6;
    font-size:.9rem;
  }
  .unit-input:focus,
  .unit-shell .form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 2px rgba(99,102,241,.25);
    background:rgba(2,6,23,.8);
    color:#fff;
  }
  
  /* Label */
  .form-label {
      font-size: 0.85rem;
      color: #cbd5e1;
      font-weight: 600;
  }

  /* Tombol Simpan */
  .btn-main-submit{
    width: 100%;
    border-radius:.75rem;
    padding:.7rem;
    font-weight:700;
    background:linear-gradient(135deg,#8b5cf6,#6366f1);
    border:none;
    color:#fff;
    box-shadow:0 4px 12px rgba(139, 92, 246, 0.4);
    transition: all 0.2s;
  }
  .btn-main-submit:hover{
    filter:brightness(1.1);
    transform: translateY(-1px);
    box-shadow:0 6px 15px rgba(139, 92, 246, 0.5);
  }

  /* ====== TABEL UNIT (NEON DARK) ====== */
  .unit-table-wrapper{
    max-height:520px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #475569 #1e293b;
  }

  .table-unit {
    width: 100%;
    margin-bottom: 0;
    color: #cbd5e1;
  }
  .table-unit thead th {
    background: rgba(15, 23, 42, 0.8);
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    padding: 0.85rem 1rem;
    white-space: nowrap;
  }
  .table-unit tbody td {
    background: transparent;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 0.85rem 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
  }
  .table-unit tbody tr:hover td {
    background: rgba(99, 102, 241, 0.08);
  }

  .mono{
    font-family: 'Consolas', 'Monaco', monospace;
    color: #818cf8;
    font-weight: 600;
  }

  /* Badge Status & Tipe */
  .badge-type {
    background: #fbbf24; /* Kuning */
    color: #1e293b; /* Hitam */
    font-weight: 800;
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
    box-shadow: 0 2px 5px rgba(251, 191, 36, 0.4);
  }
  
  .badge-inactive {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.4);
    padding: 2px 8px;
    border-radius: 99px;
    font-size: 0.7rem;
  }

  /* Action Buttons Kecil */
  .btn-action-group .btn {
    padding: 0.25rem 0.6rem;
    font-size: 0.75rem;
    border-radius: 6px;
    margin-right: 4px;
  }
  .btn-outline-secondary { color: #cbd5e1; border-color: #475569; }
  .btn-outline-secondary:hover { background: #475569; color: #fff; }
  
  .btn-outline-warning { color: #fbbf24; border-color: #fbbf24; }
  .btn-outline-warning:hover { background: #fbbf24; color: #000; }

  .btn-outline-success { color: #4ade80; border-color: #4ade80; }
  .btn-outline-success:hover { background: #4ade80; color: #000; }

  .btn-outline-danger { color: #f87171; border-color: #f87171; }
  .btn-outline-danger:hover { background: #f87171; color: #fff; }


  /* MODAL STYLE CUSTOM */
  .modal-glass .modal-content {
    background: radial-gradient(circle at top left, #1e1e2f, #0f1020);
    border: 1px solid rgba(124,58,237,.3);
    box-shadow: 0 0 30px rgba(0,0,0,.8);
    color: #e5e7eb;
    border-radius: 1.25rem;
  }
  .modal-glass .modal-header { border-bottom: 1px solid rgba(255,255,255,.08); }
  .modal-glass .modal-footer { border-top: 1px solid rgba(255,255,255,.08); }
  .modal-glass .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
  
  /* Input di dalam modal */
  .modal-glass .form-control, .modal-glass .form-select {
    background: rgba(2, 6, 23, 0.8); 
    border: 1px solid rgba(148,163,184,.2); 
    color: #f1f5f9;
    border-radius: 0.75rem;
  }
  .modal-glass .form-control:focus, .modal-glass .form-select:focus {
    border-color: #8b5cf6; 
    box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
  }

  /* ====== RESPONSIVE ====== */
  @media (max-width: 992px){
    .unit-shell{ padding:1.35rem 1.1rem 2rem; border-radius:1.1rem; }
  }
  @media (max-width: 768px){
    .unit-header-stack{ flex-direction:column; align-items:flex-start !important; gap:.7rem; }
    .unit-header-actions{ width:100%; justify-content:flex-end; }
    .unit-card{ margin-bottom:.25rem; padding:1rem; }
    .unit-table-wrapper{ max-height:360px; }
  }
  @media print{
    .unit-shell{ background:#fff; box-shadow:none; }
    .unit-card{ box-shadow:none; border-color:#e5e7eb; background:#fff; color:#111827; }
    .d-print-none{ display:none !important; }
  }
</style>
@endpush

@section('page_content')
<div class="unit-shell">

  {{-- HEADER --}}
  <div class="d-flex align-items-center justify-content-between mb-4 unit-header-stack">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="unit-chip-icon">
          <i class="bi bi-controller"></i>
        </span>
        <h4 class="m-0 fw-bold text-white">Unit PS</h4>
      </div>
      <div class="unit-subtitle">
        Kelola daftar unit PlayStation dan tarif per jam.
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 d-print-none unit-header-actions">
      <button type="button" class="btn btn-soft-dark btn-sm px-3 py-2" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
    </div>
  </div>

  {{-- ALERT MESSAGES --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-print-none bg-success-subtle border-success text-success-emphasis" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-print-none bg-danger-subtle border-danger text-danger-emphasis" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- KIRI: FORM TAMBAH UNIT PS -->
    <div class="col-lg-4 col-md-5">
      <div class="unit-card">
        <div class="unit-card-header">
            <i class="bi bi-plus-square me-2"></i> Tambah Unit Baru
        </div>
        
        <form method="post" action="{{ route('ps_units.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nama Unit</label>
            <input name="name" class="form-control unit-input" required value="{{ old('name') }}" placeholder="Contoh: Box 1">
          </div>

          {{-- DROPDOWN TIPE UNIT --}}
          <div class="mb-3">
            <label class="form-label">Tipe / Kategori</label>
            <select name="type" class="form-select unit-input" required>
              <option value="PS4" {{ old('type') == 'PS4' ? 'selected' : '' }}>PS4 (Reguler)</option>
              <option value="PS5" {{ old('type') == 'PS5' ? 'selected' : '' }}>PS5</option>
              <option value="VVIP" {{ old('type') == 'VVIP' ? 'selected' : '' }}>VVIP</option>
            </select>
            <div class="form-text text-muted small" style="font-size: 0.75rem;">*Tipe mempengaruhi harga paket otomatis.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Tarif per Jam</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-light">Rp</span>
                <input name="hourly_rate" type="number" class="form-control unit-input mono" value="{{ old('hourly_rate') }}" min="0" required placeholder="0">
            </div>
          </div>
          
          <div class="mb-4 form-check">
            <input type="checkbox" name="is_active" value="1" id="activeCheck" class="form-check-input" {{ old('is_active',1) ? 'checked' : '' }}>
            <label for="activeCheck" class="form-check-label small text-light">Status Aktif</label>
          </div>
          
          <button class="btn-main-submit">
            <i class="bi bi-save me-2"></i> SIMPAN UNIT
          </button>
        </form>
      </div>
    </div>

    <!-- KANAN: DAFTAR UNIT -->
    <div class="col-lg-8 col-md-7">
      <div class="unit-card h-100">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
          <div class="fw-bold text-light">Daftar Unit PS</div>
          <span class="badge bg-dark border border-secondary">{{ $units->count() }} Unit</span>
        </div>

        <div class="unit-table-wrapper">
          <table class="table-unit">
            <thead>
              <tr>
                <th>Nama Unit</th>
                <th>Tipe</th>
                <th>Tarif / Jam</th>
                <th class="text-end d-print-none">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($units as $u)
                <tr>
                  <td>
                    <div class="fw-bold text-white">{{ $u->name }}</div>
                    @if(!$u->is_active)
                      <span class="badge-inactive mt-1 d-inline-block">Nonaktif</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge-type">
                        {{ $u->type ?? 'PS4' }}
                    </span>
                  </td>
                  <td class="mono">
                    Rp {{ number_format($u->hourly_rate ?? 0,0,',','.') }}
                  </td>
                  <td class="text-end d-print-none">
                    <div class="btn-action-group">
                        {{-- Tombol Edit --}}
                        <button class="btn btn-outline-secondary"
                                onclick="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->type ?? 'PS4' }}', {{ $u->hourly_rate ?? 0 }})">
                          Edit
                        </button>

                        {{-- Tombol Toggle Aktif/Nonaktif --}}
                        <form class="d-inline" method="post" action="{{ route('ps_units.toggle', $u->id) }}">
                          @csrf
                          <button class="btn {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                          </button>
                        </form>

                        {{-- Tombol Hapus --}}
                        <form class="d-inline confirm-delete"
                              method="post"
                              action="{{ route('ps_units.destroy', $u->id) }}"
                              onsubmit="return confirm('Yakin ingin menghapus unit PS ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-outline-danger">
                            Hapus
                          </button>
                        </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted p-5">
                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                    Belum ada unit yang terdaftar.
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

<!-- MODAL EDIT (Fitur Baru yang Lebih Bagus) -->
<div class="modal fade modal-glass" id="editUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-white">Edit Unit PS</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <form id="editUnitForm" method="post">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label">Nama Unit</label>
            <input type="text" name="name" id="editName" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tipe / Kategori</label>
            <select name="type" id="editType" class="form-select" required>
              <option value="PS4">PS4 (Reguler)</option>
              <option value="PS5">PS5</option>
              <option value="VVIP">VVIP</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tarif per Jam (Rp)</label>
            <input type="number" name="hourly_rate" id="editRate" class="form-control mono" min="0" required>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-main-submit w-auto px-4 py-2">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function openEditModal(id, name, type, rate){
  // Set action URL form
  const form = document.getElementById('editUnitForm');
  form.action = "/ps-units/" + id;

  // Isi data ke input modal
  document.getElementById('editName').value = name;
  document.getElementById('editType').value = type;
  document.getElementById('editRate').value = rate;

  // Tampilkan modal Bootstrap
  const modalEl = document.getElementById('editUnitModal');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}
</script>
@endpush