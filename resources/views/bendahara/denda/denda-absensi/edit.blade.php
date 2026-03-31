@extends('bendahara.layouts.master')

@section('title', 'Edit Denda')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Edit Denda</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('bendahara.denda.update', $denda->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Anggota</label>
                    <select name="user_id" class="form-control" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $user->id == $denda->user_id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
{{--
                <div class="mb-3">
                    <label>Alasan</label>
                    <input type="text" name="alasan" class="form-control" value="{{ $denda->alasan }}" required>
                </div> --}}

                <div class="mb-3">
                    <label>Jumlah Denda</label>
                    <input type="number" name="jumlah_denda" class="form-control" value="{{ $denda->jumlah_denda }}" required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="belum_bayar" {{ $denda->status == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="lunas" {{ $denda->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
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
