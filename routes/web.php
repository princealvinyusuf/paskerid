<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mitra_kerja/index', function () {
    // Contoh dummy data
    $stakeholders = [
        ['name' => 'LPK A', 'address' => 'Jl. Contoh No.1', 'contact' => '08123456789', 'email' => 'lpkA@example.com'],
        ['name' => 'LPK B', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK C', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK D', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK E', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK F', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK G', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK H', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK I', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK J', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK K', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        ['name' => 'LPK L', 'address' => 'Jl. Contoh No.2', 'contact' => '08123456788', 'email' => 'lpkB@example.com'],
        // Tambahkan sesuai kebutuhan
    ];

    return view('mitra_kerja.index', compact('stakeholders'));
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
