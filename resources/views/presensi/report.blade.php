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
        <div class="fx-table-wrapper mb-4">
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

        {{-- [BARU] REKAP JADWAL & STATUS --}}
        {{-- Kita ambil data jadwal sesuai filter periode & karyawan langsung di view --}}
        @php
            $jadwalQuery = \App\Models\JadwalKaryawan::with('karyawan')
                ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()]);
            
            // Terapkan filter jika ada
            if(request('karyawan_id')) {
                $jadwalQuery->where('karyawan_id', request('karyawan_id'));
            }
            if(request('shift')) {
                $jadwalQuery->where('shift', request('shift'));
            }

            $jadwalList = $jadwalQuery->orderBy('tanggal')->get();

            // KELOMPOKKAN DATA BERDASARKAN TANGGAL & SHIFT
            $recap = [];
            foreach($jadwalList as $j) {
                $d = $j->tanggal->toDateString();
                // Normalisasi key shift (lowercase)
                $s = strtolower($j->shift); 
                $recap[$d][$s][] = [
                    'nama' => $j->karyawan->nama,
                    'catatan' => $j->catatan
                ];
            }

            // Generate rentang tanggal untuk looping tabel
            $period = \Carbon\CarbonPeriod::create($from, $to);
        @endphp

        <div class="card card-dark border-secondary border-opacity-25">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 pt-3 pb-2">
                <h6 class="fw-bold mb-0 text-info"><i class="bi bi-calendar-week me-2"></i>Rekap Jadwal & Kehadiran</h6>
                <div class="text-secondary small mt-1">Monitoring shift, sakit, dan izin beserta catatannya.</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered border-secondary table-dark mb-0 align-middle text-center" style="background: transparent;">
                        <thead class="bg-secondary bg-opacity-10">
                            <tr>
                                <th class="py-3 text-start ps-4">Hari / Tanggal</th>
                                <th class="py-3" style="width: 25%;">Shift Pagi</th>
                                <th class="py-3" style="width: 25%;">Shift Sore</th>
                                <th class="py-3" style="width: 15%;">Sakit</th>
                                <th class="py-3" style="width: 15%;">Izin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($period as $dt)
                                @php
                                    $tglStr = $dt->toDateString();
                                    $dataHari = $recap[$tglStr] ?? [];
                                    
                                    // Ambil list karyawan per kategori
                                    // Sesuaikan key array ini dengan value yang tersimpan di DB Anda (pagi, sore, sakit, izin)
                                    $pagi  = $dataHari['pagi'] ?? ($dataHari['1'] ?? []);
                                    $sore  = $dataHari['sore'] ?? ($dataHari['2'] ?? []);
                                    $sakit = $dataHari['sakit'] ?? ($dataHari['s'] ?? []);
                                    $izin  = $dataHari['izin'] ?? ($dataHari['i'] ?? []);
                                @endphp
                                <tr>
                                    <td class="text-start ps-4">
                                        <div class="fw-bold text-white">{{ $dt->locale('id')->translatedFormat('l') }}</div>
                                        <div class="small text-secondary">{{ $dt->format('d/m/Y') }}</div>
                                    </td>

                                    {{-- SHIFT PAGI --}}
                                    <td class="align-top py-3">
                                        @if(count($pagi) > 0)
                                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                                @foreach($pagi as $k)
                                                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill px-3 py-2">
                                                        {{ $k['nama'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic small opacity-50">-</span>
                                        @endif
                                    </td>

                                    {{-- SHIFT SORE --}}
                                    <td class="align-top py-3">
                                        @if(count($sore) > 0)
                                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                                @foreach($sore as $k)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-2">
                                                        {{ $k['nama'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic small opacity-50">-</span>
                                        @endif
                                    </td>

                                    {{-- SAKIT (Tampilkan Catatan) --}}
                                    <td class="align-top py-3">
                                        @if(count($sakit) > 0)
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                @foreach($sakit as $k)
                                                    <div class="text-center lh-sm">
                                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 mb-1">
                                                            {{ $k['nama'] }}
                                                        </span>
                                                        @if(!empty($k['catatan']))
                                                            <div class="small text-danger text-opacity-75 fst-italic" style="font-size: 0.75rem;">
                                                                "{{ $k['catatan'] }}"
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic small opacity-25">-</span>
                                        @endif
                                    </td>

                                    {{-- IZIN (Tampilkan Catatan) --}}
                                    <td class="align-top py-3">
                                        @if(count($izin) > 0)
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                @foreach($izin as $k)
                                                    <div class="text-center lh-sm">
                                                        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 mb-1">
                                                            {{ $k['nama'] }}
                                                        </span>
                                                        @if(!empty($k['catatan']))
                                                            <div class="small text-primary text-opacity-75 fst-italic" style="font-size: 0.75rem;">
                                                                "{{ $k['catatan'] }}"
                                                            </div>
                                                        @endif
                                                    </div>
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