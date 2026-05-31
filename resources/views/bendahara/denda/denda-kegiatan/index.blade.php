@extends('bendahara.layouts.master')

@section('title', 'Daftar Denda')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Denda Kegiatan</h3>
        <a href="{{ route('bendahara.denda.create') }}" class="btn btn-primary shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; border-radius: 8px; padding: 10px 20px;">
            <i class="fas fa-plus mr-1"></i> Tambah Denda
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success border-left-success shadow-sm">{{ session('success') }}</div>
    @endif

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
                            <input type="text"
                                   name="status"
                                   value="{{ $item->status }}"
                                   class="form-control"
                                   placeholder="Cth: Cicil 5000" required>
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
