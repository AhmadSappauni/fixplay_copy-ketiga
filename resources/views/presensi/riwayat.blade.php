@extends('layouts.fixplay')

@section('title','Riwayat Presensi')
@section('page_title','Riwayat Presensi')

@section('page_content')
<div class="row justify-content-center">
  <div class="col-xl-10">
    <div class="card card-dark fx-presensi-card mb-4">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
          <div>
            <h4 class="mb-1 fw-bold">Riwayat Presensi</h4>
            <div class="text-neon-sub small">
              Menampilkan presensi dari
              <span class="fw-semibold">{{ $from->format('d/m/Y') }}</span>
              s/d
              <span class="fw-semibold">{{ $to->format('d/m/Y') }}</span>
            </div>
          </div>

          <a href="{{ route('presensi.index') }}" class="btn fx-btn-outline rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Presensi Hari Ini
          </a>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" class="row g-3 mb-4 align-items-end">
          <div class="col-md-3">
            <label class="fx-label">Dari tanggal</label>
            <input type="date" name="from" value="{{ request('from',$from->format('Y-m-d')) }}" class="fx-select">
          </div>
          <div class="col-md-3">
            <label class="fx-label">Sampai tanggal</label>
            <input type="date" name="to" value="{{ request('to',$to->format('Y-m-d')) }}" class="fx-select">
          </div>
          <div class="col-md-3">
            <label class="fx-label">Karyawan</label>
            <select name="karyawan_id" class="fx-select">
              <option value="">Semua</option>
              @foreach($karyawans as $k)
                <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                  {{ $k->nama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="fx-label">Shift</label>
            <select name="shift" class="fx-select">
              <option value="">Semua</option>
              @foreach($shifts as $key => $label)
                <option value="{{ $key }}" {{ request('shift') == $key ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <button class="fx-btn-primary w-100" type="submit" title="Terapkan filter">
              <i class="bi bi-funnel"></i>
            </button>
          </div>
        </form>

        {{-- TABEL RIWAYAT --}}
        <div class="fx-table-wrapper">
          <table class="table mb-0 fx-table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Shift</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Status</th>
                <th>Catatan</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($presensis as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                  <td>{{ $row->karyawan->nama }}</td>
                    <td>
                    @php
                        $shiftOptions = \App\Models\Presensi::shiftOptions();
                        $shiftLabel   = $shiftOptions[$row->shift] ?? ucfirst(str_replace('_',' ',$row->shift ?? '-'));
                    @endphp

                    <span class="fx-badge-pill">
                        {{ $shiftLabel }}
                    </span>
                    </td>

                    <td>{{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '-' }}</td>
                  <td>
                    @if($row->status === 'telat')
                      <span class="fx-badge-status fx-badge-warning">telat</span>
                    @elseif($row->status === 'izin')
                      <span class="fx-badge-status fx-badge-info">izin</span>
                    @elseif($row->status === 'sakit')
                      <span class="fx-badge-status fx-badge-info">sakit</span>
                    @elseif($row->status === 'alpha')
                      <span class="fx-badge-status fx-badge-danger">tidak hadir</span>
                    @else
                      <span class="fx-badge-status fx-badge-success">hadir</span>
                    @endif
                  </td>
                  <td>{{ $row->catatan ?: '-' }}</td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('presensi.edit',$row->id) }}"
                         class="btn btn-outline-light fx-btn-icon"
                         title="Edit presensi">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('presensi.destroy',$row->id) }}"
                            method="POST"
                            class="d-inline confirm-delete"
                            data-confirm="Hapus presensi ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-outline-danger fx-btn-icon"
                                title="Hapus">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-neon-sub py-4">
                    Tidak ada data presensi pada rentang tanggal ini.
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
