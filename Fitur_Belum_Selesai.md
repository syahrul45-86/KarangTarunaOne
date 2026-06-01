# 🔧 Analisis Fitur yang Belum Selesai
# KarangTarunaOne — Incomplete Features Report

> **Tanggal Analisis:** 29 Mei 2026  
> **Metode:** Code Review & Static Analysis

---

## 📊 RINGKASAN EKSEKUTIF

| Status | Jumlah |
|---|---|
| ❌ Belum Diimplementasi Sama Sekali | 7 fitur |
| 🟡 Placeholder / Dummy Data | 4 fitur |
| 💬 Kode Dikomentari (Commented Out) | 2 fitur |
| ⚠️ Potensi Bug / Incomplete Logic | 5 masalah |
| **Total Masalah** | **18 item** |

---

## ❌ KATEGORI 1: BELUM DIIMPLEMENTASI SAMA SEKALI

### 1.1 QrcodeController — Controller Kosong
**File:** `app/Http/Controllers/Anggota/QrcodeController.php`

```php
class QrcodeController extends Controller
{
    // KOSONG — tidak ada method sama sekali
}
```

**Dampak:** Anggota tidak bisa melihat QR code milik mereka sendiri.

**Yang Dibutuhkan:**
- Method `show()` untuk menampilkan QR code anggota yang login
- View `anggota.qrcode.show` untuk halaman tampilan QR
- Route yang menghubungkan ke controller ini

**Estimasi:** 🟢 Mudah — 2-3 jam

---

### 1.2 Dashboard Anggota — Semua Data Masih Dummy / Statis

**File:** `resources/views/anggota/dashboard.blade.php`

Data yang ditampilkan:
- Saldo: **`$40,000`** — nilai hardcode, bukan dari database
- Earnings Annual: **`$215,000`** — tidak relevan dengan konteks RT
- Tasks: **`50%`** — nilai statis
- Pending Requests: **`18`** — nilai statis

**Yang Dibutuhkan:**
- Tampilkan riwayat absensi pribadi (hadir/tidak hadir)
- Tampilkan status arisan (sudah/belum bayar)
- Tampilkan saldo denda pribadi
- Tampilkan foto ID Card jika sudah dibuat

**Estimasi:** 🟡 Sedang — 1-2 hari

---

### 1.3 Rekap Denda — Tidak Ada Export PDF

**Controller:** `app/Http/Controllers/Admin/RekapDendaController.php`

Fitur yang ada: `index`, `show`, `grafik`, `perAnggota`

**Yang Hilang:** Tidak ada method `exportPdf()` / `pdf()` untuk denda, padahal absensi dan arisan sudah punya.

**Route yang Ada Tapi Tidak Berfungsi:**
- Tidak ada route export PDF untuk denda di `web.php`

**Estimasi:** 🟢 Mudah — 3-4 jam

---

### 1.4 IdcardController — Tidak Bisa Dipakai

**File:** `app/Http/Controllers/Admin/IdcardController.php`

```php
public function index()
{
    $users = User::where('rt_id', Auth::user()->rt_id)->get();
    // ERROR: Auth tidak di-import!
    return view('admin.idcard.index', compact('users'));
}
```

**Masalah:**
1. `Auth` facade tidak diimport (`use Illuminate\Support\Facades\Auth;` hilang)
2. View `admin.idcard.index` tidak ada di folder (hanya `form.blade.php` dan `preview.blade.php`)
3. Route untuk `IdcardController@index` tidak ada di `web.php`

**Estimasi:** 🟢 Mudah — 1-2 jam (fix import + buat view/route)

---

### 1.5 Fitur Edit & Update Admin RT — Dikomentar

**File:** `app/Http/Controllers/Superadmin/UserAdminRTController.php`

```php
// Kode edit dan update seluruhnya DIKOMENTARI (baris 62-89)

// public function edit($id) { ... }
// public function update(Request $request, $id) { ... }
```

**Di routes juga masih ada:**
```php
Route::get('superadmin/{id}/editAdmin', [UserAdminRTController::class, 'edit'])
Route::put('/superadmin/{id}/updateAdmin', [UserAdminRTController::class, 'update'])
```

**Dampak:** Route edit/update admin RT akan throw **500 Error** karena method tidak ada.

**Estimasi:** 🟢 Mudah — 2-3 jam (uncomment + sesuaikan)

---

### 1.6 Fitur Lihat Riwayat Absensi Pribadi (Anggota)

**Tidak ada:**
- Controller method untuk anggota lihat absensi mereka
- View untuk halaman tersebut
- Route untuk akses halaman tersebut

**Estimasi:** 🟡 Sedang — 4-6 jam

---

### 1.7 Grafik Rekap Denda — View Kosong / Minimal

**File:** `resources/views/admin/rekap_denda/per_anggota.blade.php`  
**Ukuran:** hanya **900 bytes** — kemungkinan besar belum lengkap.

**File:** `resources/views/admin/rekap_arisan/grafik.blade.php`  
**Ukuran:** hanya **850 bytes** — sangat minimal, kemungkinan placeholder.

**Estimasi:** 🟡 Sedang — 1 hari per file

---

## 🟡 KATEGORI 2: FITUR PLACEHOLDER / TIDAK FUNGSIONAL

### 2.1 Anggota Dashboard — Data Dummy

Sudah dijelaskan di 1.2. Perlu diganti dengan data real dari database.

---

### 2.2 Rekap Absensi — Grafik Ada Tapi Tidak Muncul di Sidebar

**File:** `app/Http/Controllers/Admin/RekapAbsensiControllers.php`  
Method `grafik()` sudah ada, view `grafik.blade.php` sudah ada, tapi:
- Tidak ada tombol/link navigasi ke halaman grafik dari dashboard atau menu
- Route `/rekap-absensi-grafik` tidak terintegrasi dengan navigation

---

### 2.3 Absensi Scan — File Duplikat

**Di folder:** `resources/views/sekretaris/absensi/`

Ditemukan **2 file scan**:
- `scan.blade.php` — **27,853 bytes** (yang digunakan)
- `scan.blade copy.php` — **6,801 bytes** (file lama / backup)

> ⚠️ **Aksi:** File `scan.blade copy.php` harus dihapus karena menyebabkan kebingungan dan bisa menimbulkan konflik.

---

### 2.4 Setting RT — Method `update()` Tidak Digunakan

**File:** `app/Http/Controllers/Admin/SettingRTController.php`

Terdapat method `update()` yang di-route sebagai:
```php
Route::post('/setting-rt/update', [SettingRTController::class, 'update'])
```
Tapi di dalam controller, **method `update()` tidak ada** — hanya ada `save()`.

**Dampak:** Route `/setting-rt/update` akan throw **500 Error** jika dipanggil.

---

## 💬 KATEGORI 3: KODE YANG DIKOMENTARI

### 3.1 Edit & Update Admin RT
Sudah dibahas di **1.5** — method `edit()` dan `update()` di `UserAdminRTController.php` dikomentar seluruhnya.

---

### 3.2 Route Scan QR (AbsensiScanController)
**File:** `routes/web.php` — Route untuk `AbsensiScanController` tidak ditemukan di `web.php`.

Walaupun controller `AbsensiScanController` sudah ada dengan method:
- `scanQr()`
- `prosesAbsen($token)`
- `uploadQr()`

...tidak ada route yang mendaftarkan endpoint ini, sehingga fitur scan QR mandiri oleh anggota **tidak bisa diakses**.

---

## ⚠️ KATEGORI 4: POTENSI BUG / LOGIKA TIDAK LENGKAP

### 4.1 Route Duplikat: `admin.user.qr`

**File:** `routes/web.php`, baris 80-95:

```php
// DUPLIKAT ROUTE! Nama yang sama dipakai 2x
Route::get('/admin/user-qr/{id}', function ($id) { ... })->name('admin.user.qr');
Route::get('/user-qr/{userId}', [TambahAnggotaController::class, 'getUserQR'])->name('admin.user.qr');
```

**Dampak:** Laravel akan menggunakan route yang **terakhir** didefinisikan, membuat route pertama tidak pernah dipakai. Ini bisa menyebabkan URL helper `route('admin.user.qr')` menghasilkan URL yang tidak terduga.

---

### 4.2 Double Slash di Route Sekretaris

**File:** `routes/web.php`, baris 237-256:

```php
// Ada '//' ganda di beberapa route!
Route::get('/sekretaris/absensi//{id}/scan', ...)
Route::post('/sekretaris/absensi//{id}/scan/process', ...)
Route::get('/sekretaris/absensi//{id}/list', ...)
Route::get('/sekretaris/absensi//{id}/cek', ...)
Route::get('/sekretaris/absensi//{id}/proses-denda', ...)
Route::delete('/sekretaris/absensi//{id}', ...)
```

**Dampak:** URL dengan `//` mungkin bekerja di beberapa server tapi tidak konsisten. Bisa menyebabkan 404 di lingkungan produksi tertentu.

---

### 4.3 Denda Arisan — Tidak Ada Proses Otomatis

Di `SettingRT` ada field `denda_arisan`, tapi **tidak ada kode yang menggunakannya** untuk proses denda otomatis ketika anggota tidak bayar arisan.

Hanya `denda_absensi` yang diproses otomatis di `AbsensiController::prosesDenda()`.

**Dampak:** Setting `denda_arisan` di-save tapi tidak punya efek apapun saat ini.

---

### 4.4 AbsensiScanController — `qr_token` vs URL-based QR

**Masalah Inkonsistensi Logic:**
- `TambahAnggotaController` menyimpan QR token sebagai string seperti `ABSEN-{id}-{random}` di field `qr_token`
- `AbsensiScanController::prosesAbsen()` mencari `AbsensiForm::where('qr_token', $token)` — **ini mencari di tabel absensi_forms, bukan users!**
- Tapi `AbsensiController::processScan()` mencari `User::where('qr_token', $request->qr_token)` — ini benar

**Dampak:** Dua alur scan QR berbeda menggunakan logika yang berbeda. `AbsensiScanController` kemungkinan tidak akan bekerja dengan benar.

---

### 4.5 Model `addAnggota` & `removeAnggota` di CatatanArisanController

**File:** `routes/web.php`, baris 295-300:

```php
Route::post('/arisan/anggota/add', [CatatanArisanController::class, 'addAnggota'])
Route::post('/arisan/anggota/remove', [CatatanArisanController::class, 'removeAnggota'])
```

**Tapi di `CatatanArisanController.php`** — method `addAnggota()` dan `removeAnggota()` **TIDAK ADA**.

**Dampak:** Memanggil route ini akan langsung throw **500 Error**.

---

## 🎯 REKOMENDASI PRIORITAS PERBAIKAN

### 🔴 PRIORITAS TINGGI (Harus Segera Diperbaiki)

| # | Item | File | Estimasi |
|---|---|---|---|
| 1 | Fix route duplikat `admin.user.qr` | `routes/web.php` | 30 menit |
| 2 | Fix double slash `//` di route sekretaris | `routes/web.php` | 30 menit |
| 3 | Buat method `addAnggota()` & `removeAnggota()` | `CatatanArisanController.php` | 2-3 jam |
| 4 | Fix `UserAdminRTController` — uncomment edit & update | `UserAdminRTController.php` | 2-3 jam |
| 5 | Fix `SettingRTController` — tambah method `update()` atau hapus route | `SettingRTController.php` | 1 jam |
| 6 | Fix import `Auth` di `IdcardController` | `IdcardController.php` | 15 menit |

### 🟡 PRIORITAS SEDANG (Sprint Berikutnya)

| # | Item | File | Estimasi |
|---|---|---|---|
| 7 | Implementasi QrcodeController untuk anggota | `QrcodeController.php` | 2-3 jam |
| 8 | Lengkapi Dashboard Anggota dengan data real | `AnggotaController.php` + view | 1-2 hari |
| 9 | Tambah Export PDF untuk Rekap Denda | `RekapDendaController.php` | 3-4 jam |
| 10 | Daftarkan route `AbsensiScanController` | `routes/web.php` | 1 jam |
| 11 | Hapus file duplikat `scan.blade copy.php` | views/sekretaris/absensi/ | 5 menit |
| 12 | Fix logika QR token di `AbsensiScanController` | `AbsensiScanController.php` | 2-3 jam |

### 🟢 PRIORITAS RENDAH (Nice to Have)

| # | Item | Estimasi |
|---|---|---|
| 13 | Implementasi proses denda arisan otomatis | 1 hari |
| 14 | Lengkapi view `grafik` rekap arisan & denda | 1 hari |
| 15 | Integrasi navigasi ke halaman grafik absensi | 2-3 jam |
| 16 | Fitur riwayat absensi pribadi untuk anggota | 4-6 jam |
| 17 | Fitur status arisan pribadi untuk anggota | 4-6 jam |
| 18 | Fitur status denda pribadi untuk anggota | 4-6 jam |

---

## 📁 DAFTAR FILE BERMASALAH

```
❌ app/Http/Controllers/Anggota/QrcodeController.php     → Kosong
❌ app/Http/Controllers/Admin/IdcardController.php        → Missing import Auth
❌ app/Http/Controllers/Superadmin/UserAdminRTController.php → Method edit/update dikomentar
⚠️ app/Http/Controllers/Admin/SettingRTController.php    → Route update() tidak ada
⚠️ app/Http/Controllers/Admin/RekapDendaController.php   → Tidak ada exportPdf
⚠️ app/Http/Controllers/Sekretaris/CatatanArisanController.php → Method add/removeAnggota tidak ada
⚠️ app/Http/Controllers/AbsensiScanController.php        → Logika QR token salah + tidak ada route
🟡 resources/views/anggota/dashboard.blade.php            → Data dummy/hardcode
🟡 resources/views/admin/rekap_arisan/grafik.blade.php    → Sangat minimal (850 bytes)
🟡 resources/views/admin/rekap_denda/per_anggota.blade.php → Sangat minimal (900 bytes)
🗑️ resources/views/sekretaris/absensi/scan.blade copy.php → Harus dihapus (file duplikat)
⚠️ routes/web.php (baris 80, 95)                          → Route name duplikat
⚠️ routes/web.php (baris 237-256)                         → Double slash '//'
```

---

*Laporan ini dibuat berdasarkan analisis statik source code. Pengujian runtime mungkin mengungkap masalah tambahan.*
