<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'address',
        'email',
        'phone',
        'weekday_hours',
        'saturday_hours',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
        'status',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}


