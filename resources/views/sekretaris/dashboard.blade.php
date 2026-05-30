@extends('sekretaris.layouts.master')

@section('title', 'Dashboard Sekretaris')

@section('content')
<div class="row">
    <!-- Total Anggota Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAnggota }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Attendance Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hadir (Kegiatan Terakhir)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentAttendance }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Attendance Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Kehadiran
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $averageAttendance }}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $averageAttendance }}%" aria-valuenow="{{ $averageAttendance }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming/Latest Form Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kegiatan Terdekat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $latestForm ? $latestForm->judul : 'Tidak Ada' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Forms -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kegiatan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentForms as $form)
                            <tr>
                                <td>{{ $form->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}</td>
                                <td>{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</td>
                                <td>
                                    <a href="{{ route('sekretaris.absensi.qr', $form->id) }}" class="btn btn-primary btn-sm btn-circle" title="QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('sekretaris.absensi.scan', $form->id) }}" class="btn btn-info btn-sm btn-circle" title="Scan QR">
                                        <i class="fas fa-camera"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('sekretaris.absensi.create') }}" class="btn btn-primary btn-block mb-3 py-3">
                    <i class="fas fa-plus fa-lg mr-2"></i> Buat Form Absensi
                </a>
                <a href="{{ route('sekretaris.catatan.index') }}" class="btn btn-info btn-block mb-3 py-3">
                    <i class="fas fa-list fa-lg mr-2"></i> Kelola Arisan
                </a>
                <a href="{{ route('sekretaris.spin.index') }}" class="btn btn-warning btn-block mb-3 py-3">
                    <i class="fas fa-sync fa-lg mr-2"></i> Spin Arisan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
