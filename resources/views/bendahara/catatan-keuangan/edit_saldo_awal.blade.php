@extends('bendahara.layouts.master')

@section('title','Edit Saldo Awal')

@section('content')

<style>
    .bendahara-form-wrapper {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }

    .bendahara-form-header {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        padding: 25px 30px;
        color: white;
    }

    .bendahara-form-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
        color: #cffafe;
    }

    .bendahara-form-header p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .bendahara-form-body {
        padding: 30px;
    }

    .bendahara-form-group {
        margin-bottom: 25px;
    }

    .bendahara-form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }

    .bendahara-input {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 15px;
        width: 100%;
        transition: all 0.3s ease;
        color: #1e293b;
        background: #f8fafc;
    }

    .bendahara-input:focus {
        outline: none;
        border-color: #0891b2;
        background: white;
        box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.1);
    }

    .input-group-text-custom {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        padding: 12px 15px;
        font-weight: 600;
        color: #64748b;
    }

    .bendahara-input-group .bendahara-input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .bendahara-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 35px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancel {
        background: white;
        border: 2px solid #e2e8f0;
        color: #64748b;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #334155;
        text-decoration: none;
    }

    .btn-submit {
        background: #0891b2;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        background: #0e7490;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(8, 145, 178, 0.25);
    }

    .bendahara-alert {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        padding: 15px 20px;
        border-radius: 8px;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        font-size: 14px;
        line-height: 1.5;
    }
</style>

<div class="container-fluid mt-4 mb-5" style="max-width: 600px;">
    
    <div class="bendahara-form-wrapper">
        <div class="bendahara-form-header">
            <h3><i class="fas fa-edit mr-2"></i> Edit Saldo Awal</h3>
            <p>Perbarui jumlah saldo awal kas RT Anda.</p>
        </div>

        <div class="bendahara-form-body">
            <div class="bendahara-alert">
                <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: #f59e0b;"></i>
                <div>
                    <strong>Perhatian:</strong> Mengubah saldo awal akan otomatis menghitung ulang semua saldo akhir pada transaksi sebelumnya.
                </div>
            </div>

            <form action="{{ route('bendahara.update_saldo_awal') }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="rt_id" value="{{ auth()->user()->rt_id }}">

                <div class="bendahara-form-group">
                    <label class="bendahara-form-label">Saldo Awal Baru <span class="text-danger">*</span></label>
                    <div class="input-group bendahara-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text-custom">Rp</span>
                        </div>
                        <input type="number" name="saldo_awal" 
                               class="form-control bendahara-input @error('saldo_awal') is-invalid @enderror" 
                               placeholder="0" min="0" value="{{ old('saldo_awal', $saldo_awal ?? 0) }}" required>
                    </div>
                    @error('saldo_awal')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bendahara-actions">
                    <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn-cancel">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@include('bendahara.layouts.footer')
@endsection
