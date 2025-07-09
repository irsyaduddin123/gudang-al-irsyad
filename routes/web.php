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

use App\Http\Controllers\Permintaan\PermintaanUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    BarangController, DashboardController, PenggunaController,
    PermintaanController, RopEoqController, SafetystokController, SupplierController
};
use App\Http\Controllers\Manager\ManagerDashboardController;

Route::get('/cekrole-test', function () {
    return 'AKSES DENGAN CEKROLE';
})->middleware(['auth', 'cekrole:staff']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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

Route::middleware(['auth'])->group(function ()
// Route::middleware(['auth', 'cekrole:staff,manager'])->group(function () 
{
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-manager', [ManagerDashboardController::class, 'index'])->name('dashboard.manager');

    // Route::resource('barang', BarangController::class)->except(['show', 'edit']);
    // Route::resource('supplier', SupplierController::class)->except(['show', 'edit']);
    // Route::resource('safetystok', SafetystokController::class)->except(['show', 'edit']);
    // Route::resource('pengguna', PenggunaController::class)->except(['show', 'edit']);
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.show');

    //export data
    Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
    Route::get('/barang/export-pdf', [BarangController::class, 'exportPdf'])->name('barang.exportPdf');


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

    Route::get('/safety', [SafetystokController::class, 'index'])->name('safetystok.index');
    Route::get('/safetystok/create', [SafetystokController::class, 'create'])->name('safetystok.create');
    Route::post('/safetystok', [SafetystokController::class, 'store'])->name('safetystok.store');
    Route::put('/safetystok/{id}', [SafetystokController::class, 'update'])->name('safetystok.update');
    Route::delete('/safetystok/{id}', [SafetystokController::class, 'destroy'])->name('safetystok.destroy');
    
    // Route::resource('permintaan', PermintaanController::class);
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
});

// Semua pengguna bisa akses permintaan
// Route::middleware(['auth'])->group(function () {
//     Route::resource('permintaan', PermintaanController::class);
//     Route::get('/permintaan/export/excel', [PermintaanController::class, 'exportExcel'])->name('permintaan.export.excel');
//     Route::get('/permintaan/export/pdf', [PermintaanController::class, 'exportPdf'])->name('permintaan.export.pdf');
//     Route::post('/permintaan/{id}/approve', [PermintaanController::class, 'approve'])->name('permintaan.approve');
//     Route::post('/permintaan/{id}/reject', [PermintaanController::class, 'reject'])->name('permintaan.reject');
// });

Route::middleware(['auth', 'cekrole:permintaan'])->prefix('permintaan-user')->group(function () {
    Route::get('/permintaan/user',         [PermintaanUserController::class, 'index'])->name('permintaan.user.index');
    Route::get('/create',   [PermintaanUserController::class, 'create'])->name('permintaan.user.create');
    Route::post('/',        [PermintaanUserController::class, 'store'])->name('permintaan.user.store');
});
