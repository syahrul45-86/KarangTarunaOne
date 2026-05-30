@extends('sekretaris.layouts.master')

@section('title', 'Rekapitulasi Tunggakan Arisan')

@section('content')
<div class="container-fluid">
    <!-- Header Page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">📊 Rekapitulasi Tunggakan Arisan</h1>
        <a href="{{ route('sekretaris.catatan.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Tahun
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Outstanding Sessions -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Tunggakan (Sesi)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOutstandingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Delinquent member -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tunggakan Terbanyak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mostDelinquent ? $mostDelinquent->name : '-' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Note Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Info Sistem</div>
                            <div class="small text-gray-600">
                                Anggota yang sudah lunas tidak muncul dalam daftar "Perlu Perhatian".
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info border-left-info shadow fade show" role="alert">
            <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Daftar Anggota dengan Tunggakan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="recapTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Member</th>
                            <th class="text-center">Total Tunggakan (Sesi)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members->sortByDesc('unpaid_count') as $member)
                            @if($member->unpaid_count > 0)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-3 bg-primary text-white">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $member->name }}</div>
                                            <div class="small text-muted">{{ strtoupper($member->role) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-pill badge-danger py-2 px-3 font-weight-bold">
                                        {{ $member->unpaid_count }} Sesi
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="progress progress-sm" style="height: 10px;">
                                        @php 
                                            $totalDatesCount = \App\Models\ArisanTanggal::count();
                                            $completedPercent = $totalDatesCount > 0 ? ((\App\Models\ArisanTanggal::count() - $member->unpaid_count) / $totalDatesCount) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $completedPercent }}%" aria-valuenow="{{ $completedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ round($completedPercent) }}% Terbayar</small>
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('sekretaris.arisan.pay-all') }}" method="POST" class="d-inline pay-all-form">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <button type="button" class="btn btn-success btn-sm btn-icon-split shadow-sm btn-pay-all">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                            <span class="text">LUNASKAN SEMUA</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                    <h5 class="text-gray-600">Luar Biasa! Semua anggota sudah melunasi arisan.</h5>
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
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
    }
</style>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.btn-pay-all').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let name = $(this).closest('tr').find('.font-weight-bold').first().text();
            
            Swal.fire({
                title: 'Konfirmasi Pelunasan',
                text: "Apakah Anda yakin ingin melunasi SEMUA sesi arisan untuk " + name + "? Tindakan ini akan membuat catatan pembayaran otomatis untuk setiap sesi yang belum dibayar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lunaskan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
