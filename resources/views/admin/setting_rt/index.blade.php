@extends('admin.layouts.master')

@section('title', 'Setting RT')

@section('content')


<div class="setting-rt-container">
    <div class="setting-rt-header">
        <h3>⚙️ Pengaturan RT</h3>
        <a href="{{ route('admin.dashboard') }}" class="setting-rt-btn-back">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="setting-rt-alert setting-rt-alert-success">
        <div class="setting-rt-alert-icon">✅</div>
        <div class="setting-rt-alert-content">
            <div class="setting-rt-alert-title">Berhasil!</div>
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- Alert Error --}}
    @if($errors->any())
    <div class="setting-rt-alert setting-rt-alert-error">
        <div class="setting-rt-alert-icon">❌</div>
        <div class="setting-rt-alert-content">
            <div class="setting-rt-alert-title">Periksa input anda:</div>
            <ul class="setting-rt-alert-list">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="setting-rt-form-container">
        <div class="setting-rt-form-title">
            <span>📝</span>
            <span>Form Pengaturan</span>
        </div>

        <form action="{{ route('admin.setting_rt.update') }}" method="POST">
            @csrf

            {{-- Iuran Arisan --}}
            <div class="setting-rt-form-group">
                <label class="setting-rt-label">
                    <span>💰</span>
                    <span>Iuran Arisan (Rp)</span>
                </label>
                <div class="setting-rt-input-wrapper">
                    <span class="setting-rt-input-prefix">Rp</span>
                    <input
                        type="number"
                        name="iuran_arisan"
                        class="setting-rt-input"
                        value="{{ old('iuran_arisan', $setting->iuran_arisan ?? '') }}"
                        placeholder="Masukkan nominal iuran arisan"
                        required
                    >
                </div>
            </div>

            <div class="setting-rt-divider"></div>

            {{-- Denda Absensi --}}
            <div class="setting-rt-form-group">
                <label class="setting-rt-label">
                    <span>📋</span>
                    <span>Denda Absensi (Rp)</span>
                </label>
                <div class="setting-rt-input-wrapper">
                    <span class="setting-rt-input-prefix">Rp</span>
                    <input
                        type="number"
                        name="denda_absensi"
                        class="setting-rt-input"
                        value="{{ old('denda_absensi', $setting->denda_absensi ?? '') }}"
                        placeholder="Masukkan nominal denda absensi"
                        required
                    >
                </div>
            </div>

            <div class="setting-rt-divider"></div>

            {{-- Denda Arisan --}}
            <div class="setting-rt-form-group">
                <label class="setting-rt-label">
                    <span>💸</span>
                    <span>Denda Arisan (Rp)</span>
                </label>
                <div class="setting-rt-input-wrapper">
                    <span class="setting-rt-input-prefix">Rp</span>
                    <input
                        type="number"
                        name="denda_arisan"
                        class="setting-rt-input"
                        value="{{ old('denda_arisan', $setting->denda_arisan ?? '') }}"
                        placeholder="Masukkan nominal denda arisan"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="setting-rt-btn-submit">
                <span>💾</span>
                <span>Simpan Pengaturan</span>
            </button>
        </form>
    </div>
</div>

@endsection
