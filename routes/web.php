<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MiniVideoController;
use App\Http\Controllers\Api\HighlightStatisticController;
use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\WalkinGalleryController;

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

Route::get('/informasi-pasar-kerja', [App\Http\Controllers\InformasiPasarKerjaController::class, 'index'])->name('informasi_pasar_kerja.index');

// Kemitraan routes (only create and store)
Route::get('kemitraan/under_construction', function() {
    return view('kemitraan.under_construction');
})->name('kemitraan.underconstruction');


Route::get('kemitraan/create', [App\Http\Controllers\KemitraanController::class, 'create'])->name('kemitraan.create');
Route::post('kemitraan', [App\Http\Controllers\KemitraanController::class, 'store'])->name('kemitraan.store');
Route::get('kemitraan/fully-booked-dates', [App\Http\Controllers\KemitraanController::class, 'fullyBookedDates'])->name('kemitraan.fullyBookedDates');

// Walk-in Interview Gallery (public)
Route::get('/walkin-gallery/feed', [WalkinGalleryController::class, 'feed'])->name('walkin-gallery.feed');
Route::post('/walkin-gallery/comments', [WalkinGalleryController::class, 'storeComment'])
    ->middleware('throttle:5,1')
    ->name('walkin-gallery.comments.store');

Route::get('/kebijakan-privasi', function () {
    return view('kebijakan_privasi');
})->name('kebijakan_privasi');

Route::get('/ketentuan-pengguna', function () {
    return view('ketentuan_pengguna');
})->name('ketentuan_pengguna');

// Topic Data download route
Route::get('/topic-data/download/{id}', [App\Http\Controllers\TopicDataController::class, 'download'])->name('topicdata.download');

Route::get('/datasets', [App\Http\Controllers\DatasetController::class, 'index'])->name('datasets.index');

Route::get('/media-sosial', function () {
    return view('media_sosial');
})->name('media_sosial');

Route::get('/publikasi', [App\Http\Controllers\PublikasiController::class, 'index'])->name('publikasi.index');

// Mini Video Player routes
Route::get('/mini-videos', [MiniVideoController::class, 'index'])->name('mini-videos.index');
// (Optional: for admin panel or API use)
Route::post('/mini-videos', [MiniVideoController::class, 'store'])->name('mini-videos.store');
Route::delete('/mini-videos/{id}', [MiniVideoController::class, 'destroy'])->name('mini-videos.destroy');

Route::post('/news/{id}/like', [App\Http\Controllers\NewsController::class, 'like'])->name('news.like');
Route::get('/news/{id}/like', [App\Http\Controllers\NewsController::class, 'likeStatus'])->name('news.like');

// Public API: Highlight statistics for "Highlight Pasar Kerja" section
Route::get('/api/highlight-statistics', [HighlightStatisticController::class, 'index'])
    ->name('api.highlight-statistics');

// Public API: Information records (with file_url, iframe_url, etc.)
Route::get('/api/information', [InformationController::class, 'index'])
    ->name('api.information.index');
