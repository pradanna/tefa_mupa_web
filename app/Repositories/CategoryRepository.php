<?php

namespace App\Repositories;
use App\Models\Category;
use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Schemas\CategorySchema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryRepository extends AppRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function create(Request $request)
    {
        try {
            $slugInput = $request->input('slug');
            $slug = $slugInput ? Str::slug($slugInput) : Str::slug($request->input('name'));

            $formattedData = $request->all();
            $formattedData['slug'] = $slug;

            $schema = new CategorySchema();
            $schema->hydrateSchemaBody($formattedData);

            $validator = $schema->validate();
            if ($validator->fails()) {
                throw ValidationException::withMessages(
                    $validator->errors()->toArray()
                );
            }

            $schema->hydrateBody();

            $category = $this->model->create([
                'type' => $schema->getType(),
                'name' => $schema->getName(),
                'slug' => $schema->getSlug(),
                'icon' => $schema->getIcon(),
                'description' => $schema->getDescription(),
            ]);

            return $category;
        } catch (\Throwable $e) {
            throw $e;
        }
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

                $validator = $schema->validate();
                if ($validator->fails()) {
                    throw ValidationException::withMessages(
                        $validator->errors()->toArray()
                    );
                }

                $schema->hydrateBody();

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
