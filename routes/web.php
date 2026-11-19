<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sangkarasa', [BerandaController::class, 'index'])->name
('beranda');

Route::get('/menu', [MenuController::class, 'index'])->name
('menu.index');

Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/menu/{menu}/pesan', [MenuController::class, 'pesan'])->name('menu.pesan');