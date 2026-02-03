<?php

namespace App\Repositories;
use App\Models\History;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Schemas\HistorySchema;
use App\Commons\Repositories\AppRepository;
use Illuminate\Http\Request;

class HistoryRepository extends AppRepository
{
    protected $schema;

    /**
     * Create a new class instance.
     */
    public function __construct(History $model, HistorySchema $schema)
    {
        parent::__construct($model);
        $this->schema = $schema;
    }

    public function findFirst(){
        $data = NULL;
        DB::beginTransaction();
        try {
           $data = History::first();
           DB::commit();
           return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;

            return $data;
        }
    }

    public function createHistory($schema)
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $payload = [
                'title' => $schema->getTitle(),
                'body'  => $schema->getBodyContent(),
                'path'  => $schema->getPath(),
                'image' => $schema->getImage()
            ];
           $data = $this->model->create($payload);
           DB::commit();
           return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
            return $data;
        }
    }

    public function updateData($id, $schema)
    {
        $result = null;
        DB::beginTransaction();
        try {
            $history = $this->model->findOrFail($id);

            if ($schema instanceof \App\Schemas\HistorySchema) {
                $payload = [
                    'title' => $schema->getTitle(),
                    'body'  => $schema->getBodyContent(),
                    'image' => $schema->getImage(),
                    'path'  => $schema->getPath(),
                ];
            } else if (is_array($schema)) {
                $payload = [
                    'title' => $schema['title'] ?? $history->title,
                    'body'  => $schema['body'] ?? $history->body,
                    'image' => $schema['image'] ?? $history->image,
                    'path'  => $schema['path'] ?? $history->path,
                ];
            } else {
                throw new \Exception("Invalid schema type for updateData");
            }

            $history->update($payload);

            DB::commit();
            $result = $history;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $th;
        }
        return $result;
    }
}
