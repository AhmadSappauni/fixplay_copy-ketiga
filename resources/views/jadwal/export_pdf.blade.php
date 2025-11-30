<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Mingguan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #444; padding: 8px; vertical-align: top; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9pt; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            margin: 1px;
            background: #eee;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 9pt;
        }
        .day-col { background: #fafafa; font-weight: bold; width: 20%; }
        .empty { color: #999; font-style: italic; font-size: 8pt; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekap Jadwal Karyawan</h2>
        <p>
            Periode: {{ $startOfWeek->locale('id')->translatedFormat('d F Y') }} 
            s/d 
            {{ $endOfWeek->locale('id')->translatedFormat('d F Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hari / Tanggal</th>
                <th width="25%">Shift Pagi</th>
                <th width="25%">Shift Sore</th>
                <th width="15%">Sakit</th>
                <th width="15%">Izin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dates as $date)
                @php
                    $tglStr = $date->toDateString();
                    $pagi  = $recap[$tglStr]['pagi'] ?? ($recap[$tglStr]['1'] ?? []);
                    $sore  = $recap[$tglStr]['sore'] ?? ($recap[$tglStr]['2'] ?? []);
                    $sakit = $recap[$tglStr]['sakit'] ?? ($recap[$tglStr]['s'] ?? []);
                    $izin  = $recap[$tglStr]['izin'] ?? ($recap[$tglStr]['i'] ?? []);
                @endphp
                <tr>
                    <td class="day-col">
                        {{ $date->locale('id')->translatedFormat('l') }}<br>
                        <span style="font-weight:normal; font-size:9pt; color:#666;">
                            {{ $date->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        @forelse($pagi as $nama)
                            <span class="badge">{{ $nama }}</span><br>
                        @empty
                            <span class="empty">-</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($sore as $nama)
                            <span class="badge">{{ $nama }}</span><br>
                        @empty
                            <span class="empty">-</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($sakit as $nama)
                            <span class="badge" style="background:#fee; border-color:#fcc;">{{ $nama }}</span><br>
                        @empty
                            <span class="empty">-</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($izin as $nama)
                            <span class="badge" style="background:#eef; border-color:#ccf;">{{ $nama }}</span><br>
                        @empty
                            <span class="empty">-</span>
                        @endforelse
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="font-size: 8pt; color: #777; margin-top: 10px;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>