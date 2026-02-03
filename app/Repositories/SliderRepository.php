<?php

namespace App\Repositories;
use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Models\Slider;
use App\Schemas\SliderSchema;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SliderRepository extends AppRepository {

    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    public function createNews($schema): SliderSchema
    {
        $sliderCount = $this->model->count();
        if ($sliderCount >= 3) {
            throw new \Exception('Jumlah slider sudah melebihi batas maksimum (3).');
        }

        DB::beginTransaction();
        try {
            $data = [
                'title' => $schema->getTitle(),
                'subtitle' => $schema->getSubtitle(),
                'file' => $schema->getFile(),
                'path' => $schema->getPath()
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
     * Update slider dengan formatting data dari Request
     * Repository menangani semua formatting data, bukan hanya validasi
     *
     * @param int|string $id
     * @param Request|BaseSchema|array $data
     * @return mixed
     */
    public function update($id, $data){
        try {
            if ($data instanceof Request) {
                $request = $data;
                $slider = $this->model->findOrFail($id);

                $formattedData = $request->all();

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $dateNow = now()->format('YmdHis');
                    $newFileName = $dateNow . '.' . $extension;
                    $file->storeAs('images/slider', $newFileName, 'public');
                    if ($slider->file && Storage::disk('public')->exists('images/slider/' . $slider->file)) {
                        Storage::disk('public')->delete('images/slider/' . $slider->file);
                    }
                    $formattedData['file'] = $newFileName;
                } else {
                    $formattedData['file'] = $slider->file;
                }

                $formattedData['path'] = asset('storage/images/slider');

                $schema = new SliderSchema();
                $schema->hydrateSchemaBody($formattedData);

                try {
                    $schema->validate();
                } catch (ValidationException $e) {
                    if ($request->hasFile('file') && isset($newFileName) && Storage::disk('public')->exists('images/slider/' . $newFileName)) {
                        Storage::disk('public')->delete('images/slider/' . $newFileName);
                    }
                    throw $e;
                }

                $schema->hydrate();

                $slider->update([
                    'title' => $schema->getTitle(),
                    'subtitle' => $schema->getSubtitle(),
                    'file' => $schema->getFile(),
                    'path' => $schema->getPath()
                ]);

                return $slider;
            } else {
                return parent::update($id, $data);
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
