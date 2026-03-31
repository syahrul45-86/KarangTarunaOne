@extends($layout)

@section('title', 'Scan QR Absensi')

@section('content')

<div class="container-fluid px-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <!-- Header -->
            <div class="text-center mb-4">
                <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold">Scan QR Code Absensi</h3>
                <p class="text-muted">Pilih metode scan yang Anda inginkan</p>
            </div>

            <!-- Main Card -->
            <div class="card shadow-lg border-0">

                <!-- Mode Selection Buttons -->
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-lg w-100 mode-btn active" id="cameraModeBtn" onclick="switchMode('camera')">
                                <i class="fas fa-camera fa-2x mb-2"></i>
                                <div>Scan dengan Kamera</div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-primary btn-lg w-100 mode-btn" id="uploadModeBtn" onclick="switchMode('upload')">
                                <i class="fas fa-upload fa-2x mb-2"></i>
                                <div>Upload Gambar</div>
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Camera Mode -->
                    <div id="cameraMode" class="scan-mode">
                        <div class="text-center">

                            <!-- Camera Preview -->
                            <div id="cameraPreview" class="d-none mb-3">
                                <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto; border: 2px solid #0d6efd; border-radius: 10px; overflow: hidden;"></div>
                            </div>

                            <!-- Camera Controls -->
                            <div id="cameraControls">
                                <button class="btn btn-success btn-lg mb-3" id="startCameraBtn">
                                    <i class="fas fa-play me-2"></i>Mulai Scan Kamera
                                </button>

                                <div id="activeCameraControls" class="d-none mb-3">
                                    <button class="btn btn-danger me-2" id="stopCameraBtn">
                                        <i class="fas fa-stop me-2"></i>Berhenti
                                    </button>
                                    <button class="btn btn-secondary d-none" id="switchCameraBtn">
                                        <i class="fas fa-sync-alt me-2"></i>Ganti Kamera
                                    </button>
                                </div>
                            </div>

                            <!-- Camera Info -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Arahkan kamera ke QR Code untuk scan otomatis
                            </div>

                        </div>
                    </div>

                    <!-- Upload Mode -->
                    <div id="uploadMode" class="scan-mode d-none">

                        <!-- Upload Area -->
                        <div id="uploadArea" class="text-center">
                            <div class="upload-box p-5 border border-3 border-dashed rounded">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <h5 class="mb-3">Upload Gambar QR Code</h5>
                                <p class="text-muted mb-3">Klik tombol di bawah atau seret gambar ke sini</p>

                                <input type="file" id="qrImageInput" class="d-none" accept="image/*">

                                <button type="button" class="btn btn-primary btn-lg" id="selectImageBtn">
                                    <i class="fas fa-folder-open me-2"></i>Pilih Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div id="imagePreviewArea" class="d-none text-center">
                            <img id="previewImg" src="" class="img-fluid rounded shadow mb-3" style="max-height: 400px;">
                            <p class="text-muted" id="imageFileName"></p>

                            <div class="mt-3">
                                <button class="btn btn-success btn-lg me-2" id="scanImageBtn">
                                    <i class="fas fa-search me-2"></i>Scan QR Code
                                </button>
                                <button class="btn btn-secondary btn-lg" id="changeImageBtn">
                                    <i class="fas fa-redo me-2"></i>Ganti Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Upload Info -->
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Pastikan gambar QR Code jelas dan tidak blur
                        </div>

                    </div>

                    <!-- Status Alert -->
                    <div id="statusMessage" class="alert d-none mt-3"></div>

                </div>

            </div>

            <!-- Tips -->
            <div class="alert alert-info mt-4">
                <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                <ul class="mb-0 small">
                    <li>Untuk scan kamera: Arahkan kamera dengan jarak 10-30 cm dari QR Code</li>
                    <li>Untuk upload: Pastikan gambar jelas, QR Code utuh, dan tidak terpotong</li>
                    <li>Hindari cahaya berlebihan yang membuat QR Code tidak terbaca</li>
                </ul>
            </div>

        </div>
    </div>
</div>

{{-- HTML5-QRCode Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
{{-- JSQR Library --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<style>
    .mode-btn {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.3s ease;
        border-width: 2px;
    }

    .mode-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .mode-btn.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .upload-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-box:hover {
        background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%);
        border-color: #0d6efd !important;
    }

    .upload-box.dragover {
        background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%);
        border-color: #0d6efd !important;
        transform: scale(1.02);
    }

    #reader video {
        width: 100% !important;
        border-radius: 10px;
        display: block !important;
    }

    #reader {
        border-radius: 10px;
        overflow: hidden;
        min-height: 300px;
        background: #000;
    }

    #reader__scan_region {
        min-height: 300px !important;
    }

    @media (max-width: 768px) {
        .mode-btn {
            padding: 1rem;
        }

        .mode-btn i {
            font-size: 1.5rem !important;
        }

        .upload-box {
            padding: 2rem 1rem !important;
        }
    }
</style>

<script>
let html5QrCode = null;
let cameras = [];
let currentCameraId = null;
let currentMode = 'camera';
let isScanning = false;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    initializeCamera();
    initializeUpload();
});

// ========== MODE SWITCHING ==========
function switchMode(mode) {
    currentMode = mode;

    // Update buttons
    document.getElementById('cameraModeBtn').classList.toggle('active', mode === 'camera');
    document.getElementById('cameraModeBtn').classList.toggle('btn-primary', mode === 'camera');
    document.getElementById('cameraModeBtn').classList.toggle('btn-outline-primary', mode !== 'camera');

    document.getElementById('uploadModeBtn').classList.toggle('active', mode === 'upload');
    document.getElementById('uploadModeBtn').classList.toggle('btn-primary', mode === 'upload');
    document.getElementById('uploadModeBtn').classList.toggle('btn-outline-primary', mode !== 'upload');

    // Show/hide modes
    document.getElementById('cameraMode').classList.toggle('d-none', mode !== 'camera');
    document.getElementById('uploadMode').classList.toggle('d-none', mode !== 'upload');

    // Cleanup
    if (mode === 'upload') {
        stopCamera();
    } else {
        resetUpload();
    }

    hideStatus();
}

// ========== CAMERA FUNCTIONS ==========
function initializeCamera() {
    console.log('Initializing camera...');

    // Get available cameras
    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            cameras = devices;
            console.log('Cameras found:', cameras.length, devices);

            if (cameras.length > 1) {
                document.getElementById('switchCameraBtn').classList.remove('d-none');
            }
        } else {
            console.log('No cameras found');
            showStatus('error', 'Tidak ada kamera yang terdeteksi');
        }
    }).catch(err => {
        console.error('Error getting cameras:', err);
        showStatus('error', 'Tidak dapat mengakses kamera: ' + err);
    });

    // Start camera button
    document.getElementById('startCameraBtn').addEventListener('click', startCamera);
    document.getElementById('stopCameraBtn').addEventListener('click', stopCamera);
    document.getElementById('switchCameraBtn').addEventListener('click', switchCamera);
}

function startCamera() {
    if (isScanning) {
        console.log('Camera already running');
        return;
    }

    console.log('Starting camera...');
    showStatus('warning', 'Memulai kamera...');

    // Clean up any existing instance
    if (html5QrCode) {
        try {
            html5QrCode.clear();
        } catch(e) {
            console.log('Error clearing previous instance:', e);
        }
        html5QrCode = null;
    }

    // Determine camera ID - prefer back camera
    let cameraId;
    if (currentCameraId) {
        cameraId = currentCameraId;
    } else if (cameras.length > 0) {
        // Try to find back/rear camera
        const backCamera = cameras.find(c =>
            c.label.toLowerCase().includes('back') ||
            c.label.toLowerCase().includes('rear') ||
            c.label.toLowerCase().includes('environment')
        );
        cameraId = backCamera ? backCamera.id : cameras[0].id;
    } else {
        cameraId = { facingMode: "environment" };
    }

    console.log('Using camera:', cameraId);

    // Show preview container first
    document.getElementById('cameraPreview').classList.remove('d-none');
    document.getElementById('startCameraBtn').classList.add('d-none');
    document.getElementById('activeCameraControls').classList.remove('d-none');

    // Create new instance
    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        disableFlip: false,
        videoConstraints: {
            facingMode: "environment"
        }
    };

    isScanning = true;

    html5QrCode.start(
        cameraId,
        config,
        (decodedText) => {
            console.log("QR Code detected:", decodedText);
            handleQRSuccess(decodedText);
            stopCamera();
        },
        (errorMessage) => {
            // Scanning... (silent - jangan log setiap frame)
        }
    ).then(() => {
        console.log('Camera started successfully');
        hideStatus();
    }).catch((err) => {
        console.error('Error starting camera:', err);
        showStatus('error', 'Gagal memulai kamera: ' + err);
        isScanning = false;
        html5QrCode = null;

        // Reset UI
        document.getElementById('cameraPreview').classList.add('d-none');
        document.getElementById('startCameraBtn').classList.remove('d-none');
        document.getElementById('activeCameraControls').classList.add('d-none');
    });
}

function stopCamera() {
    console.log('Stopping camera...');

    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            console.log('Camera stopped successfully');
            cleanupCamera();
        }).catch(err => {
            console.error('Error stopping camera:', err);
            cleanupCamera();
        });
    } else {
        cleanupCamera();
    }
}

function cleanupCamera() {
    if (html5QrCode) {
        try {
            html5QrCode.clear();
        } catch(e) {
            console.log('Error clearing camera:', e);
        }
        html5QrCode = null;
    }

    isScanning = false;
    document.getElementById('cameraPreview').classList.add('d-none');
    document.getElementById('startCameraBtn').classList.remove('d-none');
    document.getElementById('activeCameraControls').classList.add('d-none');
}

function switchCamera() {
    if (cameras.length > 1) {
        const currentIndex = cameras.findIndex(c => c.id === currentCameraId);
        const nextIndex = (currentIndex + 1) % cameras.length;
        currentCameraId = cameras[nextIndex].id;

        console.log('Switching to camera:', cameras[nextIndex].label);

        stopCamera();
        setTimeout(() => startCamera(), 500);
    }
}

// ========== UPLOAD FUNCTIONS ==========
function initializeUpload() {
    const uploadBox = document.querySelector('.upload-box');
    const fileInput = document.getElementById('qrImageInput');
    const selectBtn = document.getElementById('selectImageBtn');
    const scanBtn = document.getElementById('scanImageBtn');
    const changeBtn = document.getElementById('changeImageBtn');

    // Select image button
    selectBtn.addEventListener('click', () => fileInput.click());

    // Click upload box
    uploadBox.addEventListener('click', () => fileInput.click());

    // File input change
    fileInput.addEventListener('change', function(e) {
        e.stopPropagation();
        const file = this.files[0];
        if (file) {
            handleFileUpload(file);
        }
    });

    // Drag and drop
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.classList.add('dragover');
    });

    uploadBox.addEventListener('dragleave', (e) => {
        e.stopPropagation();
        uploadBox.classList.remove('dragover');
    });

    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.classList.remove('dragover');

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            handleFileUpload(file);
        } else {
            showStatus('error', 'File harus berupa gambar!');
        }
    });

    // Scan button
    scanBtn.addEventListener('click', scanUploadedImage);

    // Change button
    changeBtn.addEventListener('click', resetUpload);
}

function handleFileUpload(file) {
    if (!file.type.startsWith('image/')) {
        showStatus('error', 'File harus berupa gambar (JPG, PNG, GIF)');
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        showStatus('error', 'Ukuran file maksimal 5MB');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imageFileName').textContent = file.name;

        document.getElementById('uploadArea').classList.add('d-none');
        document.getElementById('imagePreviewArea').classList.remove('d-none');
        hideStatus();
    };
    reader.readAsDataURL(file);
}

function scanUploadedImage() {
    const file = document.getElementById('qrImageInput').files[0];
    const btn = document.getElementById('scanImageBtn');

    if (!file) {
        showStatus('error', 'Tidak ada file yang dipilih');
        return;
    }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    btn.disabled = true;

    const reader = new FileReader();
    reader.onload = function() {
        const img = new Image();
        img.src = reader.result;

        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                console.log("QR Code detected:", code.data);
                handleQRSuccess(code.data);
            } else {
                showStatus('error', 'QR Code tidak terdeteksi! Pastikan gambar jelas.');
                btn.innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
                btn.disabled = false;
            }
        };
    };
    reader.readAsDataURL(file);
}

function resetUpload() {
    document.getElementById('uploadArea').classList.remove('d-none');
    document.getElementById('imagePreviewArea').classList.add('d-none');
    document.getElementById('qrImageInput').value = '';
    document.getElementById('scanImageBtn').innerHTML = '<i class="fas fa-search me-2"></i>Scan QR Code';
    document.getElementById('scanImageBtn').disabled = false;
    hideStatus();
}

// ========== COMMON FUNCTIONS ==========
function handleQRSuccess(decodedText) {
    Swal.fire({
        icon: 'success',
        title: 'QR Code Berhasil Terbaca!',
        html: '<p class="mb-2">Sedang mengarahkan ke halaman absensi...</p><small class="text-muted">' + decodedText + '</small>',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    setTimeout(() => {
        window.location.href = decodedText;
    }, 2000);
}

function showStatus(type, message) {
    const statusEl = document.getElementById('statusMessage');
    statusEl.className = 'alert mt-3';
    statusEl.classList.remove('d-none');

    if (type === 'error') {
        statusEl.classList.add('alert-danger');
        statusEl.innerHTML = '<i class="fas fa-times-circle me-2"></i>' + message;
    } else if (type === 'success') {
        statusEl.classList.add('alert-success');
        statusEl.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + message;
    } else {
        statusEl.classList.add('alert-warning');
        statusEl.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + message;
    }
}

function hideStatus() {
    document.getElementById('statusMessage').classList.add('d-none');
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().catch(() => {});
    }
});
</script>

@endsection
