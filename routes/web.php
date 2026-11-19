<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// PUBLIK 
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/sangkarasa', function () {
    return redirect()->route('beranda');
});

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');

// HANYA TAMU (belum login) 
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
});

//  HARUS LOGIN 
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/menu/{menu}/pesan', [MenuController::class, 'pesan'])->name('menu.pesan');
});

// KHUSUS ADMIN 
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});