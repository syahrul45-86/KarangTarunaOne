@extends('sekretaris.layouts.master')

@section('title', 'Buat Form Absensi')

@section('content')

<div class="container mt-4">

    <h3>Buat Form Absensi</h3>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('sekretaris.absensi.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Judul Absensi</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('sekretaris.absensi.index') }}" class="btn btn-secondary">Kembali</a>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
