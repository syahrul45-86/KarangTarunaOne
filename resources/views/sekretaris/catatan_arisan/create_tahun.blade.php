@extends('sekretaris.layouts.master')

@section('title', 'Tambah Tahun Arisan')

@section('content')



<div class=" tambah-container mt-4">

    <!-- Page Header -->
    <div class="page-header">
        <h3>
            <span>➕</span>
            <span>Tambah Tahun Arisan</span>
        </h3>
        <a href="{{ route('sekretaris.catatan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-box-icon">💡</div>
        <div class="info-box-text">
            <strong>Cara Penggunaan:</strong><br>
            1. Tambahkan tahun arisan terlebih dahulu<br>
            2. Kemudian tambahkan tanggal-tanggal pembayaran untuk tahun tersebut
        </div>
    </div>

    <!-- Form Tambah Tahun -->
    <div class="form-card">
        <div class="card-header-custom">
            <h5>
                <span class="icon">📅</span>
                <span>Form Tambah Tahun</span>
            </h5>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('sekretaris.tahun.store') }}" method="POST" id="formTahun">
                @csrf

                <div class="form-group">
                    <label class="form-label-custom">
                        <span>Tahun Arisan</span>
                        <span class="required">*</span>
                    </label>
                    <input type="number"
                           name="tahun"
                           class="form-control-custom"
                           placeholder="Contoh: 2025"
                           min="2000"
                           max="2100"
                           required>
                </div>

                <button type="submit" class="btn-submit btn-primary-custom">
                    <i class="fas fa-save"></i>
                    <span>Simpan Tahun</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Form Tambah Tanggal -->
    <div class="form-card">
        <div class="card-header-custom">
            <h5>
                <span class="icon">📆</span>
                <span>Form Tambah Tanggal</span>
            </h5>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('sekretaris.tanggal.store') }}" method="POST" id="formTanggal">
                @csrf

                <div class="form-group">
                    <label class="form-label-custom">
                        <span>Pilih Tahun</span>
                        <span class="required">*</span>
                    </label>
                    <select name="arisan_tahun_id" class="form-select-custom" required>
                        <option value="">-- Pilih Tahun Arisan --</option>
                        @foreach($tahuns as $th)
                            <option value="{{ $th->id }}">{{ $th->tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label-custom">
                        <span>Tanggal Pembayaran</span>
                        <span class="required">*</span>
                    </label>
                    <input type="date"
                           name="tanggal"
                           class="form-control-custom"
                           required>
                </div>

                <button type="submit" class="btn-submit btn-success-custom">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Tanggal</span>
                </button>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and loading state
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-submit');

            // Add loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            // Optional: Remove loading if validation fails
            setTimeout(() => {
                if (!this.checkValidity()) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                }
            }, 100);
        });
    });

    // Auto-focus first input
    const firstInput = document.querySelector('.form-control-custom');
    if (firstInput) {
        firstInput.focus();
    }

    // Number input validation
    const yearInput = document.querySelector('input[name="tahun"]');
    if (yearInput) {
        yearInput.addEventListener('input', function() {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Limit to 4 digits
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4);
            }
        });
    }

    // Set today as default date
    const dateInput = document.querySelector('input[type="date"]');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }
});
</script>

@endsection
