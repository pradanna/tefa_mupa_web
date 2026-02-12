<?php

namespace App\Repositories;

use App\Commons\Repositories\AppRepository;
use App\Models\Inbox;

class InboxRepository extends AppRepository
{
    public function __construct(Inbox $model)
    {
        parent::__construct($model);
    }
}
