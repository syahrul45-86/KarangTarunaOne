@extends('sekretaris.layouts.master')

@section('title', 'Manajemen Izin Absensi')

@push('styles')
<style>
    .izin-card { border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.08); overflow:hidden; }
    .btn-approve {
        background: linear-gradient(135deg,#1cc88a,#17a673);
        color:#fff; border:none; border-radius:8px;
        padding:6px 14px; font-size:13px; transition:all .2s;
    }
    .btn-approve:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(28,200,138,.4); }
    .btn-reject {
        background: linear-gradient(135deg,#e74a3b,#be2617);
        color:#fff; border:none; border-radius:8px;
        padding:6px 14px; font-size:13px; transition:all .2s;
    }
    .btn-reject:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(231,74,59,.4); }
    .foto-thumb { width:60px; height:60px; object-fit:cover; border-radius:8px; cursor:pointer; transition:transform .2s; }
    .foto-thumb:hover { transform:scale(1.1); }
    .empty-state { text-align:center; padding:60px 20px; color:#aaa; }
    .empty-state i { font-size:4rem; margin-bottom:16px; }
</style>
@endpush

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-calendar-check text-primary mr-2"></i>Manajemen Izin Absensi
    </h1>
    <span class="badge badge-pill badge-primary p-2">
        {{ $izinList->count() }} Pengajuan Pending
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card izin-card">
    <div class="card-header py-3 d-flex align-items-center">
        <i class="fas fa-list text-primary mr-2"></i>
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Izin</h6>
    </div>
    <div class="card-body p-0">
        @if($izinList->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Anggota</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Bukti Foto</th>
                        <th>Diajukan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($izinList as $i => $izin)
                    <tr>
                        <td class="align-middle">{{ $i + 1 }}</td>
                        <td class="align-middle">
                            <div class="font-weight-bold">{{ $izin->user->name }}</div>
                            <div class="text-muted small">{{ $izin->user->email }}</div>
                        </td>
                        <td class="align-middle font-weight-bold">{{ $izin->form->judul }}</td>
                        <td class="align-middle">
                            <i class="fas fa-calendar text-muted mr-1"></i>
                            {{ \Carbon\Carbon::parse($izin->form->tanggal)->format('d M Y') }}
                        </td>
                        <td class="align-middle" style="max-width:200px;" title="{{ $izin->alasan }}">
                            {{ \Illuminate\Support\Str::limit($izin->alasan, 60) }}
                        </td>
                        <td class="align-middle">
                            @if($izin->foto_path)
                                <a href="{{ Storage::url($izin->foto_path) }}" target="_blank">
                                    <img src="{{ Storage::url($izin->foto_path) }}" class="foto-thumb" alt="Bukti">
                                </a>
                            @else
                                <span class="text-muted small"><i class="fas fa-image mr-1"></i>Tidak ada</span>
                            @endif
                        </td>
                        <td class="align-middle text-muted small">
                            {{ $izin->created_at->diffForHumans() }}
                        </td>
                        <td class="align-middle text-center">
                            <div class="d-flex justify-content-center" style="gap:6px;">
                                <form method="POST" action="{{ route('sekretaris.izin.approve', $izin->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-approve">
                                        <i class="fas fa-check mr-1"></i>Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('sekretaris.izin.reject', $izin->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-reject">
                                        <i class="fas fa-times mr-1"></i>Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-check text-success"></i>
            <h5>Tidak Ada Pengajuan Pending</h5>
            <p>Semua pengajuan izin sudah diproses.</p>
        </div>
        @endif
    </div>
</div>
@endsection
