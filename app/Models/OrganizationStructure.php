<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationStructure extends Model
{
    protected $table = 'organization_structures';

    protected $fillable = [
        'name',
        'position',
        'path',
        'image',
        'instagram',
        'linkedin',
        'email',
        'order',
    ];
}
