<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Public homepage
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/informasi', [App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');

Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    // Add more admin resources here
});

// Dashboard route (optional, can be removed if not needed)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
