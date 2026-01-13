<?php

namespace App\Commons\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Commons\Schema\BaseSchema;

class AppRepository{
    /**
     * https://medium.com/@luckys383/laravel-api-crud-with-repository-classes-e3ff58cbe6c6
     * Eloquent model instance.
     */
    protected $model;

    /**
     * load default class dependencies.
     *
     * @param Model $model Illuminate\Database\Eloquent\Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * get all the items collection from database table using model.
     *
     * @return Collection of items.
     */
    public function getAll()
    {
        DB::beginTransaction();
        try {
            $data = $this->model->get();
            Cache::flush();
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * get collection of items in paginate format.
     *
     * @return Collection of items.
     */
    public function paginate(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->model->orderBy('created_at','DESC')->paginate($request->input('limit', 5));
            Cache::flush();
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * create new record in database.
     *
     * @param Request|BaseSchema|array $data Illuminate\Http\Request, BaseSchema, or array
     * @return saved model object with data.
     */
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $dataToSave = $this->extractData($data);
            $result = $this->model;
            $result->fill($dataToSave);
            $result->save();

            Cache::flush();
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * update existing item.
     *
     * @param  Integer $id integer item primary key.
     * @param Request|BaseSchema|array $data Illuminate\Http\Request, BaseSchema, or array
     * @return send updated item object.
     */
    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $dataToSave = $this->extractData($data);
            $result = $this->model->findOrFail($id);
            $result->fill($dataToSave);
            $result->save();

            Cache::flush();
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * get requested item and send back.
     *
     * @param  Integer $id: integer primary key value.
     * @return send requested item data.
     */
    public function show($id)
    {
        DB::beginTransaction();
        try {
            $data = $this->model->findOrFail($id);

            Cache::flush();
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Delete item by primary key id.
     *
     * @param  Integer $id integer of primary key id.
     * @return boolean
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = $this->model->destroy($id);

            Cache::flush();
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Extract data from Request, BaseSchema, or array
     *
     * @param  Request|BaseSchema|array $data
     * @return array of data.
     */
    protected function extractData($data)
    {
        if ($data instanceof Request) {
            return $this->setDataPayload($data);
        } elseif ($data instanceof BaseSchema) {
            return $data->getBody();
        } elseif (is_array($data)) {
            return $data;
        }

        throw new \InvalidArgumentException('Data must be Request, BaseSchema, or array');
    }

    /**
     * set data for saving
     *
     * @param  Request $request Illuminate\Http\Request
     * @return array of data.
     */
    protected function setDataPayload(Request $request)
    {
        return $request->all();
    }

    public function activeNonActive($id, $status)
    {
        DB::beginTransaction();
        try {
            //$data = $this->show($id);
            //$data->update([
			//	'enabled' => $status
			//]);
			$data = $this->model->where('id',$id)->update([
					'enabled' => $status,
				]);
            Cache::flush();
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
