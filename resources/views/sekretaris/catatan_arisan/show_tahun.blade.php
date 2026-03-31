@extends('sekretaris.layouts.master')

@section('title', 'Catatan Arisan Tahun ' . $tahun->tahun)

@section('content')

<style>
    .rekap-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 30px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-header h3 {
        color: white;
        font-size: clamp(18px, 4vw, 26px);
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 0;
    }

    .btn-back {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Alerts */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: linear-gradient(135deg, #c3f0c8 0%, #a8e6cf 100%);
        color: #2d5016;
        border-left: 4px solid #48bb78;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ffc9c9 0%, #ffabab 100%);
        color: #7f1d1d;
        border-left: 4px solid #f56565;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        align-items: end;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-input,
    .month-select {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
        color: #2d3748;
    }

    .search-input:focus,
    .month-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .month-select {
        cursor: pointer;
    }

    .btn-reset {
        padding: 12px 24px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        color: #4a5568;
    }

    .btn-reset:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
    }

    /* Data Card */
    .data-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table-arisan {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .table-arisan thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .table-arisan thead th {
        color: white;
        padding: 16px 12px;
        text-align: center;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }

    .table-arisan thead th:first-child {
        text-align: left;
        padding-left: 20px;
        min-width: 200px;
    }

    .date-header {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }

    .btn-delete {
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        font-size: 10px;
        cursor: pointer;
    }

    .table-arisan tbody tr {
        border-bottom: 1px solid #e2e8f0;
    }

    .table-arisan tbody tr:hover {
        background: #f7fafc;
    }

    .table-arisan tbody td {
        padding: 14px 12px;
        text-align: center;
    }

    .table-arisan tbody td:first-child {
        text-align: left;
        padding-left: 20px;
        font-weight: 600;
    }

    .toggle-checklist {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .counter-badge {
        padding: 6px 12px;
        background: linear-gradient(135deg, #ffc9c9 0%, #ffabab 100%);
        color: #7f1d1d;
        border-radius: 8px;
        font-weight: 700;
    }

    .counter-badge.zero {
        background: linear-gradient(135deg, #c3f0c8 0%, #a8e6cf 100%);
        color: #2d5016;
    }

    .hidden {
        display: none !important;
    }

    /* Mobile Card View */
    .mobile-card-view {
        display: none;
    }

    @media (max-width: 768px) {
        .data-card {
            display: none;
        }

        .mobile-card-view {
            display: block;
        }

        .member-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .member-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px;
            border-radius: 12px;
            margin: -20px -20px 16px -20px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .member-card-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 10px;
        }

        .date-item {
            background: #f7fafc;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .date-item.checked {
            background: linear-gradient(135deg, #c3f0c8 0%, #a8e6cf 100%);
        }

        .date-item-date {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .date-item-delete {
            margin-top: 8px;
            padding: 4px 8px;
            background: #f56565;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 9px;
            width: 100%;
        }
    }
</style>

<div class="rekap-container">
    <div class="page-header">
        <div class="header-content">
            <h3>📊 Catatan Arisan Tahun {{ $tahun->tahun }}</h3>
            <a href="{{ route('sekretaris.catatan.index') }}" class="btn-back">← Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">✗ {{ session('error') }}</div>
    @endif

    <div class="filter-section">
        <div class="filter-group">
            <div class="filter-item">
                <label class="filter-label">🔍 Cari Anggota</label>
                <input type="text" id="searchInput" class="search-input" placeholder="Ketik nama anggota...">
            </div>
            <div class="filter-item">
                <label class="filter-label">📅 Filter Bulan</label>
                <select id="monthFilter" class="month-select">
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
            <div class="filter-item">
                <button id="resetBtn" class="btn-reset">🔄 Reset</button>
            </div>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="data-card">
        <div class="table-responsive">
            <table class="table-arisan">
                <thead>
                    <tr>
                        <th>Nama Anggota</th>
                        @foreach($tahun->tanggal as $tgl)
                            <th class="date-column" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}">
                                <div class="date-header">
                                    <span>{{ \Carbon\Carbon::parse($tgl->tanggal)->format('d M Y') }}</span>
                                    <form action="{{ route('sekretaris.tanggal.delete', $tgl->id) }}" method="POST" onsubmit="return confirm('Yakin?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </th>
                        @endforeach
                        <th>Belum Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggota as $user)
                        <tr class="member-row" data-name="{{ strtolower($user->name) }}">
                            <td>👤 {{ $user->name }}</td>
                            @foreach($tahun->tanggal as $tgl)
                                <td class="date-column" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}">
                                    <form action="{{ route('sekretaris.toggleChecklist') }}" method="POST" class="toggle-form">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="tanggal_id" value="{{ $tgl->id }}">
                                        <input type="checkbox" class="toggle-checklist" {{ $user->catatanArisan->where('tanggal_id', $tgl->id)->isNotEmpty() ? 'checked' : '' }}>
                                    </form>
                                </td>
                            @endforeach
                            <td>
                                @php $belumBayar = $tahun->tanggal->count() - $user->catatanArisan->count(); @endphp
                                <span class="counter-badge {{ $belumBayar == 0 ? 'zero' : '' }}">{{ $belumBayar }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-card-view">
        @foreach($anggota as $user)
            <div class="member-card" data-name="{{ strtolower($user->name) }}">
                <div class="member-card-header">
                    <span>👤 {{ $user->name }}</span>
                    @php $belumBayar = $tahun->tanggal->count() - $user->catatanArisan->count(); @endphp
                    <span class="counter-badge {{ $belumBayar == 0 ? 'zero' : '' }} mobile-counter" data-user="{{ $user->id }}">{{ $belumBayar }}</span>
                </div>
                <div class="member-card-content">
                    @foreach($tahun->tanggal as $tgl)
                        @php $isChecked = $user->catatanArisan->where('tanggal_id', $tgl->id)->isNotEmpty(); @endphp
                        <div class="date-item {{ $isChecked ? 'checked' : '' }}" data-month="{{ \Carbon\Carbon::parse($tgl->tanggal)->format('Y-m') }}">
                            <div class="date-item-date">{{ \Carbon\Carbon::parse($tgl->tanggal)->format('d M') }}</div>
                            <form action="{{ route('sekretaris.toggleChecklist') }}" method="POST" class="toggle-form-mobile">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="tanggal_id" value="{{ $tgl->id }}">
                                <input type="checkbox" class="toggle-checklist-mobile" {{ $isChecked ? 'checked' : '' }}>
                            </form>
                            <form action="{{ route('sekretaris.tanggal.delete', $tgl->id) }}" method="POST" onsubmit="return confirm('Yakin?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="date-item-delete">🗑️</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Desktop Toggle
    $('.toggle-checklist').on('change', function () {
        let form = $(this).closest('.toggle-form');
        let checkbox = $(this);
        checkbox.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: "POST",
            data: form.serialize(),
            success: function () {
                checkbox.prop('disabled', false);
                let row = checkbox.closest('tr');
                let checked = row.find('.date-column:not(.hidden) .toggle-checklist:checked').length;
                let total = row.find('.date-column:not(.hidden)').length;
                let badge = row.find('.counter-badge');
                badge.text(total - checked).toggleClass('zero', (total - checked) == 0);
            },
            error: function () {
                checkbox.prop('disabled', false);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // Mobile Toggle
    $('.toggle-checklist-mobile').on('change', function () {
        let form = $(this).closest('.toggle-form-mobile');
        let checkbox = $(this);
        let dateItem = checkbox.closest('.date-item');
        let card = checkbox.closest('.member-card');
        checkbox.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: "POST",
            data: form.serialize(),
            success: function () {
                checkbox.prop('disabled', false);
                dateItem.toggleClass('checked', checkbox.is(':checked'));
                let checked = card.find('.date-item:not(.hidden) .toggle-checklist-mobile:checked').length;
                let total = card.find('.date-item:not(.hidden)').length;
                let badge = card.find('.mobile-counter');
                badge.text(total - checked).toggleClass('zero', (total - checked) == 0);
            },
            error: function () {
                checkbox.prop('disabled', false);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // Search
    $('#searchInput').on('input', function() {
        const term = $(this).val().toLowerCase();
        $('.member-row, .member-card').each(function() {
            $(this).toggle($(this).data('name').includes(term));
        });
    });

    // Month Filter
    $('#monthFilter').on('change', function() {
        const month = $(this).val();
        if (month === 'all') {
            $('.date-column, .date-item').show();
        } else {
            $('.date-column, .date-item').each(function() {
                $(this).toggle($(this).data('month') === month);
            });
        }
        // Update counters
        $('.member-row').each(function() {
            let checked = $(this).find('.date-column:visible .toggle-checklist:checked').length;
            let total = $(this).find('.date-column:visible').length;
            $(this).find('.counter-badge').text(total - checked).toggleClass('zero', (total - checked) == 0);
        });
        $('.member-card').each(function() {
            let checked = $(this).find('.date-item:visible .toggle-checklist-mobile:checked').length;
            let total = $(this).find('.date-item:visible').length;
            $(this).find('.mobile-counter').text(total - checked).toggleClass('zero', (total - checked) == 0);
        });
    });

    // Reset
    $('#resetBtn').on('click', function() {
        $('#searchInput').val('');
        $('#monthFilter').val('all');
        $('.member-row, .member-card, .date-column, .date-item').show();
        $('#monthFilter').trigger('change');
    });

    setTimeout(function() { $('.alert').fadeOut(); }, 5000);
});
</script>

@endsection
