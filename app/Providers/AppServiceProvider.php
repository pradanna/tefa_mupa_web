<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
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
    }
}
