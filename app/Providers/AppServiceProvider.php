<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public const FOOTER_CONTACT_CACHE_KEY = 'footer_contact';
    public const FOOTER_CONTACT_CACHE_TTL = 3600; // 1 jam

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Share menu untuk semua view dan komponen (termasuk backoffice)
        $menusPath = database_path('seeders/data/menus.json');
        $menus = [];
        if (is_readable($menusPath)) {
            $menusJson = file_get_contents($menusPath);
            $menus = json_decode($menusJson, true);

            if ($menus === null && json_last_error() !== JSON_ERROR_NONE) {
                $menus = [];
            }
        }
        View::share('menus', $menus);

        // Data contact untuk footer: hanya di-load saat view footer di-render, dan di-cache
        // sehingga tidak query DB setiap request.
        View::composer('components.footer', function ($view) {
            $contact = Cache::remember(
                self::FOOTER_CONTACT_CACHE_KEY,
                self::FOOTER_CONTACT_CACHE_TTL,
                fn () => Contact::where('status', 'publis')->first()
            );
            $view->with('contact', $contact);
        });
    }
}
