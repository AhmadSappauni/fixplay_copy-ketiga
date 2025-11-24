@extends('layouts.fixplay')

@section('title','Edit Karyawan – Fixplay')
@section('page_title','Edit Karyawan')

@push('styles')
<style>
    .fx-form-card {
        max-width: 640px;
        margin: 0 auto;
    }

    .fx-label {
        font-size: 13px;
        color: #d1d5db;
        margin-bottom: 4px;
    }

    .fx-input {
        width: 100%;
        padding: 9px 11px;
        border-radius: 10px;
        border: 1px solid #1f2937;
        background: #020617;
        color: #e5e7eb;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .fx-input:focus {
        border-color: #7b2ff7;
        box-shadow: 0 0 0 1px rgba(123, 47, 247, 0.7);
    }

    .fx-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .fx-btn-primary,
    .fx-btn-secondary {
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        text-decoration: none;
    }

    .fx-btn-primary {
        background: linear-gradient(135deg, #7b2ff7, #00c6ff);
        color: #fff;
        box-shadow: 0 0 18px rgba(59, 130, 246, 0.7);
    }

    .fx-btn-primary:hover {
        filter: brightness(1.05);
        color: #fff;
    }

    .fx-btn-secondary {
        background: transparent;
        border: 1px solid rgba(148, 163, 184, 0.7);
        color: #e5e7eb;
    }

    .fx-btn-secondary:hover {
        background: rgba(148, 163, 184, 0.15);
        color: #fff;
    }

    .fx-current-photo {
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #cbd5f5;
    }

    .fx-current-photo img {
        width: 48px;
        height: 48px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(96,165,250,.7);
        cursor: pointer;
    }

    .fx-form-subtitle {
        font-size: 13px;
        color: #9ca3af;
    }

    .fx-photo-modal-img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.7);
    }
</style>
@endpush

@section('page_content')
<div class="card card-dark fx-form-card">
    <div class="card-body">
        <h5 class="mb-1 fw-bold">Edit Karyawan</h5>
        <div class="fx-form-subtitle mb-3">
            Ubah data karyawan. Semua field boleh diubah, termasuk foto.
        </div>

        <form action="{{ route('karyawan.update', $karyawan) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="fx-label">Nama</label>
                <input type="text" id="nama" name="nama"
                       class="fx-input"
                       value="{{ $karyawan->nama }}"
                       placeholder="Nama karyawan">
            </div>

            <div class="mb-3">
                <label for="phone" class="fx-label">Phone</label>
                <input type="text" id="phone" name="phone"
                       class="fx-input"
                       value="{{ $karyawan->phone }}"
                       placeholder="Nomor telepon (opsional)">
            </div>

            <div class="mb-2">
                <label for="foto" class="fx-label">Foto (opsional)</label>
                <input type="file" id="foto" name="foto" class="form-control form-control-sm">
                @if($karyawan->foto)
                    <div class="fx-current-photo">
                        <span>Foto sekarang:</span>
                        {{-- klik gambar untuk lihat besar --}}
                        <img src="{{ asset('storage/'.$karyawan->foto) }}"
                             alt="{{ $karyawan->nama }}"
                             class="fx-photo-trigger"
                             data-photo="{{ asset('storage/'.$karyawan->foto) }}"
                             title="Klik untuk perbesar foto">
                    </div>
                @endif
            </div>

            <div class="fx-form-actions">
                <a href="{{ route('karyawan.index') }}" class="fx-btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="fx-btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PREVIEW FOTO (sama seperti di index, boleh duplikat di sini) --}}
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
