@extends('admin.layouts.master')

@section('title','Grafik Arisan')

@section('content')

<div class="container mt-4">

    <a href="{{ route('admin.rekap.arisan.index') }}" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    <h3>📊 Grafik Pembayaran Arisan Tahun {{ $tahun->tahun }}</h3>

    <canvas id="chartArisan"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = {!! json_encode(array_column($grafikData,'tanggal')) !!};
    const bayar = {!! json_encode(array_column($grafikData,'bayar')) !!};

    new Chart(document.getElementById('chartArisan'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pembayar',
                data: bayar
            }]
        }
    });
</script>

@endsection
