@extends('admin.layouts.master')

@section('content')
<style>
    /* Scoped styles untuk ID Card Form */
    .idcard-form-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
    }

    .idcard-form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .idcard-form-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .idcard-form-header h2 {
        color: #1e293b;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .idcard-form-header p {
        color: #64748b;
        font-size: 16px;
    }

    .idcard-form-group {
        margin-bottom: 30px;
    }

    .idcard-form-label {
        display: block;
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .idcard-form-label .required {
        color: #ef4444;
        margin-left: 4px;
    }

    .idcard-select-wrapper {
        position: relative;
    }

    .idcard-select {
        width: 100%;
        padding: 14px 40px 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        color: #1e293b;
        background: white;
        transition: all 0.3s ease;
        appearance: none;
        cursor: pointer;
    }

    .idcard-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .idcard-select-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #64748b;
    }

    .idcard-file-input-wrapper {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8fafc;
    }

    .idcard-file-input-wrapper:hover {
        border-color: #667eea;
        background: #f1f5f9;
    }

    .idcard-file-input-wrapper.has-file {
        border-color: #16a34a;
        background: #f0fdf4;
    }

    .idcard-file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .idcard-file-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .idcard-file-icon {
        font-size: 48px;
        color: #94a3b8;
    }

    .idcard-file-text {
        color: #475569;
        font-size: 15px;
    }

    .idcard-file-hint {
        color: #94a3b8;
        font-size: 13px;
    }

    .idcard-preview-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        border: 2px solid #e2e8f0;
    }

    .idcard-preview-empty {
        color: #94a3b8;
        font-size: 14px;
        padding: 40px 20px;
    }

    .idcard-qr-image {
        width: 200px;
        height: 200px;
        margin: 0 auto 15px;
        border-radius: 12px;
        background: white;
        padding: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .idcard-qr-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .idcard-user-info {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .idcard-user-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .idcard-user-email {
        color: #64748b;
        font-size: 14px;
    }

    .idcard-btn-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .idcard-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .idcard-btn-download {
        background: #16a34a;
        color: white;
    }

    .idcard-btn-download:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3);
    }

    .idcard-btn-download:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
    }

    .idcard-btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .idcard-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .idcard-btn-primary:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
    }

    .idcard-btn-back {
        background: #64748b;
        color: white;
    }

    .idcard-btn-back:hover {
        background: #475569;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    }

    .idcard-alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .idcard-alert-info {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    .idcard-alert-icon {
        font-size: 20px;
    }

    /* Photo Preview Styles */
    .idcard-photo-preview {
        display: none;
        margin-top: 20px;
    }

    .idcard-photo-preview.show {
        display: block;
    }

    .idcard-photo-image {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #667eea;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .idcard-photo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Template Preview */
    .idcard-template-preview {
        display: none;
        margin-top: 15px;
        text-align: center;
    }

    .idcard-template-preview.show {
        display: block;
    }

    .idcard-template-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .idcard-template-remove {
        margin-top: 10px;
        padding: 8px 16px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .idcard-template-remove:hover {
        background: #dc2626;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .idcard-form-container {
            padding: 25px 20px;
        }

        .idcard-form-header h2 {
            font-size: 24px;
        }

        .idcard-btn-group {
            flex-direction: column;
        }

        .idcard-qr-image {
            width: 180px;
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .idcard-form-wrapper {
            padding: 20px 10px;
        }

        .idcard-form-container {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .idcard-qr-image {
            width: 160px;
            height: 160px;
        }
    }
</style>

<div class="idcard-form-wrapper">
    <div class="idcard-form-container">
        <!-- Header -->
        <div class="idcard-form-header">
            <h2>🪪 Buat ID Card Anggota</h2>
            <p>Generate ID Card dengan QR Code untuk anggota RT</p>
        </div>

        <!-- Info Alert -->
        <div class="idcard-alert idcard-alert-info">
            <span class="idcard-alert-icon">💡</span>
            <span>Pilih anggota dan upload template untuk membuat ID Card. QR Code akan otomatis ditambahkan.</span>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.idcard.generate') }}" method="POST" enctype="multipart/form-data" id="idcardForm">
            @csrf

            <!-- Pilih Anggota -->
            <div class="idcard-form-group">
                <label class="idcard-form-label">
                    Pilih Anggota
                    <span class="required">*</span>
                </label>
                <div class="idcard-select-wrapper">
                    <select name="user_id" id="idcardUserSelect" class="idcard-select" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-email="{{ $u->email }}"
                                    data-photo="{{ $u->image ? asset('storage/' . $u->image) : '' }}">
                                {{ $u->name }} - {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    <span class="idcard-select-icon">▼</span>
                </div>
            </div>

            <!-- Preview QR Code -->
            <div class="idcard-form-group">
                <label class="idcard-form-label">Preview QR Code</label>
                <div class="idcard-preview-card">
                    <div id="idcardQrPreview" class="idcard-preview-empty">
                        <p>Pilih anggota untuk melihat QR Code</p>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 15px;">
                    <button type="button" id="idcardDownloadQR" class="idcard-btn idcard-btn-download" style="max-width: 300px; display: none;">
                        <span>📥</span>
                        <span>Download QR Code</span>
                    </button>
                </div>
            </div>

            <!-- Preview Foto User -->
            <div class="idcard-form-group">
                <div id="idcardPhotoPreview" class="idcard-photo-preview">
                    <label class="idcard-form-label">Foto Anggota</label>
                    <div class="idcard-photo-image">
                        <img id="idcardPhotoImg" src="" alt="User Photo">
                    </div>
                </div>
            </div>

            <!-- Upload Template -->
            <div class="idcard-form-group">
                <label class="idcard-form-label">
                    Upload Template ID Card
                    <span class="required">*</span>
                </label>
                <div class="idcard-file-input-wrapper" id="idcardFileWrapper">
                    <input type="file" name="template" id="idcardTemplateInput" class="idcard-file-input" accept="image/*" required>
                    <div class="idcard-file-label">
                        <div class="idcard-file-icon">📤</div>
                        <div class="idcard-file-text">Klik atau drag file template di sini</div>
                        <div class="idcard-file-hint">Format: JPG, PNG (Max 5MB)</div>
                    </div>
                </div>

                <!-- Template Preview -->
                <div id="idcardTemplatePreview" class="idcard-template-preview">
                    <img id="idcardTemplateImg" class="idcard-template-image" src="" alt="Template Preview">
                    <button type="button" id="idcardRemoveTemplate" class="idcard-template-remove">
                        🗑️ Hapus Template
                    </button>
                </div>
            </div>

            <!-- Buttons -->
            <div class="idcard-btn-group">
                <a href="{{ route('admin.AnggotaRT.index') }}" class="idcard-btn idcard-btn-back">
                    <span>←</span>
                    <span>Kembali</span>
                </a>
                <button type="submit" id="idcardSubmitBtn" class="idcard-btn idcard-btn-primary" disabled>
                    <span>🎨</span>
                    <span>Generate ID Card</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';

    // State management - scoped to this IIFE
    const state = {
        currentQrUrl: '',
        currentUserId: '',
        currentUserName: '',
        hasTemplate: false
    };

    // DOM Elements
    const elements = {
        userSelect: document.getElementById('idcardUserSelect'),
        qrPreview: document.getElementById('idcardQrPreview'),
        downloadQRBtn: document.getElementById('idcardDownloadQR'),
        photoPreview: document.getElementById('idcardPhotoPreview'),
        photoImg: document.getElementById('idcardPhotoImg'),
        templateInput: document.getElementById('idcardTemplateInput'),
        templatePreview: document.getElementById('idcardTemplatePreview'),
        templateImg: document.getElementById('idcardTemplateImg'),
        removeTemplateBtn: document.getElementById('idcardRemoveTemplate'),
        fileWrapper: document.getElementById('idcardFileWrapper'),
        submitBtn: document.getElementById('idcardSubmitBtn')
    };

    // Update QR Preview
    function updateQRPreview(userId) {
        if (!userId) {
            elements.qrPreview.innerHTML = '<p class="idcard-preview-empty">Pilih anggota untuk melihat QR Code</p>';
            elements.downloadQRBtn.style.display = 'none';
            elements.photoPreview.classList.remove('show');
            return;
        }

        fetch(`/admin/user-qr/${userId}`)
            .then(res => res.json())
            .then(data => {
                state.currentQrUrl = data.qr;

                const selectedOption = elements.userSelect.options[elements.userSelect.selectedIndex];
                const userName = selectedOption.dataset.name;
                const userEmail = selectedOption.dataset.email;
                const userPhoto = selectedOption.dataset.photo;

                elements.qrPreview.innerHTML = `
                    <div class="idcard-qr-image">
                        <img src="${data.qr}" alt="QR Code">
                    </div>
                    <div class="idcard-user-info">
                        <div class="idcard-user-name">${userName}</div>
                        <div class="idcard-user-email">${userEmail}</div>
                    </div>
                `;

                elements.downloadQRBtn.style.display = 'flex';

                // Show photo if available
                if (userPhoto) {
                    elements.photoImg.src = userPhoto;
                    elements.photoPreview.classList.add('show');
                } else {
                    elements.photoPreview.classList.remove('show');
                }

                checkFormValidity();
            })
            .catch(err => {
                console.error('Error fetching QR:', err);
                elements.qrPreview.innerHTML = '<p style="color:#ef4444;">❌ QR Code tidak ditemukan</p>';
                elements.downloadQRBtn.style.display = 'none';
                elements.photoPreview.classList.remove('show');
            });
    }

    // Download QR Code
    function downloadQRCode() {
        if (!state.currentQrUrl || !state.currentUserId) return;

        const userName = elements.userSelect.options[elements.userSelect.selectedIndex].dataset.name;
        const sanitizedName = userName.replace(/\s/g, '_');

        const a = document.createElement('a');
        a.href = state.currentQrUrl;
        a.download = `QR_${sanitizedName}_${state.currentUserId}.svg`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        // Show success message
        showNotification('✅ QR Code berhasil diunduh!', 'success');
    }

    // Handle Template Upload
    function handleTemplateUpload(e) {
        const file = e.target.files[0];

        if (!file) return;

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('❌ Ukuran file terlalu besar! Maksimal 5MB', 'error');
            elements.templateInput.value = '';
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('❌ File harus berupa gambar!', 'error');
            elements.templateInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(event) {
            elements.templateImg.src = event.target.result;
            elements.templatePreview.classList.add('show');
            elements.fileWrapper.classList.add('has-file');
            state.hasTemplate = true;
            checkFormValidity();
        };
        reader.readAsDataURL(file);
    }

    // Remove Template
    function removeTemplate() {
        elements.templateInput.value = '';
        elements.templatePreview.classList.remove('show');
        elements.fileWrapper.classList.remove('has-file');
        state.hasTemplate = false;
        checkFormValidity();
    }

    // Check form validity
    function checkFormValidity() {
        const isValid = state.currentUserId && state.hasTemplate;
        elements.submitBtn.disabled = !isValid;
    }

    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'success' ? '#16a34a' : '#ef4444'};
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            font-size: 14px;
            font-weight: 600;
            animation: slideDown 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => document.body.removeChild(notification), 300);
        }, 3000);
    }

    // Event Listeners
    elements.userSelect.addEventListener('change', function() {
        state.currentUserId = this.value;
        state.currentUserName = this.options[this.selectedIndex]?.dataset.name || '';
        updateQRPreview(state.currentUserId);
    });

    elements.downloadQRBtn.addEventListener('click', downloadQRCode);
    elements.templateInput.addEventListener('change', handleTemplateUpload);
    elements.removeTemplateBtn.addEventListener('click', removeTemplate);

    // Drag and drop for template
    elements.fileWrapper.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#667eea';
        this.style.background = '#f1f5f9';
    });

    elements.fileWrapper.addEventListener('dragleave', function(e) {
        e.preventDefault();
        if (!state.hasTemplate) {
            this.style.borderColor = '#cbd5e1';
            this.style.background = '#f8fafc';
        }
    });

    elements.fileWrapper.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#cbd5e1';
        this.style.background = '#f8fafc';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            elements.templateInput.files = files;
            handleTemplateUpload({ target: { files: files } });
        }
    });

    // Initialize
    checkFormValidity();
})();
</script>

@endsection
