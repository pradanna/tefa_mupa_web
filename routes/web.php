<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');



// backoffice
Route::prefix('backoffice')->group(function () {
    Route::post('authentication',[App\Http\Controllers\Backoffice\Auth\AutenticationController::class,'login'])->name('auth');
    Route::get('login', function () {
        return view('backoffice.pages.login.index');
    })->name('login-backoffice');

    // Route::middleware('auth')->group(function () {
        Route::get('dashboard', function () {
            return view('backoffice.pages.dashboard.index');
        })->name('dashboard');
    // });
});




