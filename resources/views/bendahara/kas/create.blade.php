@extends('bendahara.layouts.master')

@section('title', 'Tambah Setoran Kas')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary mr-2"></i>Tambah Setoran Kas
        </h1>
        <a href="{{ route('bendahara.kas.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow-lg border-0 rounded-lg mb-5">
                <div class="card-header py-4 bg-primary text-white border-0 rounded-top-lg">
                    <h6 class="m-0 font-weight-bold">Form Input Setoran Kas Anggota</h6>
                    <p class="text-white-50 small mb-0 mt-1 text-uppercase tracking-wider">Lengkapi data setoran di bawah ini</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('bendahara.kas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="label-custom font-weight-bold text-gray-700 mb-2">Pilih Anggota</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user-friends text-primary"></i></span>
                                    </div>
                                    <select name="user_id" class="form-control form-control-lg border-left-0 @error('user_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>Cari Nama Anggota...</option>
                                        @foreach($anggota as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ strtoupper($user->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="label-custom font-weight-bold text-gray-700 mb-2">Nominal Setoran</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 font-weight-bold">Rp</span>
                                    </div>
                                    <input type="number" name="nominal" class="form-control form-control-lg border-left-0 @error('nominal') is-invalid @enderror" 
                                           placeholder="Contoh: 10000" value="{{ old('nominal') }}" required>
                                </div>
                                <small class="text-muted italic">Tanpa titik atau koma.</small>
                                @error('nominal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="label-custom font-weight-bold text-gray-700 mb-2">Tanggal Setoran</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                                    </div>
                                    <input type="date" name="tanggal" class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                </div>
                                @error('tanggal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="label-custom font-weight-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="form-control shadow-sm p-3" rows="3" 
                                          placeholder="Catatan tambahan (misal: Kas Bulan April)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light px-4 mr-2 rounded-pill font-weight-bold">Reset</button>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill font-weight-bold shadow-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Setoran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-primary { box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4); }
    .label-custom { font-size: 0.9rem; letter-spacing: 0.5px; }
    .form-control:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1); }
    .tracking-wider { letter-spacing: 1px; }
    .rounded-top-lg { border-top-left-radius: 0.75rem !important; border-top-right-radius: 0.75rem !important; }
</style>
@endsection
