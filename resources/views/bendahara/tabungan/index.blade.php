@extends('bendahara.layouts.master')

@section('title', 'Data Tabungan Anggota')

@push('styles')
<style>
    /* ===== TABUNGAN PAGE - PREMIUM + MOBILE ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .tabungan-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .tb-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(37, 99, 235, 0.3);
    }
    .tb-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .tb-header-icon {
        width: 48px; height: 48px; border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: #fff; margin-right: 14px; flex-shrink: 0;
    }
    .tb-header-title { font-size: 1.45rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.3px; }
    .tb-header-sub { color: rgba(255,255,255,0.8); font-size: 0.83rem; margin: 2px 0 0; }
    .btn-tb-add {
        background: #fff; color: #2563eb; border: none; border-radius: 12px;
        padding: 10px 20px; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15); transition: all 0.3s ease;
        display: inline-flex; align-items: center; gap: 7px; text-decoration: none; position: relative; z-index: 1; white-space: nowrap;
    }
    .btn-tb-add:hover {
        background: #2563eb; color: #fff; transform: translateY(-2px); text-decoration: none;
        box-shadow: 0 8px 25px rgba(37,99,235,0.4);
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border-radius: 18px; padding: 20px 22px; border: none;
        position: relative; overflow: hidden; transition: transform 0.3s ease; margin-bottom: 20px;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card-total { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 28px rgba(16,185,129,0.3); }
    .stat-card-history { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 8px 28px rgba(245,158,11,0.3); }
    .stat-card::after {
        content: ''; position: absolute; bottom: -25px; right: -25px;
        width: 90px; height: 90px; border-radius: 50%; background: rgba(255,255,255,0.12);
    }
    .stat-card-label { color: rgba(255,255,255,0.9); font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .stat-card-value { color: #fff; font-size: 1.35rem; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; }
    .stat-card-icon {
        width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; flex-shrink: 0; position: relative; z-index: 1;
    }

    /* ===== CONTENT TABS ===== */
    .tb-tabs { display: flex; gap: 15px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px; scrollbar-width: none; }
    .tb-tabs::-webkit-scrollbar { display: none; }
    .tb-tab {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 20px;
        color: #64748b; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease;
        display: flex; align-items: center; gap: 8px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .tb-tab:hover { background: #f8fafc; color: #0f172a; }
    .tb-tab.active { background: #2563eb; border-color: #2563eb; color: #fff; box-shadow: 0 4px 15px rgba(37,99,235,0.25); }

    .tb-pane { display: none; }
    .tb-pane.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* ===== TABLE CARDS ===== */
    .tb-card { border-radius: 20px; border: none; box-shadow: 0 4px 30px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 24px; background: #fff; }
    .tb-card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .tb-card-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px; }
    .tb-card-dot { width: 8px; height: 8px; border-radius: 50%; background: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }

    /* Table styles */
    .tb-table { margin: 0; width: 100%; }
    .tb-table thead th { background: #f8fafc; color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 14px 20px; }
    .tb-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease; }
    .tb-table tbody tr:last-child { border-bottom: none; }
    .tb-table tbody tr:hover { background: #f8fafc; }
    .tb-table td { padding: 16px 20px; vertical-align: middle; border: none; }
    
    .tb-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #0ea5e9, #2563eb); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.85rem; margin-right: 12px; box-shadow: 0 3px 10px rgba(37,99,235,0.2); }
    .tb-member-name { font-weight: 600; color: #0f172a; font-size: 0.88rem; }
    
    .badge-saldo { background: rgba(16,185,129,0.1); color: #059669; border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: 0.82rem; display: inline-block; }
    
    .badge-jenis.setoran { background: rgba(16,185,129,0.1); color: #059669; border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; }
    .badge-jenis.penarikan { background: rgba(239,68,68,0.1); color: #dc2626; border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; }

    .tb-date { color: #64748b; font-size: 0.82rem; }
    .tb-no { font-weight: 700; color: #cbd5e1; font-size: 0.8rem; }
    .tb-ket { color: #94a3b8; font-size: 0.8rem; font-style: italic; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .btn-tb-edit { width: 32px; height: 32px; border-radius: 8px; background: rgba(245,158,11,0.1); color: #d97706; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; margin-right: 4px; border: none; text-decoration: none;}
    .btn-tb-edit:hover { background: #f59e0b; color: #fff; transform: translateY(-2px); text-decoration: none;}
    .btn-tb-delete { width: 32px; height: 32px; border-radius: 8px; background: rgba(239,68,68,0.1); color: #dc2626; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; border: none; }
    .btn-tb-delete:hover { background: #ef4444; color: #fff; transform: translateY(-2px); }

    /* ===== MOBILE LIST ===== */
    .tb-mobile-list { display: none; padding: 16px; }
    .tb-mobile-item { background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 12px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .tb-m-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .tb-m-user { display: flex; align-items: center; gap: 10px; }
    .tb-m-avatar { width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #0ea5e9, #2563eb); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.9rem; flex-shrink:0;}
    .tb-m-name { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .tb-m-no { font-size: 0.72rem; color: #94a3b8; font-weight: 600; }
    
    .tb-m-body { background: #f8fafc; border-radius: 10px; padding: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px; }
    .tb-m-field { display: flex; flex-direction: column; gap: 4px; }
    .tb-m-label { font-size: 0.68rem; color: #64748b; font-weight: 700; text-transform: uppercase; }
    .tb-m-val { font-size: 0.85rem; font-weight: 600; color: #0f172a; }
    
    .tb-m-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 12px; }
    .btn-tb-m-edit { background: rgba(245,158,11,0.1); color: #d97706; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none;}
    .btn-tb-m-edit:hover { background: #f59e0b; color: #fff; text-decoration: none;}
    .btn-tb-m-delete { background: rgba(239,68,68,0.1); color: #dc2626; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; border: none;}

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .tb-header { padding: 20px; border-radius: 16px; flex-wrap: wrap; }
        .tb-header-title { font-size: 1.2rem; }
        .tb-desktop-table { display: none !important; }
        .tb-mobile-list { display: block !important; }
        .stat-card { padding: 16px; border-radius: 14px; }
        .stat-card-value { font-size: 1.15rem; }
        .tb-card-header { padding: 16px; }
    }
    @media (max-width: 480px) {
        .tb-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .btn-tb-add { width: 100%; justify-content: center; padding: 12px; }
    }
</style>
@endpush

@section('content')
<div class="tabungan-page px-2">

    {{-- HEADER --}}
    <div class="tb-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="tb-header-icon"><i class="fas fa-piggy-bank"></i></div>
            <div>
                <h1 class="tb-header-title">Data Tabungan Anggota</h1>
                <p class="tb-header-sub">Kelola setoran dan penarikan uang tabungan warga RT</p>
            </div>
        </div>
        <a href="{{ route('bendahara.tabungan.create') }}" class="btn-tb-add">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
    </div>

    {{-- STATS --}}
    @php
        $totalSaldo = $rekapSaldo->sum('saldo');
        $totalTransaksi = $tabungan->count();
    @endphp
    <div class="row">
        <div class="col-6">
            <div class="stat-card stat-card-total">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Uang Tabungan</div>
                        <div class="stat-card-value">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-card-icon"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-card stat-card-history">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Transaksi</div>
                        <div class="stat-card-value">{{ $totalTransaksi }}</div>
                    </div>
                    <div class="stat-card-icon"><i class="fas fa-exchange-alt"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="tb-tabs">
        <div class="tb-tab active" onclick="switchTab('rekap')"><i class="fas fa-users"></i> Rekap Saldo Anggota</div>
        <div class="tb-tab" onclick="switchTab('riwayat')"><i class="fas fa-history"></i> Riwayat Transaksi</div>
    </div>

    {{-- TAB PANE: REKAP SALDO --}}
    <div id="pane-rekap" class="tb-pane active">
        <div class="tb-card">
            <div class="tb-card-header">
                <h6 class="tb-card-title"><span class="tb-card-dot"></span> Saldo Tabungan Masing-Masing Anggota</h6>
            </div>
            
            <div class="tb-desktop-table p-0">
                <table class="table tb-table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Anggota</th>
                            <th>Total Setoran</th>
                            <th>Total Penarikan</th>
                            <th>Sisa Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapSaldo as $anggota)
                        <tr>
                            <td><span class="tb-no">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="tb-avatar">{{ strtoupper(substr($anggota->name, 0, 1)) }}</div>
                                    <span class="tb-member-name">{{ $anggota->name }}</span>
                                </div>
                            </td>
                            <td><span class="badge-jenis setoran">Rp {{ number_format($anggota->tabungan->where('jenis_transaksi','setoran')->sum('nominal'),0,',','.') }}</span></td>
                            <td><span class="badge-jenis penarikan">Rp {{ number_format($anggota->tabungan->where('jenis_transaksi','penarikan')->sum('nominal'),0,',','.') }}</span></td>
                            <td><span class="badge-saldo">Rp {{ number_format($anggota->saldo, 0, ',', '.') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tb-mobile-list">
                @foreach($rekapSaldo as $anggota)
                <div class="tb-mobile-item">
                    <div class="tb-m-top">
                        <div class="tb-m-user">
                            <div class="tb-m-avatar">{{ strtoupper(substr($anggota->name, 0, 1)) }}</div>
                            <div>
                                <div class="tb-m-name">{{ $anggota->name }}</div>
                                <div class="tb-m-no">#{{ $loop->iteration }}</div>
                            </div>
                        </div>
                        <span class="badge-saldo">Rp {{ number_format($anggota->saldo, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- TAB PANE: RIWAYAT TRANSAKSI --}}
    <div id="pane-riwayat" class="tb-pane">
        <div class="tb-card">
            <div class="tb-card-header">
                <h6 class="tb-card-title"><span class="tb-card-dot"></span> Semua Riwayat Transaksi</h6>
            </div>

            <div class="tb-desktop-table p-0">
                <table class="table tb-table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Anggota</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tabungan as $item)
                        <tr>
                            <td><span class="tb-no">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="tb-avatar" style="width:30px; height:30px; font-size:0.75rem;">{{ strtoupper(substr($item->user->name, 0, 1)) }}</div>
                                    <span class="tb-member-name">{{ $item->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-jenis {{ $item->jenis_transaksi }}">
                                    <i class="fas fa-{{ $item->jenis_transaksi == 'setoran' ? 'arrow-down' : 'arrow-up' }} mr-1"></i>
                                    {{ ucfirst($item->jenis_transaksi) }}
                                </span>
                            </td>
                            <td><strong>Rp {{ number_format($item->nominal, 0, ',', '.') }}</strong></td>
                            <td><span class="tb-date">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span></td>
                            <td><span class="tb-ket">{{ $item->keterangan ?: '—' }}</span></td>
                            <td class="text-center" style="white-space:nowrap;">
                                <a href="{{ route('bendahara.tabungan.edit', $item->id) }}" class="btn-tb-edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('bendahara.tabungan.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn-tb-delete" onclick="return confirm('Hapus transaksi ini?')"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tb-mobile-list">
                @forelse($tabungan as $item)
                <div class="tb-mobile-item">
                    <div class="tb-m-top">
                        <div class="tb-m-user">
                            <div class="tb-m-avatar" style="width:36px; height:36px; font-size:0.8rem;">{{ strtoupper(substr($item->user->name, 0, 1)) }}</div>
                            <div class="tb-m-name">{{ $item->user->name }}</div>
                        </div>
                        <span class="badge-jenis {{ $item->jenis_transaksi }}">{{ ucfirst($item->jenis_transaksi) }}</span>
                    </div>
                    <div class="tb-m-body">
                        <div class="tb-m-field">
                            <span class="tb-m-label">Nominal</span>
                            <span class="tb-m-val">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        </div>
                        <div class="tb-m-field">
                            <span class="tb-m-label">Tanggal</span>
                            <span class="tb-m-val" style="font-weight:500;color:#64748b;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="tb-m-footer">
                        <span class="tb-ket" style="font-size:0.75rem;">{{ $item->keterangan ?: 'Tidak ada keterangan' }}</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('bendahara.tabungan.edit', $item->id) }}" class="btn-tb-m-edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('bendahara.tabungan.delete', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn-tb-m-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Belum ada riwayat transaksi.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tb-tab').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tb-pane').forEach(el => el.classList.remove('active'));
        
        event.currentTarget.classList.add('active');
        document.getElementById('pane-' + tab).classList.add('active');
    }
</script>
@endsection
