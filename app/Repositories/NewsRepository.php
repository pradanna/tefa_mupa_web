<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Commons\Repositories\AppRepository;
use Illuminate\Http\Request;

class NewsRepository extends AppRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(News $model)
    {
        //
        parent::__construct($model);
    }

    public function findAllWithRelation(Request $request){
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = $this->model->with(['category','user'])->orderBy('created_at','DESC')->paginate($request->input('limit', 5));
            Cache::flush();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
        }

        return $data;
    }

    public function updateStatus($id,$status){
        $result = NULL;
        DB::beginTransaction();
        try {
           $dataToUpdate = $this->model->where('id', $id)->update([
               'status' => $status
           ]);
           DB::commit();
           $result = $dataToUpdate;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $th;
        }

        return $result;
    }
}
