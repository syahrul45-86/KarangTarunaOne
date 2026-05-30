@extends('bendahara.layouts.master')

@section('title','Catatan Keuangan')

@section('content')

<style>
    .bendahara-page-header {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
        color: white;
    }

    .bendahara-greeting {
        color: #e0e7ff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .bendahara-subtitle {
        font-size: 15px;
        opacity: 0.95;
    }

    .bendahara-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        border: none;
    }

    .bendahara-btn-primary {
        background: #4f46e5;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .bendahara-btn-primary:hover {
        background: #4338ca;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .bendahara-btn-header {
        background: white;
        color: #4f46e5;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .bendahara-btn-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #4f46e5;
        text-decoration: none;
    }

    .bendahara-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .bendahara-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 15px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .bendahara-table td {
        background: white;
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        color: #1e293b;
    }

    .bendahara-table td:first-child {
        border-left: 1px solid #e2e8f0;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .bendahara-table td:last-child {
        border-right: 1px solid #e2e8f0;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .bendahara-table tr {
        transition: all 0.2s ease;
    }

    .bendahara-table tr:hover td {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .bendahara-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .bendahara-badge-success { background: #dcfce7; color: #16a34a; }
    .bendahara-badge-danger { background: #fee2e2; color: #dc2626; }
    .bendahara-badge-info { background: #e0e7ff; color: #4f46e5; }
    
    .bendahara-search-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }

    .bendahara-search-form input {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 10px 15px;
    }

    .bendahara-search-form input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-edit { background: #f59e0b; }
    .btn-edit:hover { background: #d97706; transform: translateY(-2px); }
    
    .btn-delete { background: #ef4444; }
    .btn-delete:hover { background: #dc2626; transform: translateY(-2px); }
    
    .saldo-box {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="bendahara-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="bendahara-greeting"><i class="fas fa-book"></i> Catatan Keuangan</h1>
        <p class="bendahara-subtitle mb-0">Kelola semua transaksi pemasukan dan pengeluaran RT Anda.</p>
    </div>
    <div>
        @if ($bendaharas->count() == 0 && !$hasAny)
            <a href="{{ route('bendahara.create') }}" class="bendahara-btn-header">
                <i class="fas fa-plus-circle"></i> Tambah Saldo Awal
            </a>
        @else
            <a href="{{ route('bendahara.create') }}" class="bendahara-btn-header">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>
        @endif
    </div>
</div>

@if ($bendaharas->count() > 0 || $hasAny)
<div class="bendahara-card saldo-box mb-4">
    <div>
        <h5 class="text-muted mb-1" style="font-size: 14px; font-weight: 600; text-transform: uppercase;">Saldo Akhir Saat Ini</h5>
        <h3 class="mb-0" style="color: #4f46e5; font-weight: 800;">
            Rp {{ number_format($bendaharas->count() > 0 ? $bendaharas->last()->saldo_akhir : 0, 0, ',', '.') }}
        </h3>
    </div>
    <a href="{{ route('bendahara.edit_saldo_awal') }}" class="bendahara-btn-primary" style="background: #0891b2;">
        <i class="fas fa-edit"></i> Edit Saldo Awal
    </a>
</div>
@endif

<div class="bendahara-card">
    <form method="GET" action="{{ route('bendahara.catatan-keuangan.index') }}" class="bendahara-search-form mb-4">
        <div style="flex: 1; max-width: 300px;">
            <label class="form-label" style="font-size: 13px; font-weight: 600; color: #64748b;">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
        </div>
        <div>
            <button class="bendahara-btn-primary" type="submit">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request('tanggal'))
                <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn btn-light ml-2" style="border-radius: 10px; padding: 10px 20px; border: 2px solid #e2e8f0; font-weight: 600;">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="bendahara-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo Akhir</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bendaharas as $row)
                <tr>
                    <td class="text-center" style="color: #64748b; font-weight: 600;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;">
                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #334155;">{{ $row->keterangan }}</div>
                    </td>
                    <td>
                        @if($row->pemasukan > 0)
                            <span class="bendahara-badge bendahara-badge-success">
                                <i class="fas fa-arrow-down"></i> Rp {{ number_format($row->pemasukan, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->pengeluaran > 0)
                            <span class="bendahara-badge bendahara-badge-danger">
                                <i class="fas fa-arrow-up"></i> Rp {{ number_format($row->pengeluaran, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="font-weight: 700; color: #4f46e5;">
                        Rp {{ number_format($row->saldo_akhir, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="action-btns justify-content-center">
                            <a href="{{ route('bendahara.edit', $row->id) }}" class="action-btn btn-edit" title="Edit Transaksi">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <form action="{{ route('bendahara.destroy', $row->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('Yakin ingin menghapus transaksi ini?')" title="Hapus Transaksi">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-receipt mb-3" style="font-size: 48px; color: #cbd5e1;"></i>
                            <h5 class="text-muted font-weight-bold">Belum ada catatan keuangan</h5>
                            <p class="text-muted mb-0">Klik tombol di kanan atas untuk menambahkan catatan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
