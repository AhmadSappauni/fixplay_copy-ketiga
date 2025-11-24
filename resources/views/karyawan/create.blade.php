@extends('layouts.fixplay')

@section('title','Tambah Karyawan – Fixplay')
@section('page_title','Tambah Karyawan')

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

    .fx-form-subtitle {
        font-size: 13px;
        color: #9ca3af;
    }
</style>
@endpush

@section('page_content')
<div class="card card-dark fx-form-card">
    <div class="card-body">
        <h5 class="mb-1 fw-bold">Tambah Karyawan</h5>
        <div class="fx-form-subtitle mb-3">
            Isi data karyawan baru. Semua field bebas diubah kapan saja.
        </div>

        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama" class="fx-label">Nama</label>
                <input type="text" id="nama" name="nama" class="fx-input" placeholder="Nama karyawan">
            </div>

            <div class="mb-3">
                <label for="phone" class="fx-label">Phone</label>
                <input type="text" id="phone" name="phone" class="fx-input" placeholder="Nomor telepon (opsional)">
            </div>

            <div class="mb-2">
                <label for="foto" class="fx-label">Foto (opsional)</label>
                <input type="file" id="foto" name="foto" class="form-control form-control-sm">
                <small class="text-muted d-block mt-1" style="font-size:11px;">
                    Disarankan rasio kotak (1:1), ukuran kecil saja.
                </small>
            </div>

            <div class="fx-form-actions">
                <a href="{{ route('karyawan.index') }}" class="fx-btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="fx-btn-primary">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
