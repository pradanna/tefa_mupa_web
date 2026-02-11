<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\VisionMission;
use App\Schemas\VisionMissionSchema;
use Illuminate\Support\Facades\DB;

class VisionMissionRepository extends AppRepository
{
    public function __construct(VisionMission $model)
    {
        parent::__construct($model);
    }

    /**
     * Simpan data visi/misi baru dari schema.
     */
    public function createFromSchema(VisionMissionSchema $schema)
    {
        DB::beginTransaction();
        try {
            $data = [
                'type'    => $schema->getType(),
                'content' => $schema->getContent(),
            ];

            $result = $this->model->create($data);

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update data visi/misi dari schema.
     */
    public function updateFromSchema(int|string $id, VisionMissionSchema $schema)
    {
        DB::beginTransaction();
        try {
            $vm = $this->model->findOrFail($id);

            $data = [
                'type'    => $schema->getType(),
                'content' => $schema->getContent(),
            ];

            $vm->update($data);

            DB::commit();
            return $vm;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}


