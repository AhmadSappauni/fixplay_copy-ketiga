@extends('layouts.fixplay')

@section('title','Edit Presensi – Fixplay')
@section('page_title','Edit Presensi')

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

  .fx-input, .fx-select, .fx-textarea {
      width: 100%;
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid #1f2937;
      background: #020617;
      color: #e5e7eb;
      font-size: 14px;
  }

  .fx-input:focus, .fx-select:focus, .fx-textarea:focus {
      outline: none;
      border-color: #7b2ff7;
      box-shadow: 0 0 0 1px rgba(123,47,247,.7);
  }

  .fx-form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 8px;
      margin-top: 16px;
  }

  .fx-btn-primary, .fx-btn-secondary {
      border-radius: 999px;
      padding: 8px 16px;
      font-size: 14px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: none;
  }

  .fx-btn-primary {
      background: linear-gradient(135deg,#7b2ff7,#00c6ff);
      color:#fff;
      box-shadow:0 6px 18px rgba(59,130,246,.6);
  }

  .fx-btn-secondary {
      background: transparent;
      border: 1px solid rgba(148,163,184,.7);
      color:#e5e7eb;
  }
</style>
@endpush

@section('page_content')
<div class="card card-dark fx-form-card">
  <div class="card-body">
    <h5 class="mb-1 fw-bold">Edit Presensi</h5>
    <div class="text-soft mb-3" style="font-size:13px;">
      Koreksi shift, jam masuk/keluar, atau status jika ada kesalahan.
    </div>

    <form action="{{ route('presensi.update', $presensi) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="fx-label">Karyawan</label>
        <select name="karyawan_id" class="fx-select" required>
          @foreach($karyawans as $k)
            <option value="{{ $k->id }}"
              {{ $presensi->karyawan_id == $k->id ? 'selected' : '' }}>
              {{ $k->nama }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="fx-label">Tanggal</label>
        <input type="date" name="tanggal" class="fx-input"
               value="{{ $presensi->tanggal->format('Y-m-d') }}" required>
      </div>

      <div class="mb-3">
        <label class="fx-label">Shift</label>
        <select name="shift" class="fx-select" required>
          @foreach($shifts as $key => $label)
            <option value="{{ $key }}"
              {{ $presensi->shift === $key ? 'selected' : '' }}>
              {{ $label }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="fx-label">Jam Masuk</label>
          <input type="time" name="check_in" class="fx-input"
                 value="{{ $presensi->check_in ? $presensi->check_in->format('H:i') : '' }}">
        </div>
        <div class="col-md-6">
          <label class="fx-label">Jam Keluar</label>
          <input type="time" name="check_out" class="fx-input"
                 value="{{ $presensi->check_out ? $presensi->check_out->format('H:i') : '' }}">
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="fx-label">Status</label>
        <input type="text" name="status" class="fx-input"
               value="{{ $presensi->status }}"
               placeholder="hadir / telat / sakit / izin (boleh dikosongkan untuk otomatis)">
      </div>

      <div class="mb-2">
        <label class="fx-label">Catatan</label>
        <textarea name="catatan" rows="2" class="fx-textarea"
                  placeholder="Contoh: Lupa absen, koreksi oleh bos.">{{ $presensi->catatan }}</textarea>
      </div>

      <div class="fx-form-actions">
        <a href="{{ url()->previous() }}" class="fx-btn-secondary">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="fx-btn-primary">
          <i class="bi bi-save"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
