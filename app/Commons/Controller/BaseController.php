<?php

namespace App\Commons\Controller;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function makeView($view, $data = [])
    {
        $menusPath = database_path('seeders/data/menus.json');
        $menus = [];
        if (is_readable($menusPath)) {
            $menusJson = file_get_contents($menusPath);
            $menus = json_decode($menusJson, true);

            if ($menus === null && json_last_error() !== JSON_ERROR_NONE) {
                $menus = [];
            }
        }
        $data['menu'] = $menus;
        return view($view, $data);
    }

    public function goTo404Page()
    {
        abort(404);
    }

    public function goTo403Page()
    {
        abort(403);
    }
}
