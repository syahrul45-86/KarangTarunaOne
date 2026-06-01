# 📋 PRF — Project Requirements File
# KarangTarunaOne — Sistem Informasi Manajemen RT

---

## 1. GAMBARAN UMUM PROYEK

| Atribut | Detail |
|---|---|
| **Nama Proyek** | KarangTarunaOne |
| **Tipe Aplikasi** | Web Application — Sistem Informasi Manajemen Rukun Tetangga (RT) |
| **Framework** | Laravel 12.x |
| **Bahasa** | PHP 8.2, JavaScript (Vanilla), Blade Template |
| **Database** | SQLite (Development) |
| **Status** | 🚧 Dalam Pengembangan (In Progress) |
| **Tanggal Analisis** | 29 Mei 2026 |

---

## 2. DESKRIPSI APLIKASI

**KarangTarunaOne** adalah aplikasi manajemen RT (Rukun Tetangga) berbasis web yang dirancang untuk mendigitalisasi administrasi lingkungan RT. Aplikasi ini mendukung **5 peran pengguna (multi-role)** dengan hak akses berbeda, memudahkan pengelolaan absensi, arisan, keuangan, dan denda warga.

### Visi
> Mewujudkan sistem administrasi RT yang transparan, efisien, dan berbasis teknologi.

### Target Pengguna
- Pengurus RT (Admin, Sekretaris, Bendahara)
- Warga RT (Anggota)
- Pengelola sistem lintas RT (SuperAdmin)

---

## 3. TECH STACK

### Backend
| Teknologi | Versi | Kegunaan |
|---|---|---|
| **Laravel** | 12.x | PHP Framework utama |
| **PHP** | ^8.2 | Bahasa server-side |
| **barryvdh/laravel-dompdf** | ^3.1 | Generate PDF laporan |
| **simplesoftwareio/simple-qrcode** | ^4.2 | Generate QR Code SVG |
| **khanamiryan/qrcode-detector-decoder** | ^2.0 | Decode/scan QR Code dari gambar |
| **Laravel Tinker** | ^2.10.1 | REPL untuk debugging |

### Frontend
| Teknologi | Kegunaan |
|---|---|
| **Blade Template** | Template engine Laravel |
| **Bootstrap 4** (via SB Admin 2) | CSS Framework & UI komponen |
| **Font Awesome** | Icon library |
| **Chart.js** | Visualisasi grafik (absensi, arisan, denda) |
| **Vanilla JavaScript** | Interaksi UI, QR scanner real-time |
| **HTML5 QR Scanner** | Scan QR code via kamera browser |
| **Canvas API** | Generate & preview ID Card |

### Development Tools
| Teknologi | Kegunaan |
|---|---|
| **Vite** | Asset bundler |
| **Laravel Pail** | Log viewer |
| **Laravel Sail** | Docker development |
| **PHPUnit** | Unit testing |
| **Faker** | Database seeding |

### Database
| Teknologi | Kegunaan |
|---|---|
| **SQLite** | Development database |
| **Laravel Migrations** | Schema management |
| **Laravel Seeders** | Data seeding |

---

## 4. ARSITEKTUR SISTEM

### Arsitektur Aplikasi
```
KarangTarunaOne
├── Multi-Tenant (per RT)       → Setiap RT memiliki data terpisah via rt_id
├── Multi-Role Authentication   → 5 peran dengan middleware berbeda
├── MVC Architecture            → Model-View-Controller Laravel
└── RESTful Routes              → Resource routes per fitur
```

### Hierarki Peran (Role Hierarchy)
```
SuperAdmin  →  Kelola semua RT & Admin RT
    └── Admin RT  →  Kelola anggota & rekap di RT-nya
            ├── Sekretaris  →  Absensi & Arisan
            ├── Bendahara   →  Keuangan & Denda  
            └── Anggota     →  Lihat profil pribadi
```

---

## 5. DATABASE SCHEMA

### Tabel Utama

| Tabel | Keterangan |
|---|---|
| `users` | Data pengguna (semua role), menyimpan: name, email, password, role, rt_id, image, qr_code, qr_token, id_card_path |
| `rts` | Data RT (nomor RT, alamat, dll) |
| `setting_rt` | Konfigurasi per RT: iuran_arisan, denda_absensi, denda_arisan |
| `absensi_forms` | Form/kegiatan absensi: judul, tanggal, jam_mulai, jam_selesai |
| `absensi` | Catatan kehadiran: form_id, user_id, status, waktu_absen |
| `absensi_admin` | Absensi via admin (tabel terpisah) |
| `denda` | Data denda: user_id, form_id, jenis, alasan, jumlah_denda, status |
| `arisan_tahun` | Tahun periode arisan |
| `arisan_tanggal` | Tanggal/bulan arisan per tahun |
| `catatan_arisan` | Checklist pembayaran arisan: user_id, tanggal_id, sudah_bayar |
| `bendaharas` | Catatan keuangan: rt_id, tanggal, keterangan, pemasukan, pengeluaran, saldo_awal, saldo_akhir |
| `kas` | Setoran kas per anggota: rt_id, user_id, nominal, tanggal, keterangan |

### Relasi Kunci
- `users` → `rts` (many-to-one via `rt_id`)
- `absensi` → `absensi_forms` + `users`
- `denda` → `users` + `absensi_forms`
- `catatan_arisan` → `arisan_tanggal` + `users`
- `kas` → `users` + `rts`

---

## 6. FITUR PER ROLE

### 6.1 SuperAdmin
| No | Fitur | Status |
|---|---|---|
| 1 | Dashboard SuperAdmin | ✅ Ada |
| 2 | Manajemen Admin RT (CRUD) | ✅ Ada |
| 3 | Manajemen Data RT (CRUD) | ✅ Ada |
| 4 | Profil & Ganti Password | ✅ Ada |

### 6.2 Admin RT
| No | Fitur | Status |
|---|---|---|
| 1 | Dashboard dengan statistik lengkap | ✅ Ada |
| 2 | Manajemen Anggota RT (CRUD + roles) | ✅ Ada |
| 3 | Generate QR Code per anggota | ✅ Ada |
| 4 | Generate & Preview ID Card | ✅ Ada |
| 5 | Rekap Absensi (list, detail, grafik, per-anggota, export PDF) | ✅ Ada |
| 6 | Rekap Arisan (list, detail, grafik, per-anggota, export PDF) | ✅ Ada |
| 7 | Rekap Denda (list, detail, grafik, per-anggota) | ✅ Ada |
| 8 | Setting RT (iuran arisan, denda absensi, denda arisan) | ✅ Ada |
| 9 | Profil & Ganti Password | ✅ Ada |

### 6.3 Sekretaris
| No | Fitur | Status |
|---|---|---|
| 1 | Dashboard Sekretaris | ✅ Ada |
| 2 | Manajemen Form Absensi (Create, List, Delete) | ✅ Ada |
| 3 | QR Scan Absensi (via kamera browser) | ✅ Ada |
| 4 | Absensi Manual (pilih anggota dari dropdown) | ✅ Ada |
| 5 | Cek Daftar Hadir per Kegiatan | ✅ Ada |
| 6 | Proses Denda Otomatis (tidak hadir) | ✅ Ada |
| 7 | Catatan Arisan (per tahun & bulan) | ✅ Ada |
| 8 | Toggle Pembayaran Arisan per Anggota | ✅ Ada |
| 9 | Rekap Arisan Keseluruhan + Bulk Pay | ✅ Ada |
| 10 | Tambah/Hapus Anggota Arisan | ✅ Ada |
| 11 | **Spin Wheel** (Fitur undian/random) | ✅ Ada |
| 12 | Profil & Ganti Password | ✅ Ada |

### 6.4 Bendahara
| No | Fitur | Status |
|---|---|---|
| 1 | Dashboard Bendahara | ✅ Ada |
| 2 | Catatan Keuangan (Saldo Awal, Pemasukan, Pengeluaran) | ✅ Ada |
| 3 | Edit & Update Transaksi Keuangan | ✅ Ada |
| 4 | Edit Saldo Awal (rekalkuasi otomatis) | ✅ Ada |
| 5 | Manajemen Denda (CRUD) | ✅ Ada |
| 6 | Denda Absensi & Denda Kegiatan | ✅ Ada |
| 7 | Catatan Kas per Anggota | ✅ Ada |
| 8 | Profil & Ganti Password | ✅ Ada |

### 6.5 Anggota
| No | Fitur | Status |
|---|---|---|
| 1 | Dashboard Anggota | ✅ Ada (masih placeholder) |
| 2 | Profil & Ganti Password | ✅ Ada |
| 3 | Lihat QR Code Pribadi | 🚧 Controller kosong |
| 4 | Lihat Status Absensi Pribadi | ❌ Belum Ada |
| 5 | Lihat Status Arisan Pribadi | ❌ Belum Ada |
| 6 | Lihat Status Denda Pribadi | ❌ Belum Ada |
| 7 | Lihat ID Card Pribadi | ❌ Belum Ada |

---

## 7. FITUR UNGGULAN

### 7.1 QR Code Absensi System
- Setiap anggota memiliki QR code unik (format: `ABSEN-{id}-{random}`)
- QR disimpan sebagai file SVG di storage
- Sekretaris scan QR via kamera browser (HTML5 QR Scanner)
- Validasi: waktu absensi, RT yang sama, duplikasi absensi
- Absensi manual sebagai fallback

### 7.2 ID Card Generator
- Upload template gambar kustom
- Overlay nama + QR code di atas template
- Preview sebelum simpan menggunakan Canvas API
- Download & simpan ID card sebagai file PNG
- QR code ter-embed di ID card

### 7.3 Dashboard Analitik Admin
- Statistik anggota (total, sekretaris, bendahara, anggota)
- Statistik absensi (rata-rata kehadiran, trend 6 bulan)
- Statistik arisan (pembayaran bulan ini)
- Statistik denda (total, belum bayar, anggota bermasalah)
- Daftar anggota dengan kehadiran < 50%
- Grafik trend kegiatan

### 7.4 Spin Wheel
- Undian random berbasis nama anggota RT
- Digunakan oleh sekretaris untuk keperluan undian

### 7.5 Multi-Tenant RT
- Setiap RT punya data terpisah (rt_id)
- Setting konfigurasi per RT (iuran, denda)
- Admin RT hanya bisa kelola anggota RT-nya sendiri

---

## 8. MIDDLEWARE & SECURITY

| Middleware | Fungsi |
|---|---|
| `auth` | Autentikasi Laravel (session-based) |
| `role:{nama_role}` | Pembatasan akses per role |

### Role yang Didukung
- `superadmin`
- `admin`
- `sekretaris`
- `bendahara`
- `anggota`

---

## 9. DESIGN SYSTEM

| Elemen | Detail |
|---|---|
| **Template Base** | SB Admin 2 (Bootstrap 4 based) |
| **Color Scheme** | Bootstrap standard (primary, success, info, warning, danger) |
| **Icons** | Font Awesome 5 |
| **Charts** | Chart.js (bar, line, doughnut) |
| **Layout** | Sidebar + Topbar per role |
| **Responsive** | Ya (Bootstrap grid) |

---

## 10. DAFTAR FILE PENTING

### Controllers
```
app/Http/Controllers/
├── AbsensiScanController.php         ← QR scan by anggota (WIP)
├── SpinController.php                ← Spin wheel
├── Admin/
│   ├── AdminController.php           ← Dashboard admin
│   ├── TambahAnggotaController.php   ← CRUD anggota + QR + ID Card
│   ├── RekapAbsensiControllers.php   ← Laporan absensi
│   ├── RekapArisanController.php     ← Laporan arisan
│   ├── RekapDendaController.php      ← Laporan denda
│   ├── SettingRTController.php       ← Pengaturan RT
│   ├── ProfileAdminController.php    ← Profil admin
│   └── IdcardController.php          ← ID Card (WIP)
├── SuperAdmin/
│   ├── SuperadminController.php      ← Dashboard superadmin
│   ├── UserAdminRTController.php     ← CRUD admin RT
│   ├── RtController.php              ← CRUD data RT
│   └── ProfileController.php        ← Profil superadmin
├── Sekretaris/
│   ├── AbsensiController.php         ← Manajemen absensi lengkap
│   ├── CatatanArisanController.php   ← Manajemen arisan
│   ├── SekretarisController.php      ← Dashboard
│   └── ProfileSekretarisController.php
├── Bendahara/
│   ├── BendaharaController.php       ← Dashboard
│   ├── CatatanKeuanganController.php ← CRUD keuangan
│   ├── DendaController.php           ← CRUD denda
│   ├── KasController.php             ← CRUD kas
│   └── ProfileBendaharaController.php
└── Anggota/
    ├── AnggotaController.php         ← Dashboard (minimal)
    ├── ProfileAnggotaController.php  ← Profil anggota
    └── QrcodeController.php          ← KOSONG (belum diimplementasi)
```

---

## 11. POTENSI PENGEMBANGAN

1. **Notifikasi** — Push notification / email reminder pembayaran arisan & denda
2. **Export Excel** — Selain PDF, tambahkan export ke Excel
3. **API Mobile** — REST API untuk aplikasi mobile
4. **Dashboard Anggota** — Lengkapi fitur tampilan data pribadi anggota
5. **Riwayat Keuangan** — Laporan keuangan bulanan/tahunan
6. **Surat Menyurat** — Generate surat RT otomatis
7. **Pengumuman** — Sistem pengumuman RT ke anggota

---

*Dokumen ini dibuat secara otomatis berdasarkan analisis source code project KarangTarunaOne pada tanggal 29 Mei 2026.*
