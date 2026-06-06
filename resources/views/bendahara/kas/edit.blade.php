@extends('bendahara.layouts.master')

@section('title', 'Edit Setoran Kas')

@push('styles')
<style>
    /* ===== KAS CREATE/EDIT PAGE - PREMIUM + MOBILE RESPONSIVE ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .kas-create-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== PAGE HEADER ===== */
    .kas-create-header {
        background: linear-gradient(135deg, #f39c12 0%, #d35400 100%);
        border-radius: 20px;
        padding: 22px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(243, 156, 18, 0.35);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .kas-create-header::before {
        content: '';
        position: absolute;
        top: -45px; right: -45px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        pointer-events: none;
    }
    .kas-create-header-icon {
        width: 46px; height: 46px;
        border-radius: 13px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff;
        margin-right: 13px; flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .kas-create-header-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #fff; margin: 0;
        letter-spacing: -0.3px;
    }
    .kas-create-header-sub {
        color: rgba(255,255,255,0.75);
        font-size: 0.82rem; margin: 2px 0 0;
    }
    .btn-back-kas {
        background: rgba(255,255,255,0.15);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 11px;
        padding: 9px 18px;
        font-size: 0.82rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 7px;
        transition: all 0.25s ease;
        position: relative; z-index: 1;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-back-kas:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
        transform: translateX(-2px);
        text-decoration: none;
    }

    /* ===== FORM CARD ===== */
    .kas-form-card {
        border-radius: 22px;
        border: none;
        box-shadow: 0 4px 36px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .kas-form-card .card-header {
        background: linear-gradient(135deg, #fffbf2, #fff4e5);
        border-bottom: 1px solid #ffedd5;
        padding: 18px 28px;
        display: flex; align-items: center; gap: 12px;
    }
    .kas-form-header-icon {
        width: 38px; height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg, #f39c12, #d35400);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.95rem;
        flex-shrink: 0;
    }
    .kas-form-header-title {
        font-size: 0.92rem; font-weight: 700; color: #1a1f36; margin: 0;
    }
    .kas-form-header-sub {
        font-size: 0.75rem; color: #94a3b8; margin: 0;
    }
    .kas-steps {
        display: flex; gap: 5px; margin-left: auto;
    }
    .kas-step-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: rgba(243, 156, 18, 0.25);
    }
    .kas-step-dot.active {
        background: #f39c12; width: 18px; border-radius: 3px;
    }
    .kas-form-card .card-body { padding: 28px 32px; }

    /* ===== FORM FIELDS ===== */
    .kas-label {
        font-size: 0.79rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 7px;
        display: flex; align-items: center; gap: 5px;
    }
    .kas-label i { color: #f39c12; font-size: 0.75rem; }

    .kas-input-group { margin-bottom: 20px; }

    .kas-form-control {
        border-radius: 12px !important;
        border: 2px solid #ffedd5 !important;
        padding: 11px 15px !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        color: #1a1f36 !important;
        background: #fffdfa !important;
        transition: all 0.25s ease !important;
        height: auto !important;
        width: 100%;
    }
    .kas-form-control:focus {
        border-color: #f39c12 !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px rgba(243, 156, 18, 0.12) !important;
        outline: none !important;
    }
    .kas-form-control.is-invalid {
        border-color: #ef4444 !important;
        background: #fff5f5 !important;
    }

    /* Icon prefix inside inputs */
    .kas-field-wrap { position: relative; }
    .kas-field-icon {
        position: absolute;
        left: 13px; top: 50%;
        transform: translateY(-50%);
        color: #f39c12; font-size: 0.82rem;
        pointer-events: none; z-index: 2;
    }
    .kas-field-prefix {
        position: absolute;
        left: 13px; top: 50%;
        transform: translateY(-50%);
        color: #f39c12; font-size: 0.88rem; font-weight: 700;
        pointer-events: none; z-index: 2;
    }
    .kas-form-control.with-icon { padding-left: 38px !important; }
    .kas-form-control.with-prefix { padding-left: 36px !important; }

    /* Select */
    .kas-select-wrap { position: relative; }
    .kas-select-wrap::after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute;
        right: 14px; top: 50%;
        transform: translateY(-50%);
        color: #f39c12; font-size: 0.7rem;
        pointer-events: none; z-index: 2;
    }
    .kas-form-control.kas-select {
        appearance: none; -webkit-appearance: none;
        padding-right: 36px !important; cursor: pointer;
    }

    /* Textarea */
    .kas-textarea { resize: none; min-height: 100px; }

    /* Divider */
    .kas-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #ffedd5, transparent);
        margin: 6px 0 22px;
    }

    /* Hints & errors */
    .kas-hint { color: #94a3b8; font-size: 0.74rem; margin-top: 4px; }
    .kas-error {
        color: #ef4444; font-size: 0.76rem; font-weight: 500;
        margin-top: 5px; display: flex; align-items: center; gap: 4px;
    }

    /* ===== ACTION BUTTONS ===== */
    .kas-btn-group {
        display: flex; justify-content: flex-end; gap: 10px;
    }
    .btn-kas-reset {
        background: #f1f5f9; color: #64748b;
        border: none; border-radius: 12px;
        padding: 11px 24px;
        font-weight: 600; font-size: 0.88rem;
        transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-kas-reset:hover { background: #e2e8f0; color: #475569; }
    .btn-kas-submit {
        background: linear-gradient(135deg, #f39c12, #d35400);
        color: #fff; border: none; border-radius: 12px;
        padding: 11px 32px;
        font-weight: 700; font-size: 0.88rem;
        letter-spacing: 0.2px;
        box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
        transition: all 0.3s ease;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-kas-submit:hover {
        color: #fff; transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(243, 156, 18, 0.5);
    }
    .btn-kas-submit:active { transform: translateY(0); }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-1 { animation: fadeSlideIn 0.4s ease forwards; }
    .anim-2 { animation: fadeSlideIn 0.5s 0.08s ease forwards; opacity:0; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .kas-create-header {
            padding: 16px 18px;
            border-radius: 16px;
            flex-wrap: wrap;
        }
        .kas-create-header-title { font-size: 1.1rem; }
        .kas-create-header-sub { font-size: 0.76rem; }
        .kas-create-header-icon { width: 38px; height: 38px; font-size: 1rem; margin-right: 10px; }
        .btn-back-kas { padding: 8px 14px; font-size: 0.78rem; }

        .kas-form-card { border-radius: 16px; }
        .kas-form-card .card-header { padding: 14px 18px; }
        .kas-form-card .card-body { padding: 18px; }

        .kas-steps { display: none; }

        .kas-btn-group { flex-direction: column-reverse; }
        .btn-kas-reset, .btn-kas-submit { width: 100%; justify-content: center; padding: 13px; font-size: 0.9rem; }
    }

    @media (max-width: 480px) {
        .kas-create-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-back-kas { width: 100%; justify-content: center; }

        .kas-form-card .card-body { padding: 16px; }
        .kas-label { font-size: 0.74rem; }
        .kas-form-control { font-size: 0.86rem !important; padding: 10px 13px !important; }
        .kas-form-control.with-icon { padding-left: 35px !important; }
        .kas-form-control.with-prefix { padding-left: 32px !important; }
    }
</style>
@endpush

@section('content')
<div class="kas-create-page px-2">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="kas-create-header anim-1">
        <div class="d-flex align-items-center" style="min-width:0;">
            <div class="kas-create-header-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div style="min-width:0;">
                <h1 class="kas-create-header-title">Edit Setoran Kas</h1>
                <p class="kas-create-header-sub">Ubah atau sesuaikan nilai setoran kas anggota</p>
            </div>
        </div>
        <a href="{{ route('bendahara.kas.index') }}" class="btn-back-kas">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="row justify-content-center anim-2">
        <div class="col-12 col-lg-9 col-xl-8">
            <div class="card kas-form-card">

                {{-- Card Header --}}
                <div class="card-header">
                    <div class="kas-form-header-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <p class="kas-form-header-title">Form Edit Setoran Kas</p>
                        <p class="kas-form-header-sub">Gunakan form ini untuk mengurangi/mengubah kas</p>
                    </div>
                    <div class="kas-steps">
                        <div class="kas-step-dot active"></div>
                        <div class="kas-step-dot"></div>
                        <div class="kas-step-dot"></div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <form action="{{ route('bendahara.kas.update', $kas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Pilih Anggota --}}
                        <div class="kas-input-group">
                            <label class="kas-label">
                                <i class="fas fa-user-friends"></i> Anggota
                            </label>
                            <div class="kas-select-wrap kas-field-wrap">
                                <span class="kas-field-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <select name="user_id"
                                    class="kas-form-control kas-select with-icon @error('user_id') is-invalid @enderror"
                                    required>
                                    <option value="" disabled>— Pilih nama anggota —</option>
                                    @foreach($anggota as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $kas->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ strtoupper($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id')
                                <div class="kas-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nominal + Tanggal --}}
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="kas-input-group">
                                    <label class="kas-label">
                                        <i class="fas fa-money-bill-wave"></i> Nominal Kas Saat Ini
                                    </label>
                                    <div class="kas-field-wrap">
                                        <span class="kas-field-prefix">Rp</span>
                                        <input type="number"
                                            name="nominal"
                                            class="kas-form-control with-prefix @error('nominal') is-invalid @enderror"
                                            placeholder="Contoh: 10000"
                                            value="{{ old('nominal', $kas->nominal) }}"
                                            min="0"
                                            required>
                                    </div>
                                    <p class="kas-hint"><i class="fas fa-info-circle mr-1"></i>Ubah angka untuk mengurangi/menambah</p>
                                    @error('nominal')
                                        <div class="kas-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="kas-input-group">
                                    <label class="kas-label">
                                        <i class="fas fa-calendar-alt"></i> Tanggal
                                    </label>
                                    <div class="kas-field-wrap">
                                        <span class="kas-field-icon">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date"
                                            name="tanggal"
                                            class="kas-form-control with-icon @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal', $kas->tanggal) }}"
                                            required>
                                    </div>
                                    @error('tanggal')
                                        <div class="kas-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="kas-input-group">
                            <label class="kas-label">
                                <i class="fas fa-sticky-note"></i>
                                Keterangan
                                <span style="font-weight:400;color:#94a3b8;text-transform:none;letter-spacing:0;font-size:0.76rem;">(Opsional)</span>
                            </label>
                            <textarea
                                name="keterangan"
                                class="kas-form-control kas-textarea @error('keterangan') is-invalid @enderror"
                                placeholder="Contoh: Pengambilan sebagian kas..."
                                rows="3">{{ old('keterangan', $kas->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="kas-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kas-divider"></div>

                        {{-- Action Buttons --}}
                        <div class="kas-btn-group">
                            <a href="{{ route('bendahara.kas.index') }}" class="btn-kas-reset">
                                Batal
                            </a>
                            <button type="submit" class="btn-kas-submit">
                                <i class="fas fa-save"></i>
                                Perbarui Kas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
