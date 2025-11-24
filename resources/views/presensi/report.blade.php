@extends('layouts.fixplay')

@section('title','Laporan Presensi')
@section('page_title','Laporan Presensi')

@section('page_content')
<div class="row justify-content-center">
  <div class="col-xl-11">
    <div class="card card-dark fx-presensi-card mb-4">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
          <div>
            <h4 class="mb-1 fw-bold">Laporan Presensi</h4>
            <div class="text-neon-sub small">
              Periode:
              <span class="fw-semibold">{{ $from->format('d/m/Y') }}</span>
              s/d
              <span class="fw-semibold">{{ $to->format('d/m/Y') }}</span>
              (total {{ $totalHari }} hari)
            </div>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('presensi.index') }}" class="btn fx-btn-outline rounded-pill">
              <i class="bi bi-box-arrow-left me-1"></i> Presensi Hari Ini
            </a>
          </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="row g-3 mb-4 align-items-end">
          <div class="col-md-3">
            <label class="fx-label">Dari tanggal</label>
            <input type="date" name="from"
                   value="{{ request('from',$from->format('Y-m-d')) }}"
                   class="fx-select">
          </div>
          <div class="col-md-3">
            <label class="fx-label">Sampai tanggal</label>
            <input type="date" name="to"
                   value="{{ request('to',$to->format('Y-m-d')) }}"
                   class="fx-select">
          </div>
          <div class="col-md-3">
            <label class="fx-label">Karyawan</label>
            <select name="karyawan_id" class="fx-select">
              <option value="">Semua karyawan</option>
              @foreach($karyawans as $k)
                <option value="{{ $k->id }}" {{ (string)request('karyawan_id') === (string)$k->id ? 'selected' : '' }}>
                  {{ $k->nama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="fx-label">Shift</label>
            <select name="shift" class="fx-select">
              <option value="">Semua</option>
              @foreach($shifts as $key => $lbl)
                <option value="{{ $key }}" {{ request('shift') === $key ? 'selected' : '' }}>
                  {{ $lbl }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <button class="fx-btn-primary w-100" type="submit">
              <i class="bi bi-funnel"></i>
            </button>
          </div>
        </form>

        {{-- TOMBOL EXPORT --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <div class="text-neon-sub small">
            Ringkasan per karyawan untuk periode terpilih.
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('presensi.report.excel', request()->query()) }}"
              class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>

            <a href="{{ route('presensi.report.pdf', request()->query()) }}"
               class="btn btn-danger btn-sm rounded-pill" target="_blank">
              <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
          </div>
        </div>

        {{-- GRAFIK --}}
        <div class="row g-3 mb-4">
          <div class="col-lg-8">
            <div class="card card-dark h-100">
              <div class="card-header fw-bold">Grafik Hari Kerja & Telat</div>
              <div class="card-body">
                <canvas id="presensiChart" height="140"></canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card card-dark h-100">
              <div class="card-header fw-bold">Highlight Cepat</div>
              <div class="card-body">
                <ul class="list-unstyled small mb-0">
                  <li class="mb-2">
                    <span class="fw-semibold">Total karyawan:</span>
                    {{ count($summary) }}
                  </li>
                  <li class="mb-2">
                    <span class="fw-semibold">Rata-rata hari kerja:</span>
                    {{ count($summary) ? number_format(collect($summary)->avg('hari_kerja'),1) : 0 }}
                  </li>
                  <li class="mb-2">
                    <span class="fw-semibold">Total telat:</span>
                    {{ collect($summary)->sum('telat') }}
                  </li>
                  <li>
                    <span class="fw-semibold">Perkiraan total tidak hadir:</span>
                    {{ collect($summary)->sum('tidak_hadir') }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        {{-- TABEL RINGKASAN --}}
        <div class="fx-table-wrapper">
          <table class="table mb-0 fx-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th class="text-center" style="width:18%">Total Hari Kerja</th>
                <th class="text-center" style="width:18%">Jumlah Telat</th>
                <th class="text-center" style="width:20%">Perkiraan Tidak Hadir</th>
              </tr>
            </thead>
            <tbody>
              @forelse($summary as $row)
                <tr>
                  <td>{{ $row['nama'] }}</td>
                  <td class="text-center">
                    <span class="fx-badge-status fx-badge-success">
                      {{ $row['hari_kerja'] }} hari
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="fx-badge-status fx-badge-warning">
                      {{ $row['telat'] }} kali
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="fx-badge-status fx-badge-danger">
                      {{ $row['tidak_hadir'] }} hari
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-neon-sub py-4">
                    Belum ada data presensi pada periode dan filter ini.
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function () {
    const ctx = document.getElementById('presensiChart');
    if (!ctx) return;

    const labels = @json($chartLabels);
    const dataHari = @json($chartHari);
    const dataTelat = @json($chartTelat);

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Hari Kerja',
            data: dataHari,
            borderWidth: 1
          },
          {
            label: 'Telat',
            data: dataTelat,
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: '#e5e7eb'
            }
          }
        },
        scales: {
          x: {
            ticks: { color: '#e5e7eb' },
            grid: { display: false }
          },
          y: {
            beginAtZero: true,
            ticks: { color: '#e5e7eb', stepSize: 1 },
            grid: { color: 'rgba(148,163,184,0.3)' }
          }
        }
      }
    });
  })();
</script>
@endpush
