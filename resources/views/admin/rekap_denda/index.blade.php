@extends('admin.layouts.master')

@section('title', 'Rekap Denda')

@section('content')


<div class="rekap-denda-container">
    <div class="rekap-denda-header">
        <h3>💰 Rekap Denda Anggota</h3>
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
                        <th>Total Denda</th>
                        <th>Belum Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td data-label="Nama">
                            <span class="rekap-denda-nama">{{ $row['user']->name }}</span>
                        </td>
                        <td data-label="Total Denda">
                            <span class="rekap-denda-amount rekap-denda-total">
                                Rp {{ number_format($row['total'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td data-label="Belum Bayar">
                            <span class="rekap-denda-amount rekap-denda-unpaid">
                                Rp {{ number_format($row['belum_bayar'], 0, ',', '.') }}
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
                                <div class="rekap-denda-empty-state-text">Belum ada data denda</div>
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
