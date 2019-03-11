<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sent extends Model
{
    //
    protected $fillable = [
        'inquiry_id',
        'user_id',
        'city_id',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

//    public function cities()
//    {
//        return $this->hasMany(City::class);
//    }
}
