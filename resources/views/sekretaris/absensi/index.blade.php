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
    </div>

    @else
        <div class="empty-state">
            <h4>Belum Ada Form Absensi</h4>
            <p>Klik tombol "Buat Form Absensi" untuk membuat form baru</p>
        </div>
    @endif
</div>

@endsection
