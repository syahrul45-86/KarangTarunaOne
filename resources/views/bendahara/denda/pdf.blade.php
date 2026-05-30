<!DOCTYPE html>
<html>
<head>
    <title>Rekap Tunggakan RT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekap Tunggakan (Denda & Arisan)</h2>
        <p>{{ $rt->nama_rt ?? 'RT' }} / {{ $rt->rw ?? 'RW' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="35%">Nama Anggota</th>
                <th width="20%" class="text-right">Tunggakan Denda</th>
                <th width="20%" class="text-right">Tunggakan Arisan</th>
                <th width="20%" class="text-right">Total Keseluruhan</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalSemuaDenda = 0;
                $totalSemuaArisan = 0;
                $totalKeseluruhan = 0;
            @endphp
            @forelse($data as $index => $row)
                @php
                    $totalSemuaDenda += $row['belum_bayar'];
                    $totalSemuaArisan += $row['tunggakan_arisan'];
                    $totalKeseluruhan += $row['total_semua'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['user']->name }}</td>
                    <td class="text-right">Rp {{ number_format($row['belum_bayar'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row['tunggakan_arisan'], 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($row['total_semua'], 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data tunggakan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp {{ number_format($totalSemuaDenda, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalSemuaArisan, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: Bendahara {{ $rt->nama_rt ?? '' }}</p>
    </div>

</body>
</html>
