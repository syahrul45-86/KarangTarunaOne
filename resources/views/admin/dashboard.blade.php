@extends('admin.layouts.master')

@section('title','Dashboard')

@section('content')

<style>
    /* Scoped Dashboard Styles for Admin */
    .admin-dashboard-wrapper {
        padding: 0;
    }

    .admin-page-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        color: white;
    }

    .admin-greeting {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .admin-subtitle {
        font-size: 16px;
        opacity: 0.95;
        font-weight: 400;
    }

    /* Stats Cards */
    .admin-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .admin-stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .admin-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
    }

    .admin-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .admin-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .admin-stat-icon {
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

    .admin-stat-content {
        flex: 1;
    }

    .admin-stat-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .admin-stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .admin-stat-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .admin-stat-trend {
        margin-top: 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .admin-trend-positive {
        color: #16a34a;
    }

    .admin-trend-neutral {
        color: #64748b;
    }

    /* Color Variants */
    .admin-card-primary {
        --card-color: #3b82f6;
        --card-bg: #dbeafe;
    }

    .admin-card-success {
        --card-color: #16a34a;
        --card-bg: #dcfce7;
    }

    .admin-card-warning {
        --card-color: #f59e0b;
        --card-bg: #fef3c7;
    }

    .admin-card-danger {
        --card-color: #dc2626;
        --card-bg: #fee2e2;
    }

    .admin-card-info {
        --card-color: #0891b2;
        --card-bg: #cffafe;
    }

    .admin-card-purple {
        --card-color: #9333ea;
        --card-bg: #f3e8ff;
    }

    /* Section */
    .admin-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .admin-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Table */
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .admin-table thead th {
        background: #f8fafc;
        padding: 12px 15px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .admin-table tbody tr {
        background: white;
        transition: all 0.2s ease;
    }

    .admin-table tbody tr:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .admin-table tbody td {
        padding: 15px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .admin-table tbody td:first-child {
        border-left: 1px solid #e2e8f0;
        border-radius: 8px 0 0 8px;
    }

    .admin-table tbody td:last-child {
        border-right: 1px solid #e2e8f0;
        border-radius: 0 8px 8px 0;
    }

    /* Progress Bar */
    .admin-progress-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }

    .admin-progress-fill {
        height: 100%;
        background: var(--progress-color, #3b82f6);
        border-radius: 10px;
        transition: width 1s ease;
    }

    /* Badge */
    .admin-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .admin-badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .admin-badge-warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .admin-badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    /* List */
    .admin-list-item {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-list-item:last-child {
        border-bottom: none;
    }

    /* Empty State */
    .admin-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }

    .admin-empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Quick Actions */
    .admin-quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .admin-action-btn {
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

    .admin-action-btn:hover {
        border-color: #3b82f6;
        background: #f0f9ff;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        color: #3b82f6;
        text-decoration: none;
    }

    .admin-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-page-header {
            padding: 20px;
        }

        .admin-greeting {
            font-size: 22px;
        }

        .admin-stats-row {
            grid-template-columns: 1fr;
        }

        .admin-quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .admin-quick-actions {
            grid-template-columns: 1fr;
        }

        .admin-stat-value {
            font-size: 24px;
        }
    }
</style>

<div class="admin-dashboard-wrapper">
    <!-- Page Header -->
    <div class="admin-page-header">
        <h1 class="admin-greeting">👋 Selamat Datang, {{ $admin->name }}!</h1>
        <p class="admin-subtitle mb-0">Dashboard Admin RT - Kelola seluruh aktivitas RT dengan mudah</p>
    </div>

    <!-- Main Stats -->
    <div class="admin-stats-row">
        <!-- Total Anggota -->
        <div class="admin-stat-card admin-card-primary">
            <div class="admin-stat-header">
                <div class="admin-stat-content">
                    <div class="admin-stat-label">👥 Total Anggota RT</div>
                    <div class="admin-stat-value">{{ $totalAnggota }}</div>
                    <div class="admin-stat-subtitle">
                        {{ $jumlahAnggotaBiasa }} Anggota | {{ $jumlahSekretaris }} Sekretaris | {{ $jumlahBendahara }} Bendahara
                    </div>
                </div>
                <div class="admin-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Kegiatan -->
        <div class="admin-stat-card admin-card-success">
            <div class="admin-stat-header">
                <div class="admin-stat-content">
                    <div class="admin-stat-label">📅 Total Kegiatan</div>
                    <div class="admin-stat-value">{{ $totalKegiatan }}</div>
                    <div class="admin-stat-subtitle">{{ $kegiatanBulanIni }} kegiatan bulan ini</div>
                    <div class="admin-stat-trend admin-trend-positive">
                        <i class="fas fa-check-circle"></i>
                        <span>Kehadiran rata-rata: {{ $rataRataKehadiran }}%</span>
                    </div>
                </div>
                <div class="admin-stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <!-- Arisan Bulan Ini -->
        <div class="admin-stat-card admin-card-info">
            <div class="admin-stat-header">
                <div class="admin-stat-content">
                    <div class="admin-stat-label">💰 Arisan Bulan Ini</div>
                    <div class="admin-stat-value">{{ $pembayaranArisanBulanIni }}/{{ $targetArisanBulanIni }}</div>
                    <div class="admin-stat-subtitle">Progress: {{ $persentaseArisanBulanIni }}%</div>
                    <div class="admin-progress-bar">
                        <div class="admin-progress-fill"
                             style="width: {{ $persentaseArisanBulanIni }}%; --progress-color: #0891b2;"></div>
                    </div>
                </div>
                <div class="admin-stat-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="admin-stat-card admin-card-danger">
            <div class="admin-stat-header">
                <div class="admin-stat-content">
                    <div class="admin-stat-label">⚠️ Total Denda</div>
                    <div class="admin-stat-value">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                    <div class="admin-stat-subtitle">
                        Belum Bayar: Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}
                    </div>
                    <div class="admin-stat-trend admin-trend-neutral">
                        <i class="fas fa-users"></i>
                        <span>{{ $anggotaBermasalah }} anggota bermasalah</span>
                    </div>
                </div>
                <div class="admin-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-section">
        <h2 class="admin-section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </h2>
        <div class="admin-quick-actions">
            <a href="{{ route('admin.AnggotaRT.index') }}" class="admin-action-btn">
                <div class="admin-action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>Kelola Anggota</span>
            </a>
            <a href="{{ route('admin.rekap.arisan.index') }}" class="admin-action-btn">
                <div class="admin-action-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span>Kelola Arisan</span>
            </a>
            <a href="{{ route('admin.setting_rt.index') }}" class="admin-action-btn">
                <div class="admin-action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span>Pengaturan RT</span>
            </a>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="row">
        <!-- Kegiatan Terbaru -->
        <div class="col-lg-6">
            <div class="admin-section">
                <h2 class="admin-section-title">
                    <i class="fas fa-calendar"></i>
                    Kegiatan Terbaru
                </h2>
                @if($kegiatanTerbaru->count() > 0)
                    @foreach($kegiatanTerbaru as $kegiatan)
                    <div class="admin-list-item">
                        <div>
                            <strong>{{ $kegiatan->judul }}</strong>
                            <div style="font-size: 13px; color: #64748b;">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }}
                            </div>
                        </div>
                        <span class="admin-badge admin-badge-success">
                            {{ $kegiatan->absensi()->count() }} Hadir
                        </span>
                    </div>
                    @endforeach
                @else
                    <div class="admin-empty-state">
                        <i class="fas fa-calendar"></i>
                        <p>Belum ada kegiatan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Denda Tertunggak -->
        <div class="col-lg-6">
            <div class="admin-section">
                <h2 class="admin-section-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Denda Tertunggak
                </h2>
                @if($dendaTertunggak->count() > 0)
                    @foreach($dendaTertunggak as $denda)
                    <div class="admin-list-item">
                        <div>
                            <strong>{{ $denda->user->name }}</strong>
                            <div style="font-size: 13px; color: #64748b;">
                                {{ ucfirst($denda->jenis) }}
                            </div>
                        </div>
                        <span class="admin-badge admin-badge-danger">
                            Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <div class="admin-empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>Tidak ada denda tertunggak</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Anggota Terbaru -->
    <div class="admin-section">
        <h2 class="admin-section-title">
            <i class="fas fa-user-plus"></i>
            Anggota Terbaru
        </h2>
        @if($anggotaTerbaru->count() > 0)
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggotaTerbaru as $anggota)
                    <tr>
                        <td><strong>{{ $anggota->name }}</strong></td>
                        <td>{{ $anggota->email }}</td>
                        <td>
                            <span class="admin-badge admin-badge-success">
                                {{ ucfirst($anggota->role) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($anggota->created_at)->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="admin-empty-state">
            <i class="fas fa-users"></i>
            <p>Belum ada anggota baru</p>
        </div>
        @endif
    </div>
</div>

<script>
(function() {
    'use strict';

    // Animate progress bars on load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.admin-progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
})();
</script>

@include('admin.layouts.footer')
@endsection
