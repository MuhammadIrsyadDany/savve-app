<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\PengambilanController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', function () {
    return redirect('/login');
});

// Route Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('users', UserController::class);
    Route::get('/transaksis', [AdminTransaksiController::class, 'index'])->name('transaksis.index');
    Route::get('/transaksis/{transaksi}', [AdminTransaksiController::class, 'show'])->name('transaksis.show');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::delete('/transaksis/{transaksi}/destroy', [AdminTransaksiController::class, 'destroy'])->name('transaksis.destroy');
    Route::get('/rekap-event', [App\Http\Controllers\Admin\RekapEventController::class, 'index'])->name('rekap.index');
    Route::get('/rekap-event/{event}', [App\Http\Controllers\Admin\RekapEventController::class, 'show'])->name('rekap.show');
});

// Route Kasir
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('dashboard');
    Route::get('/transaksi/count-today', [TransaksiController::class, 'countToday'])->name('transaksi.count-today');
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/pengambilan', [PengambilanController::class, 'index'])->name('pengambilan.index');
    Route::post('/pengambilan/cari', [PengambilanController::class, 'cari'])->name('pengambilan.cari');
    Route::post('/pengambilan/konfirmasi/{transaksi}', [PengambilanController::class, 'konfirmasi'])->name('pengambilan.konfirmasi');
    Route::get('/transaksi/{transaksi}/nota', [TransaksiController::class, 'nota'])->name('transaksi.nota');
    Route::get('/transaksi/{transaksi}/tambah-barang', [TransaksiController::class, 'tambahBarang'])->name('transaksi.tambah-barang');
    Route::post('/transaksi/{transaksi}/tambah-barang', [TransaksiController::class, 'simpanBarang'])->name('transaksi.simpan-barang');
});

require __DIR__ . '/auth.php';
