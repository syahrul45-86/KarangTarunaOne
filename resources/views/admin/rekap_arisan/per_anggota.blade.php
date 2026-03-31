@extends('admin.layouts.master')

@section('title', 'Rekap Per Anggota')

@section('content')


<div class="rekap-anggota-container">
    <div class="rekap-anggota-header">
        <h3>👤 Rekap Pembayaran Arisan Tahun {{ $tahun->tahun }}</h3>
        <a href="{{ route('admin.rekap.arisan.index') }}" class="rekap-anggota-btn-back">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    <div class="rekap-anggota-search-container">
        <div class="rekap-anggota-search-wrapper">
            <span class="rekap-anggota-search-icon">🔍</span>
            <input
                type="text"
                id="searchInput"
                class="rekap-anggota-search-input"
                placeholder="Cari nama anggota..."
                onkeyup="searchTable()"
            >
        </div>
    </div>

    <div class="rekap-anggota-table-container">
        <div class="rekap-anggota-table-wrapper">
            <table class="rekap-anggota-table" id="rekapTable">
                <thead>
                    <tr>
                        <th>Nama Anggota</th>
                        <th>Sudah Bayar</th>
                        <th>Belum Bayar</th>
                        <th>Persentase Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                    <tr>
                        <td data-label="Nama">
                            <span class="rekap-anggota-nama">{{ $d['nama'] }}</span>
                        </td>
                        <td data-label="Sudah Bayar">
                            <span class="rekap-anggota-badge rekap-anggota-badge-success">
                                ✅ {{ $d['sudah'] }} kali
                            </span>
                        </td>
                        <td data-label="Belum Bayar">
                            <span class="rekap-anggota-badge rekap-anggota-badge-danger">
                                ❌ {{ $d['belum'] }} kali
                            </span>
                        </td>
                        <td data-label="Persentase">
                            <div class="rekap-anggota-progress-container">
                                <div class="rekap-anggota-progress-bar">
                                    <div class="rekap-anggota-progress-fill {{
                                        $d['persentase'] >= 80 ? 'excellent' :
                                        ($d['persentase'] >= 60 ? 'good' :
                                        ($d['persentase'] >= 40 ? 'average' : 'poor'))
                                    }}" style="width: {{ $d['persentase'] }}%">
                                    </div>
                                </div>
                                <span class="rekap-anggota-progress-text {{
                                    $d['persentase'] >= 80 ? 'excellent' :
                                    ($d['persentase'] >= 60 ? 'good' :
                                    ($d['persentase'] >= 40 ? 'average' : 'poor'))
                                }}">
                                    {{ $d['persentase'] }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="rekap-anggota-empty-state">
                                <div class="rekap-anggota-empty-state-icon">👥</div>
                                <div class="rekap-anggota-empty-state-text">Belum ada data pembayaran</div>
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
        const td = tr[i].getElementsByTagName('td')[0];
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
