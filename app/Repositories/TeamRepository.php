<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\OrganizationStructure;

class TeamRepository extends AppRepository
{
    public function __construct(OrganizationStructure $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        // Mengambil semua data tim diurutkan berdasarkan kolom 'order'
        return $this->model->orderBy('order', 'asc')->get();
    }
}
