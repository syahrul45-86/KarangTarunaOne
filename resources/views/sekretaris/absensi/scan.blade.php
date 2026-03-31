@extends('sekretaris.layouts.master')

@section('title', 'Scan QR Absensi')

@section('content')

<style>
    /* ========================================
       MODERN UI STYLING
    ======================================== */

    .scan-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 25px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        margin-bottom: 25px;
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-card h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-card .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .info-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 12px 16px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .info-item strong {
        display: block;
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 4px;
    }

    .info-item span {
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Scanner Card */
    .scanner-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 25px;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .scanner-card .card-body {
        padding: 30px;
    }

    /* Mode Buttons */
    .mode-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        background: #f1f3f5;
        border-radius: 12px;
        padding: 4px;
        margin-bottom: 25px;
    }

    .mode-btn {
        padding: 12px 20px;
        border: none;
        background: transparent;
        color: #495057;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .mode-btn:hover {
        color: #667eea;
    }

    .mode-btn.active {
        background: white;
        color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }

    /* Camera Frame */
    #cameraFrame {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #000;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #e9ecef;
        transition: border-color 0.3s ease;
    }

    #cameraFrame.scanning {
        border-color: #ffc107;
        animation: pulse 2s infinite;
    }

    #cameraFrame.success {
        border-color: #28a745;
    }

    #cameraFrame.error {
        border-color: #dc3545;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
        }
    }

    #reader {
        width: 100%;
    }

    /* Action Buttons */
    .action-btn {
        padding: 14px 28px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        margin-top: 15px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .btn-start {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-stop {
        background: linear-gradient(135deg, #EC0303 0%, #f5576c 100%);
        color: white;
    }

    .btn-upload {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .btn-scan {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    /* Preview Image */
    #previewImg {
        max-height: 350px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Status Alert */
    .status-alert {
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Daftar Hadir Card */
    .attendance-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeIn 0.8s ease;
    }

    .attendance-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .attendance-header h5 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .attendance-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .attendance-list {
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .attendance-list::-webkit-scrollbar {
        width: 8px;
    }

    .attendance-list::-webkit-scrollbar-track {
        background: #f1f3f5;
        border-radius: 10px;
    }

    .attendance-list::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #adb5bd;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Attendance Item */
    .attendance-item {
        background: linear-gradient(135deg, #e0f7e9 0%, #d4f1f4 100%);
        border-left: 4px solid #28a745;
        padding: 15px 18px;
        border-radius: 10px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
        animation: itemSlideIn 0.4s ease;
        transition: all 0.3s ease;
    }

    .attendance-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
    }

    @keyframes itemSlideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .attendance-icon {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .attendance-info {
        flex: 1;
    }

    .attendance-name {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.05rem;
        margin-bottom: 2px;
    }

    .attendance-email {
        color: #718096;
        font-size: 0.85rem;
    }

    .attendance-time {
        background: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #28a745;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .scan-container {
            padding: 15px;
        }

        .header-card {
            padding: 20px;
        }

        .header-card h3 {
            font-size: 1.25rem;
        }

        .scanner-card .card-body {
            padding: 20px;
        }

        .attendance-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .attendance-time {
            align-self: flex-end;
        }
    }

    /* Loading Spinner */
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="scan-container">
    <!-- Header Card -->
    <div class="header-card">
        <h3>
            <i class="fas fa-qrcode"></i>
            {{ $form->judul }}
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <strong><i class="far fa-calendar"></i> Tanggal</strong>
                <span>{{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}</span>
            </div>
            <div class="info-item">
                <strong><i class="far fa-clock"></i> Waktu</strong>
                <span>{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</span>
            </div>
        </div>
    </div>

    <!-- Scanner Card -->
    <div class="scanner-card">
        <div class="card-body">
            <!-- Mode Toggle -->
            <div class="mode-toggle">
                <button class="mode-btn active" id="cameraModeBtn">
                    <i class="fas fa-camera"></i>
                    Scan Kamera
                </button>
                <button class="mode-btn" id="uploadModeBtn">
                    <i class="fas fa-upload"></i>
                    Upload Gambar
                </button>
            </div>

            <!-- CAMERA MODE -->
            <div id="cameraMode">
                <div id="cameraFrame">
                    <div id="reader"></div>
                </div>

                <button id="startBtn" class="action-btn btn-start">
                    <i class="fas fa-play"></i>
                    Mulai Scan
                </button>

                <button id="stopBtn" class="action-btn btn-stop d-none">
                    <i class="fas fa-stop"></i>
                    Stop Scan
                </button>

                <div id="cameraStatus" class="d-none"></div>
            </div>

            <!-- UPLOAD MODE -->
            <div id="uploadMode" class="d-none">
                <input type="file" id="qrFile" class="d-none" accept="image/*">

                <div class="text-center">
                    <button class="action-btn btn-upload" id="pickImageBtn">
                        <i class="fas fa-image"></i>
                        Pilih Gambar QR
                    </button>

                    <img id="previewImg" class="img-fluid mt-3 d-none">

                    <button class="action-btn btn-scan d-none" id="scanImageBtn">
                        <i class="fas fa-search"></i>
                        Scan QR dari Gambar
                    </button>
                </div>

                <div id="uploadStatus" class="d-none"></div>
            </div>
        </div>
    </div>

    <!-- Daftar Hadir -->
    <div class="attendance-card">
        <div class="attendance-header">
            <h5>
                <i class="fas fa-users"></i>
                Daftar Hadir
            </h5>
            <div class="attendance-count">
                <span id="attendanceCount">0</span> Hadir
            </div>
        </div>
        <div class="attendance-list" id="absensiList">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>Belum ada yang melakukan absensi</p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let qrScanner = null;
const formId = {{ $form->id }};
const absensiList = [];

document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // MODE SWITCH
    // ==========================================
    const cameraModeBtn = document.getElementById('cameraModeBtn');
    const uploadModeBtn = document.getElementById('uploadModeBtn');
    const cameraMode = document.getElementById('cameraMode');
    const uploadMode = document.getElementById('uploadMode');

    cameraModeBtn.onclick = () => {
        cameraModeBtn.classList.add('active');
        uploadModeBtn.classList.remove('active');
        uploadMode.classList.add('d-none');
        cameraMode.classList.remove('d-none');
        stopCamera();
    };

    uploadModeBtn.onclick = () => {
        uploadModeBtn.classList.add('active');
        cameraModeBtn.classList.remove('active');
        cameraMode.classList.add('d-none');
        uploadMode.classList.remove('d-none');
        stopCamera();
    };

    // ==========================================
    // CAMERA MODE
    // ==========================================
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const cameraFrame = document.getElementById('cameraFrame');

    let isProcessing = false; // Prevent multiple scans
    let lastScannedToken = null; // Track last scanned token

    startBtn.onclick = function () {
        qrScanner = new Html5Qrcode("reader");
        isProcessing = false;
        lastScannedToken = null;

        qrScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            msg => {
                // Prevent duplicate scanning
                if (isProcessing || msg === lastScannedToken) {
                    return;
                }

                isProcessing = true;
                lastScannedToken = msg;

                cameraFrame.classList.remove('scanning');
                cameraFrame.classList.add('success');

                handleSuccess(msg);
            },
            error => {
                // Scanning...
            }
        ).then(() => {
            startBtn.classList.add('d-none');
            stopBtn.classList.remove('d-none');
            cameraFrame.classList.add('scanning');
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Akses Kamera',
                text: 'Pastikan browser memiliki izin akses kamera',
                confirmButtonColor: '#667eea'
            });
        });
    };

    stopBtn.onclick = stopCamera;

    function stopCamera() {
        if (qrScanner) {
            qrScanner.stop().then(() => {
                qrScanner.clear();
                qrScanner = null;
                startBtn.classList.remove('d-none');
                stopBtn.classList.add('d-none');
                cameraFrame.classList.remove('scanning', 'success', 'error');
                isProcessing = false;
                lastScannedToken = null;
            });
        }
    }

    // ==========================================
    // UPLOAD MODE
    // ==========================================
    const pickImageBtn = document.getElementById('pickImageBtn');
    const qrFile = document.getElementById('qrFile');
    const previewImg = document.getElementById('previewImg');
    const scanImageBtn = document.getElementById('scanImageBtn');

    pickImageBtn.onclick = () => qrFile.click();

    qrFile.onchange = function(e) {
        let file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function() {
            previewImg.src = reader.result;
            previewImg.classList.remove('d-none');
            scanImageBtn.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    };

    scanImageBtn.onclick = function () {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        canvas.width = previewImg.naturalWidth;
        canvas.height = previewImg.naturalHeight;
        ctx.drawImage(previewImg, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);

        if (code) {
            handleSuccess(code.data);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'QR Tidak Terbaca',
                text: 'Pastikan gambar QR code jelas dan tidak blur',
                confirmButtonColor: '#667eea'
            });
        }
    };

    // ==========================================
    // HANDLE SUCCESS SCAN
    // ==========================================
    function handleSuccess(token) {
        // Show loading
        Swal.fire({
            title: 'Memproses...',
            html: 'Validasi QR Code',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('sekretaris.absensi.process', $form->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonColor: '#667eea',
                    timer: 3000,
                    showConfirmButton: true
                }).then(() => {
                    // Reset after showing error - allow scanning again after 2 seconds
                    setTimeout(() => {
                        isProcessing = false;
                        lastScannedToken = null;
                        cameraFrame.classList.remove('success', 'error');
                        cameraFrame.classList.add('scanning');
                    }, 2000);
                });
                return;
            }

            // Success animation
            Swal.fire({
                icon: 'success',
                title: 'Absensi Berhasil!',
                html: `
                    <div style="font-size: 1.1rem; margin-top: 10px;">
                        <strong>${data.user.name}</strong><br>
                        <small style="color: #718096;">${data.user.email}</small><br>
                        <div style="margin-top: 10px; padding: 8px; background: #e0f7e9; border-radius: 8px; color: #28a745;">
                            <i class="fas fa-clock"></i> ${data.user.waktu_absen}
                        </div>
                    </div>
                `,
                confirmButtonColor: '#667eea',
                timer: 2500,
                showConfirmButton: false
            }).then(() => {
                // Reset after success - allow scanning next person after 2 seconds
                setTimeout(() => {
                    isProcessing = false;
                    lastScannedToken = null;
                    cameraFrame.classList.remove('success');
                    cameraFrame.classList.add('scanning');
                }, 2000);
            });

            addToList(data.user);
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem',
                confirmButtonColor: '#667eea'
            }).then(() => {
                // Reset after error
                setTimeout(() => {
                    isProcessing = false;
                    lastScannedToken = null;
                    cameraFrame.classList.remove('success', 'error');
                    cameraFrame.classList.add('scanning');
                }, 2000);
            });
        });
    }

    // ==========================================
    // ADD TO LIST
    // ==========================================
    function addToList(user) {
        // Remove empty state if exists
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        // Add to array
        absensiList.push(user);

        // Update count
        document.getElementById('attendanceCount').textContent = absensiList.length;

        // Add item
        const list = document.getElementById('absensiList');
        const item = document.createElement('div');
        item.className = 'attendance-item';
        item.innerHTML = `
            <div class="attendance-icon">
                ✓
            </div>
            <div class="attendance-info">
                <div class="attendance-name">${user.name}</div>
                <div class="attendance-email">${user.email}</div>
            </div>
            <div class="attendance-time">
                <i class="fas fa-clock"></i>
                ${user.waktu_absen}
            </div>
        `;

        list.insertBefore(item, list.firstChild);
    }

    // ==========================================
    // LOAD EXISTING LIST
    // ==========================================
    fetch("{{ route('sekretaris.absensi.list', $form->id) }}")
        .then(res => res.json())
        .then(data => {
            if (data.success && data.absensi.length > 0) {
                document.querySelector('.empty-state')?.remove();

                data.absensi.reverse().forEach(item => {
                    absensiList.push(item.user);

                    const list = document.getElementById('absensiList');
                    const attendanceItem = document.createElement('div');
                    attendanceItem.className = 'attendance-item';
                    attendanceItem.innerHTML = `
                        <div class="attendance-icon">
                            ✓
                        </div>
                        <div class="attendance-info">
                            <div class="attendance-name">${item.user.name}</div>
                            <div class="attendance-email">${item.user.email}</div>
                        </div>
                        <div class="attendance-time">
                            <i class="fas fa-clock"></i>
                            ${item.waktu_absen}
                        </div>
                    `;
                    list.appendChild(attendanceItem);
                });

                document.getElementById('attendanceCount').textContent = absensiList.length;
            }
        });
});
</script>

@endsection
