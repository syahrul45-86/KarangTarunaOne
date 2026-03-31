@extends('bendahara.layouts.master')

@section('title','Catatan Keuangan')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Catatan Keuangan Bendahara</h3>

        {{-- CEK apakah sudah ada saldo awal atau belum --}}
        @if ($bendaharas->count() == 0)
            <a href="{{ route('bendahara.create') }}" class="btn btn-primary">
                Tambah Saldo Awal
            </a>
        @else
            <a href="{{ route('bendahara.create') }}" class="btn btn-primary">
                Tambah Catatan
            </a>
        @endif
    </div>

    {{-- Tampilkan saldo akhir terakhir jika ada --}}
    @if ($bendaharas->count() > 0)
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5>
            Saldo Akhir:
            <span class="fw-bold text-primary">
                Rp {{ number_format($bendaharas->last()->saldo_akhir, 2, ',', '.') }}
            </span>
        </h5>

        <a href="{{ route('bendahara.edit_saldo_awal') }}" class="btn btn-info">
            Edit Saldo Awal
        </a>
    </div>
    @endif

    <form method="GET" action="{{ route('bendahara.catatan-keuangan.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="date" name="tanggal"
                   value="{{ request('tanggal') }}"
                   class="form-control"
                   placeholder="Cari tanggal...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </div>
</form>

    {{-- Tabel Catatan --}}
    <table class="table table-bordered table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Saldo Awal</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Saldo Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bendaharas as $row)
            <tr>
                <td data-label="No">{{ $loop->iteration }}</td>
                <td data-label="Tanggal">{{ $row->tanggal }}</td>
                <td data-label="Keterangan">{{ $row->keterangan }}</td>
                <td data-label="Saldo Awal">Rp {{ number_format($row->saldo_awal, 0, ',', '.') }}</td>
                <td data-label="Pemasukan">Rp {{ number_format($row->pemasukan, 0, ',', '.') }}</td>
                <td data-label="Pengeluaran">Rp {{ number_format($row->pengeluaran, 0, ',', '.') }}</td>
                <td data-label="Saldo Akhir">Rp {{ number_format($row->saldo_akhir, 0, ',', '.') }}</td>
                    <td>
                    <a href="{{ route('bendahara.edit', $row->id) }}"
                       class="btn btn-warning btn-sm">
                       Edit
                    </a>

                    <form action="{{ route('bendahara.destroy', $row->id) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin menghapus catatan ini?')">
                                Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
               {{-- Tombol utama: jika belum ada data sama sekali -> tombol Tambah Saldo Awal
                    jika sudah ada data -> tombol Tambah Catatan --}}
                <div class="d-flex justify-content-between mb-3">
                    <h3>Catatan Keuangan Bendahara</h3>

                    @if (!$hasAny)
                        {{-- Belum ada data sama sekali untuk RT ini --}}
                        <a href="{{ route('bendahara.create') }}" class="btn btn-primary">
                            Tambah Saldo Awal
                        </a>
                    @else
                        {{-- Sudah ada data (meskipun filter mungkin kosong) --}}
                        <a href="{{ route('bendahara.create') }}" class="btn btn-primary">
                            Tambah Catatan
                        </a>
                    @endif
                </div>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
