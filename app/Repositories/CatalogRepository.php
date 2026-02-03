<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\Catalog;

class CatalogRepository extends AppRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Catalog $catalog)
    {
        //
        parent::__construct($catalog);
    }

    /**
     * Ambil semua data catalog (beserta relasi kategori jika dibutuhkan di homepage).
     */
    public function getCategoryCataloge()
    {
        // Ambil semua record catalog + relasi kategori (jika diperlukan di view)
        return $this->model
            ->with('hasCategory:id,name,type,slug,icon,description')
            ->get();
    }

}
