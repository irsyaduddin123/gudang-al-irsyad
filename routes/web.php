<?php

// use App\Http\Controllers\Admin\BarangController;
// use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\Admin\PenggunaController;
// use App\Http\Controllers\Admin\PermintaanController;
// use App\Http\Controllers\Admin\RopEoqController;
// use App\Http\Controllers\Admin\SafetystokController;
// use App\Http\Controllers\Admin\SupplierController;
// use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\manager\managerdashboardController;
// use Illuminate\Support\Facades\Route;

// // Route::get('/barang',[BarangController::class,'index']);
// // Route::get('/', [BarangController::class, 'index'])->name('barang.index');

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// // Dashboard khusus Manager
// Route::get('/dashboard-manager', [managerdashboardController::class, 'index'])->name('dashboard.manager');

// // Barang
// Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
// Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
// Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
// Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
// Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
// //supplier
// Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
// Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
// Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
// Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
// Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');

// //safety stock
// Route::get('/safety', [SafetystokController::class, 'index'])->name('safetystok.index');
// Route::get('/safetystok/create', [SafetystokController::class, 'create'])->name('safetystok.create');
// Route::post('/safetystok', [SafetystokController::class, 'store'])->name('safetystok.store');
// Route::put('/safetystok/{id}', [SafetystokController::class, 'update'])->name('safetystok.update');
// Route::delete('/safetystok/{id}', [SafetystokController::class, 'destroy'])->name('safetystok.destroy');

// //pengguna
// Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
// Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
// Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
// Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
// Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

// // Permintaan
// Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
// Route::get('/permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
// Route::post('/permintaan', [PermintaanController::class, 'store'])->name('permintaan.store');
// Route::get('/permintaan/{id}', [PermintaanController::class, 'show'])->name('permintaan.show');
// Route::get('/permintaan/{id}/edit', [PermintaanController::class, 'edit'])->name('permintaan.edit');
// Route::put('/permintaan/{id}', [PermintaanController::class, 'update'])->name('permintaan.update');
// Route::delete('/permintaan/{id}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');
// Route::get('/permintaan/export/excel', [PermintaanController::class, 'exportExcel'])->name('permintaan.export.excel');
// Route::get('/permintaan/export/pdf', [PermintaanController::class, 'exportPdf'])->name('permintaan.export.pdf');


// //aprove
// Route::post('/permintaan/{id}/approve', [PermintaanController::class, 'approve'])->name('permintaan.approve');
// Route::post('/permintaan/{id}/reject', [PermintaanController::class, 'reject'])->name('permintaan.reject');

// // Hasil rop dan eoq
// Route::get('/hasil', [RopEoqController::class, 'index'])->name('rop-eoq.index');
// Route::get('/hasil/create', [RopEoqController::class, 'create'])->name('rop-eoq.create');
// Route::post('/hasil', [RopEoqController::class, 'store'])->name('rop-eoq.store');
// Route::get('/rop-eoq/export/excel', [RopEoqController::class, 'exportExcel'])->name('rop-eoq.export.excel');



// //dashboard
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

use App\Http\Controllers\Admin\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\user\PermintaanUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    BarangController, DashboardController, PenggunaController,
    PermintaanController, RopEoqController, SafetystokController, SupplierController
};
use App\Http\Controllers\Manager\ManagerDashboardController;

// Route::get('/cekrole-test', function () {
//     return 'AKSES DENGAN CEKROLE';
// })->middleware(['auth', 'cekrole:staff']);

// Login
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Middleware auth pakai pengguna
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Staff & Manager akses penuh
// Route::middleware(['auth', 'cekrole:staff,manager'])->group(callback: function () 
// Route::middleware(['auth', 'cekrole:permintaan'])->prefix('permintaan-user')->group(function () {
//     Route::get('/permintaan/user',         [PermintaanUserController::class, 'index'])->name('permintaan.user.index');
//     Route::get('/create',   [PermintaanUserController::class, 'create'])->name('permintaan.user.create');
//     Route::post('/',        [PermintaanUserController::class, 'store'])->name('permintaan.user.store');
// });


// Route::middleware(['auth'])->group(function ()
Route::middleware(['auth', 'cekrole:staff,manager'])->group(function () 
{
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-manager', [ManagerDashboardController::class, 'index'])->name('dashboard.manager');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('/barang-export-excell', [BarangController::class, 'export'])->name('barang.export');
    // Route::get('/barang/export-pdf', [BarangController::class, 'exportPdf']);
    Route::get('/barang-export-pdf', [BarangController::class, 'exportPdf'])->name('barang.exportPdf');

    //pengadaan
    // Menampilkan daftar pengadaan
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    // Form tambah pengadaan (bawa barang_id sebagai query string)
    Route::get('/pengadaan/create/{barang}', [PengadaanController::class, 'create'])->name('pengadaan.create');

    // Simpan pengadaan
    Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    // Tampilkan form ubah status
    Route::get('/pengadaan/{id}/edit-status', [PengadaanController::class, 'editStatus'])->name('pengadaan.edit-status');
    // Update status
    Route::put('/pengadaan/{id}/update-status', [PengadaanController::class, 'updateStatus'])->name('pengadaan.update-status');
    Route::post('/pengadaan/{id}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::post('/pengadaan/{id}/reject', [PengadaanController::class, 'reject'])->name('pengadaan.reject');

    //Barang Masuk
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::post('/barang-masuk/{id}/terima', [BarangMasukController::class, 'terima'])->name('barang-masuk.terima');
    Route::get('/barang-masuk/export-excel', [BarangMasukController::class, 'exportExcel'])->name('barang_masuk.export_excel');
    Route::get('/barang-masuk/export/pdf', [BarangMasukController::class, 'exportPDF'])->name('barang-masuk.export.pdf');


    //supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');

    //pengguna
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    Route::get('/hasil', [RopEoqController::class, 'index'])->name('rop-eoq.index');
    Route::get('/hasil/create', [RopEoqController::class, 'create'])->name('rop-eoq.create');
    Route::post('/hasil', [RopEoqController::class, 'store'])->name('rop-eoq.store');
    Route::delete('/rop-eoq/{id}', [RopEoqController::class, 'destroy'])->name('rop-eoq.destroy');
    Route::get('/rop-eoq/export/excel', [RopEoqController::class, 'exportExcel'])->name('rop-eoq.export.excel');
    Route::get('/rop-eoq/export/pdf', [RopEoqController::class, 'exportPDF'])->name('rop-eoq.export.pdf');
    Route::get('/rop-eoq/{id}/edit', [RopEoqController::class, 'edit'])->name('rop-eoq.edit');
    Route::put('/rop-eoq/{id}', [RopEoqController::class, 'update'])->name('rop-eoq.update');

    Route::get('/safety', [SafetystokController::class, 'index'])->name('safetystok.index');
    Route::get('/safetystok/create', [SafetystokController::class, 'create'])->name('safetystok.create');
    Route::post('/safetystok', [SafetystokController::class, 'store'])->name('safetystok.store');
    Route::put('/safetystok/{id}', [SafetystokController::class, 'update'])->name('safetystok.update');
    Route::delete('/safetystok/{id}', [SafetystokController::class, 'destroy'])->name('safetystok.destroy');
    
    // Route permintaan
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan', [PermintaanController::class, 'store'])->name('permintaan.store');
    Route::get('/permintaan/{id}', [PermintaanController::class, 'show'])->name('permintaan.show');
    Route::get('/permintaan/{id}/edit', [PermintaanController::class, 'edit'])->name('permintaan.edit');
    Route::put('/permintaan/{id}', [PermintaanController::class, 'update'])->name('permintaan.update');
    Route::delete('/permintaan/{id}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');
    Route::get('/permintaan/export/excel', [PermintaanController::class, 'exportExcel'])->name('permintaan.export.excel');
    Route::get('/permintaan/export/pdf', [PermintaanController::class, 'exportPdf'])->name('permintaan.export.pdf');
    Route::post('/permintaan/{id}/approve', [PermintaanController::class, 'approve'])->name('permintaan.approve');
    Route::post('/permintaan/{id}/reject', [PermintaanController::class, 'reject'])->name('permintaan.reject');
    Route::post('/permintaan/{id}/tolak-manager', [PermintaanController::class, 'tolakOlehManager'])->name('permintaan.tolak.manager');

    

    // Validasi permintaan khusus manager (cek role di controller)
    // Route::get('/permintaan/validasi', [PermintaanController::class, 'validasiIndex'])->name('permintaan.validasi.index');
    Route::post('/permintaan/{id}/validasi', [PermintaanController::class, 'validasiSetujui'])->name('permintaan.validasi.setujui');
    Route::post('/permintaan/{id}/tolak', [PermintaanController::class, 'validasiTolak'])->name('permintaan.validasi.tolak');

    // Barang keluar
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang_keluar.index');
    Route::get('/barang-keluar/export-excel', [BarangKeluarController::class, 'exportExcel'])->name('barang_keluar.export.excel');
    Route::get('/barang-keluar/export-pdf', [BarangKeluarController::class, 'exportPdf'])->name('barang_keluar.export.pdf');

});

// use App\Http\Controllers\user\PermintaanUserController;

Route::middleware(['auth', 'cekrole:permintaan'])->prefix('user')->group(function () {
    Route::get('/permintaan', [PermintaanUserController::class, 'index'])->name('user.permintaan.index');
    Route::post('/permintaan', [PermintaanUserController::class, 'store'])->name('user.permintaan.store');
    Route::put('/permintaan/{id}', [PermintaanUserController::class, 'update'])->name('user.permintaan.update');
    Route::delete('/permintaan/{id}', [PermintaanUserController::class, 'destroy'])->name('user.permintaan.destroy');
});
