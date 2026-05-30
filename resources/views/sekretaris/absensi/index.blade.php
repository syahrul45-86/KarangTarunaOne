@extends('sekretaris.layouts.master')

@section('title', 'Daftar Absensi')

@section('content')

<div class="absensi-container">
    <div class="header-section">
        <h3>Daftar Form Absensi</h3>
        <a href="{{ route('sekretaris.absensi.create') }}" class="btn-create">
            Buat Form Absensi
        </a>
    </div>

    @if($forms->count() > 0)

    <div class="table-container">
        <table class="desktop-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @foreach($forms as $form)
                <tr>
                    <td>{{ $form->judul }}</td>
                    <td>{{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}</td>
                    <td>{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</td>
                    <td>

                        <!-- SCAN QR -->
                        <a href="{{ route('sekretaris.absensi.scan', $form->id) }}"
                           class="btn-action btn-scan">
                            Scan QR User
                        </a>

                        <!-- CEK ABSENSI -->
                        <a href="{{ route('sekretaris.absensi.cek', $form->id) }}"
                           class="btn-action btn-cek">
                            Cek Absensi
                        </a>
                        
                        <!-- IZIN ABSENSI -->
                        <a href="{{ route('sekretaris.izin.list') }}"
                           class="btn-action" style="background-color: #f6c23e; color: #fff; position: relative;">
                            Izin Absensi
                            @if($form->pending_izin_count > 0)
                                <span class="badge badge-danger" style="position: absolute; top: -5px; right: -5px; border-radius: 50%; padding: 4px 6px; font-size: 0.7rem;">
                                    {{ $form->pending_izin_count }}
                                </span>
                            @endif
                        </a>
           
                        <!-- PROSES DENDA -->
                        <a href="{{ route('sekretaris.absensi.proses_denda', $form->id) }}"
                           class="btn-action btn-denda"
                           onclick="return confirm('Proses denda otomatis?')">
                            Proses Denda
                        </a>

                        <!-- HAPUS -->
                        <form action="{{ route('sekretaris.absensi.delete', $form->id) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus form absensi ini?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-action btn-delete">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Mobile Cards View -->
        <div class="mobile-cards">
            @foreach($forms as $form)
            <div class="absensi-card">
                <div class="card-header">
                    <h4 class="card-title">{{ $form->judul }}</h4>
                    <span class="card-badge">{{ \Carbon\Carbon::parse($form->tanggal)->format('d M') }}</span>
                </div>
                
                <div class="card-info">
                    <div class="info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="info-label">Tanggal:</span>
                        <span>{{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}</span>
                    </div>
                    
                    <div class="info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="info-label">Waktu:</span>
                        <span>{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('sekretaris.absensi.scan', $form->id) }}" class="btn-action btn-scan">Scan QR</a>
                    <a href="{{ route('sekretaris.absensi.cek', $form->id) }}" class="btn-action btn-cek">Cek Absensi</a>
                    <a href="{{ route('sekretaris.izin.list') }}" class="btn-action" style="background-color: #f6c23e; color: #fff; position: relative;">
                        Izin
                        @if($form->pending_izin_count > 0)
                            <span class="badge badge-danger" style="position: absolute; top: -5px; right: -5px; border-radius: 50%; padding: 4px 6px; font-size: 0.7rem;">
                                {{ $form->pending_izin_count }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('sekretaris.absensi.proses_denda', $form->id) }}" class="btn-action btn-denda" onclick="return confirm('Proses denda otomatis?')">Denda</a>
                    <form action="{{ route('sekretaris.absensi.delete', $form->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Yakin hapus form absensi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" style="width: 100%;">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @else
        <div class="empty-state">
            <h4>Belum Ada Form Absensi</h4>
            <p>Klik tombol "Buat Form Absensi" untuk membuat form baru</p>
        </div>
    @endif
</div>

@endsection
