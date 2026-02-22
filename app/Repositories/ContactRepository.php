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

    // Karena hanya boleh ada 1 row pada tabel contacts, kembalikan collection (0 atau 1 item) agar view bisa @forelse.
    public function getContact()
    {
        return $this->model->get();
    }

    public function createOrUpdateFromSchema(ContactSchema $schema)
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

            // Tidak bisa nambah lebih dari 1: jika sudah ada contact, update saja.
            $contact = $this->model->first();

            if ($contact) {
                $contact->update($data);
                $result = $contact->refresh();
            } else {
                $result = $this->model->create($data);
            }

            // Pastikan benar-benar hanya ada 1 baris (tidak terjadi race condition atau duplikasi karena migration manual, dsb)
            $otherContacts = $this->model->where('id', '!=', $result->id)->get();
            if ($otherContacts->count() > 0) {
                $this->model->whereIn('id', $otherContacts->pluck('id')->toArray())->delete();
            }

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
