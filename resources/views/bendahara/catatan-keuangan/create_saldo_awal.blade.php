@extends('bendahara.layouts.master')

@section('title','Dashboard')

@section('content')

<body>
<div class="container mt-4">

    <h3 class="mb-4">Edit Saldo Awal</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('bendahara.storeSaldoAwal') }}" method="POST">
                @csrf

                <input type="hidden" name="rt_id" value="{{ auth()->user()->rt_id }}">

                <label>Saldo Awal Pertama</label>
                <input type="number" name="saldo_awal" class="form-control" required>

                <button type="submit" class="btn btn-primary">Simpan Saldo Awal</button>
            </form>


        </div>
    </div>

</div>




</body>
@include('bendahara.layouts.footer')
@endsection
