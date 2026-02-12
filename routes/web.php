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

// API Promo (Public)
Route::get('/api/promos', [App\Http\Controllers\PromoController::class, 'index'])->name('api.promos');

// API License (Public)
Route::get('/api/licenses', [App\Http\Controllers\LicenseController::class, 'index'])->name('api.licenses');

// backoffice
Route::prefix('backoffice')->group(function () {
    Route::post('authentication', [App\Http\Controllers\Backoffice\Auth\AutenticationController::class, 'login'])->name('auth');
    Route::post('logout', [App\Http\Controllers\Backoffice\Auth\AutenticationController::class, 'logout'])->name('logout');
    Route::get('login', function () {
        return view('backoffice.pages.login.index');
    })->name('login-backoffice');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', function () {
            $stats = [
                'categories' => \App\Models\Category::count(),
                'catalogs' => \App\Models\Catalog::count(),
                'news' => \App\Models\News::count(),
            ];
            $runningPromotions = \App\Models\Promotion::where('expired', '>=', now())->orderBy('expired', 'asc')->take(5)->get();
            $latestNews = \App\Models\News::with('category')->latest('date')->take(5)->get();
            return view('backoffice.pages.dashboard.index', compact('stats', 'runningPromotions', 'latestNews'));
        })->name('dashboard');
        Route::resource('sliders', App\Http\Controllers\Backoffice\SliderController::class);
        Route::resource('categories', App\Http\Controllers\Backoffice\CategoryController::class);
        Route::resource('berita', App\Http\Controllers\Backoffice\NewsController::class)->names('articles');
        Route::resource('galleries', App\Http\Controllers\Backoffice\GalleriController::class)->names('album');
        Route::resource('history', App\Http\Controllers\Backoffice\HistoryController::class);
        Route::resource('catalog', App\Http\Controllers\Backoffice\CatalogController::class);
        Route::resource('organizations', App\Http\Controllers\Backoffice\OrganizationController::class);
        Route::resource('promotions', App\Http\Controllers\Backoffice\PromotionsController::class);
        Route::resource('licenses', App\Http\Controllers\Backoffice\LicenseController::class);
        Route::resource('partners', App\Http\Controllers\Backoffice\PatnerController::class);
        Route::resource('vision-missions', App\Http\Controllers\Backoffice\VissionMissionController::class);
    });
});
