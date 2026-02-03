<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Models\Promotion;
use App\Schemas\PromotionsSchema;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
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
                    $file->storeAs('images/promotions', $newFileName, 'public');
                    // Remove old image if present
                    if ($promotion->image && Storage::disk('public')->exists('images/promotions/' . $promotion->image)) {
                        Storage::disk('public')->delete('images/promotions/' . $promotion->image);
                    }
                    $formattedData['image'] = $newFileName;
                } else {
                    $formattedData['image'] = $promotion->image;
                }

                // Optionally set image path if needed by the table or schema
                $formattedData['path'] = asset('storage/images/promotions');

                $schema = new PromotionsSchema();
                $schema->hydrateSchemaBody($formattedData);

                try {
                    $schema->validate();
                } catch (ValidationException $e) {
                    if ($request->hasFile('image') && isset($newFileName) && Storage::disk('public')->exists('images/promotions/' . $newFileName)) {
                        Storage::disk('public')->delete('images/promotions/' . $newFileName);
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
        if ($promotion->image && Storage::disk('public')->exists('images/promotions/' . $promotion->image)) {
            Storage::disk('public')->delete('images/promotions/' . $promotion->image);
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
