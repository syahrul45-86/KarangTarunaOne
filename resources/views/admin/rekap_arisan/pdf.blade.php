<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border:1px solid #000; padding:6px; text-align:center; }
        h3 { margin-bottom: 0; }
    </style>
</head>
<body>

<h3>Rekap Arisan Tahun {{ $tahun->tahun }}</h3>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            @foreach($tahun->tanggal as $tgl)
                <th>{{ \Carbon\Carbon::parse($tgl->tanggal)->translatedFormat('d M') }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach($anggota as $user)
        <tr>
            <td style="text-align:left;">{{ $user->name }}</td>

            @foreach($tahun->tanggal as $tgl)
                <td>
                    @if(isset($checklist[$user->id][$tgl->id]))
                        ✔️
                    @else
                        ✖️
                    @endif
                </td>
            @endforeach

            <td>
                {{ $totalBayar[$user->id] ?? 0 }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
