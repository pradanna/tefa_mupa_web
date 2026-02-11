<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Commons\Schema\BaseSchema;
use App\Models\OrganizationStructure;
use App\Schemas\OrganizationSchemas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class OrganizationRepository extends AppRepository
{
    public function __construct(OrganizationStructure $model)
    {
        parent::__construct($model);
    }

    public function createOrganization($schema): OrganizationSchemas
    {
        $data = null;
        DB::beginTransaction();
        try {
            $data = [
                'name'      => $schema->getName(),
                'position'  => $schema->getPosition(),
                'path'      => $schema->getPath(),
                'image'     => $schema->getImage(),
                'instagram' => $schema->getInstagram(),
                'linkedin'  => $schema->getLinkedin(),
                'email'     => $schema->getEmail(),
                'order'     => $schema->getOrder(),
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
     * Update organization data with formatting from Request
     * Repository handles all formatting, not just validation
     *
     * @param int|string $id
     * @param Request|BaseSchema|array $data
     * @return mixed
     */
    public function update($id, $data)
    {
        try {
            if ($data instanceof Request) {
                $request = $data;
                $organization = $this->model->findOrFail($id);

                $formattedData = $request->all();

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $dateNow = now()->format('YmdHis');
                    $newFileName = $dateNow . '.' . $extension;
                    $destinationPath = public_path('images/organization');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $file->move($destinationPath, $newFileName);
                    if ($organization->image) {
                        $oldFilePath = public_path('images/organization/' . $organization->image);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $formattedData['image'] = $newFileName;
                } else {
                    $formattedData['image'] = $organization->image;
                }

                $formattedData['path'] = asset('images/organization');

                $schema = new OrganizationSchemas();
                $schema->hydrateSchemaBody($formattedData);

                try {
                    $schema->validate();
                } catch (ValidationException $e) {
                    if ($request->hasFile('image') && isset($newFileName)) {
                        $filePath = public_path('images/organization/' . $newFileName);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    throw $e;
                }

                $schema->hydrate();

                $organization->update([
                    'name'      => $schema->getName(),
                    'position'  => $schema->getPosition(),
                    'path'      => $schema->getPath(),
                    'image'     => $schema->getImage(),
                    'instagram' => $schema->getInstagram(),
                    'linkedin'  => $schema->getLinkedin(),
                    'email'     => $schema->getEmail(),
                    'order'     => $schema->getOrder(),
                ]);

                return $organization;
            } else {
                return parent::update($id, $data);
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function destroy($id)
    {
        $organization = $this->model->findOrFail($id);
        // Delete the organization image from storage
        if ($organization->image) {
            $filePath = public_path('images/organization/' . $organization->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return $organization->delete();
    }

    // Optionally: implement index/show/create if needed
    public function index()
    {
        return $this->model->orderBy('order')->get();
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
}
