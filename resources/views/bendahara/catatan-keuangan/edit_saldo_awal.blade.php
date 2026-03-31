@extends('bendahara.layouts.master')

@section('title','Dashboard')

@section('content')

<body>
<div class="container mt-4">

    <h3 class="mb-4">Edit Saldo Awal</h3>

    <div class="card">
        <div class="card-body">

           <form action="{{ route('bendahara.update_saldo_awal') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Wajib: kirim rt_id --}}
    <input type="hidden" name="rt_id" value="{{ auth()->user()->rt_id }}">

    <div class="mb-3">
        <label class="form-label">Saldo Awal Baru</label>
        <input type="number"
               name="saldo_awal"
               value="{{ old('saldo_awal', $saldo_awal) }}"
               class="form-control @error('saldo_awal') is-invalid @enderror"
               required
               min="0">
        @error('saldo_awal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <p class="text-muted mb-3">
        * Mengubah saldo awal akan otomatis menghitung ulang semua saldo transaksi.
    </p>

    <div class="d-flex justify-content-between">
        <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn btn-secondary">
            Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            Simpan Perubahan
        </button>
    </div>
</form>


        </div>
    </div>

</div>




</body>
@include('bendahara.layouts.footer')
@endsection
