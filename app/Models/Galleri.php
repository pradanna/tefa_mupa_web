<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galleri extends Model
{
    //
    protected $table = 'gallers';
    protected $fillable = ['path','image'];
}
