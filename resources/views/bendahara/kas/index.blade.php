@extends('bendahara.layouts.master')

@section('title', 'Data Kas Anggota')

@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-wallet text-primary mr-2"></i>Data Kas Anggota
            </h1>
            <p class="text-muted small mb-0">Kelola setoran kas rutin seluruh anggota RT.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('bendahara.kas.create') }}" class="btn btn-primary btn-icon-split shadow-sm translate-hover">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Setoran</span>
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kas Terkumpul</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($kas->sum('nominal'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Setoran Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($kas->whereBetween('tanggal', [now()->startOfMonth(), now()->endOfMonth()])->sum('nominal'), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-0 rounded-lg">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Setoran Kas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover border-0" id="kasTable" width="100%" cellspacing="0">
                    <thead class="bg-light text-gray-600 border-0">
                        <tr>
                            <th class="border-0">No</th>
                            <th class="border-0">Nama Anggota</th>
                            <th class="border-0">Tanggal</th>
                            <th class="border-0">Nominal</th>
                            <th class="border-0">Keterangan</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kas as $item)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm mr-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user-circle small"></i>
                                    </div>
                                    <span class="font-weight-bold text-gray-800">{{ $item->user->name }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                            <td>
                                <span class="badge badge-success px-3 py-2 rounded-pill font-weight-bold">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-muted small italic">{{ $item->keterangan ?: '-' }}</td>
                            <td>
                                <form action="{{ route('bendahara.kas.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" onclick="return confirm('Hapus data kas ini?')" title="Hapus">
                                        <i class="fas fa-trash-alt fa-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-box-open fa-3x text-gray-200 mb-3"></i>
                                    <p class="text-gray-500 font-weight-bold">Belum ada data setoran kas.</p>
                                    <a href="{{ route('bendahara.kas.create') }}" class="btn btn-primary btn-sm mt-2">Tambah Data Baru</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-sm { background-color: rgba(78, 115, 223, 0.1); }
    .italic { font-style: italic; }
    .translate-hover { transition: transform 0.2s ease; }
    .translate-hover:hover { transform: translateY(-3px); }
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 223, 0.02); }
</style>
@endsection
