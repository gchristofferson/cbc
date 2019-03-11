<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
