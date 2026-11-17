<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [BerandaController::class, 'index'])->name
('beranda');

Route::get('/menu', [MenuController::class, 'index'])->name
('menu.index');