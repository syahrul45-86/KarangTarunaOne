@extends(auth()->user()->role . '.layouts.master')

@section('title', 'ID Card & QR Code Saya')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<style>
    * {
        box-sizing: border-box;
    }
    
    :root {
        --card-max-width: 400px;
        --card-border-radius: 15px;
        --spacing-base: 20px;
        --color-primary: #0c4a6e;
    }

    .idcard-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .card-wrapper {
        background: white;
        border-radius: var(--card-border-radius);
        padding: calc(var(--spacing-base) * 1.5);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        margin-bottom: var(--spacing-base);
    }

    .card-title {
        text-align: center;
        color: var(--color-primary);
        font-size: clamp(18px, 5vw, 24px);
        font-weight: bold;
        margin-bottom: calc(var(--spacing-base) * 1.25);
    }

    /* ID Card Preview */
    #idcard-preview {
        position: relative;
        width: 100%;
        max-width: var(--card-max-width);
        margin: 0 auto;
        overflow: hidden;
        transition: transform 0.3s ease;
        /* Asumsi rasio standar ID Card 2:3 */
        aspect-ratio: 2 / 3;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        background-color: #ffffff;
    }

    #idcard-preview:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }

    .template-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        z-index: 1;
    }

    /* Foto Profil di dalam lingkaran */
    .user-photo {
        position: absolute;
        top: 13.5%; /* Sedikit disesuaikan */
        left: 50%;
        transform: translateX(-50%);
        width: 33%; /* Sesuaikan ukuran */
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        object-fit: cover;
        z-index: 2;
        /* Tambahkan border putih tebal */
        border: 4px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    /* Nama Anggota */
    .user-name {
        position: absolute;
        top: 42%;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        text-align: center;
        font-weight: bold;
        font-size: clamp(16px, 4vw, 22px);
        color: #ffffff; /* Ubah ke putih agar lebih kontras di background biru gelap */
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5); /* Tambahkan shadow agar lebih jelas */
        z-index: 2;
        line-height: 1.2;
    }

    /* QR Code di tengah / bagian bawah */
    .qr-code {
        position: absolute;
        top: 64%; /* Sesuaikan agar pas di tengah bracket template */
        left: 50%;
        transform: translate(-50%, -50%);
        width: 48%; /* Perbesar QR Code agar hampir memenuhi bracket putih */
        aspect-ratio: 1 / 1;
        z-index: 2;
        background: white;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .qr-code img {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* Nama RT di bawah tulisan Anggota Karang Taruna */
    .rt-name {
        position: absolute;
        bottom: 5%; /* Posisikan di bagian bawah ID card */
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        text-align: center;
        font-weight: 500;
        font-size: clamp(12px, 3.5vw, 16px);
        color: #ffffff;
        letter-spacing: 1.5px;
        z-index: 2;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .btn-custom {
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
    }

    .btn-download {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(22, 163, 74, 0.4);
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-id-badge text-primary mr-2"></i> ID Card & QR Code
    </h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-12">
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body">
                <div class="idcard-container">
                    
                    <div id="idcard-preview">
                        <!-- Template Background -->
                        <img src="{{ asset('template qr.png') }}" class="template-image" alt="Template ID Card" crossorigin="anonymous">

                        <!-- User Photo -->
                        @php
                            $photoUrl = $anggota->image ? asset('storage/' . $anggota->image) : asset('template/img/undraw_profile.svg');
                        @endphp
                        <img src="{{ $photoUrl }}" class="user-photo" alt="User Photo" crossorigin="anonymous">

                        <!-- User Name -->
                        <div class="user-name">
                            {{ strtoupper($anggota->name) }}
                        </div>

                        <!-- QR Code -->
                        <div class="qr-code">
                            <img src="{{ $qrUrl }}" alt="QR Code" crossorigin="anonymous">
                        </div>

                        <!-- RT Name -->
                        <div class="rt-name">
                            {{ $anggota->rt->nama_rt ?? '' }}
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 w-100 text-center" style="max-width: 400px;">
                        <i class="fas fa-info-circle mr-1"></i> Tunjukkan ID Card ini saat melakukan absensi kegiatan RT.
                    </div>

                    <div class="button-group">
                        <button onclick="downloadCard()" class="btn-custom btn-download" id="btn-download">
                            <i class="fas fa-download"></i>
                            <span>Download ID Card</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadCard() {
    const card = document.getElementById('idcard-preview');
    const btnDownload = document.getElementById('btn-download');
    
    // Ubah teks tombol saat proses loading
    const originalText = btnDownload.innerHTML;
    btnDownload.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
    btnDownload.disabled = true;

    // Tambahkan sedikit delay agar DOM siap
    setTimeout(() => {
        const isMobile = window.innerWidth <= 768;
        const scale = isMobile ? 3 : 4; // Kualitas tinggi untuk download

        html2canvas(card, {
            scale: scale,
            useCORS: true,           // Penting untuk meload gambar dari luar (storage/asset)
            allowTaint: true,
            backgroundColor: null,
            logging: false,
        }).then(canvas => {
            // Buat link download
            const link = document.createElement('a');
            const userName = '{{ str_replace(" ", "_", $anggota->name) }}';
            link.download = `ID_Card_KarangTaruna_${userName}.png`;
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();

            // Kembalikan tombol
            btnDownload.innerHTML = originalText;
            btnDownload.disabled = false;

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'ID Card berhasil diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(error => {
            console.error('Error generating ID card:', error);
            btnDownload.innerHTML = originalText;
            btnDownload.disabled = false;
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengunduh ID Card.',
            });
        });
    }, 300);
}
</script>

@endsection
