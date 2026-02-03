<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Commons\Repositories\AppRepository;
use Illuminate\Http\Request;
use App\Schemas\NewsSchema;

class NewsRepository extends AppRepository
{
    protected $schema;
    /**
     * Create a new class instance.
     */
    public function __construct(News $model, NewsSchema $schema)
    {
        parent::__construct($model);
        $this->schema = $schema;
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

    public function createNews($schema): NewsSchema
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = [
                'title' => $schema->getTitle(),
                'slug' => $schema->getSlug(),
                'image' => $schema->getImage(),
                'id_category' => $schema->getIdCategory(),
                'path'  => $schema->getPath(),
                'content' => $schema->getContent(),
                'date' => $schema->getDate(),
                'status' => $schema->getStatus(),
                'id_user' => $schema->getIdUser(),
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

    /**
     * Ambil berita terbaru (default 3) untuk ditampilkan di homepage.
     */
    public function getLatestNews(int $limit = 3)
    {
        return $this->model
            ->orderBy('date', 'DESC')
            ->take($limit)
            ->get();
    }

public function updateNews($id, $schema)
{
    $result = null;
    DB::beginTransaction();
    try {
        $data = [
            'title' => $schema->getTitle(),
            'slug' => $schema->getSlug(),
            'image' => $schema->getImage(),
            'id_category' => $schema->getIdCategory(),
            'path'  => $schema->getPath(),
            'content' => $schema->getContent(),
            'date' => $schema->getDate(),
            'status' => $schema->getStatus(),
            'id_user' => $schema->getIdUser(),
        ];

        $news = $this->model->findOrFail($id);
        $news->update($data);
        DB::commit();
        $result = $news;
    } catch (\Throwable $th) {
        DB::rollBack();
        $result = $th;
        throw $th;
    }
    return $result;
}
}
