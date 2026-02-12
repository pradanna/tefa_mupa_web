<?php

namespace App\Repositories;

use App\Models\Category;
use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Schemas\CategorySchema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends AppRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->model->query();
    }

    public function createNews($schema): CategorySchema
    {
        $data = NULL;
        DB::beginTransaction();
        try {
            $data = [
                'type' => $schema->getType(),
                'name' => $schema->getName(),
                'slug' => $schema->getSlug(),
                'icon' => $schema->getIcon(),
                'description' => $schema->getDescription(),
            ];
            $this->model->create($data);
            DB::commit();
            return $schema;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getCategoryNews()
    {
        // Transactions are not needed for read operations (SELECT).
        return $this->model->where('type', 'content')->get();
    }

    public function getCategoryCataloge()
    {
        // Transactions are not needed for read operations (SELECT).
        return $this->model->where('type', 'catalog')->get();
    }

    public function getSubCategoryCataloge()
    {
        // Transactions are not needed for read operations (SELECT).
        return $this->model->where('type', 'sub_catalog')->get();
    }

    public function update($id, $data)
    {
        try {
            if ($data instanceof Request) {
                $request = $data;

                $slugInput = $request->input('slug');
                $slug = $slugInput ? Str::slug($slugInput) : Str::slug($request->input('name'));

                $formattedData = $request->all();
                $formattedData['slug'] = $slug;

                $schema = new CategorySchema();
                $schema->hydrateSchemaBody($formattedData);
                $schema->validate();
                $schema->hydrate();

                $category = $this->model->findOrFail($id);
                $category->update([
                    'type' => $schema->getType(),
                    'name' => $schema->getName(),
                    'slug' => $schema->getSlug(),
                    'icon' => $schema->getIcon(),
                    'description' => $schema->getDescription(),
                ]);

                return $category;
            } else {
                return parent::update($id, $data);
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
