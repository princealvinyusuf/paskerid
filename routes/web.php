<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');

Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    // You can add more resources here later (testimonials, services, etc.)
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
