<?php

namespace App\Repositories;
use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Models\Slider;
use App\Schemas\SliderSchema;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class SliderRepository extends AppRepository {

    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    public function create(Request $request){
        try {
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['File is required']
                ]);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $dateNow = now()->format('YmdHis');
            $fileName = $dateNow . '.' . $extension;

            $file->storeAs('images/slider', $fileName, 'public');

            $pathUrl = asset('storage/images/slider');

            $formattedData = array_merge($request->all(), [
                'file' => $fileName,
                'path' => $pathUrl
            ]);

            $schema = new SliderSchema();
            $schema->hydrateSchemaBody($formattedData);

            $validator = $schema->validate();
            if ($validator->fails()) {
                // Jika validasi gagal, hapus file yang sudah diupload
                if (Storage::disk('public')->exists('images/slider/' . $fileName)) {
                    Storage::disk('public')->delete('images/slider/' . $fileName);
                }
                throw ValidationException::withMessages(
                    $validator->errors()->toArray()
                );
            }

            // Hydrate body schema
            $schema->hydrateBody();

            // Simpan data yang sudah diformat
            $slider = $this->model->create([
                'title' => $schema->getTitle(),
                'file' => $schema->getFile(),
                'path' => $schema->getPath()
            ]);

            return $slider;
        } catch (\Throwable $e) {
            throw $e;
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


                $validator = $schema->validate();
                if ($validator->fails()) {

                    if ($request->hasFile('file') && isset($newFileName) && Storage::disk('public')->exists('images/slider/' . $newFileName)) {
                        Storage::disk('public')->delete('images/slider/' . $newFileName);
                    }
                    throw ValidationException::withMessages(
                        $validator->errors()->toArray()
                    );
                }

                $schema->hydrateBody();

                $slider->update([
                    'title' => $schema->getTitle(),
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
