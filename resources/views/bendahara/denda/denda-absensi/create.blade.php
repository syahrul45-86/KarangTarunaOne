@extends('bendahara.layouts.master')

@section('title', 'Tambah Denda Manual')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Tambah Denda Manual</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('bendahara.denda.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Nama Anggota</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="mb-3">
                    <label>Alasan Denda</label>
                    <input type="text" name="alasan" class="form-control" required>
                </div> --}}

                <div class="mb-3">
                    <label>Jumlah Denda</label>
                    <input type="number" name="jumlah_denda" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('bendahara.denda.index') }}" class="btn btn-secondary">Kembali</a>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
