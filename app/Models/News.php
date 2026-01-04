<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $table = 'news';
    protected $fillable = ['title','slug','path','id_category','image','content','date','status','id_user'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }



}
