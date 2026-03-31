@extends('admin.layouts.master')

@section('title', 'Detail Rekap Absensi')

@section('content')

<div class="detail-rekap-container">
    <div class="detail-rekap-header">
        <h3>📊 Detail Rekap Absensi</h3>
        <div class="detail-rekap-btn-group">
            <a href="{{ route('admin.rekap.index') }}" class="detail-rekap-btn detail-rekap-btn-danger">
                <span>◀️</span>
                <span>Kembali</span>
            </a>
            <a href="{{ route('admin.rekap.pdf', $form->id) }}" class="detail-rekap-btn detail-rekap-btn-success">
                <span>📄</span>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <div class="detail-rekap-info-card">
        <h4 class="detail-rekap-info-title">
            <span>📋</span>
            <span>{{ $form->judul }}</span>
        </h4>
        <div class="detail-rekap-info-grid">
            <div class="detail-rekap-info-item">
                <div class="detail-rekap-info-icon">📅</div>
                <div class="detail-rekap-info-content">
                    <div class="detail-rekap-info-label">Tanggal</div>
                    <div class="detail-rekap-info-value">{{ \Carbon\Carbon::parse($form->tanggal)->format('d F Y') }}</div>
                </div>
            </div>
            <div class="detail-rekap-info-item">
                <div class="detail-rekap-info-icon">🕐</div>
                <div class="detail-rekap-info-content">
                    <div class="detail-rekap-info-label">Waktu</div>
                    <div class="detail-rekap-info-value">{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-rekap-section">
        <div class="detail-rekap-section-header">
            <h4 class="detail-rekap-section-title">
                <span>✅</span>
                <span>Daftar Hadir</span>
            </h4>
            <span class="detail-rekap-badge detail-rekap-badge-success">
                {{ count($hadir) }} Orang
            </span>
        </div>
        <div class="detail-rekap-table-wrapper">
            <table class="detail-rekap-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Waktu Absen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hadir as $h)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Nama">{{ $h->user->name }}</td>
                            <td data-label="Waktu Absen">
                                🕐 {{ \Carbon\Carbon::parse($h->waktu_absen)->format('H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="detail-rekap-empty">
                                    <div class="detail-rekap-empty-icon">😔</div>
                                    <div class="detail-rekap-empty-text">Belum ada yang hadir</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="detail-rekap-section">
        <div class="detail-rekap-section-header">
            <h4 class="detail-rekap-section-title">
                <span>❌</span>
                <span>Tidak Hadir</span>
            </h4>
            <span class="detail-rekap-badge detail-rekap-badge-warning">
                {{ count($tidakHadir) }} Orang
            </span>
        </div>
        <div class="detail-rekap-table-wrapper">
            <table class="detail-rekap-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tidakHadir as $u)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Nama">{{ $u->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                <div class="detail-rekap-empty">
                                    <div class="detail-rekap-empty-icon">🎉</div>
                                    <div class="detail-rekap-empty-text">Semua hadir!</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
