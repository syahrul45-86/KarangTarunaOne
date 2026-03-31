@extends('admin.layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<style>
    * {
        box-sizing: border-box;
    }

    :root {
        /* Variabel CSS untuk konsistensi */
        --card-max-width: 400px;
        --card-border-radius: 15px;
        --spacing-base: 20px;
        --color-primary: #0c4a6e;
        --color-gradient-start: #667eea;
        --color-gradient-end: #764ba2;
    }

    .idcard-container {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--color-gradient-start) 0%, var(--color-gradient-end) 100%);
        padding: var(--spacing-base);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .card-wrapper {
        background: white;
        border-radius: var(--card-border-radius);
        padding: calc(var(--spacing-base) * 1.5);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 600px;
        width: 100%;
        margin-bottom: var(--spacing-base);
    }

    .card-title {
        text-align: center;
        color: var(--color-primary);
        font-size: clamp(18px, 5vw, 28px);
        font-weight: bold;
        margin-bottom: calc(var(--spacing-base) * 1.25);
    }

    /* ID Card Preview - Menggunakan aspect ratio untuk konsistensi */
    #idcard-preview {
        position: relative;
        width: 100%;
        max-width: var(--card-max-width);
        margin: 0 auto;
        overflow: hidden;
        transition: transform 0.3s ease;
        /* Rasio 2:3 untuk ID card standar */
        aspect-ratio: 2 / 3;
    }

    #idcard-preview:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .template-image {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    /* Posisi elemen menggunakan PERSENTASE untuk konsistensi di semua ukuran */
    .user-photo {
        position: absolute;
        /* Posisi vertikal: 5% dari atas */
        top: 5%;
        left: 50%;
        transform: translateX(-50%);
        /* Ukuran: 30% dari lebar container */
        width: 30%;
        /* Aspect ratio 1:1 untuk foto bundar */
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        object-fit: cover;
        /* Border: 1.5% dari lebar container */
        border: calc(var(--card-max-width) * 0.015) solid #ffffff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .user-name {
        position: absolute;
        /* Posisi vertikal: 37% dari atas (konsisten dengan foto di 5% + 30% + spacing) */
        top: 37%;
        width: 100%;
        text-align: center;
        font-weight: bold;
        /* Font size responsif tapi konsisten */
        font-size: clamp(14px, 4vw, 28px);
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: clamp(1px, 0.3vw, 2px);
        padding: 0 5%;
        line-height: 1.2;
    }

    .qr-code {
        position: absolute;
        /* Posisi vertikal: 12% dari bawah */
        bottom: 12%;
        left: 50%;
        transform: translateX(-50%);
        /* Ukuran: 28% dari lebar container */
        width: 30%;
        aspect-ratio: 1 / 1;
        padding: 2%;

    }

    .qr-code img {
        width: 100%;
        height: 100%;
        display: block;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: calc(var(--spacing-base) * 1.25);
        flex-wrap: wrap;
    }

    .btn-custom {
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-size: clamp(13px, 3vw, 16px);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-download {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(22, 163, 74, 0.4);
    }

    .btn-back {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(100, 116, 139, 0.4);
    }

    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #16a34a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .info-badge {
        text-align: center;
        margin-top: var(--spacing-base);
        padding: 10px;
        background: #f0f9ff;
        border-radius: 8px;
        color: var(--color-primary);
        font-size: clamp(11px, 2.5vw, 14px);
    }

    .loading-text {
        color: white;
        margin-top: 20px;
        text-align: center;
        font-size: clamp(12px, 3vw, 16px);
        padding: 0 20px;
    }

    /* ========================================
       MEDIA QUERIES - Dari terbesar ke terkecil
       ======================================== */

    /* Desktop & Tablet Landscape */
    @media (min-width: 769px) {
        :root {
            --spacing-base: 30px;
        }

        .card-wrapper {
            padding: 40px;
        }
        .user-photo {
            width: 40%;
            top: 10%;
        }
        .user-name{
            top: 41%;
        }
        /* QR Code sedikit lebih besar di mobile */
        .qr-code {
            width: 48%;
            bottom: 18%;
        }
    }

    /* Tablet Portrait */
    @media (max-width: 768px) {
        :root {
            --spacing-base: 20px;
        }

        .user-photo {
            width: 40%;
            top: 10%;
        }
        .user-name{
            top: 41%;
        }
        /* QR Code sedikit lebih besar di mobile */
        .qr-code {
            width: 50%;
            bottom: 17%;
        }
    }

    /* SEMUA SMARTPHONE (320px - 480px) - STYLING KONSISTEN */
    @media (max-width: 480px) {
        :root {
            --spacing-base: 15px;
            --card-border-radius: 12px;
        }

        .idcard-container {
            padding: 15px 10px;
        }

        .card-wrapper {
            padding: 20px 15px;
        }

        #idcard-preview {
            /* Di mobile, gunakan hampir full width */
            max-width: 100%;
        }

        .button-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
        }

        /* Foto sedikit lebih besar di mobile agar terlihat jelas */
        .user-photo {
            width: 40%;
            top: 10%;
        }
        .user-name{
            top: 41%;
        }
        /* QR Code sedikit lebih besar di mobile */
        .qr-code {
            width: 45%;
            bottom: 17%;
        }
    }

    /* Hanya untuk device SANGAT kecil (≤320px) */
    @media (max-width: 320px) {
        :root {
            --spacing-base: 12px;
            --card-border-radius: 10px;
        }

        .card-wrapper {
            padding: 15px 10px;
        }

        .btn-custom {
            padding: 10px 15px;
            font-size: 12px;
        }
    }

    /* Landscape mode untuk semua mobile */
    @media (max-height: 600px) and (orientation: landscape) {
        .idcard-container {
            padding: 15px 10px;
        }

        #idcard-preview {
            max-width: 280px;
        }

        .button-group {
            flex-direction: row;
            margin-top: 15px;
        }

        .btn-custom {
            width: auto;
            padding: 10px 20px;
        }
        .user-photo {
            width: 40%;
            top: 10%;
        }
        .user-name{
            top: 41%;
        }
        /* QR Code sedikit lebih besar di mobile */
        .qr-code {
            width: 45%;
            bottom: 20%;
        }
    }



    /* High DPI screens */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .template-image,
        .user-photo,
        .qr-code img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        to {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
    }
</style>
<div class="idcard-container">
    <div class="card-wrapper">
        <h2 class="card-title">🎫 ID Card Preview</h2>

        <div id="idcard-preview">
            <!-- Template Background -->
            <img src="{{ asset('storage/' . $templatePath) }}"
                 class="template-image"
                 alt="ID Card Template">

            <!-- User Photo -->
            <img src="{{ asset('storage/' . $user->image) }}"
                 class="user-photo"
                 alt="User Photo">

            <!-- User Name -->
            <div class="user-name">
                {{ strtoupper($user->name) }}
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                <img src="{{ asset('storage/' . $qrPath) }}" alt="QR Code">
            </div>
        </div>

        <div class="info-badge">
            💡 Klik tombol download untuk menyimpan ID Card
        </div>

        <div class="button-group">
            <button onclick="saveCard()" class="btn-custom btn-save">
                <span>💾</span>
                <span>Simpan</span>
            </button>
            <button onclick="downloadCard()" class="btn-custom btn-download">
                <span>📥</span>
                <span>Download PNG</span>
            </button>
            <a href="{{ route('admin.AnggotaRT.index') }}" class="btn-custom btn-back">
                <span>←</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div>
        <div class="spinner"></div>
        <p style="color: white; margin-top: 20px; text-align: center;">Generating ID Card...</p>
    </div>
</div>

<script>

// ✅ TAMBAHKAN INI - Define userId dari blade
const userId = {{ $user->id }};

// save id card
function saveCard() {
    const card = document.getElementById('idcard-preview');
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.add('active');

    setTimeout(() => {
        const isMobile = window.innerWidth <= 768;
        const scale = isMobile ? 2 : 3;

        html2canvas(card, {
            scale: scale,
            useCORS: true,
            backgroundColor: null,
            logging: false,
            imageTimeout: 0,
            allowTaint: true, // ✅ PERBAIKI TYPO (ture → true)
            width: card.offsetWidth,
            height: card.offsetHeight
        }).then(canvas => {
            const imageData = canvas.toDataURL('image/png', 1.0);

            fetch("{{ route('admin.idcard.save') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    image_data: imageData
                })
            })
            .then(response => response.json())
            .then(data => {
                overlay.classList.remove('active');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Disimpan!',
                        text: 'ID Card berhasil disimpan ke database',
                        confirmButtonColor: '#3b82f6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('admin.AnggotaRT.index') }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                overlay.classList.remove('active');
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem',
                    confirmButtonColor: '#ef4444'
                });
            });
        }).catch(error => {
            console.error('Canvas error:', error);
            overlay.classList.remove('active');
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal generate ID Card',
                confirmButtonColor: '#ef4444'
            });
        });
    }, 100);
}
//
function downloadCard() {
    const card = document.getElementById('idcard-preview');
    const overlay = document.getElementById('loadingOverlay');

    // Show loading
    overlay.classList.add('active');

    // Small delay to ensure overlay is visible
    setTimeout(() => {
        // Determine scale based on device
        const isMobile = window.innerWidth <= 768;
        const scale = isMobile ? 2 : 3;

        html2canvas(card, {
            scale: scale,
            useCORS: true,
            backgroundColor: null,
            logging: false,
            imageTimeout: 0,
            allowTaint: true,
            width: card.offsetWidth,
            height: card.offsetHeight
        }).then(canvas => {
            // Create download link
            const link = document.createElement('a');
            const userName = '{{ str_replace(" ", "_", $user->name) }}';
            link.download = `ID_Card_${userName}.png`;
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();

            // Hide loading
            overlay.classList.remove('active');

            // Show success message
            if (isMobile) {
                showSuccessMessage();
            }

            console.log('ID Card downloaded successfully!');
        }).catch(error => {
            console.error('Error generating ID card:', error);
            overlay.classList.remove('active');
            alert('Terjadi kesalahan saat mengunduh ID Card. Silakan coba lagi.');
        });
    }, 100);
}

// Success message for mobile
function showSuccessMessage() {
    const message = document.createElement('div');
    message.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #16a34a;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        font-size: 14px;
        font-weight: 600;
        animation: slideDown 0.3s ease;
        max-width: 90%;
        text-align: center;
    `;
    message.textContent = '✓ ID Card berhasil diunduh!';
    document.body.appendChild(message);

    setTimeout(() => {
        message.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => document.body.removeChild(message), 300);
    }, 2000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        to {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
    }
`;
document.head.appendChild(style);

// Prevent right-click on ID card (optional security)
document.getElementById('idcard-preview').addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Improve touch interaction on mobile
if ('ontouchstart' in window) {
    const preview = document.getElementById('idcard-preview');
    preview.style.webkitTapHighlightColor = 'transparent';
}
</script>

@endsection
