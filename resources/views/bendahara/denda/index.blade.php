@extends('bendahara.layouts.master')

@section('title', 'Daftar Denda')

@section('content')



<div class="denda-page-wrapper">

    <!-- Search Box -->
    <div class="denda-search-box">
        <div class="denda-search-wrapper">
            <span class="denda-search-icon">🔍</span>
            <input type="text"
                   id="searchInput"
                   class="denda-search-input"
                   placeholder="Cari nama anggota...">
        </div>
    </div>

    <!-- Table Container -->
    <div class="denda-table-container">
        <!-- Desktop Table View -->
        <table class="denda-table-desktop" id="dendaTable">
            <thead>
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th style="min-width: 160px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($denda as $row)
                <tr style="animation-delay: {{ $loop->iteration * 0.05 }}s">
                    <td>{{ $loop->iteration }}</td>
                    <td class="denda-kolom-nama kolom-nama">{{ $row->user->name }}</td>
                    <td style="font-weight: 700; color: #ef4444;">
                        Rp {{ number_format($row->jumlah_denda, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($row->status == 'belum_bayar')
                            <span class="denda-badge denda-badge-belum">Belum Bayar</span>
                        @else
                            <span class="denda-badge denda-badge-lunas">Lunas</span>
                        @endif
                    </td>
                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                    <td>
                        <div class="denda-action-buttons">
                            <a href="{{ route('bendahara.denda.edit', $row->id) }}"
                               class="denda-btn-action denda-btn-edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('bendahara.denda.destroy', $row->id) }}"
                                  method="POST"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus denda ini?')"
                                        class="denda-btn-action denda-btn-delete">
                                     Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="denda-empty-state">
                            <div class="denda-empty-icon">💸</div>
                            <div class="denda-empty-text">
                                Belum ada data denda.<br>
                                <small>Klik "Tambah Denda Manual" untuk membuat denda baru.</small>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Mobile Card View -->
        <div class="denda-mobile-cards" id="dendaMobileCards">
            @forelse($denda as $row)
            <div class="denda-mobile-card"
                 data-nama="{{ strtolower($row->user->name) }}"
                 style="animation-delay: {{ $loop->iteration * 0.1 }}s">
                <div class="denda-card-header">
                    <div class="denda-card-nama">{{ $row->user->name }}</div>
                    <div class="denda-card-no">{{ $loop->iteration }}</div>
                </div>

                <div class="denda-card-info">


                    <div class="denda-info-item">
                        <div class="denda-info-label">Status</div>
                        <div class="denda-info-value">
                            @if($row->status == 'belum_bayar')
                                <span class="denda-badge denda-badge-belum">Belum Bayar</span>
                            @else
                                <span class="denda-badge denda-badge-lunas">Lunas</span>
                            @endif
                        </div>
                    </div>

                    <div class="denda-info-item">
                        <div class="denda-info-label">💰 Jumlah</div>
                        <div class="denda-info-value" style="color: #ef4444;">
                            Rp {{ number_format($row->jumlah_denda, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="denda-info-item">
                        <div class="denda-info-label">📅 Tanggal</div>
                        <div class="denda-info-value">
                            {{ $row->created_at->format('d-m-Y') }}
                        </div>
                    </div>
                </div>

                <div class="denda-card-alasan">
                    <div class="denda-card-alasan-label">📝 Alasan</div>
                    <div class="denda-card-alasan-text">
                        {{ collect(explode('|', $row->alasan))->last() }}
                    </div>
                </div>

                <div class="denda-card-actions">
                    <a href="{{ route('bendahara.denda.edit', $row->id) }}"
                       class="denda-btn-action denda-btn-edit">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('bendahara.denda.destroy', $row->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus denda ini?')"
                                class="denda-btn-action denda-btn-delete"
                                style="width: 100%;">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="denda-empty-state">
                <div class="denda-empty-icon">💸</div>
                <div class="denda-empty-text">
                    Belum ada data denda.<br>
                    <small>Klik "Tambah Denda Manual" untuk membuat denda baru.</small>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
// Real-time search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();

    // Search in desktop table
    const tableRows = document.querySelectorAll('#dendaTable tbody tr');
    tableRows.forEach(row => {
        const namaCell = row.querySelector('.kolom-nama');
        if (namaCell) {
            const nama = namaCell.textContent.toLowerCase();
            if (nama.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Search in mobile cards
    const mobileCards = document.querySelectorAll('.denda-mobile-card');
    mobileCards.forEach(card => {
        const nama = card.getAttribute('data-nama');
        if (nama && nama.includes(searchValue)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

@endsection
