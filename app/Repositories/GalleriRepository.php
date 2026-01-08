<?php

namespace App\Repositories;
use App\Commons\Repositories\AppRepository;
use App\Schemas\GallerSchema;
use App\Models\Galleri;

class GalleriRepository extends AppRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Galleri $model)
    {
        parent::__construct($model);
    }

    public function saveImage($payload): GallerSchema
    {
        try {
            $galleri = new Galleri();
            $galleri->fill($payload);
            $galleri->save();
            $schema = new GallerSchema();
            $schema->setPath($galleri->path)
                   ->setImage($galleri->image);

            return $schema;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
