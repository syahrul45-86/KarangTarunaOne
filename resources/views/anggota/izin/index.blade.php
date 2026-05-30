@extends(auth()->user()->role . '.layouts.master')

@section('title', 'Izin Absensi')

@push('styles')
<style>
    .izin-hero {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 16px;
        color: #fff;
        padding: 28px 32px;
        margin-bottom: 28px;
        box-shadow: 0 8px 30px rgba(78, 115, 223, .35);
    }

    .izin-hero h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .izin-hero p {
        opacity: .85;
        font-size: .95rem;
        margin: 0;
    }

    .form-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .07);
        transition: transform .2s, box-shadow .2s;
        overflow: hidden;
    }

    .form-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0, 0, 0, .12);
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #f8f9fc, #eaecf4);
        border-bottom: 1px solid #e3e6f0;
        padding: 14px 18px;
    }

    .kegiatan-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(78, 115, 223, .1);
        color: #4e73df;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-izin {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: .9rem;
        transition: all .2s;
        width: 100%;
    }

    .btn-izin:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(78, 115, 223, .45);
        color: #fff;
    }

    .btn-izin:disabled {
        opacity: .6;
        transform: none;
        cursor: not-allowed;
    }

    .history-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .07);
        overflow: hidden;
    }

    .badge-pending {
        background: #f6c23e;
        color: #000;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-approved {
        background: #1cc88a;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-rejected {
        background: #e74a3b;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #b7bac3;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 12px;
        display: block;
    }

    .alert-custom {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="izin-hero">
    <h2><i class="fas fa-calendar-check mr-2"></i>Izin Absensi</h2>
    <p>Ajukan izin kehadiran untuk kegiatan yang tidak bisa Anda hadiri. Izin yang disetujui tidak akan dikenakan denda.</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    {{-- Kiri: Daftar Kegiatan --}}
    <div class="col-lg-7 mb-4">
        <h5 class="font-weight-bold text-gray-700 mb-3">
            <i class="fas fa-list-alt text-primary mr-2"></i>Kegiatan Mendatang
        </h5>

        @forelse($activeForms as $form)
        @php
        $sudahIzin = $myIzin->firstWhere('form_id', $form->id);
        $jamMulai = \Carbon\Carbon::parse($form->tanggal . ' ' . $form->jam_mulai);
        $jamSelesai = \Carbon\Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);
        @endphp
        <div class="card form-card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="font-weight-bold text-gray-800">{{ $form->judul }}</div>
                    <div class="text-muted small mt-1">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}
                        &nbsp;|&nbsp;
                        <i class="fas fa-clock mr-1"></i>
                        {{ $form->jam_mulai }} – {{ $form->jam_selesai }}
                    </div>
                </div>
                <span class="kegiatan-badge">
                    <i class="fas fa-map-marker-alt"></i>Kegiatan RT
                </span>
            </div>
            <div class="card-body">
                @if($sudahIzin)
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted small">
                        <i class="fas fa-info-circle mr-1"></i>Izin sudah diajukan
                    </span>
                    @if($sudahIzin->status === 'pending')
                    <span class="badge-pending"><i class="fas fa-hourglass-half mr-1"></i>Menunggu</span>
                    @elseif($sudahIzin->status === 'approved')
                    <span class="badge-approved"><i class="fas fa-check mr-1"></i>Disetujui</span>
                    @else
                    <span class="badge-rejected"><i class="fas fa-times mr-1"></i>Ditolak</span>
                    @endif
                </div>
                @else
                <form action="{{ route('user.izin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">Alasan Izin <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control form-control-sm" rows="2"
                            placeholder="Contoh: Ada keperluan kerja lembur / acara keluarga..."
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Bukti Foto <span class="text-muted">(opsional)</span></label>
                        <input type="file" name="foto_bukti" accept="image/*" class="form-control-file form-control-sm">
                        <small class="text-muted">Maks. 4 MB (JPG/PNG)</small>
                    </div>
                    <button type="submit" class="btn btn-izin">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan Izin
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="card form-card">
            <div class="card-body empty-state">
                <i class="fas fa-calendar-times text-muted"></i>
                <h6>Tidak Ada Kegiatan Mendatang</h6>
                <p class="small">Belum ada form absensi yang terjadwal untuk hari ini atau ke depan.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Kanan: Riwayat Izin --}}
    <div class="col-lg-5 mb-4">
        <h5 class="font-weight-bold text-gray-700 mb-3">
            <i class="fas fa-history text-primary mr-2"></i>Riwayat Izin Saya
        </h5>
        <div class="card history-card">
            <div class="card-body p-0">
                @forelse($myIzin as $izin)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="font-weight-bold small">{{ $izin->form->judul }}</div>
                            <div class="text-muted" style="font-size:11px;">
                                {{ \Carbon\Carbon::parse($izin->form->tanggal)->format('d M Y') }}
                            </div>
                            <div class="text-muted mt-1" style="font-size:12px;">
                                "{{ \Illuminate\Support\Str::limit($izin->alasan, 50) }}"
                            </div>
                        </div>
                        <div class="ml-2">
                            @if($izin->status === 'pending')
                            <span class="badge-pending"><i class="fas fa-hourglass-half mr-1"></i>Pending</span>
                            @elseif($izin->status === 'approved')
                            <span class="badge-approved"><i class="fas fa-check mr-1"></i>Disetujui</span>
                            @else
                            <span class="badge-rejected"><i class="fas fa-times mr-1"></i>Ditolak</span>
                            @endif
                        </div>
                    </div>
                    @if($izin->foto_path)
                    <div class="mt-2">
                        <a href="{{ Storage::url($izin->foto_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:11px; border-radius:8px;">
                            <i class="fas fa-image mr-1"></i>Lihat Bukti
                        </a>
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox text-muted"></i>
                    <p class="small">Belum ada riwayat izin.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@include(auth()->user()->role . '.layouts.footer')
@endsection