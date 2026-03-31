@extends('admin.layouts.master')

@section('title','Rekap Per Anggota')

@section('content')
<div class="container mt-4">

    <a href="{{ route('admin.denda.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <h3>Rekap Denda Per Anggota</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Total Kasus</th>
                <th>Belum Bayar</th>
                <th>Total Denda (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['kasus'] }}</td>
                <td>{{ $row['belum'] }}</td>
                <td>Rp {{ number_format($row['total_denda'],0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
