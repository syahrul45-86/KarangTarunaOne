@extends('sekretaris.layouts.master')

@section('title','Dashboard')

@section('content')

<style>
    /* Scoped Dashboard Styles for Sekretaris */
    .sekretaris-dashboard-wrapper {
        padding: 0;
    }

    .sekretaris-page-header {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(29, 78, 216, 0.3);
        color: white;
    }

    .sekretaris-greeting {
        color: #dbeafe;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .sekretaris-subtitle {
        font-size: 16px;
        opacity: 0.95;
        font-weight: 400;
    }

    .sekretaris-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .sekretaris-btn-report {
        background: white;
        color: #1d4ed8;
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

    .sekretaris-btn-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #1d4ed8;
        text-decoration: none;
    }

    /* Stats Cards */
    .sekretaris-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .sekretaris-stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .sekretaris-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
    }

    .sekretaris-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .sekretaris-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .sekretaris-stat-icon {
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

    .sekretaris-stat-content {
        flex: 1;
    }

    .sekretaris-stat-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .sekretaris-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .sekretaris-stat-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .sekretaris-stat-trend {
        margin-top: 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Progress Bar */
    .sekretaris-progress-wrap {
        margin-top: 10px;
        background: #e2e8f0;
        border-radius: 99px;
        height: 6px;
        overflow: hidden;
    }

    .sekretaris-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--card-color);
        transition: width 1s ease;
    }

    /* Color Variants */
    .sekretaris-card-primary {
        --card-color: #1d4ed8;
        --card-bg: #dbeafe;
    }

    .sekretaris-card-success {
        --card-color: #16a34a;
        --card-bg: #dcfce7;
    }

    .sekretaris-card-cyan {
        --card-color: #0891b2;
        --card-bg: #cffafe;
    }

    .sekretaris-card-warning {
        --card-color: #f59e0b;
        --card-bg: #fef3c7;
    }

    .sekretaris-card-danger {
        --card-color: #dc2626;
        --card-bg: #fee2e2;
    }

    .sekretaris-card-purple {
        --card-color: #9333ea;
        --card-bg: #f3e8ff;
    }

    /* Quick Actions */
    .sekretaris-quick-actions {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .sekretaris-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sekretaris-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .sekretaris-action-btn {
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

    .sekretaris-action-btn:hover {
        border-color: #1d4ed8;
        background: #eff6ff;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        color: #1d4ed8;
        text-decoration: none;
    }

    .sekretaris-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .sekretaris-action-label {
        font-weight: 600;
        font-size: 14px;
    }

    /* Transaction Table */
    .sekretaris-transaction-table {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .sekretaris-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .sekretaris-table thead th {
        background: #f8fafc;
        padding: 12px 15px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .sekretaris-table thead th:first-child {
        border-radius: 8px 0 0 8px;
    }

    .sekretaris-table thead th:last-child {
        border-radius: 0 8px 8px 0;
    }

    .sekretaris-table tbody tr {
        background: white;
        transition: all 0.2s ease;
    }

    .sekretaris-table tbody tr:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .sekretaris-table tbody td {
        padding: 15px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .sekretaris-table tbody td:first-child {
        border-left: 1px solid #e2e8f0;
        border-radius: 8px 0 0 8px;
    }

    .sekretaris-table tbody td:last-child {
        border-right: 1px solid #e2e8f0;
        border-radius: 0 8px 8px 0;
    }

    /* Badges */
    .sekretaris-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .sekretaris-badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .sekretaris-badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .sekretaris-badge-warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .sekretaris-badge-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    /* Icon Buttons */
    .sekretaris-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .sekretaris-ib-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .sekretaris-ib-blue:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
    }

    .sekretaris-ib-cyan {
        background: #cffafe;
        color: #0891b2;
    }

    .sekretaris-ib-cyan:hover {
        background: #0891b2;
        color: white;
        text-decoration: none;
    }

    .sekretaris-icon-btn-group {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    /* Absen List */
    .sekretaris-absen-list {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .sekretaris-absen-item {
        padding: 15px;
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sekretaris-absen-item:last-child {
        margin-bottom: 0;
    }

    .sekretaris-absen-info h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .sekretaris-absen-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sekretaris-page-header {
            padding: 20px;
        }

        .sekretaris-greeting {
            font-size: 22px;
        }

        .sekretaris-subtitle {
            font-size: 14px;
        }

        .sekretaris-stats-row {
            grid-template-columns: 1fr;
        }

        .sekretaris-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .sekretaris-header-actions {
            margin-top: 15px;
        }

        .sekretaris-table {
            font-size: 13px;
        }

        .sekretaris-absen-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .sekretaris-action-grid {
            grid-template-columns: 1fr;
        }

        .sekretaris-stat-value {
            font-size: 22px;
        }
    }
</style>

<div class="sekretaris-dashboard-wrapper">

    {{-- Page Header --}}
    <div class="sekretaris-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="sekretaris-greeting">📋 Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="sekretaris-subtitle mb-0">Dashboard Absensi & Administrasi RT — kelola kegiatan dengan mudah</p>
            </div>
            
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="sekretaris-stats-row">

        {{-- Total Anggota --}}
        <div class="sekretaris-stat-card sekretaris-card-primary">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">👥 Total Anggota</div>
                    <div class="sekretaris-stat-value">{{ $totalAnggota }}</div>
                    <div class="sekretaris-stat-subtitle">anggota terdaftar</div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        {{-- Hadir Terakhir --}}
        <div class="sekretaris-stat-card sekretaris-card-success">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">✅ Hadir Terakhir</div>
                    <div class="sekretaris-stat-value">{{ $recentAttendance }}</div>
                    <div class="sekretaris-stat-subtitle">kegiatan terakhir</div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
        </div>

        {{-- Rata-rata Kehadiran --}}
        <div class="sekretaris-stat-card sekretaris-card-cyan">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">📊 Rata-rata Kehadiran</div>
                    <div class="sekretaris-stat-value">{{ $averageAttendance }}%</div>
                    <div class="sekretaris-progress-wrap">
                        <div class="sekretaris-progress-fill" style="width: {{ $averageAttendance }}%;"></div>
                    </div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        {{-- Kegiatan Terdekat --}}
        <div class="sekretaris-stat-card sekretaris-card-warning">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">📅 Kegiatan Terdekat</div>
                    <div class="sekretaris-stat-value" style="font-size: 17px; line-height: 1.4; margin-top: 4px;">
                        {{ $latestForm ? $latestForm->judul : 'Tidak ada' }}
                    </div>
                    @if($latestForm)
                    <div class="sekretaris-stat-subtitle">
                        {{ \Carbon\Carbon::parse($latestForm->tanggal)->format('d M Y') }}, {{ $latestForm->jam_mulai }}
                    </div>
                    @endif
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="sekretaris-quick-actions">
        <h2 class="sekretaris-section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </h2>
        <div class="sekretaris-action-grid">
            <a href="{{ route('sekretaris.absensi.create') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <span class="sekretaris-action-label">Buat Form Absensi</span>
            </a>
            <a href="{{ route('sekretaris.catatan.index') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-list"></i>
                </div>
                <span class="sekretaris-action-label">Kelola Arisan</span>
            </a>
            <a href="{{ route('sekretaris.spin.index') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <span class="sekretaris-action-label">Spin Arisan</span>
            </a>
            <a href="{{ route('sekretaris.profile.index') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="sekretaris-action-label">Profile</span>
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="row">

        {{-- Kolom Kiri: Kegiatan Terbaru --}}
        <div class="col-lg-8">
            <div class="sekretaris-transaction-table">
                <h2 class="sekretaris-section-title">
                    <i class="fas fa-history"></i>
                    Kegiatan Terbaru
                    <span class="sekretaris-badge sekretaris-badge-blue" style="margin-left: auto; font-size: 13px;">
                        {{ count($recentForms) }} kegiatan
                    </span>
                </h2>
                <div class="table-responsive">
                    <table class="sekretaris-table">
                        <thead>
                            <tr>
                                <th style="width: 36%">Judul</th>
                                <th style="width: 32%">Tanggal & Waktu</th>
                                <th style="width: 18%">Status</th>
                                <th style="width: 14%; text-align: right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentForms as $form)
                            <tr>
                                <td><strong style="color: #1e293b;">{{ $form->judul }}</strong></td>
                                <td>
                                    <div style="font-size: 14px; color: #1e293b;">{{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}</div>
                                    <div style="font-size: 12px; color: #64748b;">{{ $form->jam_mulai }} – {{ $form->jam_selesai }}</div>
                                </td>
                                <td>
                                    <span class="sekretaris-badge sekretaris-badge-success">Selesai</span>
                                </td>
                                <td>
                                    <div class="sekretaris-icon-btn-group">
                                        <a href="{{ route('sekretaris.absensi.qr', $form->id) }}" class="sekretaris-icon-btn sekretaris-ib-blue" title="QR Code">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        <a href="{{ route('sekretaris.absensi.scan', $form->id) }}" class="sekretaris-icon-btn sekretaris-ib-cyan" title="Scan QR">
                                            <i class="fas fa-camera"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada kegiatan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Anggota Absen --}}
        <div class="col-lg-4">
            <div class="sekretaris-absen-list">
                <h2 class="sekretaris-section-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Anggota Absen Terakhir
                </h2>

                @forelse($absenTerakhir as $item)
                <div class="sekretaris-absen-item">
                    <div class="sekretaris-absen-info">
                        <h4>{{ $item->user->name }}</h4>
                        <p>{{ $item->form->judul ?? '-' }}</p>
                        <p style="font-size: 11px; color: #f59e0b;">
                            {{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->format('d M Y') }}
                        </p>
                    </div>
                    <span class="sekretaris-badge sekretaris-badge-warning">Absen</span>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color: #16a34a;"></i>
                    Semua hadir di kegiatan terakhir
                </div>
                @endforelse

                @if(isset($absenTerakhir) && $absenTerakhir->count() > 0)
                <div style="margin-top: 15px; text-align: center;">
                    <a href="{{ route('sekretaris.absensi.index') }}" style="color: #f59e0b; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Lihat Semua Rekap Absensi ({{ $absenTerakhir->count() }}) →
                    </a>
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
            // Add hover effect to table rows
            const tableRows = document.querySelectorAll('.sekretaris-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f1f5f9';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
            });
        });
    })();
</script>

@include('sekretaris.layouts.footer')
@endsection