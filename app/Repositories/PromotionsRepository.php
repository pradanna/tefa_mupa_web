<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Models\Promotion;
use App\Schemas\PromotionsSchema;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PromotionsRepository extends AppRepository
{
    public function __construct(Promotion $model)
    {
        parent::__construct($model);
    }

    public function createPromotion(PromotionsSchema $schema): PromotionsSchema
    {
        DB::beginTransaction();
        try {
            $data = [
                'name'    => $schema->getName(),
                'desc'    => $schema->getDesc(),
                'image'   => $schema->getImage(),
                'code'    => $schema->getCode(),
                'expired' => $schema->getExpired(),
            ];
            $this->model->create($data);
            DB::commit();
            return $schema;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update promotion data with formatting from Request.
     * Handles upload logic for image and runs validation via schema.
     */
    public function update($id, $data)
    {
        try {
            if ($data instanceof Request) {
                $request = $data;
                $promotion = $this->model->findOrFail($id);

                $formattedData = $request->all();

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $dateNow = now()->format('YmdHis');
                    $newFileName = $dateNow . '.' . $extension;
                    $destinationPath = public_path('images/promotions');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $file->move($destinationPath, $newFileName);
                    // Remove old image if present
                    if ($promotion->image) {
                        $oldFilePath = public_path('images/promotions/' . $promotion->image);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $formattedData['image'] = $newFileName;
                } else {
                    $formattedData['image'] = $promotion->image;
                }

                // Optionally set image path if needed by the table or schema
                $formattedData['path'] = asset('images/promotions');

                $schema = new PromotionsSchema();
                $schema->hydrateSchemaBody($formattedData);

                try {
                    $schema->validate();
                } catch (ValidationException $e) {
                    if ($request->hasFile('image') && isset($newFileName)) {
                        $filePath = public_path('images/promotions/' . $newFileName);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    throw $e;
                }

                $schema->hydrate();

                $promotion->update([
                    'name'    => $schema->getName(),
                    'desc'    => $schema->getDesc(),
                    'image'   => $schema->getImage(),
                    'code'    => $schema->getCode(),
                    'expired' => $schema->getExpired(),
                ]);

                return $promotion;
            } else {
                return parent::update($id, $data);
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function destroy($id)
    {
        $promotion = $this->model->findOrFail($id);
        // Delete the promotion image from storage
        if ($promotion->image) {
            $filePath = public_path('images/promotions/' . $promotion->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return $promotion->delete();
    }

    public function findAll($request)
    {
        try {
            $data = $this->model->orderBy('created_at', 'DESC')->paginate($request->input('limit', 5));
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
}
