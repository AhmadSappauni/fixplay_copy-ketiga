@extends('layouts.fixplay')

@section('title','Manajemen Karyawan – Fixplay')
@section('page_title','Manajemen Karyawan')

@push('styles')
<style>
    .fx-emp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .fx-emp-subtitle {
        font-size: 13px;
        color: #9ca3af;
    }

    .fx-btn-neon-primary {
        border-radius: 999px;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        background: linear-gradient(135deg, #7b2ff7, #00c6ff);
        color: #fff;
        box-shadow: 0 0 18px rgba(59, 130, 246, 0.7);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .fx-btn-neon-primary:hover {
        filter: brightness(1.05);
        color: #fff;
    }

    .fx-emp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
    }

    .fx-emp-card {
        background:
            radial-gradient(120% 120% at 0% 0%, rgba(124,58,237,.20), transparent 40%),
            radial-gradient(120% 120% at 100% 0%, rgba(59,130,246,.18), transparent 45%),
            linear-gradient(180deg,#111827,#020617);
        border-radius: 14px;
        border: 1px solid rgba(129, 140, 248, 0.4);
        padding: 12px 14px 10px;
        box-shadow: 0 10px 30px rgba(15,23,42,.8);
        position: relative;
        overflow: hidden;
    }

    .fx-emp-inner {
        position: relative;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .fx-emp-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: 2px solid rgba(96,165,250,.7);
        overflow: hidden;
        flex-shrink: 0;
        background: radial-gradient(circle at top, #4b5563, #020617);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #e5e7eb;
    }

    .fx-emp-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .fx-emp-name {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #e5e7eb;
    }

    .fx-emp-phone {
        font-size: 13px;
        color: #cbd5f5;
    }

    .fx-emp-actions {
        margin-top: 10px;
        display: flex;
        justify-content: flex-end;
        gap: 6px;
    }

    .fx-btn-ghost,
    .fx-btn-ghost-danger {
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: transparent;
        color: #e5e7eb;
        text-decoration: none;
    }

    .fx-btn-ghost:hover {
        background: rgba(148, 163, 184, 0.15);
        color: #fff;
    }

    .fx-btn-ghost-danger {
        border-color: rgba(248, 113, 113, 0.85);
        color: #fecaca;
    }

    .fx-btn-ghost-danger:hover {
        background: rgba(248, 113, 113, 0.12);
        color: #fee2e2;
    }

    .fx-emp-empty {
        text-align: center;
        padding: 24px 16px 10px;
        font-size: 14px;
        color: #cbd5f5;
    }

    /* modal foto */
    .fx-photo-modal-img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.7);
    }
</style>
@endpush

@section('page_content')
<div class="card card-dark">
    <div class="card-body">
        <div class="fx-emp-header">
            <div>
                <h5 class="mb-1 fw-bold">Manajemen Karyawan</h5>
                <div class="fx-emp-subtitle">
                    Tambah, ubah, atau hapus data karyawan. Struktur saat ini: nama, phone, foto.
                </div>
            </div>

            <a href="{{ route('karyawan.create') }}" class="fx-btn-neon-primary">
                <i class="bi bi-person-plus"></i>
                <span>Tambah Karyawan</span>
            </a>
        </div>

        @if($karyawans->isEmpty())
            <div class="fx-emp-empty">
                <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:6px;"></i>
                Belum ada karyawan terdaftar. Klik <strong>“Tambah Karyawan”</strong> untuk mulai.
            </div>
        @else
            <div class="fx-emp-grid">
                @foreach($karyawans as $karyawan)
                    <div class="fx-emp-card">
                        <div class="fx-emp-inner">
                            {{-- AVATAR KLIK → PREVIEW BESAR --}}
                            <div class="fx-emp-avatar fx-photo-trigger"
                                 @if($karyawan->foto)
                                     data-photo="{{ asset('storage/'.$karyawan->foto) }}"
                                     style="cursor:pointer;"
                                     title="Klik untuk perbesar foto"
                                 @endif>
                                @if($karyawan->foto)
                                    <img src="{{ asset('storage/'.$karyawan->foto) }}" alt="{{ $karyawan->nama }}">
                                @else
                                    <i class="bi bi-person"></i>
                                @endif
                            </div>

                            <div>
                                <div class="fx-emp-name">{{ $karyawan->nama }}</div>
                                <div class="fx-emp-phone">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $karyawan->phone ?: '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="fx-emp-actions">
                            <a href="{{ route('karyawan.edit', $karyawan) }}" class="fx-btn-ghost">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('karyawan.destroy', $karyawan) }}"
                                  method="POST"
                                  class="confirm-delete"
                                  data-confirm="Yakin ingin menghapus karyawan ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fx-btn-ghost-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- MODAL PREVIEW FOTO --}}
<div class="modal fade" id="fxPhotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content fx-neon-card">
      <div class="modal-body text-center">
        <img id="fxPhotoImg" src="" alt="Foto karyawan" class="fx-photo-modal-img">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('fxPhotoModal');
    if (!modalEl) return;

    const modal     = new bootstrap.Modal(modalEl);
    const imgTarget = document.getElementById('fxPhotoImg');

    // semua elemen yang punya data-photo akan membuka modal
    document.querySelectorAll('.fx-photo-trigger[data-photo]').forEach(function (el) {
        el.addEventListener('click', function () {
            const src = this.getAttribute('data-photo');
            if (!src || !imgTarget) return;

            imgTarget.src = src;
            modal.show();
        });
    });
});
</script>
@endpush
