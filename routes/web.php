<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');

Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');

Route::get('/dashboard-trend', [App\Http\Controllers\DashboardTrendController::class, 'index'])->name('dashboard.trend');

Route::get('/dashboard-distribution', [App\Http\Controllers\DashboardDistributionController::class, 'index'])->name('dashboard.distribution');

Route::get('/dashboard-performance', [App\Http\Controllers\DashboardPerformanceController::class, 'index'])->name('dashboard.performance');

Route::get('/dashboard-labor-demand', [App\Http\Controllers\DashboardLaborDemandController::class, 'index'])->name('dashboard.labor_demand');
