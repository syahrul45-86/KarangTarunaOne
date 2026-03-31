<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h2 {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 12px;
            color: #718096;
            font-style: italic;
        }

        .info-section {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }

        .info-section h3 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #4a5568;
            padding: 5px 0;
            width: 120px;
        }

        .info-value {
            display: table-cell;
            color: #2d3748;
            padding: 5px 0;
        }

        .section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            border-radius: 6px 6px 0 0;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-badge {
            background: rgba(255, 255, 255, 0.3);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: white;
        }

        thead {
            background: #e6e9f0;
        }

        th {
            border: 1px solid #cbd5e0;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            color: #2d3748;
        }

        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        tbody tr:hover {
            background-color: #edf2f7;
        }

        .text-center {
            text-align: center;
            font-style: italic;
            color: #718096;
            padding: 20px;
        }

        .no-column {
            width: 50px;
            text-align: center;
            font-weight: 600;
        }

        .waktu-column {
            width: 120px;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 10px;
        }

        .summary-box {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            display: table;
            width: 100%;
        }

        .summary-row {
            display: table-row;
        }

        .summary-label {
            display: table-cell;
            font-weight: 600;
            color: #4a5568;
            padding: 5px 10px;
            width: 50%;
        }

        .summary-value {
            display: table-cell;
            text-align: right;
            font-weight: 700;
            color: #2d3748;
            padding: 5px 10px;
            font-size: 14px;
        }

        .status-hadir {
            color: #38a169;
            font-weight: 600;
        }

        .status-tidak-hadir {
            color: #e53e3e;
            font-weight: 600;
        }

        hr {
            border: none;
            border-top: 2px solid #e2e8f0;
            margin: 20px 0;
        }

        @media print {
            body {
                padding: 10px;
            }

            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>📊 Rekap Absensi Kegiatan</h2>
    <p class="subtitle">Sistem Informasi RT - Laporan Kehadiran</p>
</div>

<div class="info-section">
    <h3>📋 {{ $form->judul }}</h3>
    <div class="info-grid">
        <div class="info-row">
            <span class="info-label">📅 Tanggal:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($form->tanggal)->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">🕐 Waktu:</span>
            <span class="info-value">{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</span>
        </div>
    </div>
</div>

<div class="summary-box">
    <div class="summary-row">
        <span class="summary-label">Total Hadir:</span>
        <span class="summary-value status-hadir">{{ count($hadir) }} Orang</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Total Tidak Hadir:</span>
        <span class="summary-value status-tidak-hadir">{{ count($tidakHadir) }} Orang</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Total Peserta:</span>
        <span class="summary-value">{{ count($hadir) + count($tidakHadir) }} Orang</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Persentase Kehadiran:</span>
        <span class="summary-value">
            {{ count($hadir) + count($tidakHadir) > 0 ? round((count($hadir) / (count($hadir) + count($tidakHadir))) * 100, 1) : 0 }}%
        </span>
    </div>
</div>

<div class="section">
    <div class="section-header">
        <span>✅ Daftar Hadir</span>
        <span class="section-badge">{{ count($hadir) }} Orang</span>
    </div>
    <table>
        <thead>
            <tr>
                <th class="no-column">No</th>
                <th>Nama</th>
                <th class="waktu-column">Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hadir as $h)
                <tr>
                    <td class="no-column">{{ $loop->iteration }}</td>
                    <td>{{ $h->user->name }}</td>
                    <td class="waktu-column">{{ \Carbon\Carbon::parse($h->waktu_absen)->format('H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada yang hadir</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">
        <span>❌ Daftar Tidak Hadir</span>
        <span class="section-badge">{{ count($tidakHadir) }} Orang</span>
    </div>
    <table>
        <thead>
            <tr>
                <th class="no-column">No</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tidakHadir as $u)
                <tr>
                    <td class="no-column">{{ $loop->iteration }}</td>
                    <td>{{ $u->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">🎉 Semua hadir!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    <p>Dokumen ini digenerate secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    <p>© {{ date('Y') }} Sistem Informasi RT - Semua hak dilindungi</p>
</div>

</body>
</html>
