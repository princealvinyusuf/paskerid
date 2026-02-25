<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MiniVideoController;
use App\Http\Controllers\MiniJobiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MiniJobiApplicationController;
use App\Http\Controllers\Api\HighlightStatisticController;
use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\WalkinGalleryController;
use App\Http\Controllers\CareerBoostdayController;
use App\Http\Controllers\FormHasilKonselingController;

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

// Career Boost Day (public)
Route::get('/career-boostday', [CareerBoostdayController::class, 'index'])->name('career-boostday.index');
Route::post('/career-boostday/konsultasi', [CareerBoostdayController::class, 'store'])->name('career-boostday.store');
Route::post('/career-boostday/konfirmasi-kehadiran', [CareerBoostdayController::class, 'confirmAttendance'])->name('career-boostday.confirm-attendance');

// Form Hasil Konseling (public) - not linked in navbar
Route::get('/form-hasil-konseling', [FormHasilKonselingController::class, 'index'])->name('form-hasil-konseling.index');
Route::post('/form-hasil-konseling', [FormHasilKonselingController::class, 'store'])->name('form-hasil-konseling.store');
Route::get('/form-hasil-konseling/prefill', [FormHasilKonselingController::class, 'prefill'])->name('form-hasil-konseling.prefill');

Route::get('/informasi-pasar-kerja', [App\Http\Controllers\InformasiPasarKerjaController::class, 'index'])->name('informasi_pasar_kerja.index');

// Kemitraan routes (only create and store)
Route::get('kemitraan/under_construction', function() {
    return view('kemitraan.under_construction');
})->name('kemitraan.underconstruction');


Route::get('kemitraan/create', [App\Http\Controllers\KemitraanController::class, 'create'])->name('kemitraan.create');
Route::post('kemitraan', [App\Http\Controllers\KemitraanController::class, 'store'])->name('kemitraan.store');
Route::post('kemitraan/survey', [App\Http\Controllers\KemitraanController::class, 'storeSurvey'])->name('kemitraan.survey.store');
Route::get('kemitraan/fully-booked-dates', [App\Http\Controllers\KemitraanController::class, 'fullyBookedDates'])->name('kemitraan.fullyBookedDates');

// Walk-in Interview Gallery (public)
Route::get('/walkin-gallery/feed', [WalkinGalleryController::class, 'feed'])->name('walkin-gallery.feed');
Route::post('/walkin-gallery/comments', [WalkinGalleryController::class, 'storeComment'])
    ->middleware('throttle:5,1')
    ->name('walkin-gallery.comments.store');

// Walk-in Schedule (company-specific, public)
Route::get('/walkin-schedule/company', [App\Http\Controllers\KemitraanController::class, 'companyWalkinSchedule'])
    ->name('walkin-schedule.company');

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

Route::get('/miniJobi', [MiniJobiController::class, 'index'])->name('minijobi.index');
Route::get('/miniJobi/{id}', [MiniJobiController::class, 'show'])
    ->whereNumber('id')
    ->name('minijobi.show');
Route::post('/miniJobi/{id}/apply', [MiniJobiApplicationController::class, 'store'])
    ->middleware('auth')
    ->whereNumber('id')
    ->name('minijobi.apply');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
