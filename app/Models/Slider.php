<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    //
    protected $table = 'sliders';
    protected $fillable = ['title', 'subtitle', 'file', 'path'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->file);
    }
}
