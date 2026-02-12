<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\VisionMission;

class MissionRepository extends AppRepository
{
    public function __construct(VisionMission $model)
    {
        parent::__construct($model);
    }

    public function getMission()
    {
        // Mengambil data khusus Misi (type = mission)
        return $this->model->where('type', 'mission')->get();
    }

    public function getVision()
    {
        // Mengambil data khusus Misi (type = mission)
        return $this->model->where('type', 'vision')->get();
    }
}
