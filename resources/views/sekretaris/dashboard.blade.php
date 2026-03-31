@extends('sekretaris.layouts.master')

@section('title','Dashboard')

@section('content')

<style>
    /* Scoped Dashboard Styles */
    .sekretaris-dashboard-wrapper {
        padding: 0;
    }

    .sekretaris-page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .sekretaris-greeting {
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
        color: #667eea;
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
        color: #667eea;
        text-decoration: none;
    }

    /* Stats Cards */
    .sekretaris-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: var(--card-bg);
        color: var(--card-color);
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
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
    }

    .sekretaris-stat-trend {
        margin-top: 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sekretaris-trend-up {
        color: #16a34a;
    }

    .sekretaris-trend-down {
        color: #dc2626;
    }

    /* Color Variants */
    .sekretaris-card-primary {
        --card-color: #3b82f6;
        --card-bg: #dbeafe;
    }

    .sekretaris-card-success {
        --card-color: #16a34a;
        --card-bg: #dcfce7;
    }

    .sekretaris-card-info {
        --card-color: #0891b2;
        --card-bg: #cffafe;
    }

    .sekretaris-card-warning {
        --card-color: #f59e0b;
        --card-bg: #fef3c7;
    }

    /* Progress Bar */
    .sekretaris-progress-wrapper {
        margin-top: 12px;
    }

    .sekretaris-progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        color: #64748b;
    }

    .sekretaris-progress-bar-container {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .sekretaris-progress-bar {
        height: 100%;
        background: var(--card-color);
        border-radius: 10px;
        transition: width 1s ease;
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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        border-color: #667eea;
        background: #f8fafc;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        color: #667eea;
        text-decoration: none;
    }

    .sekretaris-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
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

    /* Recent Activity */
    .sekretaris-recent-activity {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .sekretaris-activity-item {
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .sekretaris-activity-item:last-child {
        border-bottom: none;
    }

    .sekretaris-activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .sekretaris-activity-content {
        flex: 1;
    }

    .sekretaris-activity-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .sekretaris-activity-time {
        font-size: 12px;
        color: #94a3b8;
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
    }

    @media (max-width: 480px) {
        .sekretaris-action-grid {
            grid-template-columns: 1fr;
        }

        .sekretaris-stat-value {
            font-size: 24px;
        }
    }
</style>

<div class="sekretaris-dashboard-wrapper">
    <!-- Page Header -->
    <div class="sekretaris-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="sekretaris-greeting">👋 Selamat Datang, {{ $sekretaris->name }}!</h1>
                <p class="sekretaris-subtitle mb-0">Berikut adalah ringkasan aktivitas RT Anda hari ini</p>
            </div>
            <div class="sekretaris-header-actions">
                <a href="#" class="sekretaris-btn-report">
                    <i class="fas fa-download"></i>
                    <span>Generate Report</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="sekretaris-stats-row">
        <!-- Saldo Card -->
        <div class="sekretaris-stat-card sekretaris-card-primary">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">Saldo Kas RT</div>
                    <div class="sekretaris-stat-value">Rp 40.000.000</div>
                    <div class="sekretaris-stat-trend sekretaris-trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>12% dari bulan lalu</span>
                    </div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Pemasukan Card -->
        <div class="sekretaris-stat-card sekretaris-card-success">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">Pemasukan Bulan Ini</div>
                    <div class="sekretaris-stat-value">Rp 15.500.000</div>
                    <div class="sekretaris-stat-trend sekretaris-trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>8% dari target</span>
                    </div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>

        <!-- Tasks Progress Card -->
        <div class="sekretaris-stat-card sekretaris-card-info">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">Progress Tugas</div>
                    <div class="sekretaris-stat-value">75%</div>
                    <div class="sekretaris-progress-wrapper">
                        <div class="sekretaris-progress-header">
                            <span>15 dari 20 tugas</span>
                            <span>75%</span>
                        </div>
                        <div class="sekretaris-progress-bar-container">
                            <div class="sekretaris-progress-bar" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="sekretaris-stat-card sekretaris-card-warning">
            <div class="sekretaris-stat-header">
                <div class="sekretaris-stat-content">
                    <div class="sekretaris-stat-label">Permintaan Pending</div>
                    <div class="sekretaris-stat-value">18</div>
                    <div class="sekretaris-stat-trend sekretaris-trend-down">
                        <i class="fas fa-arrow-down"></i>
                        <span>5 dari kemarin</span>
                    </div>
                </div>
                <div class="sekretaris-stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="sekretaris-quick-actions">
        <h2 class="sekretaris-section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </h2>
        <div class="sekretaris-action-grid">
            <a href="{{ route('sekretaris.catatan.index') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <span class="sekretaris-action-label">Input Arisan</span>
            </a>
            <a href="{{ route('sekretaris.absensi.index') }}" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="sekretaris-action-label">Absensi</span>
            </a>
            <a href="#" class="sekretaris-action-btn">
                <div class="sekretaris-action-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <span class="sekretaris-action-label">Pengumuman</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="sekretaris-recent-activity">
                <h2 class="sekretaris-section-title">
                    <i class="fas fa-history"></i>
                    Aktivitas Terbaru
                </h2>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #dbeafe; color: #3b82f6;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Anggota baru terdaftar</div>
                        <div class="sekretaris-activity-time">2 jam yang lalu</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Iuran bulan ini diterima dari 15 anggota</div>
                        <div class="sekretaris-activity-time">5 jam yang lalu</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #fef3c7; color: #f59e0b;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Laporan kegiatan ronda disetujui</div>
                        <div class="sekretaris-activity-time">1 hari yang lalu</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #e0e7ff; color: #6366f1;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Pengumuman rapat RT dipublikasikan</div>
                        <div class="sekretaris-activity-time">2 hari yang lalu</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #fecaca; color: #dc2626;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">3 anggota belum membayar iuran</div>
                        <div class="sekretaris-activity-time">3 hari yang lalu</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sekretaris-recent-activity">
                <h2 class="sekretaris-section-title">
                    <i class="fas fa-bell"></i>
                    Pengingat
                </h2>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #fef3c7; color: #f59e0b;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Rapat RT minggu depan</div>
                        <div class="sekretaris-activity-time">15 Februari 2026</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #fecaca; color: #dc2626;">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Batas pembayaran iuran</div>
                        <div class="sekretaris-activity-time">28 Februari 2026</div>
                    </div>
                </div>

                <div class="sekretaris-activity-item">
                    <div class="sekretaris-activity-icon" style="background: #dbeafe; color: #3b82f6;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="sekretaris-activity-content">
                        <div class="sekretaris-activity-title">Laporan bulanan deadline</div>
                        <div class="sekretaris-activity-time">5 Maret 2026</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    // Animate progress bars on load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.sekretaris-progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });

    // Animate stat values counting up
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current.toLocaleString('id-ID');
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // You can add more interactive features here
})();
</script>

@include('sekretaris.layouts.footer')
@endsection
