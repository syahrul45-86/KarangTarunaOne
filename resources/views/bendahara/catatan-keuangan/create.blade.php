@extends('bendahara.layouts.master')

@section('title','Tambah Catatan Keuangan')

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
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        padding: 25px 30px;
        color: white;
    }

    .bendahara-form-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
        color: #e0e7ff;
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
        border-color: #4f46e5;
        background: white;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .bendahara-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
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
        background: #4f46e5;
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
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
    }
</style>

<div class="container-fluid mt-4 mb-5" style="max-width: 800px;">
    
    <div class="bendahara-form-wrapper">
        <div class="bendahara-form-header">
            <h3><i class="fas fa-plus-circle mr-2"></i> Tambah Transaksi</h3>
            <p>Catat arus kas pemasukan atau pengeluaran baru.</p>
        </div>

        <div class="bendahara-form-body">
            <form action="{{ route('bendahara.storeKeuangan') }}" method="POST">
                @csrf
                <input type="hidden" name="rt_id" value="{{ auth()->user()->rt_id }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="bendahara-form-group">
                            <label class="bendahara-form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" 
                                   class="bendahara-input @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bendahara-form-group">
                            <label class="bendahara-form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                            <select name="jenis" class="bendahara-input bendahara-select" required>
                                <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bendahara-form-group">
                    <label class="bendahara-form-label">Keterangan / Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" name="keterangan" 
                           class="bendahara-input @error('keterangan') is-invalid @enderror" 
                           placeholder="Contoh: Pembayaran kas bulan Mei..."
                           value="{{ old('keterangan') }}" required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bendahara-form-group">
                    <label class="bendahara-form-label">Nominal Uang <span class="text-danger">*</span></label>
                    <div class="input-group bendahara-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text-custom">Rp</span>
                        </div>
                        <input type="number" name="jumlah" 
                               class="form-control bendahara-input @error('jumlah') is-invalid @enderror" 
                               placeholder="0" min="0" value="{{ old('jumlah') }}" required>
                    </div>
                    @error('jumlah')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bendahara-actions">
                    <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn-cancel">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
