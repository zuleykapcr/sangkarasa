<?php

use App\Http\Controllers\HalamanController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIK =====
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/sangkarasa', function () {
    return redirect()->route('beranda');
});

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/cerita-kami', [HalamanController::class, 'cerita'])->name('cerita');
Route::get('/cara-pesan', [HalamanController::class, 'cara'])->name('cara');

// ===== HANYA TAMU =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
});

// ===== HARUS LOGIN =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Riwayat pesanan
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{pesanan}/pesan-lagi', [PesananController::class, 'ulang'])->name('pesanan.ulang');

    // Pesan -> masuk keranjang
    Route::post('/menu/{menu}/pesan', [MenuController::class, 'pesan'])->name('menu.pesan');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::patch('/keranjang/{key}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{key}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/voucher', [CheckoutController::class, 'pakaiVoucher'])->name('checkout.voucher');
    Route::post('/checkout/voucher/hapus', [CheckoutController::class, 'hapusVoucher'])->name('checkout.voucher.hapus');

    // Instruksi pembayaran
    Route::get('/pesanan/{pesanan}/pembayaran', [CheckoutController::class, 'pembayaran'])->name('pesanan.pembayaran');

        // Ulasan dan rating
    Route::post('/menu/{menu}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
});

// ===== KHUSUS ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('menu', AdminMenuController::class)->except(['show']);
    Route::resource('kategori', AdminKategoriController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{pesanan}', [AdminPesananController::class, 'update'])->name('pesanan.update');
});
