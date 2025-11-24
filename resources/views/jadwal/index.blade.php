@extends('layouts.fixplay')

@section('title','Jadwal Mingguan Karyawan')
@section('page_title','Jadwal Mingguan Karyawan')

@section('page_content')
<div class="row justify-content-center">
  <div class="col-xl-10">
    <div class="card card-dark fx-presensi-card mb-4">
      <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
          <div>
            <h4 class="mb-1 fw-bold">Jadwal Mingguan</h4>
            <div class="text-neon-sub small">
              Minggu
              <span class="fw-semibold">
                {{ $startOfWeek->translatedFormat('d F Y') }}
              </span>
              s/d
              <span class="fw-semibold">
                {{ $endOfWeek->translatedFormat('d F Y') }}
              </span>
            </div>
          </div>

          <a href="{{ route('presensi.index') }}" class="btn fx-btn-outline rounded-pill px-4">
            <i class="bi bi-clipboard-check me-1"></i> Presensi Hari Ini
          </a>
        </div>

        {{-- Filter karyawan + minggu --}}
        <form method="GET" class="row g-3 mb-4 align-items-end">
          <div class="col-md-5">
            <label class="fx-label">Karyawan</label>
            <select name="karyawan_id" class="fx-select" onchange="this.form.submit()">
              @foreach($karyawans as $k)
                <option value="{{ $k->id }}"
                  {{ (int)$selectedKaryawanId === (int)$k->id ? 'selected' : '' }}>
                  {{ $k->nama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="fx-label">Pilih tanggal dalam minggu</label>
            <input type="date"
                   name="week_date"
                   value="{{ request('week_date', $weekDate->format('Y-m-d')) }}"
                   class="fx-select"
                   onchange="this.form.submit()">
          </div>
          <div class="col-md-3 text-md-end">
            {{-- tombol opsional kalau mau manual submit
            <button class="fx-btn-primary mt-3 mt-md-0" type="submit">
              Tampilkan Minggu Ini
            </button>
            --}}
          </div>
        </form>

        {{-- Form simpan jadwal minggu ini --}}
        <form method="POST" action="{{ route('jadwal.store') }}">
          @csrf
          <input type="hidden" name="karyawan_id" value="{{ $selectedKaryawanId }}">
          <input type="hidden" name="week_start"  value="{{ $startOfWeek->format('Y-m-d') }}">

          <div class="fx-table-wrapper">
            <table class="table mb-0 fx-table">
              <thead>
                <tr>
                  <th style="width:22%">Hari</th>
                  <th style="width:18%">Tanggal</th>
                  <th style="width:35%">Shift</th>
                  <th style="width:25%">Catatan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($days as $d)
                  @php
                    /** @var \Carbon\Carbon $date */
                    $date   = $d['date'];
                    $record = $d['record'];
                    $isToday = $date->isToday();

                    $shiftOptions = $shifts;
                    $selectedShift = $record->shift ?? '';
                  @endphp
                  <tr class="{{ $isToday ? 'fx-row-today' : '' }}">
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <span class="fx-chip-day">
                          {{ strtoupper($date->format('D')) }}
                        </span>
                        <span>{{ $date->translatedFormat('l') }}</span>
                      </div>
                    </td>
                    <td>
                      {{ $date->format('d/m/Y') }}
                      <input type="hidden"
                             name="tanggal_row[]"
                             value="{{ $date->toDateString() }}">
                    </td>
                    <td>
                      <select name="shifts[{{ $date->toDateString() }}]" class="fx-select fx-select-sm">
                        <option value="">(Tidak dijadwalkan)</option>
                        @foreach($shiftOptions as $key => $label)
                          <option value="{{ $key }}" {{ $selectedShift === $key ? 'selected' : '' }}>
                            {{ $label }}
                          </option>
                        @endforeach
                      </select>
                    </td>
                    <td class="text-neon-sub">
                      {{-- nanti bisa dikembangkan jadi input catatan per hari --}}
                      {{ $record?->catatan ?? '-' }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-3 d-flex justify-content-end">
            <button type="submit" class="fx-btn-primary px-4">
              <i class="bi bi-save me-1"></i> Simpan Jadwal Minggu Ini
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
