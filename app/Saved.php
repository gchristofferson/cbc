<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saved extends Model
{
    //
    protected $fillable = [
        'inquiry_id',
        'user_id',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

}
