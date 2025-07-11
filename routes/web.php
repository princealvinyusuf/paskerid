<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mitra-kerja', [App\Http\Controllers\MitraKerjaController::class, 'index'])->name('mitra_kerja.index');


Route::get('/news/{id}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.DetailBerita');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');

Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');

Route::get('/dashboard-trend', [App\Http\Controllers\DashboardTrendController::class, 'index'])->name('dashboard.trend');

Route::get('/dashboard-distribution', [App\Http\Controllers\DashboardDistributionController::class, 'index'])->name('dashboard.distribution');

Route::get('/dashboard-performance', [App\Http\Controllers\DashboardPerformanceController::class, 'index'])->name('dashboard.performance');

Route::get('/dashboard-labor-demand', [App\Http\Controllers\DashboardLaborDemandController::class, 'index'])->name('dashboard.labor_demand');

Route::get('/informasi/{id}', [App\Http\Controllers\InformasiController::class, 'show'])->name('informasi.show');

Route::get('/virtual-karir', [App\Http\Controllers\VirtualKarirController::class, 'index'])->name('virtual-karir.index');

// Kemitraan routes
Route::resource('kemitraan', App\Http\Controllers\KemitraanController::class);
