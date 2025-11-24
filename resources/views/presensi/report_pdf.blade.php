<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Presensi</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h2 { margin: 0 0 4px 0; }
    .small { font-size: 10px; color: #555; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #999; padding: 4px 6px; }
    th { background: #eee; }
    .text-center { text-align: center; }
  </style>
</head>
<body>
  <h2>Laporan Presensi Karyawan</h2>
  <div class="small">
    Periode:
    {{ $from->format('d/m/Y') }} s/d {{ $to->format('d/m/Y') }}<br>
    Total hari dalam periode: {{ $totalHari }}
  </div>

  <table>
    <thead>
      <tr>
        <th>Nama</th>
        <th class="text-center">Total Hari Kerja</th>
        <th class="text-center">Jumlah Telat</th>
        <th class="text-center">Perkiraan Tidak Hadir</th>
      </tr>
    </thead>
    <tbody>
      @forelse($summary as $row)
        <tr>
          <td>{{ $row['nama'] }}</td>
          <td class="text-center">{{ $row['hari_kerja'] }}</td>
          <td class="text-center">{{ $row['telat'] }}</td>
          <td class="text-center">{{ $row['tidak_hadir'] }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center">Tidak ada data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
