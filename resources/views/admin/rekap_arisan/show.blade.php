@extends('admin.layouts.master')

@section('title', 'Detail Rekap Arisan')

@section('content')


<div class="rekap-container">
    <div class="rekap-header">
        <h3>📊 Rekap Arisan Tahun {{ $tahun->tahun }}</h3>
        <a href="{{ route('admin.rekap.arisan.index') }}" class="rekap-btn-back">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="rekap-filter-section">
        <div class="rekap-filter-group">
            <div class="rekap-filter-item">
                <label class="rekap-filter-label">🔍 Cari Anggota</label>
                <input
                    type="text"
                    id="searchInput"
                    class="rekap-search-input"
                    placeholder="Ketik nama anggota..."
                >
            </div>

            <div class="rekap-filter-item">
                <label class="rekap-filter-label">📅 Filter Bulan</label>
                <select id="monthFilter" class="rekap-select">
                    <option value="all">Semua Bulan</option>
                    @php
                        $groupedByMonth = [];
                        foreach($tahun->tanggal as $tgl) {
                            $month = \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m');
                            $monthName = \Carbon\Carbon::parse($tgl->tanggal)->format('F Y');
                            if (!isset($groupedByMonth[$month])) {
                                $groupedByMonth[$month] = $monthName;
                            }
                        }
                    @endphp
                    @foreach($groupedByMonth as $monthKey => $monthName)
                        <option value="{{ $monthKey }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="rekap-filter-item">
                <button id="resetBtn" class="rekap-btn-reset">
                    🔄 Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="rekap-table-container">
        <div class="rekap-table-wrapper">
            <table class="rekap-table">
                <thead>
                    <tr>
                        <th>Nama Anggota</th>
                        @foreach($tahun->tanggal as $tgl)
                            <th class="date-column" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}">
                                {{ \Carbon\Carbon::parse($tgl->tanggal)->format('d M') }}
                            </th>
                        @endforeach
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($anggota as $user)
                    <tr class="member-row" data-name="{{ strtolower($user->name) }}">
                        <td>{{ $user->name }}</td>

                        @foreach($tahun->tanggal as $tgl)
                            <td class="date-column" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}">
                                @if(isset($checklist[$user->id][$tgl->id]))
                                    <span class="rekap-check rekap-check-yes">✅</span>
                                @else
                                    <span class="rekap-check rekap-check-no">❌</span>
                                @endif
                            </td>
                        @endforeach

                        <td class="total-bayar">
                            {{ $totalBayar[$user->id] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($tahun->tanggal) + 2 }}">
                            <div class="rekap-empty-state">
                                <div class="rekap-empty-state-icon">👥</div>
                                <div class="rekap-empty-state-text">Belum ada data anggota</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="scroll-hint">
            ← Geser ke kanan untuk melihat lebih banyak →
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="rekap-card-view" id="cardView">
        @forelse($anggota as $user)
        <div class="rekap-card member-card" data-name="{{ strtolower($user->name) }}">
            <div class="rekap-card-header">
                <span>{{ $user->name }}</span>
                <span class="rekap-card-total card-total">{{ $totalBayar[$user->id] }} Bayar</span>
            </div>

            <div class="rekap-card-content">
                @foreach($tahun->tanggal as $tgl)
                <div class="rekap-card-item card-date-item" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}" data-user-id="{{ $user->id }}" data-date-id="{{ $tgl->id }}">
                    <div class="rekap-card-date">
                        {{ \Carbon\Carbon::parse($tgl->tanggal)->format('d M') }}
                    </div>
                    <div class="rekap-card-status">
                        @if(isset($checklist[$user->id][$tgl->id]))
                            <span class="rekap-check-yes">✅</span>
                        @else
                            <span class="rekap-check-no">❌</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="rekap-empty-state">
            <div class="rekap-empty-state-icon">👥</div>
            <div class="rekap-empty-state-text">Belum ada data anggota</div>
        </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const monthFilter = document.getElementById('monthFilter');
    const resetBtn = document.getElementById('resetBtn');
    const memberRows = document.querySelectorAll('.member-row');
    const memberCards = document.querySelectorAll('.member-card');
    const dateColumns = document.querySelectorAll('.date-column');
    const cardDateItems = document.querySelectorAll('.card-date-item');

    // Store original total bayar values
    const originalTotals = {};
    @foreach($anggota as $user)
        originalTotals[{{ $user->id }}] = {{ $totalBayar[$user->id] }};
    @endforeach

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        memberRows.forEach(row => {
            const name = row.dataset.name;
            if (name.includes(searchTerm)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });

        memberCards.forEach(card => {
            const name = card.dataset.name;
            if (name.includes(searchTerm)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });

    // Month filter functionality
    monthFilter.addEventListener('change', function() {
        const selectedMonth = this.value;

        if (selectedMonth === 'all') {
            // Show all columns
            dateColumns.forEach(col => col.classList.remove('hidden'));
            cardDateItems.forEach(item => item.classList.remove('hidden'));

            // Reset total bayar to original
            document.querySelectorAll('.total-bayar').forEach((td, index) => {
                const userId = Array.from(memberRows)[index]?.querySelector('td')?.textContent || '';
                const userIdNum = @json(array_values($anggota->pluck('id')->toArray()));
                td.textContent = originalTotals[userIdNum[index]] || td.textContent;
            });

            document.querySelectorAll('.card-total').forEach((span, index) => {
                const userIdNum = @json(array_values($anggota->pluck('id')->toArray()));
                span.textContent = (originalTotals[userIdNum[index]] || 0) + ' Bayar';
            });
        } else {
            // Hide columns not matching selected month
            dateColumns.forEach(col => {
                if (col.dataset.month === selectedMonth) {
                    col.classList.remove('hidden');
                } else {
                    col.classList.add('hidden');
                }
            });

            cardDateItems.forEach(item => {
                if (item.dataset.month === selectedMonth) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });

            // Recalculate total bayar for selected month
            const checklist = @json($checklist);
            memberRows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll('.date-column');
                let count = 0;
                cells.forEach(cell => {
                    if (cell.dataset.month === selectedMonth && !cell.classList.contains('hidden')) {
                        if (cell.querySelector('.rekap-check-yes')) {
                            count++;
                        }
                    }
                });
                const totalCell = row.querySelector('.total-bayar');
                if (totalCell) totalCell.textContent = count;
            });

            memberCards.forEach((card, cardIndex) => {
                const items = card.querySelectorAll('.card-date-item');
                let count = 0;
                items.forEach(item => {
                    if (item.dataset.month === selectedMonth && !item.classList.contains('hidden')) {
                        if (item.querySelector('.rekap-check-yes')) {
                            count++;
                        }
                    }
                });
                const totalSpan = card.querySelector('.card-total');
                if (totalSpan) totalSpan.textContent = count + ' Bayar';
            });
        }
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        monthFilter.value = 'all';

        memberRows.forEach(row => row.classList.remove('hidden'));
        memberCards.forEach(card => card.classList.remove('hidden'));
        dateColumns.forEach(col => col.classList.remove('hidden'));
        cardDateItems.forEach(item => item.classList.remove('hidden'));

        // Reset totals
        document.querySelectorAll('.total-bayar').forEach((td, index) => {
            const userIdNum = @json(array_values($anggota->pluck('id')->toArray()));
            td.textContent = originalTotals[userIdNum[index]] || td.textContent;
        });

        document.querySelectorAll('.card-total').forEach((span, index) => {
            const userIdNum = @json(array_values($anggota->pluck('id')->toArray()));
            span.textContent = (originalTotals[userIdNum[index]] || 0) + ' Bayar';
        });
    });
});
</script>

@endsection
