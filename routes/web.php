<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');
