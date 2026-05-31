@extends('bendahara.layouts.master')

@section('title', 'Edit Denda')

@push('styles')
<style>
    /* Modern form styling */
    .modern-form-container {
        max-width: 550px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    .modern-form-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: linear-gradient(90deg, #f59e0b, #eab308, #fbbf24);
    }
    .modern-form-title {
        font-weight: 800;
        font-size: 1.7rem;
        color: #1e293b;
        margin-bottom: 10px;
        text-align: center;
        letter-spacing: -0.5px;
    }
    .modern-form-subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 35px;
        font-size: 0.95rem;
    }
    .modern-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modern-input {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 14px 16px;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #f8fafc;
        height: auto;
    }
    .modern-input:focus {
        border-color: #eab308;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(234, 179, 8, 0.15);
        outline: none;
    }
    .modern-btn-submit {
        background: linear-gradient(135deg, #f59e0b 0%, #eab308 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
        font-weight: 700;
        font-size: 1.05rem;
        width: 100%;
        margin-top: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.2);
    }
    .modern-btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(245, 158, 11, 0.3);
        color: white;
    }
    .modern-btn-cancel {
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        display: block;
        text-align: center;
        margin-top: 20px;
        transition: color 0.2s;
        font-size: 0.95rem;
    }
    .modern-btn-cancel:hover {
        color: #0f172a;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="modern-form-container">
        <h3 class="modern-form-title">💳 Bayar / Cicil Denda</h3>
        <p class="modern-form-subtitle">Catat pembayaran denda untuk <b>{{ $denda->user->name }}</b></p>

        <form action="{{ route('bendahara.denda.update', $denda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="modern-label"><i class="fas fa-file-invoice-dollar text-primary"></i> Sisa Denda Saat Ini</label>
                <div class="form-control modern-input" style="background-color: #f1f5f9; font-weight: bold; color: #ef4444;">
                    Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                </div>
            </div>

            <div class="mb-4">
                <label class="modern-label"><i class="fas fa-hand-holding-usd text-success"></i> Nominal yang Dibayar (Rp)</label>
                <input type="number" name="nominal_bayar" class="form-control modern-input" placeholder="Contoh: 5000" required min="0" max="{{ $denda->jumlah_denda }}">
                <small class="text-muted mt-1 d-block">Masukkan jumlah uang yang dibayarkan. Status (Lunas/Belum Bayar) akan otomatis terupdate.</small>
            </div>

            <button type="submit" class="modern-btn-submit">
                <i class="fas fa-save mr-2"></i> Proses Pembayaran
            </button>
            <a href="{{ route('bendahara.denda.absensi') }}" class="modern-btn-cancel">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Denda
            </a>
        </form>
    </div>
</div>
@endsection
