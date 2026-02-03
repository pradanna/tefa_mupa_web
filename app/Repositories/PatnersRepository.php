<?php

namespace App\Repositories;

use App\Models\Partners;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Commons\Repositories\AppRepository;
use Illuminate\Http\Request;
use App\Schemas\PatnersSchema;

class PatnersRepository extends AppRepository
{
    protected $schema;

    /**
     * Create a new class instance.
     */
    public function __construct(Partners $model, PatnersSchema $schema)
    {
        //
        parent::__construct($model);
        $this->schema = $schema;
    }

    public function findAll(Request $request){
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = $this->model->orderBy('created_at','DESC')->paginate($request->input('limit', 5));
            Cache::flush();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
        }

        return $data;
    }

    public function createPartner($schema): PatnersSchema
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = [
                'name' => $schema->getName(),
                'image' => $schema->getImage(),
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

    public function updatePartner($id, $schema)
    {
        $result = null;
        DB::beginTransaction();
        try {
            $data = [
                'name' => $schema->getName(),
                'image' => $schema->getImage(),
            ];

            $partner = $this->model->findOrFail($id);
            $partner->update($data);
            DB::commit();
            $result = $partner;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $th;
            throw $th;
        }
        return $result;
    }
}
