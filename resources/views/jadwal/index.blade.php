@extends('layouts.fixplay')

@section('title','Jadwal Mingguan Karyawan')
@section('page_title','Jadwal Mingguan Karyawan')

@section('page_content')
<div class="row justify-content-center">
  <div class="col-xl-10">

    {{-- ALERT ERROR (Untuk validasi kuota penuh) --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {!! $errors->first() !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- CARD 1: FORM INPUT JADWAL INDIVIDU --}}
    <div class="card card-dark fx-presensi-card mb-4">
      <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
          <div>
            <h4 class="mb-1 fw-bold">Input Jadwal Mingguan</h4>
            <div class="text-neon-sub small">
              Minggu
              <span class="fw-semibold">
                {{-- Pakai locale('id') agar Indo --}}
                {{ $startOfWeek->locale('id')->translatedFormat('d F Y') }}
              </span>
              s/d
              <span class="fw-semibold">
                {{ $endOfWeek->locale('id')->translatedFormat('d F Y') }}
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
            <label class="fx-label">Karyawan (Edit Jadwal)</label>
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
                          {{-- Singkatan Hari Indo (Sen, Sel, Rab...) --}}
                          {{ strtoupper($date->locale('id')->translatedFormat('D')) }}
                        </span>
                        {{-- Nama Hari Lengkap Indo --}}
                        <span>{{ $date->locale('id')->translatedFormat('l') }}</span>
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
                    <td>
                      <input
                          type="text"
                          name="catatan[{{ $date->toDateString() }}]"
                          class="fx-select fx-select-sm"
                          placeholder="Tambahkan catatan..."
                          value="{{ $record?->catatan ?? '' }}"
                      >
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

    {{-- CARD 2: REKAP JADWAL MINGGUAN (BARU) --}}
    <div class="card card-dark fx-presensi-card">
        <div class="card-header border-0 pb-0 pt-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1"><i class="bi bi-calendar-week me-2 text-info"></i>Rekap Jadwal Karyawan</h5>
                <p class="text-secondary small mb-3">Monitoring ketersediaan shift minggu ini (Max 2 orang/shift).</p>
            </div>
            
            {{-- TOMBOL EXPORT (BARU) --}}
            {{-- Pastikan route 'jadwal.report.excel' dan 'jadwal.report.pdf' sudah dibuat di web.php --}}
            {{-- Kita kirim parameter week_date agar controller tahu minggu mana yang diexport --}}
            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('jadwal.report.excel', ['week_date' => request('week_date', $weekDate->format('Y-m-d'))]) }}" 
                   class="btn btn-sm btn-success d-flex align-items-center gap-1">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('jadwal.report.pdf', ['week_date' => request('week_date', $weekDate->format('Y-m-d'))]) }}" 
                   class="btn btn-sm btn-danger d-flex align-items-center gap-1" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered border-secondary table-dark mb-0 align-middle text-center" style="background: transparent;">
                    <thead class="bg-secondary bg-opacity-10">
                        <tr>
                            <th class="py-3 text-start ps-4">Hari / Tanggal</th>
                            {{-- Sesuaikan lebar kolom agar Sakit/Izin muat --}}
                            <th class="py-3" style="width: 28%;">Shift Pagi</th>
                            <th class="py-3" style="width: 28%;">Shift Sore</th>
                            <th class="py-3" style="width: 12%;">Sakit</th>
                            <th class="py-3" style="width: 12%;">Izin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $d)
                            @php
                                $date = $d['date'];
                                $tglStr = $date->toDateString();
                                
                                // Mencoba menebak key shift umum. Sesuaikan 'pagi'/'sore' dgn key database Anda
                                $listPagi = $recap[$tglStr]['pagi'] ?? ($recap[$tglStr]['Pagi'] ?? ($recap[$tglStr][1] ?? [])); 
                                $listSore = $recap[$tglStr]['sore'] ?? ($recap[$tglStr]['Sore'] ?? ($recap[$tglStr][2] ?? []));
                                
                                // [BARU] Ambil data Sakit dan Izin
                                $listSakit = $recap[$tglStr]['sakit'] ?? ($recap[$tglStr]['Sakit'] ?? ($recap[$tglStr]['s'] ?? []));
                                $listIzin  = $recap[$tglStr]['izin'] ?? ($recap[$tglStr]['Izin'] ?? ($recap[$tglStr]['i'] ?? []));
                            @endphp
                            <tr>
                                <td class="text-start ps-4">
                                    {{-- Pakai locale('id') untuk hari dan tanggal --}}
                                    <div class="fw-bold text-white">{{ $date->locale('id')->translatedFormat('l') }}</div>
                                    <div class="small text-secondary">{{ $date->locale('id')->translatedFormat('d F Y') }}</div>
                                </td>
                                
                                {{-- Kolom Shift Pagi --}}
                                <td class="align-top py-3">
                                    @if(count($listPagi) > 0)
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @foreach($listPagi as $nama)
                                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill px-3 py-2">
                                                    {{ $nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @if(count($listPagi) >= 2)
                                            <div class="mt-2 badge bg-danger text-white" style="font-size: 0.65rem;">FULL</div>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic small">- Kosong -</span>
                                    @endif
                                </td>

                                {{-- Kolom Shift Sore --}}
                                <td class="align-top py-3">
                                    @if(count($listSore) > 0)
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @foreach($listSore as $nama)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-2">
                                                    {{ $nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @if(count($listSore) >= 2)
                                            <div class="mt-2 badge bg-danger text-white" style="font-size: 0.65rem;">FULL</div>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic small">- Kosong -</span>
                                    @endif
                                </td>

                                {{-- [BARU] Kolom Sakit --}}
                                <td class="align-top py-3">
                                    @if(count($listSakit) > 0)
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @foreach($listSakit as $nama)
                                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small opacity-25">-</span>
                                    @endif
                                </td>

                                {{-- [BARU] Kolom Izin --}}
                                <td class="align-top py-3">
                                    @if(count($listIzin) > 0)
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @foreach($listIzin as $nama)
                                                <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small opacity-25">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  </div>
</div>
@endsection