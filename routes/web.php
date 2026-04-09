<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\PengambilanController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;

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
});

// Route Kasir
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('dashboard');
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/pengambilan', [PengambilanController::class, 'index'])->name('pengambilan.index');
    Route::post('/pengambilan/cari', [PengambilanController::class, 'cari'])->name('pengambilan.cari');
    Route::post('/pengambilan/konfirmasi/{transaksi}', [PengambilanController::class, 'konfirmasi'])->name('pengambilan.konfirmasi');
});

require __DIR__ . '/auth.php';
