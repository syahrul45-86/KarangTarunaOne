    @extends($layout)

    @section('title', 'Scan QR Absensi')

    @section('content')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <h3 class="fw-bold text-center mb-3">
                    <i class="fas fa-qrcode me-2"></i>Scan QR Absensi
                </h3>

                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <!-- MODE SWITCH -->
                        <div class="btn-group w-100 mb-3">
                            <button class="btn btn-primary w-50" id="cameraModeBtn">Scan Kamera</button>
                            <button class="btn btn-outline-primary w-50" id="uploadModeBtn">Upload Gambar</button>
                        </div>

                        <!-- CAMERA MODE -->
                        <div id="cameraMode">
                            <!-- Kamera Frame dengan indikator -->
                            <div id="cameraFrame" class="border border-3 rounded text-center p-2"
                                style="border-color: #6c757d;">
                                <div id="reader" style="width: 100%; height: auto;"></div>
                            </div>

                            <button id="startBtn" class="btn btn-success w-100 mt-3">
                                <i class="fas fa-play me-2"></i>Mulai Kamera
                            </button>

                            <button id="stopBtn" class="btn btn-danger w-100 mt-2 d-none">
                                <i class="fas fa-stop me-2"></i>Stop Kamera
                            </button>

                            <div id="cameraStatus" class="alert mt-3 text-center d-none"></div>
                        </div>

                        <!-- UPLOAD MODE -->
                        <div id="uploadMode" class="d-none text-center">

                            <input type="file" id="qrFile" class="d-none" accept="image/*">

                            <button class="btn btn-primary w-100" id="pickImageBtn">
                                <i class="fas fa-upload me-2"></i>Pilih Gambar
                            </button>

                            <img id="previewImg" class="img-fluid mt-3 d-none rounded shadow">

                            <button class="btn btn-success w-100 mt-3 d-none" id="scanImageBtn">
                                <i class="fas fa-search me-2"></i>Scan Gambar
                            </button>

                            <div id="uploadStatus" class="alert mt-3 text-center d-none"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <script>
    let qrScanner = null;

    /* ======================================
    MODE SWITCH
    ====================================== */
    document.getElementById("cameraModeBtn").onclick = function() {
        stopCamera();
        showCameraMode();
    };

    document.getElementById("uploadModeBtn").onclick = function() {
        stopCamera();
        showUploadMode();
    };

    function showCameraMode() {
        document.getElementById("cameraMode").classList.remove("d-none");
        document.getElementById("uploadMode").classList.add("d-none");

        document.getElementById("cameraModeBtn").classList.replace("btn-outline-primary","btn-primary");
        document.getElementById("uploadModeBtn").classList.replace("btn-primary","btn-outline-primary");
    }

    function showUploadMode() {
        document.getElementById("cameraMode").classList.add("d-none");
        document.getElementById("uploadMode").classList.remove("d-none");

        document.getElementById("uploadModeBtn").classList.replace("btn-outline-primary","btn-primary");
        document.getElementById("cameraModeBtn").classList.replace("btn-primary","btn-outline-primary");
    }


    /* ======================================
    CAMERA MODE
    ====================================== */

    document.getElementById("startBtn").onclick = function() {

        qrScanner = new Html5Qrcode("reader");

        qrScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            qrCodeMessage => {
                showCameraStatus("success", "QR Berhasil Terbaca!");
                setGreenBorder();
                handleSuccess(qrCodeMessage);
                stopCamera();
            },
            errorMessage => {
                setRedBorder();
                showCameraStatus("error", "QR belum terbaca...");
            }
        ).then(() => {
            document.getElementById("startBtn").classList.add("d-none");
            document.getElementById("stopBtn").classList.remove("d-none");

        }).catch(err => {
            showCameraStatus("error", "Tidak bisa akses kamera: " + err);
        });
    };

    document.getElementById("stopBtn").onclick = stopCamera;

    function stopCamera() {
        if (qrScanner) {
            qrScanner.stop().then(() => {
                qrScanner.clear();
                qrScanner = null;
                document.getElementById("startBtn").classList.remove("d-none");
                document.getElementById("stopBtn").classList.add("d-none");
                resetBorder();
            });
        }
    }


    /* Kamera Border Indicator */
    function setGreenBorder() {
        document.getElementById("cameraFrame").style.borderColor = "green";
    }
    function setRedBorder() {
        document.getElementById("cameraFrame").style.borderColor = "red";
    }
    function resetBorder() {
        document.getElementById("cameraFrame").style.borderColor = "#6c757d";
    }


    /* Kamera Status Text */
    function showCameraStatus(type, msg) {
        let box = document.getElementById("cameraStatus");
        box.classList.remove("d-none");
        box.className = "alert mt-3 text-center";

        if (type === "success") box.classList.add("alert-success");
        else box.classList.add("alert-danger");

        box.innerText = msg;
    }


    /* ======================================
    UPLOAD MODE
    ====================================== */
    document.getElementById("pickImageBtn").onclick = () => {
        document.getElementById("qrFile").click();
    };

    document.getElementById("qrFile").onchange = function(e) {
        let file = e.target.files[0];
        if (!file) return;

        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById("previewImg").src = reader.result;
            document.getElementById("previewImg").classList.remove("d-none");
            document.getElementById("scanImageBtn").classList.remove("d-none");
        };
        reader.readAsDataURL(file);
    };

    document.getElementById("scanImageBtn").onclick = function () {
        let img = document.getElementById("previewImg");
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext("2d");

        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        ctx.drawImage(img, 0, 0);

        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let code = jsQR(imageData.data, canvas.width, canvas.height);

        if (code) {
            showUploadStatus("success", "QR Berhasil Terbaca!");
            handleSuccess(code.data);
        } else {
            showUploadStatus("error", "QR tidak terbaca. Pastikan gambar jelas.");
        }
    };

    function showUploadStatus(type, msg) {
        let box = document.getElementById("uploadStatus");
        box.classList.remove("d-none");
        box.className = "alert mt-3 text-center";

        if (type === "success") box.classList.add("alert-success");
        else box.classList.add("alert-danger");

        box.innerText = msg;
    }


    /* ======================================
    COMMON SUCCESS
    ====================================== */
    function handleSuccess(result) {
        Swal.fire({
            icon: "success",
            title: "QR Berhasil!",
            html: "Mengalihkan ke halaman absensi...",
            timer: 1800,
            showConfirmButton: false
        });

        setTimeout(() => {
            window.location.href = result;
        }, 1700);
    }

    </script>

    @endsection
