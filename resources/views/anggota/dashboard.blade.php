@extends('anggota.layouts.master')

@section('title','Dashboard')

@section('content')

<style>
    /* Scoped Dashboard Styles for Anggota */
    .anggota-dashboard-wrapper {
        padding: 0;
    }

    .anggota-page-header {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
        color: white;
    }

    .anggota-greeting {
        color: #e0e7ff;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .anggota-subtitle {
        font-size: 16px;
        opacity: 0.95;
        font-weight: 400;
    }

    .anggota-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .anggota-btn-header {
        background: white;
        color: #4f46e5;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .anggota-btn-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #4f46e5;
        text-decoration: none;
    }

    /* Stats Cards */
    .anggota-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .anggota-stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .anggota-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
    }

    .anggota-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .anggota-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .anggota-stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        background: var(--card-bg);
        color: var(--card-color);
        flex-shrink: 0;
    }

    .anggota-stat-content {
        flex: 1;
    }

    .anggota-stat-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .anggota-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .anggota-stat-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .anggota-progress-wrap {
        margin-top: 10px;
        background: #e2e8f0;
        border-radius: 99px;
        height: 6px;
        overflow: hidden;
    }

    .anggota-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--card-color);
        transition: width 1s ease;
    }

    /* Color Variants */
    .anggota-card-indigo {
        --card-color: #4f46e5;
        --card-bg: #e0e7ff;
    }

    .anggota-card-success {
        --card-color: #16a34a;
        --card-bg: #dcfce7;
    }

    .anggota-card-danger {
        --card-color: #dc2626;
        --card-bg: #fee2e2;
    }

    .anggota-card-cyan {
        --card-color: #0891b2;
        --card-bg: #cffafe;
    }

    /* Quick Actions */
    .anggota-quick-actions {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .anggota-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .anggota-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .anggota-action-btn {
        padding: 20px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: white;
        text-decoration: none;
        color: #1e293b;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        text-align: center;
    }

    .anggota-action-btn:hover {
        border-color: #4f46e5;
        background: #eef2ff;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        color: #4f46e5;
        text-decoration: none;
    }

    .anggota-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .anggota-action-label {
        font-weight: 600;
        font-size: 14px;
    }

    /* Content Cards */
    .anggota-content-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    /* List Items */
    .anggota-list-item {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #e2e8f0;
        transition: background 0.2s ease;
    }

    .anggota-list-item:hover {
        background: #f8fafc;
    }

    .anggota-list-item:last-child {
        margin-bottom: 0;
    }

    .anggota-list-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 3px;
    }

    .anggota-list-sub {
        font-size: 13px;
        color: #64748b;
    }

    /* Badges */
    .anggota-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .anggota-badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .anggota-badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .anggota-badge-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .anggota-badge-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Denda Item */
    .anggota-denda-item {
        padding: 15px;
        background: #fff1f2;
        border-left: 4px solid #dc2626;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .anggota-denda-item:last-child {
        margin-bottom: 0;
    }

    /* Tunggakan Item */
    .anggota-tunggakan-item {
        padding: 15px;
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .anggota-tunggakan-item:last-child {
        margin-bottom: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .anggota-page-header {
            padding: 20px;
        }

        .anggota-greeting {
            font-size: 22px;
        }

        .anggota-subtitle {
            font-size: 14px;
        }

        .anggota-stats-row {
            grid-template-columns: 1fr;
        }

        .anggota-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .anggota-header-actions {
            margin-top: 15px;
        }

        .anggota-list-item {
            flex-wrap: wrap;
            gap: 8px;
        }
    }

    @media (max-width: 480px) {
        .anggota-action-grid {
            grid-template-columns: 1fr;
        }

        .anggota-stat-value {
            font-size: 22px;
        }
    }
</style>

<div class="anggota-dashboard-wrapper">

    <!-- Page Header -->
    <div class="anggota-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="anggota-greeting">👋 Selamat Datang, {{ $anggota->name }}!</h1>
                <p class="anggota-subtitle mb-0">Dashboard Anggota RT — pantau kehadiran dan iuran Anda</p>
            </div>
            <div class="anggota-header-actions">
                <a href="{{ route('anggota.qrcode.show') }}" class="anggota-btn-header">
                    <i class="fas fa-qrcode"></i>
                    <span>QR Code Saya</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="anggota-stats-row">

        <!-- Kehadiran -->
        <div class="anggota-stat-card anggota-card-cyan">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">📅 Kehadiran</div>
                    <div class="anggota-stat-value">{{ $persentaseHadir }}%</div>
                    <div class="anggota-progress-wrap">
                        <div class="anggota-progress-fill" style="width: {{ $persentaseHadir }}%;"></div>
                    </div>
                    <div class="anggota-stat-subtitle">Hadir {{ $totalHadir }} / {{ $totalKegiatan }} Kegiatan</div>
                </div>
                <div class="anggota-stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <!-- Arisan -->
        <div class="anggota-stat-card anggota-card-success">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">💰 Arisan ({{ $tahunArisan->tahun ?? date('Y') }})</div>
                    <div class="anggota-stat-value">{{ $persentaseBayarArisan }}%</div>
                    <div class="anggota-progress-wrap">
                        <div class="anggota-progress-fill" style="width: {{ $persentaseBayarArisan }}%;"></div>
                    </div>
                    <div class="anggota-stat-subtitle">Bayar {{ $sudahBayarArisan }} / {{ $totalBulanArisan }} Bulan</div>
                </div>
                <div class="anggota-stat-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>

        <!-- Denda Belum Bayar -->
        <div class="anggota-stat-card anggota-card-danger">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">⚠️ Denda Belum Bayar</div>
                    <div class="anggota-stat-value">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</div>
                    <div class="anggota-stat-subtitle">Total: Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                </div>
                <div class="anggota-stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>

        <!-- Iuran Arisan -->
        <div class="anggota-stat-card anggota-card-indigo">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">🏠 Iuran Arisan / Bulan</div>
                    <div class="anggota-stat-value">Rp {{ number_format($iuranArisan, 0, ',', '.') }}</div>
                    <div class="anggota-stat-subtitle">{{ $anggota->rt->nama_rt ?? '-' }}</div>
                </div>
                <div class="anggota-stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Tunggakan & Denda Stats -->
    <h4 class="mb-3 font-weight-bold text-gray-800" style="margin-top: 10px;"><i class="fas fa-file-invoice-dollar text-danger"></i> Ringkasan Denda & Tunggakan (Pribadi)</h4>
    <div class="anggota-stats-row">
        <!-- Denda Kegiatan -->
        <div class="anggota-stat-card anggota-card-warning">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">🎯 Denda Kegiatan (Belum Bayar)</div>
                    <div class="anggota-stat-value" style="font-size: 22px;">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</div>
                    <div class="anggota-stat-subtitle">Total denda kegiatan Anda</div>
                </div>
                <div class="anggota-stat-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-calendar-times"></i>
                </div>
            </div>
        </div>

        <!-- Tunggakan Arisan -->
        <div class="anggota-stat-card anggota-card-cyan">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">💰 Tunggakan Arisan</div>
                    <div class="anggota-stat-value" style="font-size: 22px;">Rp {{ number_format($totalNominalTunggakanArisan, 0, ',', '.') }}</div>
                    <div class="anggota-stat-subtitle">Total tunggakan arisan Anda</div>
                </div>
                <div class="anggota-stat-icon" style="background: #cffafe; color: #0891b2;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Denda Keseluruhan -->
        <div class="anggota-stat-card anggota-card-danger">
            <div class="anggota-stat-header">
                <div class="anggota-stat-content">
                    <div class="anggota-stat-label">🚨 Denda Keseluruhan</div>
                    <div class="anggota-stat-value" style="font-size: 22px;">Rp {{ number_format($totalDendaKeseluruhan, 0, ',', '.') }}</div>
                    <div class="anggota-stat-subtitle">Total Denda Kegiatan + Arisan</div>
                </div>
                <div class="anggota-stat-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="anggota-quick-actions">
        <h2 class="anggota-section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </h2>
        <div class="anggota-action-grid">
            <a href="{{ route('anggota.qrcode.show') }}" class="anggota-action-btn">
                <div class="anggota-action-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="anggota-action-label">QR Code Saya</span>
            </a>
            <a href="#riwayat-kegiatan" class="anggota-action-btn">
                <div class="anggota-action-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="anggota-action-label">Riwayat Kegiatan</span>
            </a>
            <a href="#riwayat-denda" class="anggota-action-btn">
                <div class="anggota-action-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="anggota-action-label">Riwayat Denda</span>
            </a>
            <a href="#tunggakan-arisan" class="anggota-action-btn">
                <div class="anggota-action-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <span class="anggota-action-label">Tunggakan Arisan</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Riwayat Kegiatan -->
        <div class="col-lg-6" id="riwayat-kegiatan">
            <div class="anggota-content-card">
                <h2 class="anggota-section-title">
                    <i class="fas fa-history"></i>
                    Riwayat Kegiatan
                </h2>

                @if($kegiatanTerbaru->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                    Belum ada kegiatan RT.
                </div>
                @else
                @foreach($kegiatanTerbaru as $kegiatan)
                <div class="anggota-list-item">
                    <div>
                        <div class="anggota-list-title">{{ $kegiatan->judul }}</div>
                        <div class="anggota-list-sub">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</div>
                    </div>
                    @if($kegiatan->sudah_hadir)
                        <span class="anggota-badge anggota-badge-success"><i class="fas fa-check"></i> Hadir</span>
                    @elseif($kegiatan->izin)
                        @if($kegiatan->izin->status === 'approved')
                            <span class="anggota-badge anggota-badge-warning"><i class="fas fa-envelope-open-text"></i> Izin</span>
                        @elseif($kegiatan->izin->status === 'pending')
                            <span class="anggota-badge anggota-badge-secondary"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                        @else
                            <span class="anggota-badge anggota-badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                        @endif
                    @else
                        <span class="anggota-badge anggota-badge-danger"><i class="fas fa-times"></i> Tidak Hadir</span>
                    @endif
                </div>
                @endforeach
                <div class="d-flex justify-content-center mt-3">
                    {{ $kegiatanTerbaru->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Denda -->
        <div class="col-lg-6" id="riwayat-denda">
            <div class="anggota-content-card">
                <h2 class="anggota-section-title" style="color: #dc2626;">
                    <i class="fas fa-exclamation-circle"></i>
                    Riwayat Denda
                </h2>

                @if($riwayatDenda->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color: #16a34a;"></i>
                    Kamu tidak memiliki riwayat denda. Pertahankan!
                </div>
                @else
                @foreach($riwayatDenda as $denda)
                <div class="anggota-denda-item">
                    <div>
                        <div class="anggota-list-title">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</div>
                        <div class="anggota-list-sub">
                            <span class="text-capitalize">{{ $denda->jenis }}</span>
                            @if($denda->alasan) — {{ Str::limit($denda->alasan, 30) }} @endif
                        </div>
                    </div>
                    @if($denda->status == 'lunas')
                        <span class="anggota-badge anggota-badge-success">Lunas</span>
                    @else
                        <span class="anggota-badge anggota-badge-danger">Belum Bayar</span>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
        </div>

    </div>

    <!-- Tunggakan Arisan -->
    <div class="row" id="tunggakan-arisan">
        <div class="col-lg-6">
            <div class="anggota-content-card">
                <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 20px;">
                    <h2 class="anggota-section-title" style="color: #d97706; margin-bottom: 0;">
                        <i class="fas fa-piggy-bank"></i>
                        Tunggakan Arisan
                    </h2>
                    <span class="anggota-badge anggota-badge-danger" style="font-size: 13px; padding: 6px 14px;">
                        Total: Rp {{ number_format($totalNominalTunggakanArisan, 0, ',', '.') }}
                    </span>
                </div>

                @if($tunggakanArisanList->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color: #16a34a;"></i>
                    Hebat! Kamu tidak punya tunggakan arisan.
                </div>
                @else
                @foreach($tunggakanArisanList as $tunggakan)
                <div class="anggota-tunggakan-item">
                    <div>
                        <div class="anggota-list-title">Arisan {{ \Carbon\Carbon::parse($tunggakan->tanggal)->translatedFormat('F Y') }}</div>
                        <div class="anggota-list-sub">Nominal Iuran: Rp {{ number_format($iuranArisan, 0, ',', '.') }}</div>
                    </div>
                    <span class="anggota-badge anggota-badge-warning">Belum Bayar</span>
                </div>
                @endforeach
                <div style="margin-top: 12px; text-align: center;">
                    <small class="text-muted">* Menampilkan maksimal 5 tunggakan terbaru.</small>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bars
        const fills = document.querySelectorAll('.anggota-progress-fill');
        fills.forEach(fill => {
            const target = fill.style.width;
            fill.style.width = '0%';
            setTimeout(() => {
                fill.style.width = target;
            }, 300);
        });

        // Hover effect on list items
        const items = document.querySelectorAll('.anggota-list-item');
        items.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f1f5f9';
            });
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'white';
            });
        });
    });
})();
</script>

@include('anggota.layouts.footer')
@endsection