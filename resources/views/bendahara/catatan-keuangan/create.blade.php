@extends('bendahara.layouts.master')

@section('title','Tambah Catatan Keuangan')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">Tambah Catatan Keuangan</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('bendahara.storeKeuangan') }}" method="POST">
                @csrf

                <input type="hidden" name="rt_id" value="{{ auth()->user()->rt_id }}">

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal"
                           class="form-control @error('tanggal') is-invalid @enderror"
                           required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan"
                           class="form-control @error('keterangan') is-invalid @enderror"
                           required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Transaksi --}}
                <div class="mb-3">
                    <label class="form-label">Jenis Transaksi</label>
                    <select name="jenis" class="form-control" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>


                </div>

                {{-- Nominal --}}
                <div class="mb-3">
                    <label class="form-label">Nominal</label>
                    <input type="number" name="jumlah"
                           class="form-control @error('jumlah') is-invalid @enderror"
                           placeholder="Masukkan jumlah uang"
                           min="0"
                           required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Catatan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
