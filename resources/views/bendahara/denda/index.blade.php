@extends('bendahara.layouts.master')

@section('title', 'Rekap Tunggakan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/admin/rekap-denda/index.css') }}">
@endpush

@section('content')

<div class="rekap-denda-container">
    <div class="rekap-denda-header">
        <h3>💰 Rekap Tunggakan (Denda & Arisan)</h3>
        <a href="{{ route('bendahara.denda.export_pdf', request()->all()) }}" class="rekap-denda-btn rekap-denda-btn-primary">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
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
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalSemuaDenda = 0;
                        $totalSemuaArisan = 0;
                        $totalKeseluruhan = 0;
                    @endphp
                    @forelse($data as $row)
                    @php
                        $totalSemuaDenda += $row['belum_bayar'];
                        $totalSemuaArisan += $row['tunggakan_arisan'];
                        $totalKeseluruhan += $row['total_semua'];
                    @endphp
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
                <tfoot>
                    <tr style="background-color: #f8fafc; font-weight: bold;">
                        <td data-label="Keterangan" style="text-align: right; padding: 15px; border-top: 2px solid #e2e8f0; font-size: 16px;">TOTAL KESELURUHAN</td>
                        <td data-label="Total Tunggakan Denda" style="padding: 15px; border-top: 2px solid #e2e8f0; color: #dc2626; font-size: 16px;">Rp {{ number_format($totalSemuaDenda, 0, ',', '.') }}</td>
                        <td data-label="Total Tunggakan Arisan" style="padding: 15px; border-top: 2px solid #e2e8f0; color: #f59e0b; font-size: 16px;">Rp {{ number_format($totalSemuaArisan, 0, ',', '.') }}</td>
                        <td data-label="Total Keseluruhan" style="padding: 15px; border-top: 2px solid #e2e8f0; color: #dc2626; font-size: 1.1em; font-size: 16px;">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
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
