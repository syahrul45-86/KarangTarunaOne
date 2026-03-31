@extends('sekretaris.layouts.master')

@section('title', 'Daftar Hadir')

@section('content')

<div class="container mt-4">

    <h3 class="mb-3">Daftar Hadir: {{ $form->judul }}</h3>
    <p><b>Tanggal:</b> {{ $form->tanggal }} <br>
       <b>Waktu:</b> {{ $form->jam_mulai }} - {{ $form->jam_selesai }}</p>

    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Waktu Absen</th>
            </tr>
        </thead>

        <tbody>
            @forelse($absenList as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Belum ada yang absen.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('sekretaris.absensi.index') }}" class="btn btn-secondary mt-3">Kembali</a>

</div>

@endsection
