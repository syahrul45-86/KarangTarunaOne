@extends('admin.layouts.master')

@section('title', 'Rekap Absensi')

@section('content')

<div class="rekap-absensi-container">
    <div class="rekap-absensi-header">
        <h3>📊 Rekap Absensi Kegiatan</h3>
        <div class="rekap-absensi-btn-group">
            <a href="{{ route('admin.rekap.grafik') }}" class="rekap-absensi-btn rekap-absensi-btn-primary">
                <span>📈</span>
                <span>Grafik Kehadiran</span>
            </a>
            <a href="{{ route('admin.rekap.peranggota') }}" class="rekap-absensi-btn rekap-absensi-btn-secondary">
                <span>👤</span>
                <span>Rekap per Anggota</span>
            </a>
        </div>
    </div>

    <div class="rekap-absensi-search-container">
        <div class="rekap-absensi-search-wrapper">
            <span class="rekap-absensi-search-icon">🔍</span>
            <input
                type="text"
                id="searchInput"
                class="rekap-absensi-search-input"
                placeholder="Cari kegiatan..."
                onkeyup="searchTable()"
            >
        </div>
    </div>

    <div class="rekap-absensi-table-container">
        <div class="rekap-absensi-table-wrapper">
            <table class="rekap-absensi-table" id="rekapTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                    <tr>
                        <td data-label="No">{{ $loop->iteration }}</td>
                        <td data-label="Kegiatan">
                            <span class="rekap-absensi-kegiatan-title">{{ $form->judul }}</span>
                        </td>
                        <td data-label="Tanggal">
                            <span class="rekap-absensi-date">
                                📅 {{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Jam">
                            <span class="rekap-absensi-time">
                                🕐 {{ $form->jam_mulai }} - {{ $form->jam_selesai }}
                            </span>
                        </td>
                        <td data-label="Aksi">
                            <a href="{{ route('admin.rekap.show', $form->id) }}" class="rekap-absensi-btn-info">
                                👁️ Lihat Rekap
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="rekap-absensi-empty-state">
                                <div class="rekap-absensi-empty-state-icon">📋</div>
                                <div class="rekap-absensi-empty-state-text">Belum ada data rekap absensi</div>
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
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('rekapTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[1]; // Search di kolom Kegiatan
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}
</script>

@endsection
