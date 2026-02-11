<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionMission extends Model
{
    protected $table = 'vision_missions';
    protected $fillable = ['type','content'];
}
