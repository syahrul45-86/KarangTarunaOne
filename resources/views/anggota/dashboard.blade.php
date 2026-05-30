@extends('anggota.layouts.master')

@section('title', 'Dashboard Anggota')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Selamat Datang, {{ $anggota->name }}</h1>
    <a href="{{ route('anggota.qrcode.show') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-qrcode fa-sm text-white-50"></i> Lihat QR Code Saya
    </a>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Absensi Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kehadiran</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $persentaseHadir }}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentaseHadir }}%" aria-valuenow="{{ $persentaseHadir }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-muted mt-2">
                            Hadir: {{ $totalHadir }} / {{ $totalKegiatan }} Kegiatan
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arisan Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Arisan ({{ $tahunArisan->tahun ?? date('Y') }})</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $persentaseBayarArisan }}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseBayarArisan }}%" aria-valuenow="{{ $persentaseBayarArisan }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-muted mt-2">
                            Sudah bayar: {{ $sudahBayarArisan }} / {{ $totalBulanArisan }} Bulan
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Denda Belum Bayar Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Denda (Belum Bayar)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</div>
                        <div class="text-xs text-muted mt-2">
                            Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Iuran Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Iuran Arisan per Bulan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($iuranArisan, 0, ',', '.') }}</div>
                        <div class="text-xs text-muted mt-2">
                            {{ $anggota->rt->nama_rt ?? '-' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Content Row -->
<div class="row">

    <!-- Riwayat Kehadiran Terakhir -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Kegiatan</h6>
            </div>
            <div class="card-body">
                @if($kegiatanTerbaru->isEmpty())
                <p class="text-muted text-center mb-0">Belum ada kegiatan RT.</p>
                @else
                <ul class="list-group list-group-flush mb-3">
                    @foreach($kegiatanTerbaru as $kegiatan)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <h6 class="mb-0 font-weight-bold">{{ $kegiatan->judul }}</h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</small>
                        </div>
                        @if($kegiatan->sudah_hadir)
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> Hadir</span>
                        @elseif($kegiatan->izin)
                            @if($kegiatan->izin->status === 'approved')
                                <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-envelope-open-text"></i> Izin</span>
                            @elseif($kegiatan->izin->status === 'pending')
                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-hourglass-half"></i> Izin (Menunggu)</span>
                            @else
                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-times"></i> Izin Ditolak</span>
                            @endif
                        @else
                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times"></i> Tidak Hadir</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
                <div class="d-flex justify-content-center">
                    {{ $kegiatanTerbaru->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Denda -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Riwayat Denda (Absensi & Kegiatan)</h6>
            </div>
            <div class="card-body">
                @if($riwayatDenda->isEmpty())
                <p class="text-muted text-center mb-0">Kamu tidak memiliki riwayat denda. Pertahankan!</p>
                @else
                <ul class="list-group list-group-flush">
                    @foreach($riwayatDenda as $denda)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <h6 class="mb-0 font-weight-bold text-gray-800">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</h6>
                            <small class="text-muted">
                                <span class="text-capitalize">{{ $denda->jenis }}</span>
                                @if($denda->alasan) - {{ Str::limit($denda->alasan, 30) }} @endif
                            </small>
                        </div>
                        @if($denda->status == 'lunas')
                        <span class="badge badge-success px-2 py-1">Lunas</span>
                        @else
                        <span class="badge badge-danger px-2 py-1">Belum Bayar</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Content Row Tunggakan -->
<div class="row">
    <!-- Tunggakan Arisan -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-warning">Tunggakan Arisan (Belum Dibayar)</h6>
                <span class="badge badge-danger px-3 py-2" style="font-size: 0.9rem;">Total Tunggakan: Rp {{ number_format($totalNominalTunggakanArisan, 0, ',', '.') }}</span>
            </div>
            <div class="card-body">
                @if($tunggakanArisanList->isEmpty())
                <p class="text-muted text-center mb-0">Hebat! Kamu tidak punya tunggakan arisan.</p>
                @else
                <ul class="list-group list-group-flush">
                    @foreach($tunggakanArisanList as $tunggakan)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <h6 class="mb-0 font-weight-bold text-gray-800">Arisan {{ \Carbon\Carbon::parse($tunggakan->tanggal)->translatedFormat('F Y') }}</h6>
                            <small class="text-muted">Nominal Iuran: Rp {{ number_format($iuranArisan, 0, ',', '.') }}</small>
                        </div>
                        <span class="badge badge-warning px-2 py-1 text-dark">Belum Bayar</span>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-3 text-center">
                    <small class="text-muted">* Menampilkan maksimal 5 tunggakan terbaru.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('anggota.layouts.footer')
@endsection