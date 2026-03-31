@extends('bendahara.layouts.master')

@section('title', 'Daftar Denda')

@section('content')
<div class="container">

    <h3 class="mb-3">Denda Kegiatan</h3>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORM TAMBAH --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Denda Kegiatan</div>
        <div class="card-body">

            <form action="{{ route('bendahara.denda.kegiatan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="number" name="jumlah_denda"
                               class="form-control"
                               placeholder="Jumlah denda"
                               required>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-primary w-100">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-header">Data Denda Kegiatan</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @foreach($denda as $item)
                <tr>
                    <form action="{{ route('bendahara.denda.kegiatan.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <td>{{ $item->user->name }}</td>

                        <td>
                            <input type="number"
                                   name="jumlah_denda"
                                   value="{{ $item->jumlah_denda }}"
                                   class="form-control">
                        </td>

                        <td>
                            <select name="status" class="form-control">
                                <option value="belum_bayar"
                                    {{ $item->status == 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Bayar
                                </option>
                                <option value="lunas"
                                    {{ $item->status == 'lunas' ? 'selected' : '' }}>
                                    Lunas
                                </option>
                            </select>
                        </td>

                        <td class="d-flex gap-2">

                            <button class="btn btn-warning btn-sm">
                                Update
                            </button>
                    </form>

                            <form action="{{ route('bendahara.denda.kegiatan.destroy', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>

                        </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
