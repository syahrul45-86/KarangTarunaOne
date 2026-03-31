@extends('admin.layouts.master')

@section('content')


<div class="grafik-container">
    <div class="grafik-header">
        <h3>📊 Grafik Kehadiran</h3>
        <a href="{{ route('admin.rekap.index') }}" class="grafik-btn-back">
            <span>◀️</span>
            <span>Kembali</span>
        </a>
    </div>

    <div class="grafik-card">
        <div class="grafik-card-header">
            <h4 class="grafik-card-title">
                <span>📈</span>
                <span>Grafik Absensi Per Kegiatan</span>
            </h4>
        </div>

        <div class="grafik-chart-container">
            <canvas id="chartAbsensi"></canvas>
        </div>

        <div class="grafik-legend">
            <div class="grafik-legend-item">
                <div class="grafik-legend-color" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                <span class="grafik-legend-label">Jumlah Hadir</span>
            </div>
        </div>
    </div>

    <div class="grafik-stats-grid">
        <div class="grafik-stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="grafik-stat-icon">📋</div>
            <div class="grafik-stat-label">Total Kegiatan</div>
            <div class="grafik-stat-value">{{ count($forms) }}</div>
        </div>

        <div class="grafik-stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="grafik-stat-icon">✅</div>
            <div class="grafik-stat-label">Total Kehadiran</div>
            <div class="grafik-stat-value">{{ $forms->sum('absensi_count') }}</div>
        </div>

        <div class="grafik-stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="grafik-stat-icon">📊</div>
            <div class="grafik-stat-label">Rata-rata Hadir</div>
            <div class="grafik-stat-value">{{ count($forms) > 0 ? round($forms->sum('absensi_count') / count($forms), 1) : 0 }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartAbsensi').getContext('2d');

    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
    gradient.addColorStop(1, 'rgba(118, 75, 162, 0.8)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($forms->pluck('judul')) !!},
            datasets: [{
                label: 'Jumlah Hadir',
                data: {!! json_encode($forms->pluck('absensi_count')) !!},
                backgroundColor: gradient,
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(45, 55, 72, 0.95)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Hadir: ' + context.parsed.y + ' orang';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        color: '#4a5568'
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11,
                            weight: '600'
                        },
                        color: '#4a5568',
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
</script>

@endsection
