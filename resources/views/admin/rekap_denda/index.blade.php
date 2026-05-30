@extends('admin.layouts.master')

@section('title', 'Rekap Denda')

@section('content')


<div class="rekap-denda-container">
    <div class="rekap-denda-header">
        <h3>💰 Rekap Tunggakan (Denda & Arisan)</h3>
        <div class="rekap-denda-btn-group">

            <a href="{{ route('admin.denda.per_anggota') }}" class="rekap-denda-btn rekap-denda-btn-secondary">
                <span>👤</span>
                <span>Rekap Per Anggota</span>
            </a>
        </div>
    </div>

    <div class="rekap-denda-search-container">
        <div class="rekap-denda-search-wrapper">
            <span class="rekap-denda-search-icon">🔍</span>
            <input
                type="text"
                id="searchInput"
                class="rekap-denda-search-input"
                placeholder="Cari nama anggota..."
                onkeyup="searchTable()"
            >
        </div>
    </div>

    <div class="rekap-denda-table-container">
        <div class="rekap-denda-table-wrapper">
            <table class="rekap-denda-table" id="dendaTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tunggakan Denda</th>
                        <th>Tunggakan Arisan</th>
                        <th>Total Keseluruhan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td data-label="Nama">
                            <span class="rekap-denda-nama">{{ $row['user']->name }}</span>
                        </td>
                        <td data-label="Tunggakan Denda">
                            <span class="rekap-denda-amount rekap-denda-unpaid">
                                Rp {{ number_format($row['belum_bayar'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td data-label="Tunggakan Arisan">
                            <span class="rekap-denda-amount" style="color: #f59e0b; font-weight: bold;">
                                Rp {{ number_format($row['tunggakan_arisan'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td data-label="Total Keseluruhan">
                            <span class="rekap-denda-amount" style="color: #dc2626; font-weight: bold; font-size: 1.1em;">
                                Rp {{ number_format($row['total_semua'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td data-label="Aksi">
                            <a href="{{ route('admin.denda.show', $row['user']->id) }}" class="rekap-denda-btn-detail">
                                👁️ Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="rekap-denda-empty-state">
                                <div class="rekap-denda-empty-state-icon">💰</div>
                                <div class="rekap-denda-empty-state-text">Belum ada data tunggakan/denda</div>
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
    const table = document.getElementById('dendaTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[0]; // Search di kolom Nama
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
