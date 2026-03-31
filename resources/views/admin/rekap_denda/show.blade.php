@extends('admin.layouts.master')

@section('title', 'Detail Denda')

@section('content')

<div class="detail-denda-container">
    <div class="detail-denda-header">
        <h3>💰 Detail Denda: {{ $user->name }}</h3>
        <a href="{{ route('admin.denda.index') }}" class="detail-denda-btn-back">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    <div class="detail-denda-search-container">
        <div class="detail-denda-search-wrapper">
            <span class="detail-denda-search-icon">🔍</span>
            <input
                type="text"
                id="searchInput"
                class="detail-denda-search-input"
                placeholder="Cari alasan denda..."
                onkeyup="searchTable()"
            >
        </div>
    </div>

    <div class="detail-denda-table-container">
        <div class="detail-denda-table-wrapper">
            <table class="detail-denda-table" id="dendaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Alasan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($denda as $dn)
                    <tr>
                        <td data-label="No">{{ $loop->iteration }}</td>

                        <td data-label="Alasan">{{ $dn->alasan }}</td>

                        <td data-label="Jumlah">
                            <span class="detail-denda-jumlah">
                                Rp {{ number_format($dn->jumlah_denda, 0, ',', '.') }}
                            </span>
                        </td>

                        <td data-label="Status">
                            @if($dn->status === 'belum_bayar')
                                <span class="detail-denda-badge detail-denda-badge-belum">
                                    ❌ Belum Bayar
                                </span>
                            @else
                                <span class="detail-denda-badge detail-denda-badge-sudah">
                                    ✅ Sudah Bayar
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="detail-denda-empty-state">
                                <div class="detail-denda-empty-state-icon">💰</div>
                                <div class="detail-denda-empty-state-text">Tidak ada data denda</div>
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
        const td = tr[i].getElementsByTagName('td')[1];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
        }
    }
}
</script>

@endsection
