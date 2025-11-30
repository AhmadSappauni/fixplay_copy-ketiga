@extends('layouts.fixplay')

@section('title','Presensi Karyawan')
@section('page_title','Presensi Karyawan')

@section('page_content')
<div class="row justify-content-center">
  <div class="col-xl-10">
    <div class="card card-dark fx-presensi-card mb-4">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
          <div>
            <h4 class="mb-1 fw-bold">Presensi Hari Ini</h4>
            <div class="text-neon-sub">
              Tanggal:
              <span class="fw-semibold">
                {{ now()->translatedFormat('l, d F Y') }}
              </span>
            </div>
          </div>

          <a href="{{ route('presensi.riwayat') }}" class="btn fx-btn-outline rounded-pill px-4">
            <i class="bi bi-clock-history me-1"></i> Riwayat
          </a>
        </div>

        {{-- FORM CHECK-IN / CHECK-OUT --}}
        <form class="row g-3 mb-4 align-items-end" method="POST">
          @csrf

          <div class="col-md-5">
            <label class="fx-label">Karyawan</label>
            <select name="karyawan_id" id="karyawan_id" class="fx-select" required>
              <option value="">Pilih karyawan...</option>
              @foreach($karyawans as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="fx-label">Shift</label>
            <select name="shift" id="shift" class="fx-select" required>
              @foreach($shifts as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 d-flex gap-2 justify-content-end">
            {{-- <button type="submit"
                    class="fx-btn-outline flex-fill"
                    formaction="{{ route('presensi.checkout') }}">
              <i class="bi bi-box-arrow-right me-1"></i> Check-out
            </button> --}}

            <button type="submit"
                    class="fx-btn-primary flex-fill"
                    formaction="{{ route('presensi.checkin') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i> Check-in
            </button>
          </div>
        </form>

        {{-- TABEL PRESENSI HARI INI --}}
        <div class="fx-table-wrapper">
          <table class="table mb-0 fx-table">
            <thead>
              <tr>
                <th style="width:28%">Nama</th>
                <th style="width:16%">Shift</th>
                <th style="width:16%">Masuk</th>
                <th style="width:16%">Keluar</th>
                <th style="width:14%">Status</th>
                <th style="width:10%" class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($todayPresensis as $row)
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                        @php
                        $foto = $row->karyawan->foto ?? null;
                        @endphp

                        @if($foto)
                        <div class="fx-avatar fx-avatar-xs fx-avatar-photo">
                            <img src="{{ asset('storage/'.$foto) }}" alt="{{ $row->karyawan->nama }}">
                        </div>
                        @else
                        <div class="fx-avatar fx-avatar-xs">
                            <span>{{ strtoupper(mb_substr($row->karyawan->nama,0,1)) }}</span>
                        </div>
                        @endif

                        <span>{{ $row->karyawan->nama }}</span>
                    </div>
                  </td>

                  <td>
                    @php
                        $shiftOptions = \App\Models\Presensi::shiftOptions();
                        $shiftLabel   = $shiftOptions[$row->shift] ?? ucfirst(str_replace('_',' ',$row->shift ?? '-'));
                    @endphp

                    <span class="fx-badge-pill">
                        {{ $shiftLabel }}
                    </span>
                  </td>

                  {{-- JAM MASUK --}}
                  <td>{{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '-' }}</td>
                  
                  {{-- JAM KELUAR / TOMBOL KELUAR --}}
                  <td>
                    @if($row->check_out)
                        {{ \Carbon\Carbon::parse($row->check_out)->format('H:i') }}
                    @elseif($row->check_in)
                        {{-- Jika sudah check-in tapi belum check-out, tampilkan tombol --}}
                        <form action="{{ route('presensi.checkout') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="karyawan_id" value="{{ $row->karyawan_id }}">
                            <input type="hidden" name="shift" value="{{ $row->shift }}">
                            
                            <button type="submit" 
                                    class="btn btn-sm btn-danger rounded-pill px-3 py-1 d-flex align-items-center gap-1" 
                                    style="font-size: 0.75rem;">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    @else
                        -
                    @endif
                  </td>

                  <td>
                    @if($row->status === 'telat')
                      <span class="fx-badge-status fx-badge-warning">Telat</span>
                    @elseif($row->status === 'izin')
                      <span class="fx-badge-status fx-badge-info">Izin</span>
                    @elseif($row->status === 'sakit')
                      <span class="fx-badge-status fx-badge-info">Sakit</span>
                    @elseif($row->status === 'alpha')
                      <span class="fx-badge-status fx-badge-danger">Tidak Hadir</span>
                    @else
                      <span class="fx-badge-status fx-badge-success">Hadir</span>
                    @endif
                  </td>
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
                  <td colspan="6" class="text-center text-neon-sub py-4">
                    Belum ada presensi untuk hari ini.
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