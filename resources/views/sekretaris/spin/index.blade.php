@extends('sekretaris.layouts.master')

@section('title', 'Catatan Arisan')

@section('content')

    <div class="roda-spin-wrapper">
        <div class="roda-center-container">
            <h1 class="roda-wheel-title">🎡 Spin Arisan</h1>
            <div id="roda-chart">
                <div id="roda-arrow"></div>
                <div id="roda-spin-button">PUTAR</div>
            </div>

            <div id="roda-input-names">
                <div class="roda-form-group">
                    <label class="roda-form-label" for="roda-anggota-select">Pilih Anggota Arisan</label>
                    <select id="roda-anggota-select" class="roda-form-select">
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggota as $user)
                            <option value="{{ $user->id }}" data-name="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="roda-add-member" class="roda-btn roda-btn-add">
                    ➕ Tambah ke Roda
                </button>

                <div class="roda-selected-list">
                    <div class="roda-selected-title">Peserta Terpilih:</div>
                    <div id="roda-selected-members"></div>
                </div>

                <button type="button" id="roda-generate-wheel" class="roda-btn roda-btn-primary" disabled>
                    ✨ Buat Roda
                </button>

                <button type="button" id="roda-reset-names" class="roda-btn roda-btn-secondary">
                    🔄 Reset Semua
                </button>
            </div>
        </div>
    </div>

    <div id="roda-winner-display">
        <div class="roda-winner-content">
            <span class="roda-winner-emoji">🎉</span>
            <h1 id="roda-winner-name"></h1>
            <button id="roda-close-winner" class="roda-btn-close">Tutup</button>
        </div>
    </div>

    <script src="https://d3js.org/d3.v3.min.js"></script>

@endsection
