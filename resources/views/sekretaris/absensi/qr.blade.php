@extends('sekretaris.layouts.master')

@section('title', 'QR Absensi')

@section('content')

<div class="container-fluid px-2 px-md-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-md-10">

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>QR Code Absensi
                    </h3>
                </div>

                <div class="card-body p-2 p-md-4">

                    <!-- DETAIL FORM -->
                    <div class="mb-4">
                        <div class="alert alert-info shadow-sm border-0">
                            <h5 class="alert-heading mb-3">
                                <i class="fas fa-info-circle me-2"></i>Detail Kegiatan
                            </h5>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong><i class="fas fa-calendar-alt me-2"></i>Kegiatan:</strong>
                                    <p class="ms-4 mb-0">{{ $form->judul }}</p>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <strong><i class="fas fa-calendar-day me-2"></i>Tanggal:</strong>
                                    <p class="ms-4 mb-0">
                                        {{ \Carbon\Carbon::parse($form->tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <strong><i class="fas fa-clock me-2"></i>Waktu:</strong>
                                    <p class="ms-4 mb-0">{{ $form->jam_mulai }} - {{ $form->jam_selesai }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR CODE -->
                    <div class="text-center mb-4">
                        <div id="qrWrapper" class="p-4 bg-white border rounded shadow-sm d-inline-block">
                            {!! QrCode::format('svg')->size(300)->margin(2)->generate(url('/absen/' . $form->qr_token)) !!}
                        </div>

                        <p class="text-muted mt-3 small">
                            <i class="fas fa-mobile-alt me-1"></i>Scan QR Code untuk melakukan absensi
                        </p>
                    </div>

                    <!-- PETUNJUK -->
                    <div class="alert alert-warning border-0 shadow-sm">
                        <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Petunjuk:</h6>
                        <ol class="mb-0 ps-3">
                            <li>Scan QR menggunakan kamera smartphone</li>
                            <li>Pastikan internet stabil</li>
                            <li>Absensi hanya berlaku sesuai waktu</li>
                        </ol>
                    </div>

                    <!-- TOMBOL -->
                    <div class="row g-2 mt-4 justify-content-center">
                        <div class="col-12 col-md-4">
                            <button onclick="downloadQR()" class="btn btn-secondary w-100">
                                <i class="fas fa-download me-1"></i>Download QR
                            </button>
                        </div>

                        <div class="col-12 col-md-4">
                            <a href="{{ route('sekretaris.absensi.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function downloadQR() {
    const svgElement = document.querySelector("#qrWrapper svg");
    const svgData = new XMLSerializer().serializeToString(svgElement);

    // FIX UTAMA → Tambah viewBox supaya QR tidak terpotong
    if (!svgElement.getAttribute("viewBox")) {
        const size = svgElement.getAttribute("width") || 300;
        svgElement.setAttribute("viewBox", `0 0 ${size} ${size}`);
    }

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    const img = new Image();

    img.onload = function() {
        canvas.width = img.width + 40;  // padding kiri kanan
        canvas.height = img.height + 40; // padding atas bawah

        // background putih
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.drawImage(img, 20, 20); // gambar QR di tengah dengan padding

        const pngFile = canvas.toDataURL("image/png");
        const link = document.createElement("a");
        link.download = "QR-Absensi-{{ $form->qr_token }}.png";
        link.href = pngFile;
        link.click();
    };

    img.src = "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svgData)));
}
</script>

@endsection
