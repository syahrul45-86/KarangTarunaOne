@extends('bendahara.layouts.master')

@section('title', 'Edit Transaksi Tabungan')

@push('styles')
<style>
    /* ===== TABUNGAN CREATE/EDIT PAGE - PREMIUM + MOBILE RESPONSIVE ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .tb-form-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .tb-form-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 20px;
        padding: 22px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3);
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .tb-form-header::before {
        content: ''; position: absolute; top: -45px; right: -45px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,0.08); pointer-events: none;
    }
    .tb-form-icon {
        width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff;
        margin-right: 13px; flex-shrink: 0; position: relative; z-index: 1;
    }
    .tb-form-title { font-size: 1.35rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.3px; }
    .tb-form-sub { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin: 2px 0 0; }
    .btn-back-tb {
        background: rgba(255,255,255,0.15); color: #fff; border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 11px; padding: 9px 18px; font-size: 0.82rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 7px; transition: all 0.25s ease;
        position: relative; z-index: 1; text-decoration: none; white-space: nowrap; flex-shrink: 0;
    }
    .btn-back-tb:hover { background: rgba(255,255,255,0.25); color: #fff; transform: translateX(-2px); text-decoration: none; }

    /* ===== FORM CARD ===== */
    .tb-card { border-radius: 22px; border: none; box-shadow: 0 4px 36px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 24px; background: #fff;}
    .tb-card-header { background: #fffbf2; border-bottom: 1px solid #ffedd5; padding: 18px 28px; display: flex; align-items: center; gap: 12px; }
    .tb-card-header-icon { width: 38px; height: 38px; border-radius: 11px; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.95rem; flex-shrink: 0; }
    .tb-card-header-title { font-size: 0.92rem; font-weight: 700; color: #0f172a; margin: 0; }
    .tb-card-header-sub { font-size: 0.75rem; color: #64748b; margin: 0; }
    .tb-card-body { padding: 28px 32px; }

    /* ===== FORM FIELDS ===== */
    .tb-label { font-size: 0.79rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .tb-label i { color: #f59e0b; font-size: 0.8rem; }
    .tb-group { margin-bottom: 22px; }

    .tb-control {
        border-radius: 12px !important; border: 2px solid #e2e8f0 !important; padding: 11px 15px !important;
        font-size: 0.9rem !important; font-weight: 500 !important; color: #0f172a !important; background: #f8fafc !important;
        transition: all 0.25s ease !important; width: 100%; height: auto !important;
    }
    .tb-control:focus { border-color: #f59e0b !important; background: #fff !important; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important; outline: none !important; }
    .tb-control.is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; }

    .tb-field-wrap { position: relative; }
    .tb-icon-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #f59e0b; font-size: 0.85rem; pointer-events: none; }
    .tb-text-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #f59e0b; font-size: 0.88rem; font-weight: 700; pointer-events: none; }
    .tb-control.w-icon { padding-left: 40px !important; }
    .tb-control.w-prefix { padding-left: 38px !important; }

    .tb-select-wrap::after { content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.7rem; pointer-events: none; }
    .tb-control.tb-select { appearance: none; -webkit-appearance: none; padding-right: 36px !important; cursor: pointer; }

    .tb-radio-group { display: flex; gap: 15px; }
    .tb-radio-card {
        flex: 1; border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px; cursor: pointer;
        display: flex; align-items: center; gap: 10px; transition: all 0.2s ease; background: #f8fafc;
    }
    .tb-radio-card:hover { border-color: #cbd5e1; }
    .tb-radio-card input { margin: 0; width: 18px; height: 18px; accent-color: #f59e0b; cursor: pointer; }
    .tb-radio-card .r-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; }
    .tb-radio-card.setoran .r-icon { background: rgba(16,185,129,0.15); color: #059669; }
    .tb-radio-card.penarikan .r-icon { background: rgba(239,68,68,0.15); color: #dc2626; }
    .tb-radio-card .r-text { font-weight: 700; color: #334155; font-size: 0.9rem; margin: 0; }

    /* Custom Radio styling effect using JS class toggle or pseudo-selectors */
    input[type="radio"]:checked + .r-content { font-weight: 800; }
    .tb-radio-card:has(input[value="setoran"]:checked) { border-color: #10b981; background: #f0fdf4; box-shadow: 0 4px 12px rgba(16,185,129,0.15); }
    .tb-radio-card:has(input[value="penarikan"]:checked) { border-color: #ef4444; background: #fef2f2; box-shadow: 0 4px 12px rgba(239,68,68,0.15); }

    .tb-error { color: #ef4444; font-size: 0.76rem; font-weight: 500; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
    .tb-divider { height: 1px; background: #e2e8f0; margin: 10px 0 24px; }

    /* ===== BUTTONS ===== */
    .tb-btns { display: flex; justify-content: flex-end; gap: 12px; }
    .btn-tb-reset { background: #f1f5f9; color: #64748b; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 600; font-size: 0.88rem; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;}
    .btn-tb-reset:hover { background: #e2e8f0; color: #334155; text-decoration: none;}
    .btn-tb-submit { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: none; border-radius: 12px; padding: 12px 32px; font-weight: 700; font-size: 0.88rem; box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 7px; }
    .btn-tb-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4); color: #fff; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .tb-form-header { padding: 16px 18px; border-radius: 16px; flex-wrap: wrap; }
        .tb-form-title { font-size: 1.15rem; }
        .btn-back-tb { width: 100%; justify-content: center; }
        .tb-card-body { padding: 20px; }
        .tb-radio-group { flex-direction: column; gap: 10px; }
        .tb-btns { flex-direction: column-reverse; }
        .btn-tb-reset, .btn-tb-submit { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="tb-form-page px-2">

    <div class="tb-form-header">
        <div class="d-flex align-items-center" style="min-width:0;">
            <div class="tb-form-icon"><i class="fas fa-edit"></i></div>
            <div style="min-width:0;">
                <h1 class="tb-form-title">Edit Transaksi Tabungan</h1>
                <p class="tb-form-sub">Perbarui data setoran atau penarikan tabungan</p>
            </div>
        </div>
        <a href="{{ route('bendahara.tabungan.index') }}" class="btn-back-tb"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-8">
            <div class="card tb-card">
                <div class="tb-card-header">
                    <div class="tb-card-header-icon"><i class="fas fa-pen-square"></i></div>
                    <div>
                        <p class="tb-card-header-title">Form Edit Transaksi</p>
                        <p class="tb-card-header-sub">Sesuaikan jika ada kesalahan pencatatan</p>
                    </div>
                </div>

                <div class="tb-card-body">
                    <form action="{{ route('bendahara.tabungan.update', $tabungan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Alert Error Saldo --}}
                        @if(session('error'))
                        <div class="alert alert-danger" style="border-radius:12px; font-size:0.85rem; font-weight:600;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                        @endif

                        {{-- Anggota --}}
                        <div class="tb-group">
                            <label class="tb-label"><i class="fas fa-user-friends"></i> Pilih Anggota</label>
                            <div class="tb-select-wrap tb-field-wrap">
                                <span class="tb-icon-prefix"><i class="fas fa-user"></i></span>
                                <select name="user_id" class="tb-control tb-select w-icon @error('user_id') is-invalid @enderror" required>
                                    <option value="" disabled>— Pilih nama anggota —</option>
                                    @foreach($anggota as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $tabungan->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ strtoupper($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id') <div class="tb-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        {{-- Jenis Transaksi Radio --}}
                        <div class="tb-group">
                            <label class="tb-label"><i class="fas fa-exchange-alt"></i> Jenis Transaksi</label>
                            <div class="tb-radio-group">
                                <label class="tb-radio-card setoran">
                                    <input type="radio" name="jenis_transaksi" value="setoran" {{ old('jenis_transaksi', $tabungan->jenis_transaksi) == 'setoran' ? 'checked' : '' }} required>
                                    <div class="r-icon"><i class="fas fa-arrow-down"></i></div>
                                    <div class="r-text">Setoran (Menabung)</div>
                                </label>
                                <label class="tb-radio-card penarikan">
                                    <input type="radio" name="jenis_transaksi" value="penarikan" {{ old('jenis_transaksi', $tabungan->jenis_transaksi) == 'penarikan' ? 'checked' : '' }} required>
                                    <div class="r-icon"><i class="fas fa-arrow-up"></i></div>
                                    <div class="r-text">Penarikan (Ambil Uang)</div>
                                </label>
                            </div>
                            @error('jenis_transaksi') <div class="tb-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        {{-- Nominal & Tanggal --}}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="tb-group">
                                    <label class="tb-label"><i class="fas fa-money-bill-wave"></i> Nominal Uang</label>
                                    <div class="tb-field-wrap">
                                        <span class="tb-text-prefix">Rp</span>
                                        <input type="number" name="nominal" class="tb-control w-prefix @error('nominal') is-invalid @enderror" placeholder="Contoh: 50000" value="{{ old('nominal', $tabungan->nominal) }}" min="100" required>
                                    </div>
                                    @error('nominal') <div class="tb-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="tb-group">
                                    <label class="tb-label"><i class="fas fa-calendar-alt"></i> Tanggal</label>
                                    <div class="tb-field-wrap">
                                        <span class="tb-icon-prefix"><i class="fas fa-calendar"></i></span>
                                        <input type="date" name="tanggal" class="tb-control w-icon @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $tabungan->tanggal) }}" required>
                                    </div>
                                    @error('tanggal') <div class="tb-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="tb-group">
                            <label class="tb-label"><i class="fas fa-sticky-note"></i> Keterangan <span style="font-weight:400;color:#94a3b8;text-transform:none;">(Opsional)</span></label>
                            <textarea name="keterangan" class="tb-control @error('keterangan') is-invalid @enderror" placeholder="Contoh: Tabungan bulan Agustus..." rows="3">{{ old('keterangan', $tabungan->keterangan) }}</textarea>
                            @error('keterangan') <div class="tb-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="tb-divider"></div>

                        <div class="tb-btns">
                            <a href="{{ route('bendahara.tabungan.index') }}" class="btn-tb-reset">Batal</a>
                            <button type="submit" class="btn-tb-submit"><i class="fas fa-save"></i> Perbarui Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
