<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;

use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Profil
Route::get('/profil', [ProfileController::class, 'index'])->name('profile');

// Halaman Produk
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

// Halaman Berita
Route::get('/berita', [ArticleController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('news.show');

// Halaman Gallery
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Halaman Kontak
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');

// backoffice
Route::prefix('backoffice')->group(function () {
    Route::post('authentication', [App\Http\Controllers\Backoffice\Auth\AutenticationController::class, 'login'])->name('auth');
    Route::get('login', function () {
        return view('backoffice.pages.login.index');
    })->name('login-backoffice');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', function () {
            return view('backoffice.pages.dashboard.index');
        })->name('dashboard');
    });
});
