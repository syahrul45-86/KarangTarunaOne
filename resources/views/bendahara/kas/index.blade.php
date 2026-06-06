@extends('bendahara.layouts.master')

@section('title', 'Data Kas Anggota')

@push('styles')
<style>
    /* ===== KAS PAGE - PREMIUM REDESIGN + MOBILE ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .kas-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .kas-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35);
    }
    .kas-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .kas-header-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: #fff;
        margin-right: 14px;
        flex-shrink: 0;
    }
    .kas-header-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        letter-spacing: -0.3px;
    }
    .kas-header-sub {
        color: rgba(255,255,255,0.75);
        font-size: 0.83rem;
        margin: 2px 0 0;
    }
    .btn-kas-add {
        background: #fff;
        color: #667eea;
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        display: inline-flex; align-items: center; gap: 7px;
        position: relative; z-index: 1;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-kas-add:hover {
        background: #667eea; color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        text-decoration: none;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border-radius: 18px;
        padding: 20px 22px;
        border: none;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card-green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        box-shadow: 0 8px 28px rgba(17,153,142,0.35);
    }
    .stat-card-blue {
        background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
        box-shadow: 0 8px 28px rgba(33,147,176,0.35);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -25px; right: -25px;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .stat-card-label {
        color: rgba(255,255,255,0.85);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }
    .stat-card-value {
        color: #fff;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    .stat-card-note {
        color: rgba(255,255,255,0.7);
        font-size: 0.75rem;
        margin-top: 5px;
    }
    .stat-card-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff;
        flex-shrink: 0;
        position: relative; z-index: 1;
    }

    /* ===== TABLE CARD (DESKTOP) ===== */
    .kas-table-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 30px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .kas-table-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f2f7;
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .kas-table-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1f36;
        margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .kas-table-title-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
        display: inline-block;
    }
    .kas-table-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .kas-table {
        margin: 0;
    }
    .kas-table thead th {
        background: #f8f9fc;
        color: #8892b0;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        padding: 13px 20px;
    }
    .kas-table tbody tr {
        border-bottom: 1px solid #f0f2f7;
        transition: background 0.2s ease;
    }
    .kas-table tbody tr:last-child { border-bottom: none; }
    .kas-table tbody tr:hover { background: rgba(102,126,234,0.03); }
    .kas-table tbody td {
        border: none;
        padding: 14px 20px;
        vertical-align: middle;
    }
    .kas-avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 0.85rem;
        margin-right: 10px; flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(102,126,234,0.35);
    }
    .kas-member-name {
        font-weight: 600; color: #1a1f36; font-size: 0.88rem;
    }
    .nominal-badge {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
        border-radius: 8px;
        padding: 5px 12px;
        font-weight: 700; font-size: 0.8rem;
        display: inline-block;
        box-shadow: 0 3px 8px rgba(17,153,142,0.3);
    }
    .kas-date { color: #64748b; font-size: 0.83rem; }
    .kas-no { font-weight: 700; color: #cbd5e1; font-size: 0.8rem; }
    .kas-ket {
        color: #94a3b8; font-size: 0.8rem; font-style: italic;
        max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .btn-kas-edit {
        width: 32px; height: 32px;
        border-radius: 9px; border: none;
        background: rgba(243, 156, 18, 0.08);
        color: #f39c12;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.25s ease; font-size: 0.75rem;
        margin-right: 4px;
        text-decoration: none;
    }
    .btn-kas-edit:hover {
        background: #f39c12; color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.35);
    }
    .btn-kas-delete {
        width: 32px; height: 32px;
        border-radius: 9px; border: none;
        background: rgba(231,76,60,0.08);
        color: #e74c3c;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.25s ease; font-size: 0.75rem;
    }
    .btn-kas-delete:hover {
        background: #e74c3c; color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(231,76,60,0.35);
    }

    /* ===== MOBILE CARD LIST ===== */
    .kas-mobile-list { display: none; padding: 12px 16px; }
    .kas-mobile-item {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f0f2f7;
        transition: box-shadow 0.25s ease, transform 0.2s ease;
    }
    .kas-mobile-item:active {
        transform: scale(0.98);
    }
    .kas-mobile-item:last-child { margin-bottom: 4px; }

    .kas-mobile-top {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .kas-mobile-member {
        display: flex; align-items: center; gap: 10px;
        flex: 1; min-width: 0;
    }
    .kas-mobile-avatar {
        width: 40px; height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(102,126,234,0.3);
    }
    .kas-mobile-name {
        font-weight: 700; color: #1a1f36; font-size: 0.9rem;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .kas-mobile-no {
        font-size: 0.72rem; font-weight: 600; color: #cbd5e1;
    }

    .kas-mobile-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .kas-mobile-field { }
    .kas-mobile-field-label {
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #b0bad0;
        margin-bottom: 3px;
    }
    .kas-mobile-field-value {
        font-size: 0.82rem;
        font-weight: 500;
        color: #475569;
    }
    .kas-mobile-nominal-badge {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
        border-radius: 8px;
        padding: 4px 10px;
        font-weight: 700; font-size: 0.78rem;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(17,153,142,0.3);
    }
    .kas-mobile-footer {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f2f7;
        display: flex; align-items: center; justify-content: space-between;
    }
    .kas-mobile-ket {
        font-size: 0.78rem; color: #94a3b8; font-style: italic;
        flex: 1; margin-right: 10px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .btn-kas-edit-mobile {
        display: flex; align-items: center; gap: 5px;
        background: rgba(243, 156, 18, 0.08);
        color: #f39c12;
        border: none;
        border-radius: 9px;
        padding: 7px 13px;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.25s ease;
        flex-shrink: 0;
        text-decoration: none;
    }
    .btn-kas-edit-mobile:hover, .btn-kas-edit-mobile:active {
        background: #f39c12; color: #fff;
        text-decoration: none;
    }
    .btn-kas-delete-mobile {
        display: flex; align-items: center; gap: 5px;
        background: rgba(231,76,60,0.08);
        color: #e74c3c;
        border: none;
        border-radius: 9px;
        padding: 7px 13px;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }
    .btn-kas-delete-mobile:hover, .btn-kas-delete-mobile:active {
        background: #e74c3c; color: #fff;
    }

    /* ===== EMPTY STATE ===== */
    .kas-empty {
        padding: 50px 20px;
        text-align: center;
    }
    .kas-empty-icon {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f0f2ff, #e8ecff);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem; color: #667eea; opacity: 0.7;
    }
    .kas-empty-title { font-weight: 700; color: #1a1f36; font-size: 1rem; margin-bottom: 6px; }
    .kas-empty-sub { color: #94a3b8; font-size: 0.85rem; margin-bottom: 18px; }
    .btn-kas-empty {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff; border: none; border-radius: 12px;
        padding: 10px 22px; font-weight: 600; font-size: 0.86rem;
        transition: all 0.3s ease;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none;
    }
    .btn-kas-empty:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102,126,234,0.4); text-decoration: none; }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-1 { animation: fadeSlideIn 0.4s ease forwards; }
    .anim-2 { animation: fadeSlideIn 0.5s 0.06s ease forwards; opacity:0; }
    .anim-3 { animation: fadeSlideIn 0.5s 0.12s ease forwards; opacity:0; }

    /* ===== RESPONSIVE BREAKPOINTS ===== */

    /* Tablet: md */
    @media (max-width: 767px) {
        .kas-header { padding: 18px 20px; border-radius: 16px; }
        .kas-header-title { font-size: 1.15rem; }
        .kas-header-sub { font-size: 0.78rem; }
        .kas-header-icon { width: 40px; height: 40px; font-size: 1.1rem; margin-right: 10px; }

        .btn-kas-add { padding: 9px 14px; font-size: 0.8rem; border-radius: 10px; }

        .stat-card { padding: 16px 18px; border-radius: 14px; margin-bottom: 14px; }
        .stat-card-value { font-size: 1.15rem; }

        /* Table → hide on mobile, show card list */
        .kas-desktop-table { display: none !important; }
        .kas-mobile-list { display: block !important; }

        .kas-table-card .card-header { padding: 14px 18px; }
        .kas-table-title { font-size: 0.88rem; }
    }

    /* Extra small phones */
    @media (max-width: 480px) {
        .kas-header {
            padding: 14px 16px;
            border-radius: 14px;
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }
        .kas-header::before { display: none; }
        .btn-kas-add { width: 100%; justify-content: center; }

        .stat-card-value { font-size: 1.05rem; }

        .kas-mobile-body { grid-template-columns: 1fr; gap: 8px; }
        .kas-mobile-item { padding: 14px; }
    }
</style>
@endpush

@section('content')
<div class="kas-page px-2">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="kas-header d-flex align-items-center justify-content-between anim-1">
        <div class="d-flex align-items-center">
            <div class="kas-header-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <h1 class="kas-header-title">Data Kas Anggota</h1>
                <p class="kas-header-sub">Kelola & pantau setoran kas rutin seluruh anggota RT</p>
            </div>
        </div>
        <a href="{{ route('bendahara.kas.create') }}" class="btn-kas-add">
            <i class="fas fa-plus"></i>
            Tambah Setoran
        </a>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="row anim-2">
        <div class="col-6 col-md-6">
            <div class="stat-card stat-card-green">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width:0;">
                        <p class="stat-card-label">Total Kas</p>
                        <div class="stat-card-value">
                            Rp {{ number_format($kas->sum('nominal'), 0, ',', '.') }}
                        </div>
                        <div class="stat-card-note">
                            <i class="fas fa-layer-group mr-1"></i>{{ $kas->count() }} transaksi
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="stat-card stat-card-blue">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width:0;">
                        <p class="stat-card-label">Bulan Ini</p>
                        <div class="stat-card-value">
                            Rp {{ number_format($kas->whereBetween('tanggal', [now()->startOfMonth(), now()->endOfMonth()])->sum('nominal'), 0, ',', '.') }}
                        </div>
                        <div class="stat-card-note">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ now()->format('M Y') }}
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABLE CARD ===== --}}
    <div class="card kas-table-card anim-3">
        <div class="card-header">
            <h6 class="kas-table-title">
                <span class="kas-table-title-dot"></span>
                Riwayat Setoran Kas
            </h6>
            <span class="kas-table-badge">{{ $kas->count() }} data</span>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="card-body p-0 kas-desktop-table">
            <div class="table-responsive">
                <table class="table kas-table" id="kasTable">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Anggota</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th style="width:60px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kas as $item)
                        <tr>
                            <td><span class="kas-no">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="kas-avatar">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <span class="kas-member-name">{{ $item->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="kas-date">
                                    <i class="fas fa-calendar mr-1" style="color:#a0aec0;font-size:0.72rem;"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="nominal-badge">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="kas-ket" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan ?: '—' }}
                                </span>
                            </td>
                            <td class="text-center" style="white-space: nowrap;">
                                <a href="{{ route('bendahara.kas.edit', $item->id) }}" class="btn-kas-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bendahara.kas.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-kas-delete"
                                        onclick="return confirm('Hapus data kas ini?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="kas-empty">
                                    <div class="kas-empty-icon"><i class="fas fa-box-open"></i></div>
                                    <div class="kas-empty-title">Belum ada data setoran</div>
                                    <p class="kas-empty-sub">Tambahkan setoran kas pertama untuk mulai mencatat</p>
                                    <a href="{{ route('bendahara.kas.create') }}" class="btn-kas-empty">
                                        <i class="fas fa-plus"></i> Tambah Data Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== MOBILE CARD LIST ===== --}}
        <div class="kas-mobile-list">
            @forelse($kas as $item)
            <div class="kas-mobile-item">
                {{-- Top: avatar + nama + nomor --}}
                <div class="kas-mobile-top">
                    <div class="kas-mobile-member">
                        <div class="kas-mobile-avatar">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                        <div style="min-width:0;">
                            <div class="kas-mobile-name">{{ $item->user->name }}</div>
                            <div class="kas-mobile-no">#{{ $loop->iteration }}</div>
                        </div>
                    </div>
                    <span class="kas-mobile-nominal-badge">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Body: tanggal + nominal --}}
                <div class="kas-mobile-body">
                    <div class="kas-mobile-field">
                        <div class="kas-mobile-field-label"><i class="fas fa-calendar-alt mr-1"></i> Tanggal</div>
                        <div class="kas-mobile-field-value">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="kas-mobile-field">
                        <div class="kas-mobile-field-label"><i class="fas fa-sticky-note mr-1"></i> Keterangan</div>
                        <div class="kas-mobile-field-value" style="font-style:italic;color:#94a3b8;">
                            {{ $item->keterangan ?: '—' }}
                        </div>
                    </div>
                </div>

                <div class="kas-mobile-footer">
                    <span class="kas-mobile-ket"></span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('bendahara.kas.edit', $item->id) }}" class="btn-kas-edit-mobile">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('bendahara.kas.delete', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-kas-delete-mobile"
                                onclick="return confirm('Hapus data kas ini?')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="kas-empty">
                <div class="kas-empty-icon"><i class="fas fa-box-open"></i></div>
                <div class="kas-empty-title">Belum ada data setoran</div>
                <p class="kas-empty-sub">Tambahkan setoran kas pertama untuk mulai mencatat</p>
                <a href="{{ route('bendahara.kas.create') }}" class="btn-kas-empty">
                    <i class="fas fa-plus"></i> Tambah Data Sekarang
                </a>
            </div>
            @endforelse
        </div>
        {{-- END MOBILE LIST --}}

    </div>{{-- end kas-table-card --}}

</div>
@endsection
