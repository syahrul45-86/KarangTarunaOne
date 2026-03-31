@extends('admin.layouts.master')

@section('content')


<div class="peranggota-container">
    <div class="peranggota-header">
        <h3>👥 Rekap Kehadiran Per Anggota</h3>
        <a href="{{ route('admin.rekap.index') }}" class="peranggota-btn-back">
            <span>◀️</span>
            <span>Kembali</span>
        </a>
    </div>

    <div class="peranggota-search-container">
        <span class="peranggota-search-icon">🔍</span>
        <input type="text" id="peranggotaSearchInput" class="peranggota-search-input" placeholder="Cari nama anggota...">
    </div>

    <div class="peranggota-table-container">
        <div class="peranggota-table-wrapper">
            <table class="peranggota-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hadir</th>
                        <th>Tidak Hadir</th>
                        <th>Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody id="peranggotaTableBody">
                    @forelse($data as $d)
                    <tr>
                        <td data-label="Nama">
                            <div class="peranggota-nama">
                                <span>👤</span>
                                <span>{{ $d['nama'] }}</span>
                            </div>
                        </td>
                        <td data-label="Hadir">
                            <span class="peranggota-badge peranggota-badge-hadir">
                                ✅ {{ $d['hadir'] }}
                            </span>
                        </td>
                        <td data-label="Tidak Hadir">
                            <span class="peranggota-badge peranggota-badge-tidak-hadir">
                                ❌ {{ $d['tidak_hadir'] }}
                            </span>
                        </td>
                        <td data-label="Persentase Kehadiran">
                            <div class="peranggota-progress-container">
                                <div class="peranggota-progress-bar">
                                    <div class="peranggota-progress-fill" style="width: {{ $d['persentase'] }}%"></div>
                                </div>
                                <div class="peranggota-progress-text">{{ $d['persentase'] }}%</div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="peranggota-empty">
                                <div class="peranggota-empty-icon">📊</div>
                                <div class="peranggota-empty-text">Belum ada data kehadiran</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('peranggotaSearchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#peranggotaTableBody tr');

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Animate progress bars on load
    window.addEventListener('load', function() {
        const progressBars = document.querySelectorAll('.peranggota-progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
</script>

@endsection
