@extends('sekretaris.layouts.master')

@section('title', 'Cek Daftar Hadir')

@push('styles')
<style>
    .cek-hero {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 16px;
        color: #fff;
        padding: 24px 32px;
        margin-bottom: 24px;
        box-shadow: 0 8px 30px rgba(78,115,223,.3);
    }
    .hero-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 4px; }
    .hero-subtitle { opacity: .9; font-size: 0.95rem; margin: 0; }
    
    .stat-card {
        border: none; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,.05);
        transition: transform .2s;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .bg-light-primary { background: rgba(78,115,223,.1); color: #4e73df; }
    .bg-light-success { background: rgba(28,200,138,.1); color: #1cc88a; }
    .bg-light-warning { background: rgba(246,194,62,.1); color: #f6c23e; }
    .bg-light-danger  { background: rgba(231,74,59,.1); color: #e74a3b; }

    .data-card {
        border: none; border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .data-card .card-header {
        background: #f8f9fc; border-bottom: 1px solid #e3e6f0;
        padding: 16px 20px; font-weight: bold;
    }
    
    .empty-state { text-align: center; padding: 40px 20px; color: #b7bac3; }
    .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
</style>
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('sekretaris.absensi.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
    </a>
</div>

{{-- Hero Section --}}
<div class="cek-hero d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="hero-title"><i class="fas fa-clipboard-list mr-2"></i>{{ $form->judul }}</h2>
        <p class="hero-subtitle">
            <i class="fas fa-calendar-day mr-1"></i> {{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }} &nbsp;|&nbsp; 
            <i class="fas fa-clock mr-1"></i> {{ $form->jam_mulai }} - {{ $form->jam_selesai }}
        </p>
    </div>
</div>

{{-- Statistics Row --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-light-primary mr-3"><i class="fas fa-users"></i></div>
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Target</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUser }} Anggota</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-success">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-light-success mr-3"><i class="fas fa-user-check"></i></div>
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir (Scan)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHadir }} Anggota</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-light-warning mr-3"><i class="fas fa-envelope-open-text"></i></div>
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin (Disetujui)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalIzin }} Anggota</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-danger">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-light-danger mr-3"><i class="fas fa-user-times"></i></div>
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tidak Hadir (Alpa)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTidakHadir }} Anggota</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Tabel Hadir --}}
    <div class="col-lg-7">
        <div class="card data-card border-left-success">
            <div class="card-header text-success d-flex justify-content-between align-items-center">
                <span><i class="fas fa-check-circle mr-2"></i>Daftar Anggota Hadir</span>
                <span class="badge badge-success px-2 py-1">{{ $totalHadir }}</span>
            </div>
            <div class="card-body p-0">
                @if($absenList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="10%" class="text-center">No</th>
                                <th>Nama Anggota</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absenList as $i => $absen)
                            <tr>
                                <td class="text-center align-middle">{{ $i + 1 }}</td>
                                <td class="align-middle">
                                    <div class="font-weight-bold">{{ $absen->user->name }}</div>
                                    <div class="small text-muted">{{ $absen->user->email }}</div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light p-2 border">
                                        <i class="fas fa-clock text-success mr-1"></i>
                                        {{ \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i:s') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-user-slash text-gray-300"></i>
                    <h6>Belum Ada Anggota Hadir</h6>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Izin --}}
    <div class="col-lg-5">
        <div class="card data-card border-left-warning">
            <div class="card-header text-warning d-flex justify-content-between align-items-center">
                <span><i class="fas fa-envelope-open-text mr-2"></i>Daftar Izin (Disetujui)</span>
                <span class="badge badge-warning text-dark px-2 py-1">{{ $totalIzin }}</span>
            </div>
            <div class="card-body p-0">
                @if($izinList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izinList as $izin)
                            <tr>
                                <td class="align-middle">
                                    <div class="font-weight-bold">{{ $izin->user->name }}</div>
                                </td>
                                <td class="align-middle">
                                    <div class="small text-muted" title="{{ $izin->alasan }}">
                                        {{ \Illuminate\Support\Str::limit($izin->alasan, 40) }}
                                    </div>
                                    @if($izin->foto_path)
                                        <a href="{{ Storage::url($izin->foto_path) }}" target="_blank" class="small text-primary">
                                            <i class="fas fa-image mr-1"></i>Bukti
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox text-gray-300"></i>
                    <h6>Tidak Ada Izin</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tabel Tidak Hadir --}}
@php
    $hadirUserIds = $absenList->pluck('user_id')->toArray();
    $izinUserIds  = $izinList->pluck('user_id')->toArray();
    $tidakHadirUsers = \App\Models\User::where('rt_id', auth()->user()->rt_id)
        ->whereIn('role', ['admin', 'anggota', 'sekretaris', 'bendahara'])
        ->whereNotIn('id', $hadirUserIds)
        ->whereNotIn('id', $izinUserIds)
        ->orderBy('name', 'asc')
        ->get();
@endphp

<div class="row mt-2">
    <div class="col-12">
        <div class="card data-card border-left-danger">
            <div class="card-header text-danger d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user-times mr-2"></i>Daftar Tidak Hadir (Alpa)</span>
                <span class="badge badge-danger px-2 py-1">{{ $tidakHadirUsers->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($tidakHadirUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Anggota</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tidakHadirUsers as $i => $user)
                            <tr>
                                <td class="text-center align-middle">{{ $i + 1 }}</td>
                                <td class="align-middle">
                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                </td>
                                <td class="align-middle text-muted small">{{ $user->email }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-secondary text-capitalize">{{ $user->role }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-danger px-2 py-1">
                                        <i class="fas fa-times mr-1"></i>Tidak Hadir
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-check-double text-success"></i>
                    <h6 class="text-success">Semua anggota hadir atau izin!</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
