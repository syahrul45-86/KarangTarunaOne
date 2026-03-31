<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Superadmin\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Bendahara\BendaharaController;
use App\Http\Controllers\Sekretaris\SekretarisController;
use App\Http\Controllers\Anggota\AnggotaController;
use App\Http\Controllers\Anggota\ProfileAnggotaController;
use App\Http\Controllers\Bendahara\ProfileBendaharaController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\TambahAnggotaController;
use App\Http\Controllers\Superadmin\UserAdminRTController;
use App\Http\Controllers\SuperAdmin\RtController;
use App\Http\Controllers\Bendahara\CatatanKeuanganController;
use App\Http\Controllers\Bendahara\DendaController;
use App\Http\Controllers\Sekretaris\AbsensiController;
use App\Http\Controllers\Sekretaris\ProfileSekretarisController;
use App\Http\Controllers\AbsensiScanController;
use App\Http\Controllers\admin\AbsensiAdminController;
use App\Http\Controllers\bendahara\KasController;
use App\Http\Controllers\Sekretaris\CatatanArisanController;
use App\Http\Controllers\SpinController;
use App\Http\Controllers\Admin\RekapAbsensiControllers;
use App\Http\Controllers\Admin\RekapArisanController;
use App\Http\Controllers\Admin\RekapDendaController;
use App\Http\Controllers\Admin\SettingRTController;



// Role superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

    Route::get('/superadmin/profile', [ProfileController::class, 'index'])->name('superadmin.profile.index');
    Route::get('/superadmin/profile/edit', [ProfileController::class, 'edit'])->name('superadmin.profile.edit');
    Route::post('/superadmin/profile/update', [ProfileController::class, 'update'])->name('superadmin.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('superadmin.profile.password');


    Route::get('superadmin/adminrt', [UserAdminRTController::class, 'index'])->name('superadmin.adminrt.index');
    Route::post('/superadmin/create', [UserAdminRTController::class, 'store'])->name('superadmin.adminrt.store');
    Route::get('superadmin/{id}/editAdmin',[UserAdminRTController::class, 'edit'])->name('superadmin.adminrt.edit');
    Route::put('/superadmin/{id}/updateAdmin',[UserAdminRTController::class, 'update'])->name('superadmin.adminrt.update');
    Route::delete('/superadmin/adminrt/{id}', [UserAdminRTController::class, 'destroy'])->name('superadmin.adminrt.destroy');



Route::get('/superadmin/rt', [RtController::class, 'index'])->name('superadmin.rt.index');
Route::post('/superadmin/rt/store', [RtController::class, 'store'])->name('superadmin.rt.store');
Route::get('/superadmin/rt/{id}/edit', [RtController::class, 'edit'])->name('superadmin.rt.edit');
Route::put('/superadmin/rt/{id}/update', [RtController::class, 'update'])->name('superadmin.rt.update');
Route::delete('/superadmin/rt/{id}', [RtController::class, 'destroy'])->name('superadmin.rt.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/profile', [ProfileAdminController::class, 'index'])->name('admin.profile.index');
    Route::get('/admin/profile/edit', [ProfileAdminController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile/update', [ProfileAdminController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [ProfileAdminController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/admin/AnggotaRT', [TambahAnggotaController::class, 'index'])->name('admin.AnggotaRT.index');
    Route::post('/admin/TambahAnggota', [TambahAnggotaController::class, 'store'])->name('admin.AnggotaRT.store');

    Route::get('/anggota/{id}/card',
    [TambahAnggotaController::class, 'card']
)->name('admin.AnggotaRT.card');

    Route::get('/admin/id-card', [TambahAnggotaController::class, 'idCardForm'])
    ->name('admin.idcard.form');

    Route::post('/admin/id-card/generate', [TambahAnggotaController::class, 'generateIdCard'])
        ->name('admin.idcard.generate');
    Route::post('/admin/idcard/save', [TambahAnggotaController::class, 'saveIdCard'])->name('admin.idcard.save');
    
    Route::get('/admin/user-qr/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);

    return response()->json([
        'qr' => asset('storage/' . $user->qr_code)
    ]);


    })->name('admin.user.qr');
    Route::get('/admin/id-card/preview',
        [TambahAnggotaController::class, 'previewIdCard']
    )->name('admin.idcard.preview');


// Di grup admin
Route::get('/user-qr/{userId}', [TambahAnggotaController::class, 'getUserQR'])->name('admin.user.qr');

    Route::delete('/admin/{id}/AnggotaRT', [TambahAnggotaController::class, 'destroy'])->name('admin.AnggotaRT.destroy');
    Route::get('/rekap-absensi', [RekapAbsensiControllers::class, 'index'])
            ->name('admin.rekap.index');

    // rekab absensi
    Route::get('/rekap-absensi/{id}', [RekapAbsensiControllers::class, 'show'])
            ->name('admin.rekap.show');

     Route::get('/rekap-absensi-export', [RekapAbsensiControllers::class, 'exportPdf'])
        ->name('admin.rekap.export.pdf');

    Route::get('/rekap-absensi-grafik', [RekapAbsensiControllers::class, 'grafik'])
        ->name('admin.rekap.grafik');

    Route::get('/rekap-per-anggota', [RekapAbsensiControllers::class, 'perAnggota'])
        ->name('admin.rekap.peranggota');

    Route::get('/rekap-absensi/{id}/pdf',
    [RekapAbsensiControllers::class, 'exportPdf'])
    ->name('admin.rekap.pdf');
    // end

    // rekab arisan
    Route::get('/rekap-arisan',
        [RekapArisanController::class, 'index'])->name('admin.rekap.arisan.index');

    Route::get('/rekap-arisan/{id}',
        [RekapArisanController::class, 'show'])->name('admin.rekap.arisan.show');

    Route::get('/rekap-arisan/{id}/pdf',
        [RekapArisanController::class, 'pdf'])->name('admin.rekap.arisan.pdf');

    Route::get('/rekap-arisan/{id}/grafik',
        [RekapArisanController::class, 'grafik'])->name('admin.rekap.arisan.grafik');

    Route::get('/rekap-arisan/{id}/per-anggota',
        [RekapArisanController::class, 'perAnggota'])->name('admin.rekap.arisan.perAnggota');

    //  end

    Route::get('/admin/rekab-denda', [RekapDendaController::class, 'index'])
        ->name('admin.denda.index');

    Route::get('/admin/rekab-denda/show/{id}', [RekapDendaController::class, 'show'])
        ->name('admin.denda.show');

    Route::get('/admin/rekab-denda/grafik', [RekapDendaController::class, 'grafik'])
        ->name('admin.denda.grafik');

    Route::get('/admin/rekab-denda/per-anggota', [RekapDendaController::class, 'perAnggota'])
        ->name('admin.denda.per_anggota');

    Route::get('/setting-rt', [SettingRTController::class, 'index'])->name('admin.setting_rt.index');
    Route::post('/setting-rt/update', [SettingRTController::class, 'update'])->name('admin.setting_rt.update');
      // Simpan Setting RT
    Route::post('/setting-rt/save', [SettingRTController::class, 'save'])->name('admin.setting_rt.save');

});

Route::middleware(['auth', 'role:bendahara'])->group( function() {
    Route::get('/dashboard', [BendaharaController::class, 'index'])->name('bendahara.dashboard');

    Route::get('/bendahara/profile', [ProfileBendaharaController::class, 'index'])->name('bendahara.profile.index');
    Route::get('/bendahara/profile/edit', [ProfileBendaharaController::class, 'edit'])->name('bendahara.profile.edit');
    Route::post('/bendahara/profile/update', [ProfileBendaharaController::class, 'update'])->name('bendahara.profile.update');
    Route::post('/bendahara/password', [ProfileBendaharaController::class, 'updatePassword'])->name('bendahara.profile.password');


    //    Bendahara
    Route::get('/bendahara/edit-saldo-awal', [CatatanKeuanganController::class, 'editSaldoAwal'])
    ->name('bendahara.edit_saldo_awal');

    Route::put('/bendahara/update-saldo-awal', [CatatanKeuanganController::class, 'updateSaldoAwal'])
    ->name('bendahara.update_saldo_awal');

    Route::post('/bendahara/saldo-awal', [CatatanKeuanganController::class, 'storeSaldoAwal'])->name('bendahara.storeSaldoAwal');
    Route::post('/bendahara/keuangan', [CatatanKeuanganController::class, 'storeKeuangan'])->name('bendahara.storeKeuangan');

    Route::get('bendahara', [CatatanKeuanganController::class, 'index'])->name('bendahara.catatan-keuangan.index');
    Route::get('bendahara/create', [CatatanKeuanganController::class, 'create'])->name('bendahara.create');
    Route::post('bendahara/store', [CatatanKeuanganController::class, 'store'])->name('bendahara.store');
    Route::get('bendahara/{id}/edit', [CatatanKeuanganController::class, 'edit'])->name('bendahara.edit');
    Route::put('bendahara/{id}', [CatatanKeuanganController::class, 'update'])->name('bendahara.update');
    Route::delete('bendahara/{id}', [CatatanKeuanganController::class, 'destroy'])->name('bendahara.destroy');

    // denda

    Route::get('/bendahara/denda', [DendaController::class, 'index'])->name('bendahara.denda.index');
    Route::get('/bendahara/denda/create', [DendaController::class, 'create'])->name('bendahara.denda.create');
    Route::post('/bendahara/denda', [DendaController::class, 'store'])->name('bendahara.denda.store');
    Route::get('/bendahara/denda/{id}/edit', [DendaController::class, 'edit'])->name('bendahara.denda.edit');
    Route::put('/bendahara/denda/{id}', [DendaController::class, 'update'])->name('bendahara.denda.update');
    Route::delete('/bendahara/denda/{id}', [DendaController::class, 'destroy'])->name('bendahara.denda.destroy');

    Route::get('/bendahara/denda/absensi', [DendaController::class, 'absensi'])
        ->name('bendahara.denda.absensi');

    Route::get('/bendahara/denda/kegiatan', [DendaController::class, 'kegiatan'])
        ->name('bendahara.denda.kegiatan');

        Route::prefix('bendahara/denda/kegiatan')
->name('bendahara.denda.kegiatan.')
->group(function () {

    Route::get('/', [DendaController::class, 'kegiatan'])->name('index');
    Route::post('/store', [DendaController::class, 'storeKegiatan'])->name('store');
    Route::put('/update/{id}', [DendaController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DendaController::class, 'destroy'])->name('destroy');
});
    // end

    Route::get('bendahara/kas', [KasController::class, 'index'])->name('bendahara.kas.index');
    Route::get('bendahara/kas/create', [KasController::class, 'create'])->name('bendahara.kas.create');
    Route::post('bendahara/kas/store', [KasController::class, 'store'])->name('bendahara.kas.store');
    Route::delete('bendahara/kas/{id}', [KasController::class, 'destroy'])->name('bendahara.kas.delete');
});




Route::middleware(['auth', 'role:sekretaris'])->group(function(){
    Route::get('/sekretaris', [SekretarisController::class, 'index'])->name('sekretaris.dashboard');

    Route::get('/sekretaris/profile', [ProfileSekretarisController::class, 'index'])->name('sekretaris.profile.index');
    Route::get('/sekretaris/profile/edit', [ProfileSekretarisController::class, 'edit'])->name('sekretaris.profile.edit');
    Route::post('/sekretaris/profile/update', [ProfileSekretarisController::class, 'update'])->name('sekretaris.profile.update');
    Route::post('/sekretaris/profile/password', [ProfileSekretarisController::class, 'updatePassword'])->name('sekretaris.profile.password');

 // Route yang sudah ada...
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('sekretaris.absensi.index');
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::get('/create', [AbsensiController::class, 'create'])
        ->name('sekretaris.absensi.create');

    Route::post('/sekretaris/store', [AbsensiController::class, 'store'])
        ->name('sekretaris.absensi.store');

    Route::get('/sekretaris/absensi/{id}/qr', [AbsensiController::class, 'showQR'])
        ->name('sekretaris.absensi.qr');

    Route::get('/sekretaris/absensi//{id}/scan', [AbsensiController::class, 'scanPage'])
        ->name('sekretaris.absensi.scan');

    Route::post('/sekretaris/absensi//{id}/scan/process', [AbsensiController::class, 'processScan'])
        ->name('sekretaris.absensi.process');

    Route::get('/sekretaris/absensi//{id}/list', [AbsensiController::class, 'getAbsensiList'])
        ->name('sekretaris.absensi.list');

    Route::get('/sekretaris/absensi//{id}/cek', [AbsensiController::class, 'cekAbsensi'])
        ->name('sekretaris.absensi.cek');

    Route::get('/sekretaris/absensi//{id}/proses-denda', [AbsensiController::class, 'prosesDenda'])
        ->name('sekretaris.absensi.proses_denda');

    Route::delete('/sekretaris/absensi//{id}', [AbsensiController::class, 'destroy'])
        ->name('sekretaris.absensi.delete');




    Route::get('/catatan-arisan', [CatatanArisanController::class, 'index'])->name('sekretaris.catatan.index');
       // Form tambah tahun
    Route::get('/arisan/tahun/create', [CatatanArisanController::class, 'create'])
        ->name('sekretaris.tahun.create');

    // Simpan tahun baru
    Route::post('/arisan/tahun/store', [CatatanArisanController::class, 'storeTahun'])
        ->name('sekretaris.tahun.store');

    // Simpan tanggal/bulan
    Route::post('/arisan/tanggal/store', [CatatanArisanController::class, 'storeTanggal'])
        ->name('sekretaris.tanggal.store');

    // Tampilkan detail tahun (daftar tanggal + checklist)
    Route::get('/arisan/tahun/show', [CatatanArisanController::class, 'showTahun'])
        ->name('sekretaris.tahun.show');

    // Toggle checklist bayar arisan
    Route::post('/arisan/toggle-checklist', [CatatanArisanController::class, 'toggleChecklist'])
        ->name('sekretaris.toggleChecklist');

    // Hapus tanggal
    Route::delete('/arisan/tanggal/{id}/delete', [CatatanArisanController::class, 'deleteTanggal'])
        ->name('sekretaris.tanggal.delete');

    // Tambah anggota arisan
Route::post('/arisan/anggota/add', [CatatanArisanController::class, 'addAnggota'])
    ->name('sekretaris.anggota.add');

// Hapus anggota arisan
Route::post('/arisan/anggota/remove', [CatatanArisanController::class, 'removeAnggota'])
    ->name('sekretaris.anggota.remove');

    Route::get('sekretaris/spin', [SpinController::class, 'index'])->name('sekretaris.spin.index');

});




Route::middleware(['auth', 'role:anggota'])->group(function(){
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.dashboard');

    Route::get('/anggota/profile', [ProfileAnggotaController::class, 'index'])->name('anggota.profile.index');
    Route::get('/anggota/profile/edit', [ProfileAnggotaController::class, 'edit'])->name('anggota.profile.edit');
    Route::post('/anggota/profile/update', [ProfileAnggotaController::class, 'update'])->name('anggota.profile.update');
    Route::post('/anggota/profile/password', [ProfileAnggotaController::class, 'updatePassword'])->name('anggota.profile.password');



});


require __DIR__.'/auth.php';
