<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    //
    protected $fillable = [
        'read',
        'subject',
        'body',
    ];

//    public function cities () {
//        return $this->hasManyThrough(City::class, Sent::class, 'city_id');
//    }

    public function documents () {
        return $this->hasMany(Document::class);
    }

    public function received_inquiries () {
        return $this->hasMany(Received::class);
    }

    public function saved_inquiries () {
        return $this->hasMany(Saved::class);
    }

    public function sent_inquiry () {
        return $this->hasOne(Sent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
