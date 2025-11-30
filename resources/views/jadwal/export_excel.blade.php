<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Jadwal</title>
</head>
<body>
    <h3>REKAP JADWAL KARYAWAN</h3>
    <p>Periode: {{ $startOfWeek->translatedFormat('d F Y') }} s/d {{ $endOfWeek->translatedFormat('d F Y') }}</p>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #eeeeee;">
                <th style="width: 150px;">HARI / TANGGAL</th>
                <th>SHIFT PAGI</th>
                <th>SHIFT SORE</th>
                <th style="width: 100px;">SAKIT</th>
                <th style="width: 100px;">IZIN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dates as $date)
                @php
                    $tglStr = $date->toDateString();
                    
                    // Ambil data (sesuaikan key shift dengan database)
                    $pagi  = $recap[$tglStr]['pagi'] ?? ($recap[$tglStr]['1'] ?? []);
                    $sore  = $recap[$tglStr]['sore'] ?? ($recap[$tglStr]['2'] ?? []);
                    $sakit = $recap[$tglStr]['sakit'] ?? ($recap[$tglStr]['s'] ?? []);
                    $izin  = $recap[$tglStr]['izin'] ?? ($recap[$tglStr]['i'] ?? []);
                @endphp
                <tr>
                    <td style="vertical-align: top;">
                        <strong>{{ $date->locale('id')->translatedFormat('l') }}</strong><br>
                        {{ $date->format('d/m/Y') }}
                    </td>
                    <td style="vertical-align: top;">
                        @if(count($pagi) > 0)
                            {{ implode(', ', $pagi) }}
                        @else - @endif
                    </td>
                    <td style="vertical-align: top;">
                        @if(count($sore) > 0)
                            {{ implode(', ', $sore) }}
                        @else - @endif
                    </td>
                    <td style="vertical-align: top;">
                        @if(count($sakit) > 0)
                            {{ implode(', ', $sakit) }}
                        @else - @endif
                    </td>
                    <td style="vertical-align: top;">
                        @if(count($izin) > 0)
                            {{ implode(', ', $izin) }}
                        @else - @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>