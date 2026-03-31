@extends('bendahara.layouts.master')

@section('title','Dashboard')

@section('content')

<style>
    /* Scoped Dashboard Styles for Bendahara */
    .bendahara-dashboard-wrapper {
        padding: 0;
    }

    .bendahara-page-header {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
        color: white;
    }

    .bendahara-greeting {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .bendahara-subtitle {
        font-size: 16px;
        opacity: 0.95;
        font-weight: 400;
    }

    .bendahara-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .bendahara-btn-report {
        background: white;
        color: #16a34a;
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

    .bendahara-btn-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #16a34a;
        text-decoration: none;
    }

    /* Stats Cards */
    .bendahara-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .bendahara-stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .bendahara-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
    }

    .bendahara-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .bendahara-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .bendahara-stat-icon {
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

    .bendahara-stat-content {
        flex: 1;
    }

    .bendahara-stat-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .bendahara-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .bendahara-stat-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .bendahara-stat-trend {
        margin-top: 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .bendahara-trend-up {
        color: #16a34a;
    }

    .bendahara-trend-down {
        color: #dc2626;
    }

    /* Color Variants */
    .bendahara-card-primary {
        --card-color: #3b82f6;
        --card-bg: #dbeafe;
    }

    .bendahara-card-success {
        --card-color: #16a34a;
        --card-bg: #dcfce7;
    }

    .bendahara-card-warning {
        --card-color: #f59e0b;
        --card-bg: #fef3c7;
    }

    .bendahara-card-danger {
        --card-color: #dc2626;
        --card-bg: #fee2e2;
    }

    .bendahara-card-info {
        --card-color: #0891b2;
        --card-bg: #cffafe;
    }

    .bendahara-card-purple {
        --card-color: #9333ea;
        --card-bg: #f3e8ff;
    }

    /* Financial Summary Section */
    .bendahara-financial-summary {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .bendahara-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bendahara-finance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .bendahara-finance-item {
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 4px solid var(--item-color);
    }

    .bendahara-finance-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .bendahara-finance-value {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
    }

    /* Transaction Table */
    .bendahara-transaction-table {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .bendahara-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .bendahara-table thead th {
        background: #f8fafc;
        padding: 12px 15px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .bendahara-table thead th:first-child {
        border-radius: 8px 0 0 8px;
    }

    .bendahara-table thead th:last-child {
        border-radius: 0 8px 8px 0;
    }

    .bendahara-table tbody tr {
        background: white;
        transition: all 0.2s ease;
    }

    .bendahara-table tbody tr:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .bendahara-table tbody td {
        padding: 15px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .bendahara-table tbody td:first-child {
        border-left: 1px solid #e2e8f0;
        border-radius: 8px 0 0 8px;
    }

    .bendahara-table tbody td:last-child {
        border-right: 1px solid #e2e8f0;
        border-radius: 0 8px 8px 0;
    }

    .bendahara-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .bendahara-badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .bendahara-badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .bendahara-badge-warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    /* Denda Section */
    .bendahara-denda-list {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .bendahara-denda-item {
        padding: 15px;
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bendahara-denda-item:last-child {
        margin-bottom: 0;
    }

    .bendahara-denda-info h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .bendahara-denda-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .bendahara-denda-amount {
        font-size: 18px;
        font-weight: 800;
        color: #dc2626;
    }

    /* Quick Actions */
    .bendahara-quick-actions {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .bendahara-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .bendahara-action-btn {
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

    .bendahara-action-btn:hover {
        border-color: #16a34a;
        background: #f0fdf4;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        color: #16a34a;
        text-decoration: none;
    }

    .bendahara-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .bendahara-action-label {
        font-weight: 600;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bendahara-page-header {
            padding: 20px;
        }

        .bendahara-greeting {
            font-size: 22px;
        }

        .bendahara-subtitle {
            font-size: 14px;
        }

        .bendahara-stats-row {
            grid-template-columns: 1fr;
        }

        .bendahara-finance-grid {
            grid-template-columns: 1fr;
        }

        .bendahara-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .bendahara-header-actions {
            margin-top: 15px;
        }

        .bendahara-table {
            font-size: 13px;
        }

        .bendahara-denda-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .bendahara-action-grid {
            grid-template-columns: 1fr;
        }

        .bendahara-stat-value {
            font-size: 22px;
        }
    }
</style>

<div class="bendahara-dashboard-wrapper">
    <!-- Page Header -->
    <div class="bendahara-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="bendahara-greeting">💰 Selamat Datang, {{ $bendahara->name }}!</h1>
                <p class="bendahara-subtitle mb-0">Dashboard Keuangan RT - Kelola kas dan transaksi dengan mudah</p>
            </div>
            <div class="bendahara-header-actions">
                <a href="{{ route('bendahara.profile.index') }}" class="bendahara-btn-report">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Stats Cards -->
    <div class="bendahara-stats-row">
        <!-- Total Kas Card -->
        <div class="bendahara-stat-card bendahara-card-success">
            <div class="bendahara-stat-header">
                <div class="bendahara-stat-content">
                    <div class="bendahara-stat-label">💎 Total Kas RT</div>
                    <div class="bendahara-stat-value">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
                    <div class="bendahara-stat-trend {{ $perubahanKas >= 0 ? 'bendahara-trend-up' : 'bendahara-trend-down' }}">
                        <i class="fas fa-arrow-{{ $perubahanKas >= 0 ? 'up' : 'down' }}"></i>
                        <span>{{ number_format(abs($perubahanKas), 1) }}% dari bulan lalu</span>
                    </div>
                </div>
                <div class="bendahara-stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

            <!-- Pemasukan Tahun Ini -->
    <div class="bendahara-stat-card bendahara-card-primary">
        <div class="bendahara-stat-header">
            <div class="bendahara-stat-content">
                <div class="bendahara-stat-label">📈 Pemasukan Tahun Ini</div>
                <div class="bendahara-stat-value">Rp {{ number_format($pemasukanTahunIni, 0, ',', '.') }}</div>
                <div class="bendahara-stat-subtitle">{{ \Carbon\Carbon::now()->format('Y') }}</div>
            </div>
            <div class="bendahara-stat-icon">
                <i class="fas fa-arrow-circle-up"></i>
            </div>
        </div>
    </div>

    <!-- Pengeluaran Tahun Ini -->
    <div class="bendahara-stat-card bendahara-card-danger">
        <div class="bendahara-stat-header">
            <div class="bendahara-stat-content">
                <div class="bendahara-stat-label">📉 Pengeluaran Tahun Ini</div>
                <div class="bendahara-stat-value">Rp {{ number_format($pengeluaranTahunIni, 0, ',', '.') }}</div>
                <div class="bendahara-stat-subtitle">{{ \Carbon\Carbon::now()->format('Y') }}</div>
            </div>
            <div class="bendahara-stat-icon">
                <i class="fas fa-arrow-circle-down"></i>
            </div>
        </div>
    </div>

        <!-- Total Denda -->
        <div class="bendahara-stat-card bendahara-card-warning">
            <div class="bendahara-stat-header">
                <div class="bendahara-stat-content">
                    <div class="bendahara-stat-label">⚠️ Total Denda Tertunggak</div>
                    <div class="bendahara-stat-value">Rp {{ number_format($totalDendaTertunggak, 0, ',', '.') }}</div>
                    <div class="bendahara-stat-subtitle">{{ $jumlahAnggotaBelumBayar }} anggota belum bayar</div>
                </div>
                <div class="bendahara-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bendahara-quick-actions">
        <h2 class="bendahara-section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </h2>
        <div class="bendahara-action-grid">
            <a href="{{ route('bendahara.create') }}" class="bendahara-action-btn">
                <div class="bendahara-action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <span class="bendahara-action-label">Tambah Transaksi</span>
            </a>
            <a href="{{ route('bendahara.denda.index') }}" class="bendahara-action-btn">
                <div class="bendahara-action-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <span class="bendahara-action-label">Kelola Denda</span>
            </a>
            <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="bendahara-action-btn">
                <div class="bendahara-action-icon">
                    <i class="fas fa-book"></i>
                </div>
                <span class="bendahara-action-label">Lihat Buku Kas</span>
            </a>
            <a href="{{ route('bendahara.profile.index') }}" class="bendahara-action-btn">
                <div class="bendahara-action-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="bendahara-action-label">Profile</span>
            </a>
        </div>
    </div>

    <!-- Financial Summary & Denda -->
    <div class="row">
        <!-- Ringkasan Keuangan -->
        <div class="col-lg-8">


            <!-- Transaksi Terakhir -->
            <div class="bendahara-transaction-table">
                <h2 class="bendahara-section-title">
                    <i class="fas fa-history"></i>
                    Transaksi Terbaru
                </h2>
                <div class="table-responsive">
                    <table class="bendahara-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Tipe</th>
                                <th>Nominal</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $trans)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($trans->tanggal)->format('d M Y') }}</td>
                                <td>{{ $trans->keterangan }}</td>
                                <td>
                                    @if($trans->pemasukan > 0)
                                        <span class="bendahara-badge bendahara-badge-success">Pemasukan</span>
                                    @elseif($trans->pengeluaran > 0)
                                        <span class="bendahara-badge bendahara-badge-danger">Pengeluaran</span>
                                    @else
                                        <span class="bendahara-badge bendahara-badge-warning">Saldo Awal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trans->pemasukan > 0)
                                        <strong style="color: #16a34a;">+ Rp {{ number_format($trans->pemasukan, 0, ',', '.') }}</strong>
                                    @elseif($trans->pengeluaran > 0)
                                        <strong style="color: #dc2626;">- Rp {{ number_format($trans->pengeluaran, 0, ',', '.') }}</strong>
                                    @else
                                        <strong style="color: #6c757d;">Rp 0</strong>
                                    @endif
                                </td>
                                <td><strong>Rp {{ number_format($trans->saldo_akhir, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Denda Tertunggak -->
        <div class="col-lg-4">
            <div class="bendahara-denda-list">
                <h2 class="bendahara-section-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Denda Tertunggak
                </h2>

                @forelse($dendaTertunggak as $denda)
                <div class="bendahara-denda-item">
                    <div class="bendahara-denda-info">
                        <h4>{{ $denda->user->name }}</h4>
                        <p>{{ $denda->alasan ?? ucfirst($denda->jenis) }}</p>
                        <p style="font-size: 11px; color: #f59e0b;">
                            Dibuat: {{ \Carbon\Carbon::parse($denda->created_at)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="bendahara-denda-amount">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 d-block"></i>
                    Tidak ada denda tertunggak
                </div>
                @endforelse

                @if($dendaTertunggak->count() > 0)
                <div style="margin-top: 15px; text-align: center;">
                    <a href="{{ route('bendahara.denda.index') }}" style="color: #f59e0b; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Lihat Semua Denda ({{ $jumlahAnggotaBelumBayar }}) →
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

    // Format rupiah animation
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Animate number counting
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = formatRupiah(current);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Initialize animations on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Animate all stat values
        const statValues = document.querySelectorAll('.bendahara-stat-value');
        statValues.forEach(stat => {
            const text = stat.textContent;
            if (text.includes('Rp')) {
                const value = parseInt(text.replace(/\D/g, ''));
                stat.textContent = 'Rp 0';
                setTimeout(() => {
                    animateValue(stat, 0, value, 1500);
                }, 200);
            }
        });

        // Add hover effect to table rows
        const tableRows = document.querySelectorAll('.bendahara-table tbody tr');
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

@include('bendahara.layouts.footer')
@endsection
