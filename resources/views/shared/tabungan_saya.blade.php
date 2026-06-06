@extends(auth()->user()->role . '.layouts.master')

@section('title', 'Tabungan Saya')

@push('styles')
<style>
    /* ===== TABUNGAN SAYA - PREMIUM VIEW ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .tb-saya-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== HEADER ===== */
    .tb-saya-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        border-radius: 20px; padding: 24px 28px; margin-bottom: 24px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 40px rgba(37,99,235,0.28);
    }
    .tb-saya-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,0.1);
    }
    .tb-saya-header-icon {
        width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        color: #fff; margin-right: 14px; flex-shrink: 0; position: relative; z-index: 1;
    }
    .tb-saya-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; }
    .tb-saya-sub { color: rgba(255,255,255,0.8); font-size: 0.85rem; margin: 3px 0 0; }

    /* ===== SALDO BESAR ===== */
    .saldo-utama {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 20px; padding: 28px 32px; margin-bottom: 24px;
        text-align: center; position: relative; overflow: hidden;
        box-shadow: 0 12px 40px rgba(37,99,235,0.25);
    }
    .saldo-utama::before {
        content: ''; position: absolute; width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,0.05); top: -60px; right: -60px;
    }
    .saldo-utama::after {
        content: ''; position: absolute; width: 120px; height: 120px; border-radius: 50%;
        background: rgba(255,255,255,0.05); bottom: -30px; left: -30px;
    }
    .saldo-label { color: rgba(255,255,255,0.7); font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; position: relative; z-index: 1; }
    .saldo-angka { color: #fff; font-size: 2.4rem; font-weight: 800; letter-spacing: -1px; position: relative; z-index: 1; }
    .saldo-note { color: rgba(255,255,255,0.6); font-size: 0.78rem; margin-top: 8px; position: relative; z-index: 1; }

    /* ===== MINI STATS ===== */
    .mini-stat {
        border-radius: 16px; padding: 20px; margin-bottom: 20px;
        position: relative; overflow: hidden; transition: transform 0.3s ease;
    }
    .mini-stat:hover { transform: translateY(-4px); }
    .mini-setoran { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 6px 24px rgba(16,185,129,0.3); }
    .mini-penarikan { background: linear-gradient(135deg, #f87171, #dc2626); box-shadow: 0 6px 24px rgba(239,68,68,0.3); }
    .mini-stat::after { content: ''; position: absolute; bottom: -20px; right: -20px; width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.12); }
    .mini-label { color: rgba(255,255,255,0.85); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .mini-val { color: #fff; font-size: 1.25rem; font-weight: 800; }
    .mini-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.22); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; position: relative; z-index: 1; }

    /* ===== RIWAYAT CARD ===== */
    .tb-riwayat-card { border-radius: 20px; background: #fff; border: none; box-shadow: 0 4px 30px rgba(0,0,0,0.05); overflow: hidden; }
    .tb-riwayat-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .tb-riwayat-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px; }
    .tb-dot { width: 8px; height: 8px; border-radius: 50%; background: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }

    /* Table */
    .tb-table { margin: 0; width: 100%; }
    .tb-table thead th { background: #f8fafc; color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 14px 20px; }
    .tb-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
    .tb-table tbody tr:last-child { border-bottom: none; }
    .tb-table tbody tr:hover { background: #f8fafc; }
    .tb-table td { padding: 15px 20px; vertical-align: middle; border: none; }

    .badge-set { background: rgba(16,185,129,0.1); color: #059669; border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: 0.78rem; }
    .badge-tar { background: rgba(239,68,68,0.1); color: #dc2626; border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: 0.78rem; }
    .tb-date { color: #64748b; font-size: 0.82rem; }
    .tb-no { font-weight: 700; color: #cbd5e1; font-size: 0.8rem; }
    .tb-ket { color: #94a3b8; font-size: 0.8rem; font-style: italic; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* Mobile */
    .tb-mobile-list { display: none; padding: 16px; }
    .tb-m-item { background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 12px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .tb-m-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .tb-m-no { font-size: 0.72rem; color: #94a3b8; font-weight: 600; }
    .tb-m-body { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f8fafc; border-radius: 10px; padding: 12px; margin-bottom: 10px; }
    .tb-m-field-label { font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px; }
    .tb-m-field-val { font-size: 0.84rem; font-weight: 600; color: #0f172a; }

    /* Responsive */
    @media (max-width: 767px) {
        .tb-saya-header { padding: 20px; border-radius: 16px; flex-wrap: wrap; }
        .tb-saya-title { font-size: 1.2rem; }
        .saldo-angka { font-size: 1.9rem; }
        .tb-table { display: none; }
        .tb-mobile-list { display: block !important; }
        .mini-stat { padding: 16px; }
        .mini-val { font-size: 1.05rem; }
    }
    @media (max-width: 480px) {
        .saldo-utama { padding: 22px 20px; }
        .tb-m-body { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="tb-saya-page px-2">

    {{-- HEADER --}}
    <div class="tb-saya-header d-flex align-items-center">
        <div class="tb-saya-header-icon"><i class="fas fa-piggy-bank"></i></div>
        <div>
            <h1 class="tb-saya-title">Tabungan Saya</h1>
            <p class="tb-saya-sub">Riwayat simpanan dan penarikan tabungan Anda di RT ini</p>
        </div>
    </div>

    {{-- SALDO UTAMA --}}
    <div class="saldo-utama">
        <div class="saldo-label"><i class="fas fa-wallet mr-2"></i>Total Saldo Tabungan Anda</div>
        <div class="saldo-angka">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        <div class="saldo-note"><i class="fas fa-shield-alt mr-1"></i>Diperbarui otomatis setiap transaksi dicatat</div>
    </div>

    {{-- MINI STATS --}}
    <div class="row">
        <div class="col-6">
            <div class="mini-stat mini-setoran">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="mini-label">Total Setoran</div>
                        <div class="mini-val">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</div>
                    </div>
                    <div class="mini-icon"><i class="fas fa-arrow-down"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="mini-stat mini-penarikan">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="mini-label">Total Penarikan</div>
                        <div class="mini-val">Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</div>
                    </div>
                    <div class="mini-icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT TRANSAKSI --}}
    <div class="card tb-riwayat-card">
        <div class="tb-riwayat-header">
            <h6 class="tb-riwayat-title"><span class="tb-dot"></span> Riwayat Transaksi</h6>
            <span style="background: #2563eb; color: #fff; border-radius: 20px; padding: 3px 12px; font-size:0.72rem; font-weight:700;">{{ $riwayat->count() }} data</span>
        </div>

        {{-- Desktop Table --}}
        <div class="p-0" id="tb-desktop">
            <table class="table tb-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                    <tr>
                        <td><span class="tb-no">{{ $loop->iteration }}</span></td>
                        <td>
                            <span class="badge-{{ $item->jenis_transaksi == 'setoran' ? 'set' : 'tar' }}">
                                <i class="fas fa-arrow-{{ $item->jenis_transaksi == 'setoran' ? 'down' : 'up' }} mr-1"></i>
                                {{ ucfirst($item->jenis_transaksi) }}
                            </span>
                        </td>
                        <td><strong>Rp {{ number_format($item->nominal, 0, ',', '.') }}</strong></td>
                        <td><span class="tb-date">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span></td>
                        <td><span class="tb-ket">{{ $item->keterangan ?: '—' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-piggy-bank fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>
                            Belum ada riwayat tabungan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile List --}}
        <div class="tb-mobile-list">
            @forelse($riwayat as $item)
            <div class="tb-m-item">
                <div class="tb-m-top">
                    <span class="badge-{{ $item->jenis_transaksi == 'setoran' ? 'set' : 'tar' }}">
                        <i class="fas fa-arrow-{{ $item->jenis_transaksi == 'setoran' ? 'down' : 'up' }} mr-1"></i>
                        {{ ucfirst($item->jenis_transaksi) }}
                    </span>
                    <span class="tb-m-no">#{{ $loop->iteration }}</span>
                </div>
                <div class="tb-m-body">
                    <div>
                        <div class="tb-m-field-label">Nominal</div>
                        <div class="tb-m-field-val">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="tb-m-field-label">Tanggal</div>
                        <div class="tb-m-field-val" style="font-weight:500;color:#475569;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                    </div>
                </div>
                @if($item->keterangan)
                <div style="font-size:0.78rem; color:#94a3b8; font-style:italic;">{{ $item->keterangan }}</div>
                @endif
            </div>
            @empty
            <div class="text-center py-4 text-muted">Belum ada riwayat tabungan.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
