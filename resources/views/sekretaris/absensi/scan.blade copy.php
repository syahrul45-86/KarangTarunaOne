@extends('sekretaris.layouts.master')

@section('title', 'Scan QR Absensi')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h3 class="fw-bold text-center mb-3">
                <i class="fas fa-qrcode me-2"></i>
                Scan QR Absensi - {{ $form->judul }}
            </h3>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal:</strong>
                                {{ \Carbon\Carbon::parse($form->tanggal)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Waktu:</strong>
                                {{ $form->jam_mulai }} - {{ $form->jam_selesai }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="btn-group w-100 mb-3">
                        <button class="btn btn-primary w-50" id="cameraModeBtn">
                            Scan Kamera
                        </button>
                        <button class="btn btn-outline-primary w-50" id="uploadModeBtn">
                            Upload Gambar
                        </button>
                    </div>

                    <!-- CAMERA -->
                    <div id="cameraMode">
                        <div id="cameraFrame" class="border border-3 rounded text-center p-2">
                            <div id="reader" style="width:100%"></div>
                        </div>

                        <button id="startBtn" class="btn btn-success w-100 mt-3">
                            Mulai Kamera
                        </button>

                        <button id="stopBtn" class="btn btn-danger w-100 mt-2 d-none">
                            Stop Kamera
                        </button>

                        <div id="cameraStatus" class="alert mt-3 text-center d-none"></div>
                    </div>

                    <!-- UPLOAD -->
                    <div id="uploadMode" class="d-none text-center">

                        <input type="file" id="qrFile" class="d-none" accept="image/*">

                        <button class="btn btn-primary w-100" id="pickImageBtn">
                            Pilih Gambar
                        </button>

                        <img id="previewImg" class="img-fluid mt-3 d-none rounded shadow">

                        <button class="btn btn-success w-100 mt-3 d-none" id="scanImageBtn">
                            Scan Gambar
                        </button>

                        <div id="uploadStatus" class="alert mt-3 text-center d-none"></div>
                    </div>

                </div>
            </div>

            <!-- LIST -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-primary text-white">
                    Daftar Hadir
                </div>
                <div class="card-body">
                    <div id="absensiList">
                        <p class="text-muted text-center">
                            Belum ada yang absen
                        </p>
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
const formId = {{ $form->id }};
const absensiList = [];

document.addEventListener("DOMContentLoaded", function () {

    // MODE SWITCH
    cameraModeBtn.onclick = () => {
        uploadMode.classList.add("d-none");
        cameraMode.classList.remove("d-none");
    };

    uploadModeBtn.onclick = () => {
        cameraMode.classList.add("d-none");
        uploadMode.classList.remove("d-none");
    };

    // CAMERA START
    startBtn.onclick = function () {
        qrScanner = new Html5Qrcode("reader");

        qrScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            msg => handleSuccess(msg)
        );

        startBtn.classList.add("d-none");
        stopBtn.classList.remove("d-none");
    };

    stopBtn.onclick = function () {
        if (qrScanner) {
            qrScanner.stop();
            startBtn.classList.remove("d-none");
            stopBtn.classList.add("d-none");
        }
    };

    // UPLOAD MODE
    pickImageBtn.onclick = () => qrFile.click();

    qrFile.onchange = function(e) {
        let file = e.target.files[0];
        if (!file) return;

        let reader = new FileReader();
        reader.onload = function() {
            previewImg.src = reader.result;
            previewImg.classList.remove("d-none");
            scanImageBtn.classList.remove("d-none");
        };
        reader.readAsDataURL(file);
    };

    scanImageBtn.onclick = function () {
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext("2d");

        canvas.width = previewImg.naturalWidth;
        canvas.height = previewImg.naturalHeight;
        ctx.drawImage(previewImg, 0, 0);

        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let code = jsQR(imageData.data, canvas.width, canvas.height);

        if (code) handleSuccess(code.data);
        else alert("QR tidak terbaca");
    };

    // load existing list
    fetch("{{ route('sekretaris.absensi.list', $form->id) }}")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                data.absensi.forEach(item => addToList(item.user));
            }
        });
});

function handleSuccess(token) {

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
            alert(data.message);
            return;
        }

        addToList(data.user);
    })
    .catch(() => alert("Error sistem"));
}

function addToList(user) {
    absensiList.push(user);

    let list = document.getElementById("absensiList");
    list.innerHTML += `
        <div class="alert alert-success mt-2">
            ✔ ${user.name} hadir
        </div>
    `;
}
</script>

@endsection
