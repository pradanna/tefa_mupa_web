<?php

namespace App\Repositories;

use App\Models\License;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Commons\Repositories\AppRepository;
use Illuminate\Http\Request;
use App\Schemas\LicenseSchema;

class LicenseRepository extends AppRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(License $model)
    {
        parent::__construct($model);
    }

    public function findAll(Request $request)
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = $this->model->orderBy('created_at', 'DESC')->paginate($request->input('limit', 5));
            Cache::flush();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
        }

        return $data;
    }

    public function createLicense($schema): LicenseSchema
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = [
                'name' => $schema->getName(),
                'code' => $schema->getCode(),
                'type' => $schema->getType(),
                'file' => $schema->getFile(),
            ];
            $this->model->create($data);
            DB::commit();
            return $schema;
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
            throw $data;
        }
    }

    public function updateLicense($id, $schema)
    {
        $result = null;
        DB::beginTransaction();
        try {
            $data = [
                'name' => $schema->getName(),
                'code' => $schema->getCode(),
                'type' => $schema->getType(),
                'file' => $schema->getFile(),
            ];

            $license = $this->model->findOrFail($id);
            $license->update($data);
            DB::commit();
            $result = $license;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $th;
            throw $th;
        }
        return $result;
    }
}
