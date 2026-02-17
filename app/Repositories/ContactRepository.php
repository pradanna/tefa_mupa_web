<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\Contact;
use App\Schemas\ContactSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContactRepository extends AppRepository
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilter(Request $request)
    {
        $query = $this->model->query()->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return $query->paginate($request->input('limit', 10));
    }

    public function createFromSchema(ContactSchema $schema)
    {
        DB::beginTransaction();
        try {
            $data = [
                'address'        => $schema->getAddress(),
                'email'          => $schema->getEmail(),
                'phone'          => $schema->getPhone(),
                'weekday_hours'  => $schema->getWeekdayHours(),
                'saturday_hours' => $schema->getSaturdayHours(),
                'facebook_url'   => $schema->getFacebookUrl(),
                'instagram_url'  => $schema->getInstagramUrl(),
                'tiktok_url'     => $schema->getTiktokUrl(),
                'youtube_url'    => $schema->getYoutubeUrl(),
                'status'         => $schema->getStatus(),
                'id_user'        => $schema->getIdUser(),
            ];

            $result = $this->model->create($data);

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateFromSchema(int|string $id, ContactSchema $schema)
    {
        DB::beginTransaction();
        try {
            $contact = $this->model->findOrFail($id);

            $data = [
                'address'        => $schema->getAddress(),
                'email'          => $schema->getEmail(),
                'phone'          => $schema->getPhone(),
                'weekday_hours'  => $schema->getWeekdayHours(),
                'saturday_hours' => $schema->getSaturdayHours(),
                'facebook_url'   => $schema->getFacebookUrl(),
                'instagram_url'  => $schema->getInstagramUrl(),
                'tiktok_url'     => $schema->getTiktokUrl(),
                'youtube_url'    => $schema->getYoutubeUrl(),
                'status'         => $schema->getStatus(),
                'id_user'        => $schema->getIdUser(),
            ];

            $contact->update($data);

            DB::commit();
            return $contact;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}


