@extends('bendahara.layouts.master')

@section('title','Edit Transaksi')

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
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        padding: 25px 30px;
        color: white;
    }

    .bendahara-form-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
        color: #fffbeb;
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
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
        background: #f59e0b;
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
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.25);
    }
</style>

<div class="container-fluid mt-4 mb-5" style="max-width: 800px;">
    
    <div class="bendahara-form-wrapper">
        <div class="bendahara-form-header">
            <h3><i class="fas fa-edit mr-2"></i> Edit Transaksi</h3>
            <p>Perbarui rincian catatan pemasukan atau pengeluaran.</p>
        </div>

        <div class="bendahara-form-body">
            <form action="{{ route('bendahara.update', $bendahara->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bendahara-form-group">
                    <label class="bendahara-form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" 
                           class="bendahara-input @error('tanggal') is-invalid @enderror" 
                           value="{{ old('tanggal', $bendahara->tanggal) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bendahara-form-group">
                    <label class="bendahara-form-label">Keterangan / Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" name="keterangan" 
                           class="bendahara-input @error('keterangan') is-invalid @enderror" 
                           value="{{ old('keterangan', $bendahara->keterangan) }}" required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="bendahara-form-group">
                            <label class="bendahara-form-label">Nominal Pemasukan</label>
                            <div class="input-group bendahara-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text-custom">Rp</span>
                                </div>
                                <input type="number" name="pemasukan" 
                                       class="form-control bendahara-input @error('pemasukan') is-invalid @enderror" 
                                       min="0" value="{{ old('pemasukan', $bendahara->pemasukan) }}">
                            </div>
                            <small class="text-muted mt-1 d-block">Kosongkan jika ini pengeluaran.</small>
                            @error('pemasukan')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bendahara-form-group">
                            <label class="bendahara-form-label">Nominal Pengeluaran</label>
                            <div class="input-group bendahara-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text-custom">Rp</span>
                                </div>
                                <input type="number" name="pengeluaran" 
                                       class="form-control bendahara-input @error('pengeluaran') is-invalid @enderror" 
                                       min="0" value="{{ old('pengeluaran', $bendahara->pengeluaran) }}">
                            </div>
                            <small class="text-muted mt-1 d-block">Kosongkan jika ini pemasukan.</small>
                            @error('pengeluaran')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bendahara-actions">
                    <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="btn-cancel">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Perbarui Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
