@extends('admin.layouts.master')

@section('title', 'Rekap Arisan')

@section('content')


<div class="rekap-arisan-container">
    <div class="rekap-arisan-header">
        <h3>📑 Rekap Arisan</h3>
    </div>

    <div class="rekap-arisan-table-container">
        <div class="rekap-arisan-table-wrapper">
            <table class="rekap-arisan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Jumlah Bulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahuns as $t)
                    <tr>
                        <td data-label="No">{{ $loop->iteration }}</td>
                        <td data-label="Tahun">
                            <span class="rekap-arisan-tahun">{{ $t->tahun }}</span>
                        </td>
                        <td data-label="Jumlah Bulan">
                            <span class="rekap-arisan-count">
                                📅 {{ $t->tanggal->count() }} Bulan
                            </span>
                        </td>
                        <td data-label="Aksi">
                            <div class="rekap-arisan-btn-group">
                                <a href="{{ route('admin.rekap.arisan.show', $t->id) }}" class="rekap-arisan-btn rekap-arisan-btn-detail">
                                    👁️ Detail
                                </a>
                                {{-- <a href="{{ route('admin.rekap.arisan.pdf', $t->id) }}" class="rekap-arisan-btn rekap-arisan-btn-pdf">
                                    📄 PDF
                                </a> --}}
                                <a href="{{ route('admin.rekap.arisan.grafik', $t->id) }}" class="rekap-arisan-btn rekap-arisan-btn-grafik">
                                    📈 Grafik
                                </a>
                                <a href="{{ route('admin.rekap.arisan.perAnggota', $t->id) }}" class="rekap-arisan-btn rekap-arisan-btn-anggota">
                                    👤 Per Anggota
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="rekap-arisan-empty-state">
                                <div class="rekap-arisan-empty-state-icon">📑</div>
                                <div class="rekap-arisan-empty-state-text">Belum ada data rekap arisan</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
