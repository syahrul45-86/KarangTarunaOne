@extends($layout)

@section('title', 'Scan QR Absensi')

@section('content')

<div class="container mt-5 text-center">

    <h3 class="fw-bold mb-4">Scan QR Absensi</h3>
    <p class="text-muted">Unggah gambar QR Code untuk melakukan absensi.</p>

    <div class="card shadow p-4 mx-auto" style="max-width: 450px;">

        <form id="qrForm">
            @csrf

            <input type="file" id="qrImage" class="form-control mb-3" accept="image/*" required>

            <button type="submit" class="btn btn-primary w-100">
                Upload Gambar QR
            </button>
        </form>

        <p id="statusText" class="mt-3 text-danger fw-bold" style="display:none;"></p>

    </div>

</div>

{{-- JSQR Library --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
    document.getElementById('qrForm').addEventListener('submit', function (e) {
        e.preventDefault();

        let fileInput = document.getElementById('qrImage');
        let file = fileInput.files[0];

        if (!file) {
            alert("Pilih gambar QR terlebih dahulu!");
            return;
        }

        let reader = new FileReader();

        reader.onload = function () {
            let img = new Image();
            img.src = reader.result;

            img.onload = function () {

                // Buat canvas untuk decode
                let canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;

                let ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) {
                    console.log("QR:", code.data);

                    document.getElementById("statusText").style.display = "none";

                    // Kasih pesan berhasil
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Berhasil Terbaca!',
                        text: 'Sedang mengarahkan ke halaman absensi...',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Redirect ke URL QR
                    setTimeout(() => {
                        window.location.href = code.data;
                    }, 1500);

                } else {
                    document.getElementById("statusText").style.display = "block";
                    document.getElementById("statusText").innerHTML = "QR tidak terbaca, coba gambar lain...";
                }
            }
        };

        reader.readAsDataURL(file);
    });
</script>

@endsection
