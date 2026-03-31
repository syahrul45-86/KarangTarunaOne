@extends($layout)

@section('title', 'Scan QR Absensi')

@section('content')

<div class="container-fluid px-2 px-md-4 mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6 col-md-8">

            <!-- Header -->
            <div class="text-center mb-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-qrcode fa-4x text-primary"></i>
                </div>
                <h3 class="fw-bold mb-2">Scan QR Code Absensi</h3>
                <p class="text-muted">Upload gambar QR Code untuk melakukan absensi</p>
            </div>

            <!-- Main Card -->
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Card Body -->
                <div class="card-body p-4">

                    <form id="qrForm">
                        @csrf

                        <!-- Upload Area -->
                        <div class="upload-zone mb-4" id="uploadZone" onclick="document.getElementById('qrImage').click()">
                            <div class="upload-content text-center p-4">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5 class="mb-2">Upload Gambar QR Code</h5>
                                <p class="text-muted small mb-3">Klik atau seret gambar ke sini</p>

                                <input type="file" id="qrImage" class="d-none" accept="image/*" required>

                                <button type="button" class="btn btn-primary" onclick="event.stopPropagation(); document.getElementById('qrImage').click()">
                                    <i class="fas fa-folder-open me-2"></i>Pilih Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div id="previewArea" class="d-none mb-4">
                            <div class="preview-wrapper text-center">
                                <img id="previewImage" src="" alt="Preview" class="img-fluid rounded shadow-sm mb-3" style="max-height: 300px;">
                                <p class="text-muted small mb-0" id="fileName"></p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="btn btn-success btn-lg w-100 d-none">
                            <i class="fas fa-search me-2"></i>Scan QR Code
                        </button>

                    </form>

                    <!-- Status Message -->
                    <div id="statusAlert" class="alert d-none mt-3 mb-0" role="alert"></div>

                </div>

                <!-- Info Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex align-items-center justify-content-center text-muted small">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Format: JPG, PNG, GIF (Max 5MB)</span>
                    </div>
                </div>

            </div>

            <!-- Instructions -->
            <div class="mt-4">
                <div class="alert alert-info border-0 shadow-sm">
                    <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                    <ul class="mb-0 small ps-3">
                        <li>Pastikan gambar QR Code jelas dan tidak blur</li>
                        <li>QR Code harus terlihat utuh dalam gambar</li>
                        <li>Hindari gambar dengan cahaya berlebihan</li>
                        <li>Format yang didukung: JPG, PNG, GIF</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- JSQR Library --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<style>
    .icon-wrapper {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .upload-zone {
        border: 3px dashed #dee2e6;
        border-radius: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: #0d6efd;
        background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    .upload-zone.dragover {
        border-color: #0d6efd;
        background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%);
        transform: scale(1.02);
    }

    .card {
        border-radius: 1rem !important;
    }

    .preview-wrapper img {
        max-width: 100%;
        object-fit: contain;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .upload-content {
            padding: 2rem 1rem !important;
        }

        .upload-content i {
            font-size: 2rem !important;
        }

        .upload-content h5 {
            font-size: 1rem;
        }

        .icon-wrapper i {
            font-size: 2.5rem !important;
        }
    }
</style>

<script>
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('qrImage');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    const statusAlert = document.getElementById('statusAlert');

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Set file to input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                handleFileSelect(file);
            } else {
                showStatus('error', 'File harus berupa gambar!');
            }
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    // Handle file selection
    function handleFileSelect(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showStatus('error', 'File harus berupa gambar (JPG, PNG, GIF)');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showStatus('error', 'Ukuran file maksimal 5MB');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = file.name;

            uploadZone.classList.add('d-none');
            previewArea.classList.remove('d-none');
            submitBtn.classList.remove('d-none');
            statusAlert.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    // Form submit
    document.getElementById('qrForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let file = fileInput.files[0];

        if (!file) {
            showStatus('error', 'Pilih gambar QR terlebih dahulu!');
            return;
        }

        // Show loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;

        let reader = new FileReader();

        reader.onload = function() {
            let img = new Image();
            img.src = reader.result;

            img.onload = function() {
                // Create canvas for decoding
                let canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;

                let ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) {
                    console.log("QR Code Data:", code.data);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Code Berhasil Terbaca!',
                        html: '<p class="mb-2">Data QR Code:</p><code class="text-break">' + code.data + '</code>',
                        text: 'Sedang mengarahkan ke halaman absensi...',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    // Redirect to QR URL
                    setTimeout(() => {
                        window.location.href = code.data;
                    }, 2000);

                } else {
                    // QR Code not detected
                    showStatus('error', '❌ QR Code tidak terdeteksi! Pastikan gambar jelas dan QR Code terlihat utuh.');

                    submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
                    submitBtn.disabled = false;
                }
            };

            img.onerror = function() {
                showStatus('error', 'Gagal memuat gambar. Coba file lain.');
                submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
                submitBtn.disabled = false;
            };
        };

        reader.onerror = function() {
            showStatus('error', 'Gagal membaca file.');
            submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
            submitBtn.disabled = false;
        };

        reader.readAsDataURL(file);
    });

    // Show status message
    function showStatus(type, message) {
        statusAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');

        if (type === 'success') {
            statusAlert.classList.add('alert-success');
            statusAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + message;
        } else if (type === 'error') {
            statusAlert.classList.add('alert-danger');
            statusAlert.innerHTML = '<i class="fas fa-times-circle me-2"></i>' + message;
        } else {
            statusAlert.classList.add('alert-warning');
            statusAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + message;
        }
    }

    // Reset upload
    function resetUpload() {
        uploadZone.classList.remove('d-none');
        previewArea.classList.add('d-none');
        submitBtn.classList.add('d-none');
        statusAlert.classList.add('d-none');
        fileInput.value = '';
        submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
        submitBtn.disabled = false;
    }
</script>

@endsection
